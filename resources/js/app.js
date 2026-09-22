import './bootstrap';

const formatRupiah = (value) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(Number(value || 0));

window.formatRupiah = formatRupiah;

function updateTotal() {
  const qty = document.querySelector('#quantityInput');
  const unit = document.querySelector('#unitPriceInput');
  const total = document.querySelector('#totalDisplay');
  if (qty && unit && total) total.value = formatRupiah((Number(qty.value) || 0) * (Number(unit.value) || 0));
}

document.querySelectorAll('#quantityInput,#unitPriceInput').forEach((el) => el.addEventListener('input', updateTotal));
updateTotal();

document.querySelectorAll('.entry-tab').forEach((tab) => tab.addEventListener('click', () => {
  document.querySelectorAll('.entry-tab').forEach((item) => item.classList.remove('active'));
  tab.classList.add('active');
  const type = tab.dataset.type;
  const typeInput = document.querySelector('#typeInput');
  if (typeInput) typeInput.value = type;
  document.querySelectorAll('#categorySelect option').forEach((option) => {
    if (!option.value) return;
    const allowed = option.dataset.type === type || option.dataset.type === 'both';
    option.hidden = !allowed;
  });
  document.querySelectorAll('.income-only').forEach((el) => { el.style.display = type === 'income' ? '' : 'none'; });
  const selected = document.querySelector('#categorySelect option:checked');
  if (selected && selected.hidden) document.querySelector('#categorySelect').value = '';
}));

document.querySelectorAll('.entry-tab.active').forEach((t) => t.click());

document.querySelectorAll('[data-toggle-password]').forEach((btn) => btn.addEventListener('click', () => {
  const input = document.querySelector(btn.dataset.togglePassword);
  if (!input) return;
  input.type = input.type === 'password' ? 'text' : 'password';
  btn.setAttribute('aria-label', input.type === 'password' ? 'Tampilkan kata sandi' : 'Sembunyikan kata sandi');
}));

document.querySelectorAll('[data-sidebar-toggle]').forEach((button) => button.addEventListener('click', () => {
  const shell = document.querySelector('.app-shell');
  if (!shell) return;
  const open = shell.classList.toggle('sidebar-open');
  document.querySelectorAll('[data-sidebar-toggle]').forEach((item) => item.setAttribute('aria-expanded', String(open)));
}));

document.querySelector('[data-sidebar-backdrop]')?.addEventListener('click', () => {
  document.querySelector('.app-shell')?.classList.remove('sidebar-open');
  document.querySelectorAll('[data-sidebar-toggle]').forEach((item) => item.setAttribute('aria-expanded', 'false'));
});

const proofInput = document.querySelector('[data-proof-input]');
if (proofInput) proofInput.addEventListener('change', (e) => {
  const file = e.target.files?.[0];
  const preview = document.querySelector('[data-proof-preview]');
  if (!file || !preview) return;
  preview.innerHTML = '';
  preview.style.display = 'block';
  if (file.type.startsWith('image/')) {
    const img = document.createElement('img');
    img.src = URL.createObjectURL(file);
    img.alt = 'Preview bukti bayar';
    preview.appendChild(img);
  } else {
    preview.textContent = `Bukti terpilih: ${file.name}`;
  }
});

window.downloadTableCsv = function(tableId, filename = 'export.csv') {
  const table = document.getElementById(tableId);
  if (!table) return;
  const rows = [...table.querySelectorAll('tr')].map((row) => [...row.children].map((cell) => `"${cell.innerText.replaceAll('"','""').replaceAll('\n',' ')}"`).join(','));
  const blob = new Blob([rows.join('\n')], { type: 'text/csv;charset=utf-8' });
  const a = document.createElement('a');
  a.href = URL.createObjectURL(blob);
  a.download = filename;
  a.click();
  URL.revokeObjectURL(a.href);
};
