<?php require_once('parts/top.php'); ?>

<?php
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name     = mysqli_real_escape_string($conn, $_POST['admin_name']);
  $email    = mysqli_real_escape_string($conn, $_POST['admin_email']);
  $contact  = mysqli_real_escape_string($conn, $_POST['admin_contact']);
  $address  = mysqli_real_escape_string($conn, $_POST['admin_address']);
  $role     = mysqli_real_escape_string($conn, $_POST['admin_role']);
  $status   = mysqli_real_escape_string($conn, $_POST['admin_status']);
  $password = mysqli_real_escape_string($conn, $_POST['admin_password']);

  //  $hashed_password = $password;

  $sql = "INSERT INTO admins 
        (admin_name, admin_email, admin_contact, admin_address, admin_password, admin_role, admin_status) 
        VALUES 
        ('$name', '$email', '$contact', '$address', '$password', '$role', '$status')";

  if (mysqli_query($conn, $sql)) {
    $message = '<div class="alert alert-success">Admin added successfully.</div>';
  } else {
    $message = '<div class="alert alert-danger">Error: ' . mysqli_error($conn) . '</div>';
  }
}
?>

</head>

<body>
  <div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <?php require_once('parts/sidebar.php'); ?>

    <!-- Page Content -->
    <div id="page-content-wrapper" class="flex-grow-1">
      <!-- Topbar -->
      <?php require_once('parts/navbar.php'); ?>

      <div class="container-fluid py-4">
        <h2 class="fw-bold mb-4">Add Member</h2>

        <!-- Display message -->
        <?= $message ?>

        <div class="custom-card p-3">
          <form method="POST">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="admin_name" placeholder="Enter username" required>
              </div>

              <div class="col-md-6 mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="admin_email" placeholder="Enter email" required>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-select" id="role" name="admin_role" required>
                  <option selected disabled>Choose role</option>
                  <option value="admin">Admin</option>
                  <option value="employee">Employee</option>
                </select>
              </div>

              <div class="col-md-6 mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="admin_status" required>
                  <option selected disabled>Select status</option>
                  <option value="active">Active</option>
                  <option value="inactive">Inactive</option>
                  <option value="suspended">Suspended</option>
                </select>
              </div>
            </div>

            
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="contact" class="form-label">Contact</label>
                <input type="text" class="form-control" id="contact" name="admin_contact" placeholder="Enter contact number" >
              </div>

              <div class="col-md-6 mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" id="address" name="admin_address" placeholder="Enter address" >
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="admin_password" placeholder="Enter password" required>
              </div>
            </div>



            <div class="row">
              <div class="col-auto">
                <button type="submit" class="btn btn-primary btn-sm">Add Member</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- /#page-content-wrapper -->
  </div>

  <?php require_once('parts/footer.php'); ?>
</body>

</html>