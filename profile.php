<?php 
require_once('parts/top.php'); 
$page = "profile";

// Check if user is admingit 
if (!isset($_SESSION['admin_role']) || $_SESSION['admin_role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

// Get admin information
$admin_id = $_SESSION['admin_id'];
require_once('parts/db.php');

$query = "SELECT * FROM admins WHERE admin_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $admin_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$admin = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);
?>
</head>

<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar (unchanged) -->

        <?php require_once('parts/sidebar.php'); ?>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper" class="flex-grow-1">
            <!-- Topbar -->

            <?php require_once('parts/navbar.php'); ?>
            <!-- /Topbar -->

            <div class="container-fluid py-4">
                <div class="row">
                    <div class="col-12">
                        <h2 class="fw-bold mb-4">Admin Profile</h2>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-user-edit me-2"></i>
                                    Edit Profile Information
                                </h5>
                            </div>
                            <div class="card-body">
                                <form id="profileForm" method="POST" action="query/update_profile.php">
                                    <input type="hidden" name="admin_id" value="<?php echo $admin['admin_id']; ?>">
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="admin_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="admin_name" name="admin_name" 
                                                   value="<?php echo htmlspecialchars($admin['admin_name']); ?>" required>
                                            <div class="invalid-feedback">Please enter your full name.</div>
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label for="admin_email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" id="admin_email" name="admin_email" 
                                                   value="<?php echo htmlspecialchars($admin['admin_email']); ?>" required>
                                            <div class="invalid-feedback">Please enter a valid email address.</div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="admin_contact" class="form-label">Contact Number</label>
                                            <input type="tel" class="form-control" id="admin_contact" name="admin_contact" 
                                                   value="<?php echo htmlspecialchars($admin['admin_contact']); ?>">
                                            <div class="invalid-feedback">Please enter a valid contact number.</div>
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label for="admin_role" class="form-label">Role</label>
                                            <input type="text" class="form-control" id="admin_role" name="admin_role" 
                                                   value="<?php echo htmlspecialchars($admin['admin_role']); ?>" readonly>
                                            <small class="form-text text-muted">Role cannot be changed</small>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="admin_address" class="form-label">Address</label>
                                        <textarea class="form-control" id="admin_address" name="admin_address" rows="3"><?php echo htmlspecialchars($admin['admin_address']); ?></textarea>
                                        <div class="invalid-feedback">Please enter your address.</div>
                                    </div>

                                    <hr class="my-4">

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="current_password" class="form-label">Current Password</label>
                                            <input type="password" class="form-control" id="current_password" name="current_password">
                                            <small class="form-text text-muted">Required only if changing password</small>
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label for="new_password" class="form-label">New Password</label>
                                            <input type="password" class="form-control" id="new_password" name="new_password">
                                            <small class="form-text text-muted">Leave blank to keep current password</small>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="confirm_password" class="form-label">Confirm New Password</label>
                                            <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                                            <div class="invalid-feedback">Passwords do not match.</div>
                                        </div>
                                        
                                        <div class="col-md-6 mb-3 d-flex align-items-end">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="show_password">
                                                <label class="form-check-label" for="show_password">
                                                    Show Password
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end gap-2">
                                        <button type="button" class="btn btn-secondary" onclick="resetForm()">
                                            <i class="fas fa-undo me-2"></i>Reset
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>Update Profile
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Profile Information
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="text-center mb-3">
                                    <div class="avatar-placeholder bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" 
                                         style="width: 80px; height: 80px; font-size: 2rem;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <strong>Name:</strong> <?php echo htmlspecialchars($admin['admin_name']); ?>
                                </div>
                                <div class="mb-3">
                                    <strong>Email:</strong> <?php echo htmlspecialchars($admin['admin_email']); ?>
                                </div>
                                <div class="mb-3">
                                    <strong>Role:</strong> 
                                    <span class="badge bg-primary"><?php echo ucfirst(htmlspecialchars($admin['admin_role'])); ?></span>
                                </div>
                                <div class="mb-3">
                                    <strong>Contact:</strong> <?php echo htmlspecialchars($admin['admin_contact']) ?: 'Not provided'; ?>
                                </div>
                                <div class="mb-3">
                                    <strong>Status:</strong> 
                                    <span class="badge bg-<?php echo $admin['admin_status'] === 'active' ? 'success' : 'danger'; ?>">
                                        <?php echo ucfirst(htmlspecialchars($admin['admin_status'])); ?>
                                    </span>
                                </div>
                                <div class="mb-3">
                                    <strong>Member Since:</strong> <?php echo date('M d, Y', strtotime($admin['created_at'])); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /#page-content-wrapper -->
    </div>

    <?php require_once('parts/footer.php'); ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Form validation
            const form = document.getElementById('profileForm');
            const newPassword = document.getElementById('new_password');
            const confirmPassword = document.getElementById('confirm_password');
            const currentPassword = document.getElementById('current_password');

            // Show/hide password functionality
            document.getElementById('show_password').addEventListener('change', function() {
                const passwordFields = [currentPassword, newPassword, confirmPassword];
                passwordFields.forEach(field => {
                    if (field) {
                        field.type = this.checked ? 'text' : 'password';
                    }
                });
            });

            // Password confirmation validation
            confirmPassword.addEventListener('input', function() {
                if (newPassword.value && this.value !== newPassword.value) {
                    this.setCustomValidity('Passwords do not match');
                    this.classList.add('is-invalid');
                } else {
                    this.setCustomValidity('');
                    this.classList.remove('is-invalid');
                }
            });

            // Form submission
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Validate password fields
                if (newPassword.value && !currentPassword.value) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Current Password Required',
                        text: 'Please enter your current password to change it.'
                    });
                    return;
                }

                if (newPassword.value && newPassword.value !== confirmPassword.value) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Password Mismatch',
                        text: 'New password and confirmation password do not match.'
                    });
                    return;
                }

                // Submit form
                submitProfile();
            });
        });

        function submitProfile() {
            const formData = new FormData(document.getElementById('profileForm'));
            
            Swal.fire({
                title: 'Updating Profile...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch('query/update_profile.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Request Failed',
                    text: 'Please try again.'
                });
            });
        }

        function resetForm() {
            Swal.fire({
                title: 'Reset Form?',
                text: 'This will reset all changes. Are you sure?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, reset it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('profileForm').reset();
                    // Reset password fields to password type
                    const passwordFields = ['current_password', 'new_password', 'confirm_password'];
                    passwordFields.forEach(id => {
                        const field = document.getElementById(id);
                        if (field) {
                            field.type = 'password';
                            field.classList.remove('is-invalid');
                        }
                    });
                    document.getElementById('show_password').checked = false;
                    
                    Swal.fire(
                        'Reset!',
                        'Form has been reset.',
                        'success'
                    );
                }
            });
        }
    </script>