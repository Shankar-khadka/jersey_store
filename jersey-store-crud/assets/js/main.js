// Auto-dismiss flash alerts after 5 seconds
document.querySelectorAll('.alert-dismissible').forEach(function (el) {
  setTimeout(function () {
    if (window.bootstrap) bootstrap.Alert.getOrCreateInstance(el).close();
  }, 5000);
});
// Confirm destructive actions: <a data-confirm="Are you sure?">
document.addEventListener('click', function (e) {
  var t = e.target.closest('[data-confirm]');
  if (t && !confirm(t.getAttribute('data-confirm'))) e.preventDefault();
});
