import { defineConfig } from '@playwright/test';
import { chromiumPath } from './browser.mjs';

export default defineConfig({
  testDir: './tests',
  timeout: 60_000,
  fullyParallel: false,
  workers: 1,
  reporter: [['list'], ['json', { outputFile: '../docs/testes/resultados.json' }]],
  use: {
    baseURL: process.env.DVS_URL || 'http://diverscape.localhost:8093',
    launchOptions: { executablePath: chromiumPath() },
    viewport: { width: 1440, height: 900 },
    actionTimeout: 10_000,
    navigationTimeout: 20_000,
  },
});
