/**
 * LectorThema - Notifications System (AJAX)
 */
document.addEventListener('DOMContentLoaded', () => {
  if (typeof lectorThemaData === 'undefined') return;

  const btnNotificationsToggle = document.getElementById('btnNotificationsToggle');
  const badge = document.getElementById('notificationsUnreadBadge');
  
  if (!btnNotificationsToggle) return;

  // Create notifications dropdown container
  const notificationsDropdown = document.createElement('div');
  notificationsDropdown.id = 'notificationsDropdown';
  notificationsDropdown.className = 'notifications-dropdown';
  notificationsDropdown.style.cssText = `
    display: none;
    position: absolute;
    top: 60px;
    right: 20px;
    width: 320px;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    z-index: 9999;
    overflow: hidden;
    max-height: 400px;
    flex-direction: column;
  `;

  const header = document.createElement('div');
  header.style.cssText = `
    padding: 12px 15px;
    border-bottom: 1px solid var(--border);
    font-family: var(--font-heading);
    font-weight: 700;
    color: var(--text-primary);
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: var(--surface-secondary);
  `;
  header.innerHTML = `
    <span>Notificaciones</span>
    <button id="btnMarkAllRead" style="background: none; border: none; color: var(--primary); font-size: 12px; cursor: pointer; font-weight: 600;">Marcar leídas</button>
  `;
  
  const content = document.createElement('div');
  content.id = 'notificationsContent';
  content.style.cssText = `
    overflow-y: auto;
    max-height: 350px;
  `;
  content.innerHTML = '<div style="padding: 20px; text-align: center; color: var(--text-secondary);"><i class="fa-solid fa-spinner fa-spin"></i> Cargando...</div>';

  notificationsDropdown.appendChild(header);
  notificationsDropdown.appendChild(content);
  document.body.appendChild(notificationsDropdown);

  let isOpen = false;

  const fetchNotifications = async () => {
    try {
      const formData = new FormData();
      formData.append('action', 'lectorthema_ajax_get_notifications');
      formData.append('security', lectorThemaData.nonce);

      const res = await fetch(lectorThemaData.ajaxUrl, {
        method: 'POST',
        body: formData
      });
      const data = await res.json();
      
      if (data.success) {
        if (data.data.unread_count > 0) {
          badge.style.display = 'block';
        } else {
          badge.style.display = 'none';
        }
        
        content.innerHTML = data.data.html;
      }
    } catch (err) {
      console.error(err);
    }
  };

  btnNotificationsToggle.addEventListener('click', (e) => {
    e.stopPropagation();
    isOpen = !isOpen;
    if (isOpen) {
      notificationsDropdown.style.display = 'flex';
      fetchNotifications(); // Refresh on open
    } else {
      notificationsDropdown.style.display = 'none';
    }
  });

  document.addEventListener('click', (e) => {
    if (isOpen && !notificationsDropdown.contains(e.target) && !btnNotificationsToggle.contains(e.target)) {
      isOpen = false;
      notificationsDropdown.style.display = 'none';
    }
  });

  const btnMarkAllRead = document.getElementById('btnMarkAllRead');
  if (btnMarkAllRead) {
    btnMarkAllRead.addEventListener('click', async (e) => {
      e.stopPropagation();
      e.preventDefault();
      try {
        const formData = new FormData();
        formData.append('action', 'lectorthema_ajax_mark_notifications_read');
        formData.append('security', lectorThemaData.nonce);

        const res = await fetch(lectorThemaData.ajaxUrl, {
          method: 'POST',
          body: formData
        });
        const data = await res.json();
        
        if (data.success) {
          badge.style.display = 'none';
          document.querySelectorAll('.notification-item.unread').forEach(el => {
            el.classList.remove('unread');
            el.style.background = 'transparent';
          });
        }
      } catch (err) {
        console.error(err);
      }
    });
  }

  // Poll initially
  fetchNotifications();
  // Optional: Poll every 2 minutes
  // setInterval(fetchNotifications, 120000);
});
