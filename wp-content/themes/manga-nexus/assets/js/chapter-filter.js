/**
 * MangaNexus - Filtro y Búsqueda en Vivo de Capítulos
 */
document.addEventListener('DOMContentLoaded', () => {
  const searchInput = document.getElementById('chapterSearchInput');
  const chaptersContainer = document.getElementById('chaptersListContainer');

  if (!searchInput || !chaptersContainer) return;

  const chapterRows = chaptersContainer.querySelectorAll('.chapter-item-row');

  searchInput.addEventListener('input', (e) => {
    const query = e.target.value.toLowerCase().trim();

    chapterRows.forEach(row => {
      const title = row.getAttribute('data-chapter-title')?.toLowerCase() || '';
      const num = row.getAttribute('data-chapter-num')?.toLowerCase() || '';

      if (title.includes(query) || num.includes(query)) {
        row.style.display = 'flex';
      } else {
        row.style.display = 'none';
      }
    });
  });
});
