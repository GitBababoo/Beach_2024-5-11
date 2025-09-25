const { chromium, firefox, webkit } = require('playwright');

function resolveBrowser(browserName) {
  const name = (browserName || process.env.BROWSER || 'chromium').toLowerCase();
  if (name === 'firefox') return firefox;
  if (name === 'webkit') return webkit;
  return chromium;
}

async function withRetries(task, maxRetries) {
  let attempt = 0;
  // eslint-disable-next-line no-constant-condition
  while (true) {
    try {
      return await task();
    } catch (error) {
      attempt += 1;
      if (attempt > maxRetries) throw error;
    }
  }
}

async function runBot(options) {
  const {
    url,
    selector,
    waitUntil = 'domcontentloaded',
    headless = true,
    timeoutMs = 30000,
    retries = 0,
    clickCount = 1,
    browser: browserName,
  } = options;

  const browserType = resolveBrowser(browserName);

  return withRetries(async () => {
    const browser = await browserType.launch({ headless });
    const context = await browser.newContext();
    const page = await context.newPage();
    try {
      await page.goto(url, { waitUntil, timeout: timeoutMs });

      const target = await page.waitForSelector(selector, { timeout: timeoutMs, state: 'visible' });
      for (let i = 0; i < clickCount; i += 1) {
        await target.click({ timeout: timeoutMs });
      }

      // Optional: keep page open briefly if headful for visual confirmation
      if (!headless) {
        await page.waitForTimeout(1000);
      }
    } finally {
      await page.close();
      await context.close();
      await browser.close();
    }
  }, retries);
}

module.exports = { runBot };

