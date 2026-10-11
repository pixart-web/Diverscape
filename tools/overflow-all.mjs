// Verifica overflow horizontal em todas as URLs do mapa do site, a várias larguras.
import { chromium } from 'playwright';
import { chromiumPath } from './browser.mjs';
const base = process.env.DVS_URL || 'http://diverscape.localhost:8093';
const widths = (process.argv[2] || '360,768').split(',').map(Number);
const b = await chromium.launch({ executablePath: chromiumPath() });
const p0 = await b.newPage();
const idx = await (await p0.request.get(base + '/wp-sitemap.xml')).text();
let urls = [];
for (const m of [...idx.matchAll(/<loc>([^<]+)<\/loc>/g)].map((x) => x[1])) urls.push(...[...(await (await p0.request.get(m)).text()).matchAll(/<loc>([^<]+)<\/loc>/g)].map((x) => x[1]));
const bad = [];
for (const w of widths) {
  const page = await b.newPage({ viewport: { width: w, height: 800 } });
  for (const u of urls) { await page.goto(u, { waitUntil: 'domcontentloaded' }); const d = await page.evaluate(() => document.documentElement.scrollWidth - document.documentElement.clientWidth); if (d > 0) bad.push(`${w} ${u} +${d}px`); }
  await page.close();
}
console.log(`${urls.length} URLs × ${widths.length} larguras; com overflow: ${bad.length}`);
bad.forEach((x) => console.log(x));
await b.close();
