// Runs before first paint: applies the saved (or system) theme to <html>
// and marks the page as JS-enabled (reveal animations only hide content when JS runs).
(function () {
  document.documentElement.classList.add('js');
  try {
    var saved = localStorage.getItem('theme');
    var dark =
      saved === 'dark' ||
      (saved !== 'light' &&
        window.matchMedia('(prefers-color-scheme: dark)').matches);
    document.documentElement.classList.toggle('dark', dark);
  } catch (e) {}
})();
