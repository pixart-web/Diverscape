// Testes end-to-end da proposta DiversCape. Correr com: npx playwright test
import { test, expect } from '@playwright/test';
import AxeBuilder from '@axe-core/playwright';
import { execSync } from 'node:child_process';

const BASE = process.env.DVS_URL || 'http://diverscape.localhost:8093';
const ROOT = new URL('../..', import.meta.url).pathname;
const wp = (cmd) => execSync(`docker-compose run --rm -T cli wp ${cmd}`, { cwd: ROOT, stdio: ['ignore', 'pipe', 'ignore'] }).toString().trim();

async function sitemapUrls(request) {
  const index = await (await request.get('/wp-sitemap.xml')).text();
  const maps = [...index.matchAll(/<loc>([^<]+)<\/loc>/g)].map((m) => m[1]);
  const urls = [];
  for (const m of maps) {
    const xml = await (await request.get(m)).text();
    urls.push(...[...xml.matchAll(/<loc>([^<]+)<\/loc>/g)].map((x) => x[1]));
  }
  return urls;
}

test.describe('Conteúdo e SEO', () => {
  test('todas as páginas do mapa do site respondem 200, com um H1, título, descrição, canonical e hreflang', async ({ page, request }) => {
    const urls = await sitemapUrls(request);
    expect(urls.length).toBe(60 + 136);
    const problems = [];
    for (const url of urls) {
      const res = await page.goto(url, { waitUntil: 'domcontentloaded' });
      const info = await page.evaluate(() => ({
        h1: document.querySelectorAll('h1').length,
        title: document.title,
        desc: document.querySelector('meta[name="description"]')?.content || '',
        canonical: document.querySelector('link[rel="canonical"]')?.href || '',
        alts: [...document.querySelectorAll('link[rel="alternate"][hreflang]')].map((l) => l.hreflang).sort().join(','),
        lang: document.documentElement.lang,
        robots: document.querySelector('meta[name="robots"]')?.content || '',
      }));
      const expectedLang = url.includes('/pt/') ? 'pt-PT' : url.includes('/de/') ? 'de' : url.includes('/sp/') ? 'es' : 'en';
      if (res.status() !== 200) problems.push(`${url} status ${res.status()}`);
      if (info.h1 !== 1) problems.push(`${url} h1=${info.h1}`);
      if (!info.title || info.desc.length < 20) problems.push(`${url} título/descrição`);
      if (info.canonical !== url) problems.push(`${url} canonical ${info.canonical}`);
      if (info.alts !== 'de,en,es,pt-PT,x-default') problems.push(`${url} hreflang ${info.alts}`);
      if (info.lang !== expectedLang) problems.push(`${url} lang ${info.lang}`);
      if (!info.robots.includes('noindex')) problems.push(`${url} sem noindex (demonstração)`);
    }
    expect(problems).toEqual([]);
  });

  test('sem ligações internas partidas', async ({ page, request }) => {
    const pages = ['/', '/pt/', '/de/', '/sp/', '/courses/', '/pt/cursos/', '/prices/', '/sp/precios/', '/de/preise/', '/certified-divers/', '/discover-sagres/', '/contact/', '/faq/'];
    const links = new Set();
    for (const p of pages) {
      await page.goto(p);
      (await page.$$eval('a[href]', (as) => as.map((a) => a.href))).forEach((h) => { if (h.startsWith(BASE)) links.add(h.split('#')[0]); });
    }
    const broken = [];
    for (const l of links) {
      const r = await request.get(l, { maxRedirects: 5 });
      if (r.status() >= 400) broken.push(`${r.status()} ${l}`);
    }
    expect(broken).toEqual([]);
    expect(links.size).toBeGreaterThan(80);
  });

  test('o seletor de idioma leva à tradução da página atual', async ({ page }) => {
    await page.goto('/pt/cursos/mergulho-profundo/');
    const hrefs = await page.$$eval('#lang-menu a', (as) => Object.fromEntries(as.map((a) => [a.hreflang, new URL(a.href).pathname])));
    expect(hrefs).toEqual({ en: '/courses/deep-diver/', 'pt-PT': '/pt/cursos/mergulho-profundo/', de: '/de/kurse/deep-diver/', es: '/sp/cursos/buceo-profundo/' });
    await page.getByRole('button', { name: /Escolher idioma/ }).click();
    await page.getByRole('link', { name: 'Deutsch' }).first().click();
    await expect(page).toHaveURL(/\/de\/kurse\/deep-diver\/$/);
    await expect(page.locator('h1')).toHaveText('PADI Deep Diver');
  });

  test('âncoras históricas da página única existem na homepage de cada idioma', async ({ page }) => {
    for (const p of ['/', '/pt/', '/de/', '/sp/']) {
      await page.goto(p);
      for (const id of ['frontpa', 'Gallery', 'coursesprices', 'location']) await expect(page.locator(`#${id}`)).toHaveCount(1);
    }
  });

  test('404 localizado', async ({ page }) => {
    const r = await page.goto('/pt/pagina-que-nao-existe/');
    expect(r.status()).toBe(404);
    await expect(page.locator('h1')).toHaveText('Esta página foi levada pela corrente');
    const r2 = await page.goto('/sp/nada/');
    expect(r2.status()).toBe(404);
    await expect(page.locator('h1')).toHaveText('Esta página se la llevó la corriente');
  });

  test('demonstração não indexável', async ({ request }) => {
    const r = await request.get('/');
    expect(r.headers()['x-robots-tag']).toContain('noindex');
    expect(await (await request.get('/robots.txt')).text()).toContain('Disallow: /');
  });
});

test.describe('Catálogo central', () => {
  test('pesquisa, categoria, nível e estado vazio', async ({ page }) => {
    await page.goto('/courses/');
    const visible = () => page.locator('[data-results] > .dvs-card:visible');
    await expect(visible()).toHaveCount(34);
    await page.getByLabel('Search courses and experiences').fill('nitrox');
    await expect(visible()).toHaveCount(2); // Enriched Air Nitrox + Gas Blender (variante Nitrox)
    await expect(page.locator('[data-count]')).toHaveText('2 results');
    await page.getByLabel('Search courses and experiences').fill('rescue');
    await expect(visible()).toHaveCount(1);
    await expect(page.locator('[data-count]')).toHaveText('1 result');
    await page.getByLabel('Search courses and experiences').fill('');
    await page.locator('.dvs-chip', { hasText: 'Specialties' }).click();
    await expect(visible()).toHaveCount(12);
    await expect(page).toHaveURL(/category=specialties/);
    await page.locator('.dvs-chip', { hasText: 'All' }).click();
    await page.getByLabel('Your experience').selectOption('tec');
    await expect(visible()).toHaveCount(8);
    await page.getByLabel('Search courses and experiences').fill('zzzz');
    await expect(page.locator('[data-empty]')).toBeVisible();
    await page.locator('[data-empty] [data-filters-clear]').click();
    await expect(visible()).toHaveCount(34);
  });

  test('filtros funcionam sem JavaScript (servidor)', async ({ browser }) => {
    const ctx = await browser.newContext({ javaScriptEnabled: false, reducedMotion: 'reduce' });
    const page = await ctx.newPage();
    await page.goto(`${BASE}/pt/cursos/?category=technical`);
    await expect(page.locator('[data-results] > .dvs-card:visible')).toHaveCount(8);
    await page.goto(`${BASE}/de/kurse/?q=wrack`);
    await expect(page.locator('[data-results] > .dvs-card:visible')).toHaveCount(1);
    await ctx.close();
  });

  test('um preço alterado no catálogo reflete-se em todas as línguas e vistas', async ({ page }) => {
    const original = 105;
    const setPrice = (v) => wp(`eval '$p=get_page_by_path("discover-scuba-diving",OBJECT,"dvs_item"); $d=get_post_meta($p->ID,"_dvs_item",true); $d["variants"][0]["price"]=${v}; update_post_meta($p->ID,"_dvs_item",$d);'`);
    try {
      setPrice(99);
      const checks = [
        ['/', 'from €99'], ['/pt/', 'desde 99 €'], ['/de/', 'ab 99 €'], ['/sp/', 'desde 99 €'],
        ['/experiences/discover-scuba-diving/', '€99'], ['/pt/experiencias/batismo-de-mergulho/', '99 €'],
        ['/prices/', '€99'], ['/sp/precios/', '99 €'],
      ];
      for (const [url, text] of checks) {
        await page.goto(url);
        await expect(page.locator('body')).toContainText(text);
      }
      await page.goto('/de/kontakt/?offer=discover-scuba-diving');
      await expect(page.locator('#f-variant optgroup[data-offer="discover-scuba-diving"] option').first()).toContainText('99 €');
    } finally {
      setPrice(original);
    }
  });

  test('pacotes de mergulho: escolha atualiza preço e pedido', async ({ page }) => {
    await page.goto('/certified-divers/');
    const picker = page.locator('[data-packages]');
    await picker.locator('label', { hasText: '6 dives' }).click();
    await picker.locator('label', { hasText: 'Rental included' }).click();
    await expect(page.locator('[data-pkg-price]')).toHaveText('€335');
    await expect(page.locator('[data-pkg-cta]')).toHaveAttribute('href', /offer=boat-dives&variant=6-equip/);
  });
});

test.describe('Pedido de disponibilidade (modo demonstração)', () => {
  test.beforeEach(() => { wp('transient delete --all'); });
  test('pré-seleção, validação por passos, resumo e resultado de demonstração', async ({ page, request }) => {
    const before = await (await request.get('http://localhost:8029/api/v1/messages')).json().catch(() => ({ total: 0 }));
    await page.goto('/certified-divers/boat-dives/');
    await page.locator('#options').getByRole('link', { name: /6 boat dives · equipment included/ }).click();
    await expect(page.locator('#f-offer')).toHaveValue('boat-dives');
    await expect(page.locator('#f-variant')).toHaveValue('boat-dives:6-equip');
    await page.getByRole('button', { name: 'Continue' }).first().click();
    await expect(page.locator('legend', { hasText: 'Dates and group' })).toBeVisible();
    await page.locator('#f-people').fill('0');
    await page.locator('.dvs-step.is-active').getByRole('button', { name: 'Continue' }).click();
    await expect(page.locator('#err-people')).toHaveText('Enter a number between 1 and 20.');
    await expect(page.locator('#f-people')).toBeFocused();
    await page.locator('#f-people').fill('2');
    await page.locator('.dvs-step.is-active').getByRole('button', { name: 'Continue' }).click();
    await page.locator('#f-email').fill('not-an-email');
    await page.locator('.dvs-step.is-active').getByRole('button', { name: 'Continue' }).click();
    await expect(page.locator('#err-name')).toBeVisible();
    await expect(page.locator('#f-name')).toHaveAttribute('aria-invalid', 'true');
    await page.locator('#f-name').fill('Test Diver');
    await page.locator('#f-email').fill('test@example.com');
    await page.locator('.dvs-step.is-active').getByRole('button', { name: 'Continue' }).click();
    await expect(page.locator('[data-dvs-summary]')).toContainText('6 boat dives · equipment included');
    await expect(page.locator('[data-dvs-summary]')).toContainText('test@example.com');
    await page.getByRole('button', { name: 'Send request' }).click();
    await expect(page.locator('#err-privacy')).toBeVisible();
    await page.locator('#f-privacy').check();
    await page.waitForTimeout(3200);
    await page.getByRole('button', { name: 'Send request' }).click();
    await expect(page.locator('.dvs-request__result')).toContainText('not sent (demonstration)');
    await expect(page.locator('.dvs-request__result')).toBeFocused();
    const after = await (await request.get('http://localhost:8029/api/v1/messages')).json().catch(() => ({ total: 0 }));
    expect(after.total).toBe(before.total);
  });

  test('“ainda não sei” e textos localizados em português', async ({ page }) => {
    await page.goto('/pt/contactos/');
    await expect(page.locator('label[for="f-offer"]')).toContainText('O que gostaria de fazer?');
    await page.getByRole('button', { name: 'Continuar' }).first().click();
    await expect(page.locator('#err-offer')).toHaveText('Escolha uma atividade ou «ainda não sei».');
    await page.locator('#f-offer').selectOption('unsure');
    await expect(page.locator('[data-variant-field]')).toBeHidden();
    await page.locator('.dvs-step.is-active').getByRole('button', { name: 'Continuar' }).click();
    await expect(page.locator('legend', { hasText: 'Datas e grupo' })).toBeVisible();
  });

  test('sem JavaScript: validação no servidor e resultado de demonstração', async ({ browser }) => {
    const ctx = await browser.newContext({ javaScriptEnabled: false, reducedMotion: 'reduce' });
    const page = await ctx.newPage();
    await page.goto(`${BASE}/de/kontakt/`);
    await page.getByRole('button', { name: 'Anfrage senden' }).click();
    await expect(page.locator('.dvs-request__errors')).toContainText('Bitte prüfen Sie die markierten Felder');
    await page.locator('#f-offer').selectOption('open-water-diver');
    await page.locator('#f-name').fill('Testperson');
    await page.locator('#f-email').fill('test@example.com');
    await page.locator('#f-privacy').check();
    await page.waitForTimeout(3200);
    await page.getByRole('button', { name: 'Anfrage senden' }).click();
    await expect(page.locator('.dvs-request__result')).toContainText('nicht gesendet (Demo)');
    await ctx.close();
  });

  test('o servidor rejeita campo isco e envios demasiado rápidos', async ({ request, page }) => {
    await page.goto('/contact/');
    const token = await page.locator('input[name="dvs_token"]').inputValue();
    const base = { offer: 'unsure', people: '1', name: 'x', email: 'x@example.com', privacy: '1', lang: 'en', dvs_token: token };
    const fast = await request.post('/wp-json/diverscape/v1/request', { form: base });
    expect(fast.status()).toBe(400);
    await page.waitForTimeout(3200);
    const bot = await request.post('/wp-json/diverscape/v1/request', { form: { ...base, website: 'http://spam' } });
    expect(bot.status()).toBe(400);
    const ok = await request.post('/wp-json/diverscape/v1/request', { form: base });
    expect(ok.status()).toBe(200);
    expect((await ok.json()).mode).toBe('demo');
  });
});

test.describe('Interação e acessibilidade', () => {
  test('galeria: lightbox com teclado, legenda, Escape e foco', async ({ page }) => {
    await page.goto('/gallery/');
    const first = page.locator('.dvs-gallery__link').first();
    await first.focus();
    await page.keyboard.press('Enter');
    const dialog = page.locator('dialog[data-lightbox]');
    await expect(dialog).toBeVisible();
    const cap1 = await dialog.locator('[data-lb-caption]').textContent();
    await page.keyboard.press('ArrowRight');
    await expect(dialog.locator('[data-lb-caption]')).not.toHaveText(cap1);
    await expect(dialog.locator('[data-lb-count]')).toContainText('2 of 19');
    await page.keyboard.press('Escape');
    await expect(dialog).toBeHidden();
    await expect(first).toBeFocused();
  });

  test('submenu acessível por teclado', async ({ page }) => {
    await page.goto('/');
    const toggle = page.getByRole('button', { name: 'Show submenu for Courses' });
    await toggle.focus();
    await page.keyboard.press('Enter');
    await expect(toggle).toHaveAttribute('aria-expanded', 'true');
    await expect(page.locator('#' + (await toggle.getAttribute('aria-controls')))).toBeVisible();
    await page.keyboard.press('Escape');
    await expect(toggle).toHaveAttribute('aria-expanded', 'false');
    await expect(toggle).toBeFocused();
  });

  test('menu móvel com idiomas e chamada para ação', async ({ browser }) => {
    const ctx = await browser.newContext({ viewport: { width: 390, height: 844 } });
    const page = await ctx.newPage();
    await page.goto(`${BASE}/sp/`);
    await page.getByRole('button', { name: 'Menú' }).click();
    const nav = page.locator('#site-nav');
    await expect(nav.getByRole('link', { name: 'Experiencias' }).first()).toBeVisible();
    await expect(nav.getByRole('link', { name: 'Planifica tu inmersión' })).toBeVisible();
    await expect(nav.locator('.site-nav__langs a')).toHaveCount(4);
    const box = await nav.boundingBox();
    expect(box.height).toBeGreaterThan(600); // o painel cobre o ecrã
    await expect(nav.locator('.site-nav__langs a').first()).toBeInViewport();
    await page.keyboard.press('Escape');
    await expect(nav.getByRole('link', { name: 'Experiencias' }).first()).toBeHidden();
    await ctx.close();
  });

  for (const width of [360, 390, 768, 1024, 1440]) {
    test(`sem overflow horizontal e com CTA visível a ${width}px`, async ({ browser }) => {
      const ctx = await browser.newContext({ viewport: { width, height: 900 } });
      const page = await ctx.newPage();
      for (const p of ['/', '/courses/', '/experiences/discover-scuba-diving/', '/certified-divers/', '/prices/', '/contact/', '/pt/', '/de/kurse/', '/sp/precios/', '/gallery/']) {
        await page.goto(BASE + p);
        const sw = await page.evaluate(() => document.documentElement.scrollWidth - document.documentElement.clientWidth);
        expect(sw, `${p} @${width}`).toBeLessThanOrEqual(0);
        const cta = width >= 1080 ? page.locator('.site-header__cta') : page.locator('.site-header__menu');
        await expect(cta, `${p} @${width}`).toBeVisible();
      }
      await ctx.close();
    });
  }

  for (const p of ['/', '/pt/cursos/', '/experiences/discover-scuba-diving/', '/certified-divers/', '/prices/', '/contact/', '/gallery/', '/de/haeufige-fragen/', '/sp/privacidad/']) {
    test(`axe (WCAG 2.2 AA) sem violações graves: ${p}`, async ({ page }) => {
      await page.emulateMedia({ reducedMotion: 'reduce' });
      await page.goto(p);
      const res = await new AxeBuilder({ page }).withTags(['wcag2a', 'wcag2aa', 'wcag21a', 'wcag21aa', 'wcag22aa']).analyze();
      const serious = res.violations.filter((v) => ['serious', 'critical'].includes(v.impact)).map((v) => `${v.id}: ${v.nodes.slice(0, 3).map((n) => n.target.join(' ')).join(' | ')}`);
      expect(serious).toEqual([]);
    });
  }
});
