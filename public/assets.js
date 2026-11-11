(() => {
  'use strict';
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('sidebarOverlay');
  const trigger = document.getElementById('sidebarTrigger');
  const toggleSidebar = (open) => {
    if (!sidebar || !overlay) return;
    sidebar.classList.toggle('mobile-open', open);
    overlay.classList.toggle('open', open);
  };
  trigger?.addEventListener('click', () => toggleSidebar(!sidebar.classList.contains('mobile-open')));
  overlay?.addEventListener('click', () => toggleSidebar(false));
  document.addEventListener('keydown', (event) => { if (event.key === 'Escape') toggleSidebar(false); });

  document.querySelectorAll('[data-confirm]').forEach((button) => {
    button.addEventListener('click', (event) => {
      if (!window.confirm(button.getAttribute('data-confirm'))) event.preventDefault();
    });
  });
  document.querySelectorAll('.password-toggle').forEach((toggle) => {
    toggle.addEventListener('click', () => {
      const input = toggle.parentElement?.querySelector('input');
      if (!input) return;
      input.type = input.type === 'password' ? 'text' : 'password';
      toggle.setAttribute('aria-label', input.type === 'text' ? 'Ocultar contraseña' : 'Mostrar contraseña');
    });
  });
  const bookModal = document.getElementById('bookModal');
  const bookForm = document.getElementById('bookForm');
  if (bookModal && bookForm) {
    const fields = ['title', 'author', 'genre', 'year', 'copies_total', 'isbn', 'cover_tone', 'description'];
    const setBook = (book) => {
      bookForm.reset();
      bookForm.elements['action'].value = book ? 'book_edit' : 'book_create';
      bookForm.elements['id'].value = book ? book.id : '';
      bookModal.querySelector('.modal-title').textContent = book ? 'Editar libro' : 'Agregar libro';
      bookModal.querySelector('#saveBookBtn').innerHTML = (book ? 'Guardar cambios ' : 'Guardar libro ') + '<span aria-hidden="true">✓</span>';
      fields.forEach((field) => {
        if (bookForm.elements[field]) bookForm.elements[field].value = book ? (book[field] ?? '') : (field === 'copies_total' || field === 'cover_tone' ? '1' : '');
      });
    };
    document.querySelectorAll('[data-new-book]').forEach((b) => b.addEventListener('click', () => setBook(null)));
    document.querySelectorAll('[data-edit-book]').forEach((b) => b.addEventListener('click', () => {
      try { setBook(JSON.parse(b.getAttribute('data-book'))); }
      catch (error) { setBook(null); }
    }));
    bookModal.addEventListener('shown.bs.modal', () => bookForm.elements['title']?.focus());
  }
  const userModal = document.getElementById('userModal');
  const userForm = document.getElementById('userForm');
  if (userModal && userForm) {
    const setUser = (user) => {
      userForm.reset();
      userForm.elements['action'].value = user ? 'user_edit' : 'user_create';
      userForm.elements['id'].value = user ? user.id : '';
      userForm.elements['username'].value = user ? user.username : '';
      userForm.elements['email'].value = user ? user.email : '';
      userForm.elements['role_id'].value = user ? user.role_id : '3';
      userForm.elements['password'].required = !user;
      userModal.querySelector('.modal-title').textContent = user ? 'Editar usuario' : 'Nuevo usuario';
      userModal.querySelector('#userPasswordLabel').textContent = user ? 'Nueva contraseña (opcional)' : 'Contraseña inicial *';
      userModal.querySelector('#userPasswordHint').textContent = user ? 'Dejar en blanco para mantener la contraseña actual.' : 'Mínimo 10 caracteres.';
    };
    document.querySelectorAll('[data-new-user]').forEach((b) => b.addEventListener('click', () => setUser(null)));
    document.querySelectorAll('[data-edit-user]').forEach((b) => b.addEventListener('click', () => {
      try { setUser(JSON.parse(b.getAttribute('data-user'))); }
      catch (error) { setUser(null); }
    }));
    userModal.addEventListener('shown.bs.modal', () => userForm.elements['username']?.focus());
  }
})();
