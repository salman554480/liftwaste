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
    
    if (bell && dropdown) {
      document.addEventListener('click', function(event) {
        if (bell.contains(event.target)) {
          const isVisible = dropdown.style.display === 'block';
          dropdown.style.display = isVisible ? 'none' : 'block';
          
          // If opening dropdown, refresh the notifications
          if (!isVisible) {
            updateNotificationDropdown();
          }
        } else if (!dropdown.contains(event.target)) {
          dropdown.style.display = 'none';
        }
      });
    }

    // Notification system for new pending emails
    let lastPendingCount = 0;
    let notificationSound;
    let isFirstLoad = true;

    // Initialize notification sound
    function initNotificationSound() {
      try {
        notificationSound = new Audio('assets/notification.mp3');
        notificationSound.volume = 0.5; // Set volume to 50%
      } catch (error) {
        console.log('Notification sound not available:', error);
      }
    }

    // Check for new pending emails
    async function checkNewEmails() {
      try {
        console.log('Checking for new emails...');
        const response = await fetch('query/check_new_emails.php');
        const data = await response.json();
        
        console.log('Response data:', data);
        
        if (data.success) {
          const currentCount = data.pending_count;
          const hasNewEmails = data.new_emails;
          
          // Update notification badge
          const badge = document.querySelector('#notification-bell .badge');
          if (badge) {
            badge.textContent = currentCount;
            badge.style.display = currentCount > 0 ? 'block' : 'none';
          }
          
          // Skip notification on first load
          if (isFirstLoad) {
            isFirstLoad = false;
            lastPendingCount = currentCount;
            updateNotificationDropdown();
            return;
          }
          
          // If there are new emails, play sound and reload page
          if (hasNewEmails && currentCount > lastPendingCount) {
            console.log('New emails detected! Playing sound and reloading...');
            
            // Play notification sound
            if (notificationSound) {
              try {
                await notificationSound.play();
              } catch (error) {
                console.log('Could not play notification sound:', error);
              }
            }
            
            // Show notification
            showNotification(`New email received! ${currentCount} pending emails.`);
            
            // Reload index.php page after 2 seconds
            setTimeout(() => {
              console.log('Reloading index.php page...');
              window.location.href = 'index.php';
            }, 2000);
          }
          
          lastPendingCount = currentCount;
          
          // Update notification dropdown content
          updateNotificationDropdown();
        }
      } catch (error) {
        console.log('Error checking for new emails:', error);
      }
    }

    // Update notification dropdown with pending emails
    async function updateNotificationDropdown() {
      try {
        console.log('Updating notification dropdown...');
        const response = await fetch('query/get_emails.php');
        const data = await response.json();
        
        if (data.success) {
          const pendingEmails = data.emails.filter(email => email.status === 'pending');
          const notificationList = document.getElementById('notification-list');
          
          console.log('Found pending emails:', pendingEmails.length);
          
          if (notificationList) {
            if (pendingEmails.length === 0) {
              notificationList.innerHTML = '<div class="notification-item px-3 py-2 text-center text-muted"><div>No pending emails</div></div>';
            } else {
              const notifications = pendingEmails.slice(0, 5).map(email => {
                const timeAgo = getTimeAgo(email.received_at);
                return `
                  <div class="notification-item px-3 py-2 border-bottom">
                    <div class="small text-muted">${timeAgo}</div>
                    <div>New email from <b>${email.sender}</b></div>
                    <div class="small text-truncate">${email.subject}</div>
                  </div>
                `;
              }).join('');
              
              notificationList.innerHTML = notifications;
            }
          }
        }
      } catch (error) {
        console.log('Error updating notification dropdown:', error);
      }
    }

    // Calculate time ago from timestamp
    function getTimeAgo(timestamp) {
      const now = new Date();
      const emailTime = new Date(timestamp);
      const diffMs = now - emailTime;
      const diffMins = Math.floor(diffMs / 60000);
      const diffHours = Math.floor(diffMs / 3600000);
      const diffDays = Math.floor(diffMs / 86400000);
      
      if (diffMins < 1) return 'Just now';
      if (diffMins < 60) return `${diffMins} min${diffMins > 1 ? 's' : ''} ago`;
      if (diffHours < 24) return `${diffHours} hour${diffHours > 1 ? 's' : ''} ago`;
      return `${diffDays} day${diffDays > 1 ? 's' : ''} ago`;
    }

    // Show browser notification
    function showNotification(message) {
      if ('Notification' in window && Notification.permission === 'granted') {
        new Notification('LiftWaste', { body: message, icon: 'assets/img/favicon.png' });
      } else if ('Notification' in window && Notification.permission !== 'denied') {
        Notification.requestPermission().then(permission => {
          if (permission === 'granted') {
            new Notification('LiftWaste', { body: message, icon: 'assets/img/favicon.png' });
          }
        });
      }
    }

    // Initialize notification system
    function initNotifications() {
      console.log('Initializing notification system...');
      
      try {
        initNotificationSound();
        
        // Check for new emails every 30 seconds
        setInterval(checkNewEmails, 30000);
        
        // Initial check after a short delay to ensure DOM is ready
        setTimeout(() => {
          checkNewEmails();
        }, 1000);
        
        console.log('Notification system initialized successfully');
      } catch (error) {
        console.log('Error initializing notification system:', error);
      }
    }

    // Start notification system when page loads
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', initNotifications);
    } else {
      // DOM is already loaded
      initNotifications();
    }