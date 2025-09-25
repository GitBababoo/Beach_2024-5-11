const path = require('path');
const fs = require('fs');
require('dotenv').config({ path: path.resolve(process.cwd(), '.env') });

const { runBot } = require('./robot');

async function main() {
  const configPath = process.env.CONFIG_PATH || path.resolve(process.cwd(), 'config.json');

  let config = {};
  if (fs.existsSync(configPath)) {
    try {
      const raw = fs.readFileSync(configPath, 'utf-8');
      config = JSON.parse(raw);
    } catch (error) {
      console.error('Failed to read config.json:', error);
      process.exit(1);
    }
  }

  const url = process.env.URL || config.url;
  const selector = process.env.SELECTOR || config.selector;
  const waitUntil = process.env.WAIT_UNTIL || config.waitUntil || 'domcontentloaded';
  const headless = (process.env.HEADLESS || `${config.headless ?? 'true'}`).toLowerCase() !== 'false';
  const timeoutMs = Number(process.env.TIMEOUT_MS || config.timeoutMs || 30000);
  const retries = Number(process.env.RETRIES || config.retries || 0);
  const clickCount = Number(process.env.CLICK_COUNT || config.clickCount || 1);

  if (!url) {
    console.error('Missing URL. Set URL in .env or config.json');
    process.exit(1);
  }
  if (!selector) {
    console.error('Missing SELECTOR. Set SELECTOR in .env or config.json');
    process.exit(1);
  }

  await runBot({ url, selector, waitUntil, headless, timeoutMs, retries, clickCount });
}

main().catch((err) => {
  console.error(err);
  process.exit(1);
});

