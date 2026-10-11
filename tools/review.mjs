// Revisão rápida: capturas de página inteira em várias larguras + deteção de overflow horizontal,
// erros de consola, imagens partidas. Uso: node review.mjs <outDir> <largura,...> <caminho> [caminho…]
import { chromium } from 'playwright';
import { mkdirSync } from 'node:fs';
import { chromiumPath } from './browser.mjs';

const [out, widthsArg, ...paths] = process.argv.slice(2);
const base = process.env.DVS_URL || 'http://diverscape.localhost:8093';
const widths = widthsArg.split(',').map(Number);
mkdirSync(out, { recursive: true });
const browser = await chromium.launch({ executablePath: chromiumPath() });
const report = [];
for (const w of widths) {
  const ctx = await browser.newContext({ viewport: { width: w, height: w < 700 ? 800 : 900 }, reducedMotion: 'reduce', deviceScaleFactor: 1 });
  const page = await ctx.newPage();
  const errors = [];
  page.on('console', (m) => { if (m.type() === 'error') errors.push(m.text()); });
  page.on('pageerror', (e) => errors.push(String(e)));
  for (const p of paths) {
    errors.length = 0;
    const res = await page.goto(base + p, { waitUntil: 'networkidle' });
    await page.evaluate(() => document.querySelectorAll('img').forEach((i) => { i.loading = 'eager'; if (i.sizes) i.sizes = i.sizes.replace(/^auto,\s*/, ''); }));
    await page.evaluate(async () => { for (let y = 0; y < document.body.scrollHeight; y += 600) { window.scrollTo(0, y); await new Promise((r) => setTimeout(r, 30)); } window.scrollTo(0, 0); });
    await page.waitForLoadState('networkidle'); await page.evaluate(() => Promise.all([...document.images].filter((i) => i.src).map((i) => i.decode().catch(() => null)))); await page.waitForTimeout(300);
    const info = await page.evaluate(() => {
      const over = [];
      const W = document.documentElement.clientWidth;
      document.querySelectorAll('body *').forEach((el) => { const r = el.getBoundingClientRect(); if (r.width && r.right > W + 1 && getComputedStyle(el).position !== 'fixed' && !el.closest('.dvs-table-wrap, .dvs-prices__toc ul, dialog')) over.push(el.tagName.toLowerCase() + '.' + String(el.className).split(' ')[0]); });
      const broken = [...document.images].filter((i) => i.complete && i.naturalWidth === 0 && i.src).map((i) => i.src);
      return { scrollW: document.documentElement.scrollWidth, W, over: [...new Set(over)].slice(0, 6), broken, h1: document.querySelectorAll('h1').length };
    });
    const name = `${p.replace(/\W+/g, '_') || 'home'}_${w}.png`;
    await page.screenshot({ path: `${out}/${name}`, fullPage: true });
    report.push({ w, p, status: res.status(), ...info, errors: [...errors] });
  }
  await ctx.close();
}
await browser.close();
for (const r of report) {
  const flag = r.scrollW > r.W || r.over.length || r.broken.length || r.errors.length || r.h1 !== 1 ? '⚠' : '✓';
  console.log(flag, r.w, r.status, r.p, r.scrollW > r.W ? `overflow ${r.scrollW}>${r.W}` : '', r.over.join(' '), r.broken.join(' '), r.h1 !== 1 ? `h1=${r.h1}` : '', r.errors.join(' | ').slice(0, 300));
}
