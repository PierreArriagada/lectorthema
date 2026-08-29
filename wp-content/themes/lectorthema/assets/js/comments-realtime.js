/**
 * LectorThema - Comments Real-time System (AJAX)
 */
document.addEventListener('DOMContentLoaded', () => {
  if (typeof lectorThemaData === 'undefined') return;

  const commentForm = document.getElementById('commentform');
  const commentListContainer = document.getElementById('comment-list-container');
  const commentsCounterText = document.getElementById('comments-counter-text');

  if (commentForm) {
    commentForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      
      const submitBtn = commentForm.querySelector('button[type="submit"]');
      const originalBtnText = submitBtn.innerHTML;
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> ' + lectorThemaData.strings.processing;

      const formData = new FormData(commentForm);
      formData.append('action', 'lectorthema_ajax_submit_comment');
      formData.append('security', lectorThemaData.nonce);
      
      // WordPress default comment form fields
      const postId = document.getElementById('comment_post_ID').value;
      const parentId = document.getElementById('comment_parent').value;
      
      formData.append('post_id', postId);

      try {
        const res = await fetch(lectorThemaData.ajaxUrl, {
          method: 'POST',
          body: formData
        });
        const data = await res.json();

        if (data.success) {
          // Clear text area
          commentForm.querySelector('textarea[name="comment"]').value = '';
          
          const newCommentHtml = data.data.html;
          const isReply = data.data.parent > 0;

          if (isReply) {
            // Append to children ul of the parent comment
            let childrenUl = document.getElementById('children-of-' + data.data.parent);
            if (!childrenUl) {
              const parentLi = document.getElementById('comment-' + data.data.parent);
              if (parentLi) {
                childrenUl = document.createElement('ul');
                childrenUl.id = 'children-of-' + data.data.parent;
                childrenUl.className = 'children';
                childrenUl.style.paddingLeft = '0';
                parentLi.appendChild(childrenUl);
              }
            }
            if (childrenUl) {
              childrenUl.insertAdjacentHTML('beforeend', newCommentHtml);
            }
            
            // Hide reply form and reset parent ID
            const replyFormContainer = document.getElementById('reply-form-' + data.data.parent);
            if (replyFormContainer) replyFormContainer.style.display = 'none';
            document.getElementById('comment_parent').value = '0';
            
            const cancelBtn = document.getElementById('cancel-comment-reply-link');
            if (cancelBtn) cancelBtn.style.display = 'none';

            // Move form back to main container
            const mainContainer = document.getElementById('lectorthema-main-comment-form');
            if (mainContainer) mainContainer.appendChild(commentForm);
            
          } else {
            // Remove empty placeholder if present
            const emptyNotice = document.getElementById('no-comments-yet');
            if (emptyNotice) emptyNotice.remove();

            // Top level comment
            if (commentListContainer) {
              commentListContainer.insertAdjacentHTML('afterbegin', newCommentHtml);
            } else {
              window.location.reload();
            }
          }
          
          if (typeof showMangaToast === 'function') {
            showMangaToast(data.data.message, 'success');
          } else {
            alert(data.data.message);
          }

        } else {
          alert(data.data.message || 'Error al enviar el comentario.');
        }
      } catch (err) {
        console.error(err);
        alert('Error de conexión.');
      } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
      }
    });
  }

  // Delegación de eventos para botones de Responder y Reportar
  document.body.addEventListener('click', async (e) => {
    
    // Responder a un comentario
    const btnReply = e.target.closest('.btn-reply');
    if (btnReply) {
      e.preventDefault();
      const commentId = btnReply.getAttribute('data-comment-id');
      const replyContainer = document.getElementById('reply-form-' + commentId);
      
      if (replyContainer && commentForm) {
        // Mover el formulario principal al contenedor de respuesta
        replyContainer.appendChild(commentForm);
        replyContainer.style.display = 'block';
        document.getElementById('comment_parent').value = commentId;
        const cancelBtn = document.getElementById('cancel-comment-reply-link');
        if (cancelBtn) cancelBtn.style.display = 'inline-flex';
        commentForm.querySelector('textarea[name="comment"]').focus();
      }
    }

    // Cancelar respuesta
    const btnCancelReply = e.target.closest('#cancel-comment-reply-link');
    if (btnCancelReply) {
      e.preventDefault();
      const mainContainer = document.getElementById('lectorthema-main-comment-form');
      if (mainContainer && commentForm) {
        mainContainer.appendChild(commentForm);
        document.getElementById('comment_parent').value = '0';
        btnCancelReply.style.display = 'none';
        document.querySelectorAll('.reply-form-container').forEach(el => el.style.display = 'none');
      }
    }

    // Reportar comentario
    const btnReport = e.target.closest('.btn-report');
    if (btnReport) {
      e.preventDefault();
      if (!confirm('¿Seguro que deseas reportar este comentario por contenido inapropiado?')) return;
      
      const commentId = btnReport.getAttribute('data-comment-id');
      
      const formData = new FormData();
      formData.append('action', 'lectorthema_ajax_report_comment');
      formData.append('security', lectorThemaData.nonce);
      formData.append('comment_id', commentId);

      try {
        const res = await fetch(lectorThemaData.ajaxUrl, {
          method: 'POST',
          body: formData
        });
        const data = await res.json();
        
        if (typeof showMangaToast === 'function') {
          showMangaToast(data.data.message, data.success ? 'success' : 'error');
        } else {
          alert(data.data.message);
        }
        
        if (data.success) {
          btnReport.style.color = 'var(--error)';
          btnReport.disabled = true;
        }
      } catch (err) {
        console.error(err);
      }
    }
  });
});
