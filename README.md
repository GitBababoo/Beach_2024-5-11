## Click Bot (Playwright)

A small, configurable bot that opens a page and clicks a target element (e.g., a product button) using Playwright.

### Quick start

1) Install dependencies and Chromium runtime:

```bash
npm install
npx playwright install chromium
```

2) Configure via `.env` or `config.json`:

- Copy `.env.example` to `.env` and edit values, or
- Edit `config.json` and keep `CONFIG_PATH` unset (defaults to `./config.json`).

Minimal required:

```bash
URL=https://example.com/product
SELECTOR=text=Add to cart
```

3) Run the bot:

```bash
npm run start
```

### Configuration

Environment variables (or same keys in `config.json`):

- `URL` (required): Page to open
- `SELECTOR` (required): Playwright selector to click (e.g., `text=Add to cart`, `#buy`, `data-test=buy`)
- `HEADLESS` (default: `true`): `true` or `false`
- `WAIT_UNTIL` (default: `domcontentloaded`): `load` | `domcontentloaded` | `networkidle`
- `TIMEOUT_MS` (default: `30000`)
- `RETRIES` (default: `0`): retry whole flow this many times
- `CLICK_COUNT` (default: `1`)
- `BROWSER` (default: `chromium`): `chromium` | `firefox` | `webkit`
- `CONFIG_PATH`: Absolute path to a JSON config file

### Notes

- Tune the `SELECTOR` to match the target site. Prefer stable selectors like `data-testid` when available.
- If a site uses async loading, consider `WAIT_UNTIL=networkidle` and increasing `TIMEOUT_MS`.
- For debugging, set `HEADLESS=false`.

