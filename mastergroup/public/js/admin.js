// Мобильное открытие/закрытие сайдбара. На десктопе бургер скрыт.
(function () {
  const shell = document.querySelector('[data-admin-shell]');
  const toggleBtn = document.querySelector('[data-sidebar-toggle]');
  const sidebar = document.getElementById('adminSidebar');
  if (!shell || !toggleBtn || !sidebar) return;

  const MQ_DESKTOP = window.matchMedia('(min-width: 1024px)');

  function isDesktop() { return MQ_DESKTOP.matches; }

  toggleBtn.addEventListener('click', () => {
    if (isDesktop()) return; // на десктопе ничего не делаем
    const open = !shell.classList.contains('is-sidebar-open');
    shell.classList.toggle('is-sidebar-open', open);
    toggleBtn.setAttribute('aria-expanded', String(open));
    if (open) sidebar.focus?.();
  });

  document.addEventListener('click', (e) => {
    if (isDesktop()) return;
    if (!shell.classList.contains('is-sidebar-open')) return;
    const insideSidebar = sidebar.contains(e.target);
    const insideButton  = toggleBtn.contains(e.target);
    if (!insideSidebar && !insideButton) {
      shell.classList.remove('is-sidebar-open');
      toggleBtn.setAttribute('aria-expanded', 'false');
    }
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && !isDesktop() && shell.classList.contains('is-sidebar-open')) {
      shell.classList.remove('is-sidebar-open');
      toggleBtn.setAttribute('aria-expanded', 'false');
    }
  });

  // При переходе на десктоп — гарантированно закрыть мобильный слой
  MQ_DESKTOP.addEventListener?.('change', () => {
    if (isDesktop()) shell.classList.remove('is-sidebar-open');
  });
})();
