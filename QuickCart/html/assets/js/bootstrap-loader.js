// Small loader that dynamically loads Bootstrap JS bundle from CDN
(function(){
  if(window.bootstrap) return;
  const s = document.createElement('script');
  s.src = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js';
  s.defer = true;
  document.head.appendChild(s);
})();
