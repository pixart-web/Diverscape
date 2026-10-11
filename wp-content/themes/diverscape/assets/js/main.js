/* DiversCape — interações do tema. Sem dependências; tudo funciona sem JavaScript (melhoria progressiva). */
(() => {
  'use strict';
  const T = window.dvsI18n || {};
  const $ = (s, c = document) => c.querySelector(s);
  const $$ = (s, c = document) => Array.from(c.querySelectorAll(s));
  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
  const desktop = window.matchMedia('(min-width: 1080px)');
  const fmt = (s, ...a) => { let i = 0; return String(s).replace(/%(\d\$)?[sd]/g, () => a[i++]); };
  const norm = (s) => String(s || '').toLowerCase().normalize('NFD').replace(/[̀-ͯ]/g, '');

  /* ------------------------------------------------------------ Cabeçalho e menu */
  const header = $('[data-header]');
  if (header) {
    const onScroll = () => header.classList.toggle('is-solid', window.scrollY > 40);
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });

    const toggle = $('[data-menu-toggle]', header);
    const label = $('[data-menu-label]', header);
    const nav = $('[data-nav]', header);
    const setMenu = (open, focus = true) => {
      header.classList.toggle('is-open', open);
      document.body.classList.toggle('menu-open', open);
      if (toggle) toggle.setAttribute('aria-expanded', String(open));
      if (label) label.textContent = open ? (T.menu_close || 'Close') : (T.menu || 'Menu');
      if (open && focus) { const first = $('a, button', nav); first && first.focus(); }
    };
    toggle && toggle.addEventListener('click', () => setMenu(!header.classList.contains('is-open')));
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && header.classList.contains('is-open')) { setMenu(false); toggle && toggle.focus(); }
    });
    desktop.addEventListener('change', (e) => { if (e.matches) setMenu(false, false); });
    nav && nav.addEventListener('click', (e) => { const a = e.target.closest('a'); if (a && a.hash && a.pathname === location.pathname) setMenu(false, false); });

    // Submenus: botão de divulgação; no desktop abre também ao passar o rato.
    const subs = $$('.site-nav__toggle', header);
    const closeAll = (except) => subs.forEach((b) => {
      if (b === except) return;
      b.setAttribute('aria-expanded', 'false');
      const m = document.getElementById(b.getAttribute('aria-controls'));
      m && m.classList.remove('is-open');
    });
    subs.forEach((btn) => {
      const menu = document.getElementById(btn.getAttribute('aria-controls'));
      const li = btn.closest('li');
      const set = (open) => { btn.setAttribute('aria-expanded', String(open)); menu && menu.classList.toggle('is-open', open); };
      btn.addEventListener('click', () => { const open = btn.getAttribute('aria-expanded') !== 'true'; closeAll(btn); set(open); });
      li && li.addEventListener('mouseenter', () => { if (desktop.matches) { closeAll(btn); set(true); } });
      li && li.addEventListener('mouseleave', () => { if (desktop.matches) set(false); });
      li && li.addEventListener('keydown', (e) => { if (e.key === 'Escape' && btn.getAttribute('aria-expanded') === 'true') { e.stopPropagation(); set(false); btn.focus(); } });
      li && li.addEventListener('focusout', (e) => { if (desktop.matches && !li.contains(e.relatedTarget)) set(false); });
    });
    document.addEventListener('click', (e) => { if (!e.target.closest('.site-nav__menu')) closeAll(); });

    // Seletor de idioma.
    const ls = $('[data-lang-switch]', header);
    if (ls) {
      const b = $('button', ls);
      const m = $('ul', ls);
      const set = (open) => { b.setAttribute('aria-expanded', String(open)); m.hidden = !open; };
      b.addEventListener('click', () => { const open = m.hidden; set(open); if (open) { const cur = $('[aria-current]', m) || $('a', m); cur && cur.focus(); } });
      ls.addEventListener('keydown', (e) => { if (e.key === 'Escape' && !m.hidden) { set(false); b.focus(); } });
      document.addEventListener('click', (e) => { if (!ls.contains(e.target)) set(false); });
      ls.addEventListener('focusout', (e) => { if (!ls.contains(e.relatedTarget)) set(false); });
    }
  }

  /* ------------------------------------------------------------ Entradas subtis */
  const reveals = $$('[data-reveal]');
  if ('IntersectionObserver' in window && !reduceMotion.matches) {
    const io = new IntersectionObserver((entries) => entries.forEach((en) => {
      if (en.isIntersecting) { en.target.classList.add('is-visible'); io.unobserve(en.target); }
    }), { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });
    reveals.forEach((el) => io.observe(el));
  } else {
    reveals.forEach((el) => el.classList.add('is-visible'));
  }

  /* ------------------------------------------------------------ Parallax ligeiro (desktop) */
  const plx = $$('[data-parallax]');
  if (plx.length && !reduceMotion.matches) {
    const wide = window.matchMedia('(min-width: 900px)');
    let ticking = false;
    const update = () => {
      ticking = false;
      if (!wide.matches) { plx.forEach((el) => { el.style.transform = ''; }); return; }
      const vh = window.innerHeight;
      plx.forEach((el) => {
        const r = el.parentElement.getBoundingClientRect();
        if (r.bottom < 0 || r.top > vh) return;
        const p = (r.top + r.height / 2 - vh / 2) / vh; // -1 … 1
        el.style.transform = `translate3d(0, ${(p * -36).toFixed(1)}px, 0)`;
      });
    };
    const req = () => { if (!ticking) { ticking = true; requestAnimationFrame(update); } };
    window.addEventListener('scroll', req, { passive: true });
    window.addEventListener('resize', req);
    update();
  }

  /* ------------------------------------------------------------ Galeria e lightbox */
  $$('[data-dvs-gallery]').forEach((g) => {
    const dialog = $('[data-lightbox]', g);
    if (!dialog || typeof dialog.showModal !== 'function') return;
    const links = $$('[data-index]', g);
    const img = $('[data-lb-img]', dialog);
    const cap = $('[data-lb-caption]', dialog);
    const count = $('[data-lb-count]', dialog);
    const tpl = $('[data-lb-count-tpl]', dialog);
    let idx = 0;
    let opener = null;
    const show = (i) => {
      idx = (i + links.length) % links.length;
      const a = links[idx];
      img.src = a.dataset.full;
      if (a.dataset.w) { img.width = a.dataset.w; img.height = a.dataset.h; }
      img.alt = a.dataset.caption || '';
      cap.textContent = a.dataset.caption || '';
      count.textContent = tpl ? tpl.innerHTML.replace('999', String(idx + 1)) : '';
    };
    links.forEach((a, i) => a.addEventListener('click', (e) => { e.preventDefault(); opener = a; show(i); dialog.showModal(); $('[data-lb-close]', dialog).focus(); }));
    $('[data-lb-prev]', dialog).addEventListener('click', () => show(idx - 1));
    $('[data-lb-next]', dialog).addEventListener('click', () => show(idx + 1));
    $('[data-lb-close]', dialog).addEventListener('click', () => dialog.close());
    dialog.addEventListener('keydown', (e) => {
      if (e.key === 'ArrowLeft') { e.preventDefault(); show(idx - 1); }
      if (e.key === 'ArrowRight') { e.preventDefault(); show(idx + 1); }
    });
    dialog.addEventListener('click', (e) => { if (e.target === dialog) dialog.close(); });
    dialog.addEventListener('close', () => { img.removeAttribute('src'); opener && opener.focus(); });
  });

  /* ------------------------------------------------------------ Catálogo: pesquisa e filtros */
  $$('[data-dvs-catalog]').forEach((root) => {
    const form = $('[data-filters]', root);
    const cards = $$('[data-results] > .dvs-card', root);
    const countEl = $('[data-count]', root);
    const empty = $('[data-empty]', root);
    const q = $('input[name="q"]', form);
    const level = $('select[name="level"]', form);
    const apply = (push = true) => {
      const needle = norm(q.value.trim());
      const cat = (form.querySelector('input[name="category"]:checked') || {}).value || '';
      const aud = level.value;
      let n = 0;
      cards.forEach((c) => {
        const ok = (!cat || c.dataset.category === cat) && (!aud || c.dataset.audience.split(' ').includes(aud)) && (!needle || c.dataset.search.includes(needle));
        c.hidden = !ok;
        if (ok) n++;
      });
      countEl.textContent = n === 1 ? countEl.dataset.one : countEl.dataset.many.replace('999', String(n));
      empty.hidden = n > 0;
      if (push) {
        const url = new URL(location.href);
        [['q', q.value.trim()], ['category', cat], ['level', aud]].forEach(([k, v]) => (v ? url.searchParams.set(k, v) : url.searchParams.delete(k)));
        history.replaceState(null, '', url.pathname + url.search + '#catalog');
      }
    };
    let t;
    q.addEventListener('input', () => { clearTimeout(t); t = setTimeout(apply, 120); });
    form.addEventListener('change', () => apply());
    form.addEventListener('submit', (e) => { e.preventDefault(); apply(); });
    $$('[data-filters-clear]', root).forEach((a) => a.addEventListener('click', (e) => {
      e.preventDefault();
      q.value = ''; level.value = '';
      const all = form.querySelector('input[name="category"][value=""]'); all && (all.checked = true);
      apply(); q.focus();
    }));
  });

  /* ------------------------------------------------------------ Pacotes de mergulho */
  $$('[data-packages]').forEach((form) => {
    let data = {};
    try { data = JSON.parse(form.dataset.packages); } catch (e) { return; }
    form.hidden = false;
    const price = $('[data-pkg-price]', form);
    const dur = $('[data-pkg-dur]', form);
    const cta = $('[data-pkg-cta]', form);
    const update = () => {
      const d = (form.querySelector('input[name="dives"]:checked') || {}).value;
      const e = (form.querySelector('input[name="equip"]:checked') || {}).value;
      const v = data[`${d}-${e}`];
      if (!v) return;
      price.textContent = v.price;
      dur.textContent = v.duration || '';
      cta.href = v.url;
      cta.setAttribute('aria-label', `${cta.textContent.trim()}: ${v.label}`);
    };
    form.addEventListener('change', update);
    form.addEventListener('submit', (e) => e.preventDefault());
    update();
  });

  /* ------------------------------------------------------------ Avaliações Tripadvisor (só após pedido do visitante) */
  $$('[data-dvs-reviews]').forEach((root) => {
    const btn = $('[data-reviews-load]', root);
    const status = $('[data-reviews-status]', root);
    const target = $('[data-reviews-target]', root);
    const consent = $('[data-reviews-consent]', root);
    if (!btn || !target) return;
    btn.hidden = false;
    btn.addEventListener('click', () => {
      btn.disabled = true;
      status.textContent = T.reviews_loading || 'Loading…';
      target.hidden = false;
      const initial = target.innerHTML;
      const fail = () => { status.textContent = T.reviews_failed || ''; target.hidden = true; btn.disabled = false; };
      const s = document.createElement('script');
      s.async = true;
      s.src = root.dataset.src;
      s.onerror = fail;
      document.body.appendChild(s);
      let tries = 0;
      const check = setInterval(() => {
        tries++;
        if (target.innerHTML !== initial && target.querySelector('img, iframe, .widSSP, [class*="wid"]')) {
          clearInterval(check); consent.hidden = true;
        } else if (tries > 32) { clearInterval(check); fail(); }
      }, 250);
    });
  });

  /* ------------------------------------------------------------ Pedido de disponibilidade em passos */
  $$('[data-dvs-request]').forEach((root) => {
    const form = $('[data-dvs-form]', root);
    if (!form) { const r = $('[data-dvs-result]', root); r && r.focus(); return; }
    const steps = $$('.dvs-step', form);
    const indicator = $('[data-dvs-steps]', root);
    const live = $('[data-dvs-live]', root);
    const errorsBox = $('[data-dvs-errors]', root);
    const total = steps.length;
    let current = 1;
    form.classList.add('is-stepped');
    indicator.hidden = false;

    const stepName = (n) => T[`step${n}`] || '';
    const show = (n, focus = true) => {
      current = Math.max(1, Math.min(total, n));
      steps.forEach((s) => s.classList.toggle('is-active', Number(s.dataset.step) === current));
      $$('[data-step-indicator]', indicator).forEach((li) => {
        const k = Number(li.dataset.stepIndicator);
        li.classList.toggle('is-current', k === current);
        li.classList.toggle('is-done', k < current);
        if (k === current) li.setAttribute('aria-current', 'step'); else li.removeAttribute('aria-current');
      });
      $$('[data-step-count]', form).forEach((el) => { el.textContent = fmt(T.step_of || 'Step %1$d of %2$d', current, total); });
      if (current === total) buildSummary();
      live.textContent = `${fmt(T.step_of || '', current, total)}: ${stepName(current)}`;
      if (focus) {
        const step = steps[current - 1];
        step.setAttribute('tabindex', '-1');
        step.focus({ preventScroll: true });
        root.scrollIntoView({ behavior: reduceMotion.matches ? 'auto' : 'smooth', block: 'start' });
      }
    };

    // Variantes: mostrar só as do item escolhido.
    const offer = $('#f-offer', form);
    const variant = $('#f-variant', form);
    const variantField = $('[data-variant-field]', form);
    const syncVariants = () => {
      let any = false;
      $$('optgroup', variant).forEach((g) => {
        const match = g.dataset.offer === offer.value;
        g.hidden = !match; g.disabled = !match;
        if (match) any = true;
      });
      if (variant.value && !variant.value.startsWith(`${offer.value}:`)) variant.value = '';
      variantField.hidden = !any;
    };
    offer.addEventListener('change', syncVariants);
    syncVariants();

    // Validação no cliente (o servidor valida sempre de novo).
    const setError = (field, msg) => {
      const wrap = field.closest('.dvs-field');
      let err = wrap && wrap.querySelector('.dvs-field__error');
      const id = `err-${field.name}`;
      if (msg) {
        if (!err) { err = document.createElement('p'); err.className = 'dvs-field__error'; err.id = id; wrap.appendChild(err); }
        err.textContent = msg;
        field.setAttribute('aria-invalid', 'true');
        const desc = (field.getAttribute('aria-describedby') || '').split(' ').filter((x) => x && x !== id);
        field.setAttribute('aria-describedby', [id, ...desc].join(' '));
      } else {
        err && err.remove();
        field.removeAttribute('aria-invalid');
        const desc = (field.getAttribute('aria-describedby') || '').split(' ').filter((x) => x && x !== id);
        desc.length ? field.setAttribute('aria-describedby', desc.join(' ')) : field.removeAttribute('aria-describedby');
      }
    };
    const check = (f) => {
      const v = (f.value || '').trim();
      switch (f.name) {
        case 'offer': return v ? '' : T.e_offer;
        case 'name': return v ? '' : T.e_required;
        case 'email': return !v ? T.e_required : (/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(v) ? '' : T.e_email);
        case 'phone': return !v || /^\+?[0-9 ()-]{6,20}$/.test(v) ? '' : T.e_phone;
        case 'people': { const n = Number(v); return Number.isInteger(n) && n >= 1 && n <= 20 ? '' : T.e_people; }
        case 'date': return !v || v >= f.min ? '' : T.e_date;
        case 'privacy': return f.checked ? '' : T.e_privacy;
        default: return '';
      }
    };
    const validateStep = (n) => {
      const fields = $$('input, select, textarea', steps[n - 1]).filter((f) => f.name && f.type !== 'hidden' && f.type !== 'radio');
      let first = null;
      fields.forEach((f) => { const msg = check(f); setError(f, msg); if (msg && !first) first = f; });
      return first;
    };
    // Revalidar enquanto se corrige (não no blur: remover a mensagem nesse momento desloca o botão sob o cursor).
    const recheck = (e) => { const f = e.target; if (f.name && f.getAttribute('aria-invalid') === 'true' && !check(f)) setError(f, ''); };
    form.addEventListener('input', recheck);
    form.addEventListener('change', recheck);

    form.addEventListener('click', (e) => {
      if (e.target.closest('[data-next]')) {
        const bad = validateStep(current);
        if (bad) { bad.focus(); return; }
        show(current + 1);
      }
      if (e.target.closest('[data-back]')) show(current - 1);
    });
    form.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' && e.target.tagName === 'INPUT' && current < total && e.target.type !== 'checkbox') { e.preventDefault(); $('[data-next]', steps[current - 1]).click(); }
    });

    const buildSummary = () => {
      const list = $('[data-dvs-summary-list]', form);
      const sel = (s) => (s && s.selectedIndex >= 0 ? s.options[s.selectedIndex] : null);
      const notSet = T.f_not_set || '—';
      const o = sel(offer);
      let offerTxt = o && o.value ? o.textContent.trim() : notSet;
      if (o && o.dataset.price && !variant.value) offerTxt += ` (${o.dataset.price})`;
      const v = sel(variant);
      if (v && v.value) offerTxt += ` — ${v.textContent.trim()}`;
      const dateV = $('#f-date', form).value;
      let dateTxt = notSet;
      if (dateV) { try { dateTxt = new Intl.DateTimeFormat(document.documentElement.lang, { dateStyle: 'long' }).format(new Date(`${dateV}T12:00:00`)); } catch (err) { dateTxt = dateV; } }
      const flex = form.querySelector('input[name="flex"]:checked');
      if (flex) dateTxt += ` · ${flex.closest('label').textContent.trim()}`;
      const lab = (id) => { const l = form.querySelector(`label[for="${id}"]`); return l ? l.firstChild.textContent.trim() : ''; };
      const val = (id) => { const el = $(`#${id}`, form); if (!el) return notSet; if (el.tagName === 'SELECT') { const op = sel(el); return op && op.value ? op.textContent.trim() : notSet; } return el.value.trim() || notSet; };
      const rows = [
        [lab('f-offer'), offerTxt], [lab('f-date'), dateTxt], [lab('f-people'), val('f-people')], [lab('f-level'), val('f-level')],
        [lab('f-name'), val('f-name')], [lab('f-email'), val('f-email')], [lab('f-phone'), val('f-phone')], [lab('f-reply_lang'), val('f-reply_lang')], [lab('f-message'), val('f-message')],
      ];
      list.innerHTML = '';
      rows.forEach(([k, v2]) => { const d = document.createElement('div'); const dt = document.createElement('dt'); const dd = document.createElement('dd'); dt.textContent = k; dd.textContent = v2; d.append(dt, dd); list.appendChild(d); });
      $('[data-dvs-summary]', form).hidden = false;
    };

    const showServerErrors = (res) => {
      errorsBox.innerHTML = '';
      const p = document.createElement('p'); const strong = document.createElement('strong'); strong.textContent = res.message || T.e_spam; p.appendChild(strong); errorsBox.appendChild(p);
      if (res.errors && Object.keys(res.errors).length) {
        const ul = document.createElement('ul');
        Object.entries(res.errors).forEach(([name, msg]) => {
          const f = form.elements[name];
          const field = f && (f.length && !f.tagName ? f[0] : f);
          field && setError(field, msg);
          const li = document.createElement('li'); const a = document.createElement('a'); a.href = `#f-${name}`; a.textContent = msg;
          a.addEventListener('click', (ev) => { ev.preventDefault(); show(res.steps ? res.steps[name] : 1, false); field && field.focus(); });
          li.appendChild(a); ul.appendChild(li);
        });
        errorsBox.appendChild(ul);
        const firstStep = Math.min(...Object.values(res.steps || { x: 1 }));
        show(firstStep, false);
      }
      errorsBox.hidden = false;
      errorsBox.focus();
    };

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      for (let n = 1; n <= total; n++) {
        const bad = validateStep(n);
        if (bad) { show(n, false); bad.focus(); return; }
      }
      const submit = $('[data-submit]', form);
      submit.setAttribute('aria-busy', 'true');
      submit.textContent = T.f_sending || '…';
      errorsBox.hidden = true;
      try {
        const resp = await fetch(root.dataset.rest, { method: 'POST', body: new FormData(form), credentials: 'same-origin', headers: { Accept: 'application/json' } });
        const res = await resp.json();
        if (res && res.ok) {
          const box = document.createElement('div');
          box.className = 'dvs-request__result'; box.setAttribute('role', 'status'); box.tabIndex = -1;
          const h = document.createElement('h2'); h.className = 'dvs-request__result-title'; h.textContent = res.title;
          const p = document.createElement('p'); p.textContent = res.message;
          const again = document.createElement('p'); const a = document.createElement('a'); a.className = 'dvs-btn dvs-btn--ghost'; a.href = location.pathname + '#request'; a.textContent = T.f_new_request || 'New request'; again.appendChild(a);
          box.append(h, p, again);
          root.innerHTML = ''; root.appendChild(box); box.focus();
          root.scrollIntoView({ behavior: reduceMotion.matches ? 'auto' : 'smooth', block: 'start' });
        } else {
          showServerErrors(res || {});
        }
      } catch (err) {
        showServerErrors({ message: T.e_spam });
      } finally {
        submit.removeAttribute('aria-busy');
        submit.textContent = T.f_submit || 'Send';
      }
    });

    // Abrir no passo do primeiro erro devolvido pelo servidor (envio sem JavaScript).
    const firstInvalid = $('[aria-invalid="true"]', form);
    const startStep = firstInvalid ? Number(firstInvalid.closest('.dvs-step').dataset.step) : 1;
    show(startStep, false);
    if (!errorsBox.hidden) errorsBox.focus();
  });
})();
