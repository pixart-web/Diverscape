// Capturas para a documentação e apresentação: node screenshots.mjs  →  ../docs/capturas/
import { chromium } from 'playwright';
import { mkdirSync, readFileSync } from 'node:fs';
import { chromiumPath } from './browser.mjs';

const base = process.env.DVS_URL || 'http://diverscape.localhost:8093';
const out = new URL('../docs/capturas/', import.meta.url).pathname;
mkdirSync(out, { recursive: true });
const b = await chromium.launch({ executablePath: chromiumPath() });

async function settle(page) {
  await page.evaluate(() => document.querySelectorAll('img').forEach((i) => { i.loading = 'eager'; if (i.sizes) i.sizes = i.sizes.replace(/^auto,\s*/, ''); }));
  await page.evaluate(async () => { for (let y = 0; y < document.body.scrollHeight; y += 700) { window.scrollTo(0, y); await new Promise((r) => setTimeout(r, 40)); } window.scrollTo(0, 0); });
  await page.waitForLoadState('networkidle');
  await page.evaluate(() => Promise.all([...document.images].filter((i) => i.src).map((i) => i.decode().catch(() => null))));
  await page.waitForTimeout(400);
}

async function shoot(name, path, { width = 1440, height = 900, full = false, before, scrollTo } = {}) {
  const ctx = await b.newContext({ viewport: { width, height }, deviceScaleFactor: width < 700 ? 2 : 1, reducedMotion: 'reduce' });
  const page = await ctx.newPage();
  await page.goto(path.startsWith('http') ? path : base + path, { waitUntil: 'networkidle' });
  await settle(page);
  if (before) await before(page);
  if (scrollTo) { await page.locator(scrollTo).first().evaluate((el) => el.scrollIntoView({ block: 'start' })); await page.waitForTimeout(300); }
  await page.screenshot({ path: `${out}${name}.png`, fullPage: full });
  await ctx.close();
  console.log('✓', name);
}

// Site novo
await shoot('01-homepage-desktop', '/');
await shoot('02-homepage-desktop-completa', '/', { full: true });
await shoot('03-homepage-mobile', '/', { width: 390, height: 844 });
await shoot('04-homepage-mobile-completa', '/', { width: 390, height: 844, full: true });
await shoot('05-homepage-pt-desktop', '/pt/');
await shoot('06-catalogo-desktop', '/courses/', { scrollTo: '#catalog' });
await shoot('07-catalogo-filtrado-mobile', '/pt/cursos/?category=specialties', { width: 390, height: 844, scrollTo: '#catalog' });
await shoot('08-detalhe-desktop', '/experiences/discover-scuba-diving/', { full: true });
await shoot('09-detalhe-mobile', '/de/kurse/nitrox/', { width: 390, height: 844, full: true });
await shoot('10-precos-desktop', '/prices/', { scrollTo: '.dvs-prices' });
await shoot('11-precos-mobile', '/sp/precios/', { width: 390, height: 844, scrollTo: '#prices-courses' });
await shoot('12-pacotes-certificados', '/certified-divers/', { scrollTo: '#packages' });
await shoot('13-pedido-passo1-preselecionado', '/contact/?offer=boat-dives&variant=6-equip', { scrollTo: '#request' });
await shoot('14-pedido-erros', '/pt/contactos/', {
  scrollTo: '#request',
  before: async (p) => { await p.locator('#f-offer').selectOption('open-water-diver'); await p.click('.dvs-step.is-active [data-next]'); await p.click('.dvs-step.is-active [data-next]'); await p.locator('#f-email').fill('nome@'); await p.click('.dvs-step.is-active [data-next]'); },
});
await shoot('15-pedido-resumo', '/contact/?offer=discover-scuba-diving&variant=boat', {
  scrollTo: '#request',
  before: async (p) => {
    await p.click('.dvs-step.is-active [data-next]');
    await p.locator('#f-people').fill('2');
    await p.click('.dvs-step.is-active [data-next]');
    await p.locator('#f-name').fill('Alex Example'); await p.locator('#f-email').fill('alex@example.com');
    await p.click('.dvs-step.is-active [data-next]');
  },
});
await shoot('16-pedido-resultado-demonstracao', '/contact/?offer=discover-scuba-diving', {
  scrollTo: '#request',
  before: async (p) => {
    await p.click('.dvs-step.is-active [data-next]'); await p.click('.dvs-step.is-active [data-next]');
    await p.locator('#f-name').fill('Alex Example'); await p.locator('#f-email').fill('alex@example.com');
    await p.click('.dvs-step.is-active [data-next]'); await p.locator('#f-privacy').check();
    await p.waitForTimeout(3200); await p.click('[data-submit]'); await p.waitForSelector('.dvs-request__result');
  },
});
await shoot('17-galeria-lightbox', '/gallery/', { before: async (p) => { await p.locator('.dvs-gallery__link').nth(2).click(); await p.waitForTimeout(500); } });
await shoot('18-menu-mobile', '/sp/', { width: 390, height: 844, before: async (p) => { await p.click('[data-menu-toggle]'); await p.waitForTimeout(400); } });
await shoot('19-sagres-desktop', '/discover-sagres/', { full: true });
await shoot('20-404-pt', '/pt/pagina-antiga/');

// Painel (credenciais de teste locais)
const cred = Object.fromEntries(readFileSync(new URL('../.credentials.local', import.meta.url), 'utf8').trim().split('\n').map((l) => l.split('=')));
const ctx = await b.newContext({ viewport: { width: 1440, height: 900 } });
const page = await ctx.newPage();
await page.goto(`${base}/wp-login.php`);
await page.fill('#user_login', cred.USER); await page.fill('#user_pass', cred.PASS); await page.click('#wp-submit');
await page.waitForURL(/wp-admin/);
await page.goto(`${base}/wp-admin/edit.php?post_type=dvs_item&orderby=menu_order&order=asc`);
await page.screenshot({ path: `${out}21-painel-catalogo.png` });
const id = (await page.locator('tr:has-text("Discover Scuba") a.row-title').first().getAttribute('href')).match(/post=(\d+)/)[1];
await page.goto(`${base}/wp-admin/post.php?post=${id}&action=edit`);
await page.waitForSelector('#dvs-item');
await page.screenshot({ path: `${out}22-painel-item-precos.png`, fullPage: true });
await page.goto(`${base}/wp-admin/edit.php?post_type=page&dvs_lang=pt`);
await page.screenshot({ path: `${out}23-painel-paginas-idioma.png` });
console.log('✓ painel');
await ctx.close();

// Site atual (antes) — apenas leitura
await shoot('00-antes-diverscape-net-desktop', 'https://diverscape.net/', { full: false });
await shoot('00-antes-diverscape-net-mobile', 'https://diverscape.net/pt/', { width: 390, height: 844 });
await b.close();
