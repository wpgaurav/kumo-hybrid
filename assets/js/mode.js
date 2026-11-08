/* Apply before first paint; localStorage may be unavailable in privacy modes. */
(() => {
  'use strict';
  const root = document.documentElement;
  root.classList.add('js');
  const media = window.matchMedia('(prefers-color-scheme: dark)');
  const valid = ['light', 'dark', 'system'];
  let preference = root.dataset.siteMode || 'light';
  try {
    const stored = localStorage.getItem('kumo-hybrid-mode');
    if (!root.hasAttribute('data-kumo-preview') && valid.includes(stored)) preference = stored;
  } catch { /* Follow the site default when storage is unavailable. */ }
  const apply = () => {
    const mode = preference === 'system' ? (media.matches ? 'dark' : 'light') : preference;
    root.dataset.mode = mode;
    document.querySelectorAll('.kh-mode-toggle').forEach((button) => {
      button.setAttribute('aria-pressed', String(mode === 'dark'));
    });
  };
  apply();
  media.addEventListener('change', apply);
  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.kh-mode-toggle').forEach((button) => {
      button.hidden = false;
      button.addEventListener('click', () => {
        preference = root.dataset.mode === 'dark' ? 'light' : 'dark';
        try { localStorage.setItem('kumo-hybrid-mode', preference); } catch { /* Still works for this page. */ }
        apply();
      });
    });
    apply();
  });
  window.addEventListener('storage', (event) => {
    if (!root.hasAttribute('data-kumo-preview') && event.key === 'kumo-hybrid-mode') {
      preference = valid.includes(event.newValue) ? event.newValue : (root.dataset.siteMode || 'light');
      apply();
    }
  });
})();
