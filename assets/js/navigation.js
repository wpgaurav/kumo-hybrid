(() => {
  'use strict';
  const navigation = document.getElementById('site-navigation');
  const toggle = document.querySelector('.kh-menu-toggle');
  if (!navigation || !toggle) return;
  const compact = window.matchMedia('(max-width: 800px)');
  const closeChildren = () => {
    navigation.querySelectorAll('.kh-submenu-open').forEach((item) => item.classList.remove('kh-submenu-open'));
    navigation.querySelectorAll('.kh-submenu-toggle').forEach((button) => button.setAttribute('aria-expanded', 'false'));
  };
  const setOpen = (open) => {
    navigation.classList.toggle('is-open', open);
    toggle.setAttribute('aria-expanded', String(open));
    if (!open) closeChildren();
  };
  const update = () => { toggle.hidden = !compact.matches; setOpen(false); };
  toggle.addEventListener('click', () => setOpen(toggle.getAttribute('aria-expanded') !== 'true'));
  let submenuIndex = 0;
  navigation.querySelectorAll('li').forEach((item) => {
    const submenu = Array.from(item.children).find((child) => child.tagName === 'UL');
    const link = Array.from(item.children).find((child) => child.tagName === 'A');
    if (!submenu || !link) return;
    submenu.id = `kh-submenu-${++submenuIndex}`;
    const button = document.createElement('button');
    button.className = 'kh-submenu-toggle';
    button.type = 'button';
    button.setAttribute('aria-label', link.textContent.trim());
    button.setAttribute('aria-expanded', 'false');
    button.setAttribute('aria-controls', submenu.id);
    button.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>';
    link.after(button);
    button.addEventListener('click', () => {
      const open = button.getAttribute('aria-expanded') !== 'true';
      button.setAttribute('aria-expanded', String(open));
      item.classList.toggle('kh-submenu-open', open);
      if (!open) {
        item.querySelectorAll('li.kh-submenu-open').forEach((child) => {
          child.classList.remove('kh-submenu-open');
          child.querySelector('.kh-submenu-toggle')?.setAttribute('aria-expanded', 'false');
        });
      }
    });
  });
  navigation.addEventListener('keydown', (event) => {
    if (event.key !== 'Escape') return;
    const owner = event.target.closest('.kh-submenu-open');
    if (owner) {
      const button = Array.from(owner.children).find((child) => child.classList.contains('kh-submenu-toggle'));
      button?.click();
      button?.focus();
    } else {
      setOpen(false);
      toggle.focus();
    }
    event.stopPropagation();
  });
  document.addEventListener('click', (event) => {
    if (!navigation.contains(event.target) && !toggle.contains(event.target)) setOpen(false);
  });
  navigation.addEventListener('focusout', (event) => {
    if (event.relatedTarget && !navigation.contains(event.relatedTarget)) closeChildren();
  });
  compact.addEventListener('change', update);
  update();
})();
