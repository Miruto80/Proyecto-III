document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.modal').forEach(modalEl => {
    modalEl.addEventListener('hidden.bs.modal', () => {
      const form = modalEl.querySelector('form');
      if (form) form.reset();
    });
  });
});
