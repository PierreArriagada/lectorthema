/**
 * LectorThema - Script Principal
 * Gestión de pestañas de Tops, filtros de géneros, modal de autenticación y navegación móvil
 */
document.addEventListener('DOMContentLoaded', () => {
  // 1. Pestañas de Tops (Semanal, Diario, Mensual, Histórico)
  const topTabButtons = document.querySelectorAll('.top-tab-btn');
  const topRankGrids = document.querySelectorAll('.top-rankings-grid, .top-rankings-list');

  topTabButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      const targetTab = btn.getAttribute('data-tab');

      topTabButtons.forEach(b => b.classList.remove('active'));
      topRankGrids.forEach(grid => {
        grid.classList.add('is-hidden');
        grid.style.display = 'none';
      });

      btn.classList.add('active');
      const activeGrid = document.getElementById(`topList-${targetTab}`);
      if (activeGrid) {
        activeGrid.classList.remove('is-hidden');
        activeGrid.style.display = 'grid';
      }
    });
  });

  // 2. Filtro de Géneros en Portada
  const genrePills = document.querySelectorAll('.genre-pill-btn');
  const genreGrids = document.querySelectorAll('.genre-manga-grid');

  genrePills.forEach(pill => {
    pill.addEventListener('click', () => {
      const genreSlug = pill.getAttribute('data-genre');

      genrePills.forEach(p => p.classList.remove('active'));
      genreGrids.forEach(grid => {
        grid.classList.add('is-hidden');
        grid.style.display = 'none';
      });

      pill.classList.add('active');
      const activeGrid = document.getElementById(`genreGrid-${genreSlug}`);
      if (activeGrid) {
        activeGrid.classList.remove('is-hidden');
        activeGrid.style.display = 'grid';
      }
    });
  });

  // 3. Modal de Autenticación (Login & Registro)
  const authModal = document.getElementById('mangaAuthModal');
  const openAuthBtns = document.querySelectorAll('.btn-open-auth');
  const closeAuthBtn = document.querySelector('.auth-modal-close');
  const authTabs = document.querySelectorAll('.auth-tab-btn');
  const authForms = document.querySelectorAll('.auth-form-wrap');

  if (authModal) {
    // Abrir Modal (Delegación de eventos para capturar cualquier botón con .btn-open-auth)
    document.addEventListener('click', (e) => {
      const openBtn = e.target.closest('.btn-open-auth');
      if (openBtn) {
        e.preventDefault();
        const tab = openBtn.getAttribute('data-auth-tab') || 'login';
        authModal.classList.add('open');
        switchAuthTab(tab);
      }
    });

    // Cerrar Modal
    if (closeAuthBtn) {
      closeAuthBtn.addEventListener('click', () => authModal.classList.remove('open'));
    }

    authModal.addEventListener('click', (e) => {
      if (e.target === authModal) {
        authModal.classList.remove('open');
      }
    });

    // Cambiar Pestaña (Login vs Registro)
    authTabs.forEach(tab => {
      tab.addEventListener('click', () => {
        const tabName = tab.getAttribute('data-tab');
        switchAuthTab(tabName);
      });
    });

    function switchAuthTab(tabName) {
      authTabs.forEach(t => t.classList.toggle('active', t.getAttribute('data-tab') === tabName));
      authForms.forEach(f => f.style.display = f.getAttribute('data-form') === tabName ? 'block' : 'none');
      clearAuthMessages();
    }

    function clearAuthMessages() {
      const msgBoxes = authModal.querySelectorAll('.auth-msg-box');
      msgBoxes.forEach(b => {
        b.style.display = 'none';
        b.className = 'auth-msg-box';
      });
    }

    // Envío de Formulario: LOGIN AJAX
    const loginForm = document.getElementById('mangaLoginForm');
    if (loginForm) {
      loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const msgBox = document.getElementById('loginMsgBox');
        const submitBtn = loginForm.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Iniciando sesión...';

        try {
          const formData = new FormData(loginForm);
          formData.append('action', 'lectorthema_ajax_login');
          formData.append('security', lectorThemaData.nonce);

          const res = await fetch(lectorThemaData.ajaxUrl, {
            method: 'POST',
            body: formData
          });

          const data = await res.json();

          if (data.success) {
            msgBox.className = 'auth-msg-box success';
            msgBox.textContent = data.data.message;
            msgBox.style.display = 'block';
            setTimeout(() => {
              window.location.reload();
            }, 800);
          } else {
            msgBox.className = 'auth-msg-box error';
            msgBox.textContent = data.data.message;
            msgBox.style.display = 'block';
          }
        } catch (err) {
          msgBox.className = 'auth-msg-box error';
          msgBox.textContent = 'Error al procesar la solicitud.';
          msgBox.style.display = 'block';
        } finally {
          submitBtn.disabled = false;
          submitBtn.textContent = 'Iniciar Sesión';
        }
      });
    }

    // Envío de Formulario: REGISTRO AJAX
    const registerForm = document.getElementById('mangaRegisterForm');
    if (registerForm) {
      registerForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const msgBox = document.getElementById('registerMsgBox');
        const submitBtn = registerForm.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Creando cuenta...';

        try {
          const formData = new FormData(registerForm);
          formData.append('action', 'lectorthema_ajax_register');
          formData.append('security', lectorThemaData.nonce);

          const res = await fetch(lectorThemaData.ajaxUrl, {
            method: 'POST',
            body: formData
          });

          const data = await res.json();

          if (data.success) {
            msgBox.className = 'auth-msg-box success';
            msgBox.textContent = data.data.message;
            msgBox.style.display = 'block';
            setTimeout(() => {
              window.location.reload();
            }, 1000);
          } else {
            msgBox.className = 'auth-msg-box error';
            msgBox.textContent = data.data.message;
            msgBox.style.display = 'block';
          }
        } catch (err) {
          msgBox.className = 'auth-msg-box error';
          msgBox.textContent = 'Error al crear la cuenta.';
          msgBox.style.display = 'block';
        } finally {
          submitBtn.disabled = false;
          submitBtn.textContent = 'Registrarse';
        }
      });
    }
  }

  // 4. Menú Drawer Móvil
  const mobileToggle = document.getElementById('mobileMenuToggle') || document.querySelector('.mobile-menu-toggle');
  const mobileDrawer = document.getElementById('mobileNavDrawer');
  const closeMobileNav = document.getElementById('mobileNavClose') || document.querySelector('.mobile-nav-close');

  if (mobileToggle && mobileDrawer) {
    mobileToggle.addEventListener('click', (e) => {
      e.preventDefault();
      mobileDrawer.classList.add('open');
    });

    if (closeMobileNav) {
      closeMobileNav.addEventListener('click', (e) => {
        e.preventDefault();
        mobileDrawer.classList.remove('open');
      });
    }

    mobileDrawer.addEventListener('click', (e) => {
      if (e.target === mobileDrawer) {
        mobileDrawer.classList.remove('open');
      }
    });
  }

  // 5. Buscador Desplegable en PC (Lupa interactiva)
  const btnSearchToggle = document.getElementById('btnSearchToggle');
  const btnSearchClose = document.getElementById('btnSearchClose');
  const searchCollapsible = document.getElementById('headerSearchCollapsible');
  const searchInput = document.getElementById('headerSearchInput');

  if (btnSearchToggle && searchCollapsible) {
    btnSearchToggle.addEventListener('click', (e) => {
      e.stopPropagation();
      searchCollapsible.classList.add('is-open');
      if (searchInput) {
        setTimeout(() => searchInput.focus(), 150);
      }
    });

    if (btnSearchClose) {
      btnSearchClose.addEventListener('click', (e) => {
        e.stopPropagation();
        searchCollapsible.classList.remove('is-open');
      });
    }

    document.addEventListener('click', (e) => {
      if (!searchCollapsible.contains(e.target)) {
        searchCollapsible.classList.remove('is-open');
      }
    });

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        searchCollapsible.classList.remove('is-open');
      }
    });
  }

  // 6. Selector de Modo Claro / Modo Oscuro
  const btnThemeToggle = document.getElementById('btnThemeToggle');
  if (btnThemeToggle) {
    btnThemeToggle.addEventListener('click', () => {
      const currentTheme = document.documentElement.getAttribute('data-theme') || 'dark';
      const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
      document.documentElement.setAttribute('data-theme', newTheme);
      localStorage.setItem('lectorThemaTheme', newTheme);
    });
  }

  // Escuchar cambios automáticos del sistema si el usuario no ha forzado un tema
  window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
    if (!localStorage.getItem('lectorThemaTheme')) {
      const newTheme = e.matches ? 'dark' : 'light';
      document.documentElement.setAttribute('data-theme', newTheme);
    }
  });
});
