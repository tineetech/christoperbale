document.addEventListener('DOMContentLoaded', function() {
  // =============================================
  // Mobile Drawer
  // =============================================
  var menuToggle    = document.getElementById('menuToggle');
  var mobileDrawer  = document.getElementById('mobileDrawer');
  var drawerOverlay = document.getElementById('drawerOverlay');
  var drawerClose   = document.getElementById('drawerClose');

  if (menuToggle && mobileDrawer && drawerOverlay && drawerClose) {
    function openDrawer() {
      mobileDrawer.classList.add('open');
      drawerOverlay.classList.add('open');
      document.body.style.overflow = 'hidden';
    }
    function closeDrawer() {
      mobileDrawer.classList.remove('open');
      drawerOverlay.classList.remove('open');
      document.body.style.overflow = '';
    }

    menuToggle.addEventListener('click', openDrawer);
    drawerClose.addEventListener('click', closeDrawer);
    drawerOverlay.addEventListener('click', closeDrawer);
    document.querySelectorAll('.mobile-nav-links a').forEach(function(l) {
      l.addEventListener('click', closeDrawer);
    });
  }

  // =============================================
  // Logout
  // =============================================
  var logoutBtn = document.getElementById('logoutBtn');
  if(logoutBtn) {
    logoutBtn.addEventListener('click', function(e){
      e.preventDefault();
      localStorage.removeItem('token');
      window.location.href = '/';
    });
  }
});
