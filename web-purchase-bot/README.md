Web Purchase Click Bot (Playwright, Python)

This small tool watches a product page and clicks the add‑to‑cart button as soon as it becomes available. You supply the URL and selectors in a YAML config.

Usage

1) Create venv and install deps

```bash
cd /workspace/web-purchase-bot
python3 -m venv .venv
source .venv/bin/activate
pip install -r requirements.txt
python -m playwright install chromium
```

2) Configure

```bash
cp config.example.yaml config.yaml
# Edit config.yaml: set url, selectors, and polling settings
```

3) Run

```bash
python bot.py --config config.yaml
```

Notes

- Respect the website's Terms of Service and rate limits. Use reasonable polling intervals.
- If the site requires login or uses advanced bot protection, you may need to adapt the script (e.g., manual login in a non‑headless window, setting cookies/headers, or adding delays).
- For debugging, you can run headful by setting `headless: false` in `config.yaml`.
