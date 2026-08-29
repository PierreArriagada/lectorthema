/**
 * LectorThema - Sistema de Favoritos y Marcadores AJAX
 */
document.addEventListener('DOMContentLoaded', () => {
  const favButtons = document.querySelectorAll('.btn-toggle-favorite, .btn-favorite-large, .manga-card-fav-btn');

  favButtons.forEach(btn => {
    btn.addEventListener('click', async (e) => {
      e.preventDefault();
      e.stopPropagation();

      if (!lectorThemaData.isLoggedIn) {
        // Abrir modal de autenticación si no ha iniciado sesión
        const authModal = document.getElementById('mangaAuthModal');
        if (authModal) {
          authModal.classList.add('open');
          const loginTab = authModal.querySelector('[data-tab="login"]');
          if (loginTab) loginTab.click();
        } else {
          alert(lectorThemaData.strings.loginRequired);
        }
        return;
      }

      const mangaId = btn.getAttribute('data-manga-id');
      if (!mangaId) return;

      const originalHtml = btn.innerHTML;
      btn.disabled = true;
      btn.style.opacity = '0.6';

      try {
        const formData = new FormData();
        formData.append('action', 'lectorthema_toggle_favorite');
        formData.append('security', lectorThemaData.nonce);
        formData.append('manga_id', mangaId);

        const response = await fetch(lectorThemaData.ajaxUrl, {
          method: 'POST',
          body: formData
        });

        const data = await response.json();

        if (data.success) {
          const isFav = data.data.is_favorite;

          // Sincronizar todos los botones del mismo manga en la página
          const allMatchingBtns = document.querySelectorAll(`[data-manga-id="${mangaId}"].btn-toggle-favorite, [data-manga-id="${mangaId}"].btn-favorite-large, [data-manga-id="${mangaId}"].manga-card-fav-btn`);
          
          allMatchingBtns.forEach(b => {
            if (b.classList.contains('manga-card-fav-btn')) {
              // Botón pequeño de la card
              b.classList.toggle('is-active', isFav);
              b.innerHTML = `<i class="${isFav ? 'fa-solid' : 'fa-regular'} fa-bookmark"></i>`;
              b.title = isFav ? 'Guardado en Favoritos' : 'Guardar en Favoritos';
            } else if (b.classList.contains('btn-favorite-large')) {
              // Botón grande de la ficha
              b.classList.toggle('is-active', isFav);
              b.innerHTML = `<i class="${isFav ? 'fa-solid' : 'fa-regular'} fa-bookmark"></i> <span>${isFav ? 'En tus Favoritos' : 'Agregar a Favoritos'}</span>`;
            } else {
              // Otros botones (ej: slider hero)
              b.classList.toggle('is-active', isFav);
              b.innerHTML = `<i class="${isFav ? 'fa-solid' : 'fa-regular'} fa-bookmark"></i> ${isFav ? 'En Favoritos' : 'Favoritos'}`;
            }
          });

          // Actualizar contador si existe
          const counterEl = document.querySelector(`.fav-count-${mangaId}`);
          if (counterEl && data.data.total_favs !== undefined) {
            counterEl.textContent = data.data.total_favs;
          }

          // Mostrar notificación flotante (Toast)
          showMangaToast(data.data.message, 'success');
        } else {
          showMangaToast(data.data.message || lectorThemaData.strings.errorOccurred, 'error');
          btn.innerHTML = originalHtml;
        }
      } catch (err) {
        console.error(err);
        showMangaToast(lectorThemaData.strings.errorOccurred, 'error');
        btn.innerHTML = originalHtml;
      } finally {
        btn.disabled = false;
        btn.style.opacity = '1';
      }
    });
  });

  // Notificación Toast con la paleta editorial
  function showMangaToast(message, type = 'success') {
    let toast = document.getElementById('lectorThemaToast');
    if (!toast) {
      toast = document.createElement('div');
      toast.id = 'lectorThemaToast';
      toast.style.cssText = `
        position: fixed;
        bottom: 25px;
        right: 25px;
        padding: 12px 20px;
        border-radius: 8px;
        font-family: var(--font-heading, sans-serif);
        font-size: 13.5px;
        font-weight: 600;
        z-index: 9999;
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.6);
        transition: all 0.25s ease;
        opacity: 0;
        transform: translateY(15px);
      `;
      document.body.appendChild(toast);
    }

    if (type === 'success') {
      toast.style.background = 'var(--surface-elevated, #1B1B23)';
      toast.style.border = '1px solid var(--accent, #A855F7)';
      toast.style.color = 'var(--text-primary, #F5F5F7)';
      toast.innerHTML = `<i class="fa-solid fa-bookmark" style="color: var(--accent, #A855F7);"></i> ${message}`;
    } else {
      toast.style.background = 'var(--surface-elevated, #1B1B23)';
      toast.style.border = '1px solid var(--error, #EF4444)';
      toast.style.color = 'var(--text-primary, #F5F5F7)';
      toast.innerHTML = `<i class="fa-solid fa-circle-exclamation" style="color: var(--error, #EF4444);"></i> ${message}`;
    }

    toast.style.opacity = '1';
    toast.style.transform = 'translateY(0)';

    setTimeout(() => {
      toast.style.opacity = '0';
      toast.style.transform = 'translateY(15px)';
    }, 3500);
  }
});
