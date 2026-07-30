document.querySelectorAll('[data-rich-editor]').forEach((editor) => {
  const input = editor.querySelector('[data-rich-input]');
  const output = editor.querySelector('[data-rich-output]');
  const form = editor.closest('form');

  if (!input || !output || !form) return;

  const syncValue = () => {
    output.value = input.innerHTML.trim();
  };

  editor.querySelectorAll('[data-rich-command]').forEach((button) => {
    button.addEventListener('mousedown', (event) => event.preventDefault());
    button.addEventListener('click', () => {
      input.focus();
      document.execCommand(button.dataset.richCommand, false);
      syncValue();
    });
  });

  const linkButton = editor.querySelector('[data-rich-link]');
  if (linkButton) {
    linkButton.addEventListener('mousedown', (event) => event.preventDefault());
    linkButton.addEventListener('click', () => {
      const selection = window.getSelection();
      if (!selection || selection.isCollapsed) {
        window.alert('Highlight the words you want to link first.');
        return;
      }

      const url = window.prompt('Paste the web address for this link:');
      if (!url) return;
      input.focus();
      document.execCommand('createLink', false, url.trim());
      syncValue();
    });
  }

  input.addEventListener('input', syncValue);
  input.addEventListener('paste', (event) => {
    event.preventDefault();
    const text = event.clipboardData?.getData('text/plain') ?? '';
    document.execCommand('insertText', false, text);
    syncValue();
  });
  form.addEventListener('submit', syncValue);
});
