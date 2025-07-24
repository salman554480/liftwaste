  // Sidebar toggle for mobile
    document.getElementById('menu-toggle')?.addEventListener('click', function() {
      document.getElementById('wrapper').classList.toggle('toggled');
    });
    // Sidebar close button for mobile
    document.getElementById('sidebar-close')?.addEventListener('click', function() {
      document.getElementById('wrapper').classList.remove('toggled');
    });

    // Notification dropdown toggle
    const bell = document.getElementById('notification-bell');
    const dropdown = document.getElementById('notification-dropdown');
    document.addEventListener('click', function(event) {
      if (bell && dropdown) {
        if (bell.contains(event.target)) {
          dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        } else if (!dropdown.contains(event.target)) {
          dropdown.style.display = 'none';
        }
      }
    });