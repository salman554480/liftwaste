    
<div class="bg-white border-end sidebar" id="sidebar-wrapper">
      <div class="d-flex align-items-center justify-content-between sidebar-heading text-primary fw-bold py-2 mx-auto">
        <a href="index.php" class="text-decoration-none"><img src="assets/img/favicon.png" height="50px" alt=""></a>
        <button class="btn btn-link d-lg-none p-0 ms-2" id="sidebar-close" aria-label="Close sidebar"><i class="fas fa-times fa-lg"></i></button>
      </div>
      <div class="list-group list-group-flush">
        <a href="index.php" class="list-group-item list-group-item-action <?php if($page == 'index') { echo 'active'; } ?>"><i
            class="fas fa-tachometer-alt me-2"></i>Dashboard</a>
        <a href="#" class="list-group-item list-group-item-action <?php if($page == 'order_view') { echo 'active'; } ?>" onclick="authenticateAdmin('order_view.php')"><i class="fas fa-list me-2"></i>All Orders</a>
        <a href="#" class="list-group-item list-group-item-action" onclick="authenticateAdmin('member_view.php')"><i class="fas fa-user me-2"></i>Members</a>
      </div>
      <!--<div class="sidebar-footer mt-auto px-3 pb-3">
        <a href="#" class="list-group-item list-group-item-action"><i class="fas fa-cog me-2"></i>Settings</a>
      </div>-->
    </div>

<script>
function authenticateAdmin(targetPage) {
  Swal.fire({
    title: 'Admin Authentication Required',
    html: `
      <div class="mb-3">
        <label for="admin_email" class="form-label">Admin Email</label>
        <input type="email" id="admin_email" class="form-control" placeholder="Enter admin email">
      </div>
      <div class="mb-3">
        <label for="admin_password" class="form-label">Admin Password</label>
        <input type="password" id="admin_password" class="form-control" placeholder="Enter admin password">
      </div>
    `,
    showCancelButton: true,
    confirmButtonText: 'Login',
    cancelButtonText: 'Cancel',
    preConfirm: () => {
      const email = document.getElementById('admin_email').value;
      const password = document.getElementById('admin_password').value;
      
      if (!email || !password) {
        Swal.showValidationMessage('Please enter both email and password');
        return false;
      }
      
      return { email, password };
    }
  }).then((result) => {
    if (result.isConfirmed) {
      // Authenticate admin
      fetch('query/authenticate_user.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'email=' + encodeURIComponent(result.value.email) + '&password=' + encodeURIComponent(result.value.password)
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Check if user has admin role
          if (data.user.admin_role === 'admin') {
            Swal.fire({
              icon: 'success',
              title: 'Access Granted',
              text: 'Welcome, ' + data.user.admin_name,
              timer: 1500,
              showConfirmButton: false
            }).then(() => {
              // Redirect to the target page
              window.location.href = targetPage;
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Access Denied',
              text: 'You need admin privileges to access this page.'
            });
          }
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Authentication Failed',
            text: data.message || 'Invalid credentials'
          });
        }
      })
      .catch((error) => {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Authentication failed. Please try again.'
        });
      });
    }
  });
}
</script>