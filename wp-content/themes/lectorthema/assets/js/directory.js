/**
 * LectorThema - Script Interactivo del Directorio y Filtros (directory.js)
 *
 * Controla:
 * - Botón de borrado rápido en el campo de búsqueda (X)
 * - Envío automático al cambiar menús desplegables (Género, Orden)
 * - Activación dinámica de filtros tipo Pill
 * - Reseteo fluido y atajos de teclado
 *
 * @package LectorThema
 * @version 1.0.0
 */

document.addEventListener('DOMContentLoaded', () => {
  const filterForm = document.getElementById('directoryFilterForm');
  if (!filterForm) {
    return;
  }

  const searchInput = document.getElementById('directorySearchInput');
  const clearBtn = document.getElementById('btnDirectoryClear');
  const selectGenre = document.getElementById('directorySelectGenre');
  const selectOrder = document.getElementById('directorySelectOrder');

  // ============================================================================
  // 1. Control del Botón de Limpieza en el Buscador
  // ============================================================================
  if (searchInput && clearBtn) {
    const toggleClearBtn = () => {
      if (searchInput.value.trim().length > 0) {
        clearBtn.classList.add('is-visible');
      } else {
        clearBtn.classList.remove('is-visible');
      }
    };

    // Estado inicial
    toggleClearBtn();

    // Evento al escribir
    searchInput.addEventListener('input', toggleClearBtn);

    // Evento al hacer clic en la "X"
    clearBtn.addEventListener('click', (e) => {
      e.preventDefault();
      searchInput.value = '';
      toggleClearBtn();
      searchInput.focus();

      // Si ya se había filtrado por búsqueda en la URL, auto-enviar para refrescar
      const urlParams = new URLSearchParams(window.location.search);
      if (urlParams.has('s') || urlParams.has('q')) {
        filterForm.submit();
      }
    });

    // Tecla Escape para limpiar
    searchInput.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && searchInput.value.length > 0) {
        e.preventDefault();
        searchInput.value = '';
        toggleClearBtn();
      }
    });
  }

  // ============================================================================
  // 2. Auto-envío fluido al cambiar selectores (Género u Orden)
  // ============================================================================
  const handleSelectChange = () => {
    // Si el usuario cambia el género u orden, enviar automáticamente el formulario
    filterForm.submit();
  };

  if (selectGenre) {
    selectGenre.addEventListener('change', handleSelectChange);
  }

  if (selectOrder) {
    selectOrder.addEventListener('change', handleSelectChange);
  }

  // ============================================================================
  // 3. Manejo de botones tipo Pill mediante inputs ocultos
  // ============================================================================
  const pillButtons = filterForm.querySelectorAll('.directory-pill[data-param]');
  pillButtons.forEach((pill) => {
    pill.addEventListener('click', (e) => {
      e.preventDefault();
      const paramName = pill.getAttribute('data-param');
      const paramVal = pill.getAttribute('data-value');

      // Buscar o crear el input oculto correspondiente
      let hiddenInput = filterForm.querySelector(`input[name="${paramName}"]`);
      if (!hiddenInput) {
        hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = paramName;
        filterForm.appendChild(hiddenInput);
      }

      hiddenInput.value = paramVal;
      filterForm.submit();
    });
  });
});
