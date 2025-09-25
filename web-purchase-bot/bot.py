import argparse
import sys
import time
from dataclasses import dataclass
from typing import Dict, List, Optional

import yaml
from rich.console import Console
from playwright.sync_api import sync_playwright, TimeoutError as PlaywrightTimeoutError


console = Console()


@dataclass
class BotConfig:
    url: str
    check_selector: Optional[str]
    click_selector: str
    available_text_contains: List[str]
    unavailable_text_contains: List[str]
    poll_interval_seconds: float
    max_run_minutes: float
    headless: bool
    wait_until: str
    navigation_timeout_ms: int
    user_agent: Optional[str]
    extra_headers: Dict[str, str]
    cookies: List[Dict]
    save_screenshot_on_click: Optional[str]


def load_config(path: str) -> BotConfig:
    with open(path, "r", encoding="utf-8") as f:
        data = yaml.safe_load(f) or {}

    url = data.get("url")
    click_selector = data.get("click_selector")
    check_selector = data.get("check_selector")
    if not url or not click_selector:
        raise ValueError("Config must include 'url' and 'click_selector'.")

    available_text_contains = data.get("available_text_contains") or []
    unavailable_text_contains = data.get("unavailable_text_contains") or []

    return BotConfig(
        url=str(url),
        check_selector=str(check_selector) if check_selector else None,
        click_selector=str(click_selector),
        available_text_contains=[str(x) for x in available_text_contains],
        unavailable_text_contains=[str(x) for x in unavailable_text_contains],
        poll_interval_seconds=float(data.get("poll_interval_seconds", 2.5)),
        max_run_minutes=float(data.get("max_run_minutes", 20)),
        headless=bool(data.get("headless", True)),
        wait_until=str(data.get("wait_until", "domcontentloaded")),
        navigation_timeout_ms=int(data.get("navigation_timeout_ms", 30000)),
        user_agent=(str(data.get("user_agent")) if data.get("user_agent") else None),
        extra_headers=dict(data.get("extra_headers", {})),
        cookies=list(data.get("cookies", [])),
        save_screenshot_on_click=(
            str(data.get("save_screenshot_on_click")) if data.get("save_screenshot_on_click") else None
        ),
    )


def is_button_available_text(text: str, available_keywords: List[str], unavailable_keywords: List[str]) -> Optional[bool]:
    lower_text = (text or "").lower()
    for word in unavailable_keywords:
        if word.lower() in lower_text:
            return False
    for word in available_keywords:
        if word.lower() in lower_text:
            return True
    return None


def run_bot(config: BotConfig) -> int:
    deadline = time.monotonic() + (config.max_run_minutes * 60.0)
    with sync_playwright() as p:
        browser = p.chromium.launch(headless=config.headless)
        context_args: Dict = {}
        if config.user_agent:
            context_args["user_agent"] = config.user_agent
        if config.extra_headers:
            context_args["extra_http_headers"] = config.extra_headers

        context = browser.new_context(**context_args)
        if config.cookies:
            context.add_cookies(config.cookies)
        page = context.new_page()
        page.set_default_navigation_timeout(config.navigation_timeout_ms)
        page.set_default_timeout(config.navigation_timeout_ms)

        console.log(f"Navigating to {config.url}")
        page.goto(config.url, wait_until=config.wait_until)

        check_selector = config.check_selector or config.click_selector
        locator = page.locator(check_selector)
        click_locator = page.locator(config.click_selector)

        while time.monotonic() < deadline:
            try:
                count = locator.count()
                if count > 0 and locator.first.is_visible(timeout=2000):
                    text = locator.first.text_content(timeout=2000) or ""
                    text_signal = is_button_available_text(
                        text,
                        config.available_text_contains,
                        config.unavailable_text_contains,
                    )
                    visible_and_enabled = locator.first.is_enabled(timeout=2000)

                    is_available = False
                    if text_signal is not None:
                        is_available = text_signal
                    else:
                        is_available = visible_and_enabled

                    if is_available:
                        console.log("Button appears available. Attempting click…")
                        try:
                            click_locator.first.click(timeout=5000)
                            console.log("Clicked the button.")
                            if config.save_screenshot_on_click:
                                page.screenshot(path=config.save_screenshot_on_click)
                                console.log(f"Saved screenshot to {config.save_screenshot_on_click}")
                            return 0
                        except PlaywrightTimeoutError:
                            console.log("Click timed out. Will retry.")
                        except Exception as click_err:  # noqa: BLE001
                            console.log(f"Click error: {click_err}. Will retry.")

                console.log("Not available yet. Sleeping…")
                time.sleep(config.poll_interval_seconds)
                page.reload(wait_until=config.wait_until)
            except PlaywrightTimeoutError:
                console.log("Timeout while checking page. Reloading…")
                try:
                    page.reload(wait_until=config.wait_until)
                except Exception as reload_err:  # noqa: BLE001
                    console.log(f"Reload error: {reload_err}")
            except Exception as err:  # noqa: BLE001
                console.log(f"Unexpected error: {err}. Continuing…")
                time.sleep(config.poll_interval_seconds)

        console.log("Reached max run time without clicking. Exiting with code 2.")
        return 2


def parse_args(argv: List[str]) -> argparse.Namespace:
    parser = argparse.ArgumentParser(description="Watch a product page and click the add-to-cart button when available.")
    parser.add_argument("--config", default="config.yaml", help="Path to YAML config file")
    return parser.parse_args(argv)


def main(argv: List[str]) -> int:
    args = parse_args(argv)
    try:
        config = load_config(args.config)
    except Exception as e:  # noqa: BLE001
        console.print(f"[red]Failed to load config:[/red] {e}")
        return 1

    try:
        return run_bot(config)
    except KeyboardInterrupt:
        console.print("Interrupted by user.")
        return 130
    except Exception as e:  # noqa: BLE001
        console.print(f"[red]Fatal error:[/red] {e}")
        return 1


if __name__ == "__main__":
    sys.exit(main(sys.argv[1:]))

