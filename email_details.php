<?php 
require_once('parts/top.php'); 
$page = "email_details";

// Require login for email details access
requireLogin();

// Get email ID from URL
$email_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($email_id <= 0) {
    header('Location: index.php');
    exit();
}

// Get email details
require_once('parts/db.php');
$query = "SELECT e.*, a.admin_name as assigned_admin_name 
          FROM email e 
          LEFT JOIN admins a ON e.admin_id = a.admin_id 
          WHERE e.id = ?";
$stmt = mysqli_prepare($conn, $query);
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $email_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $email = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
} else {
    header('Location: index.php');
    exit();
}

if (!$email) {
    header('Location: index.php');
    exit();
}
?>
</head>

<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <?php require_once('parts/sidebar.php'); ?>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper" class="flex-grow-1">
            <!-- Topbar -->
            <?php require_once('parts/navbar.php'); ?>
            <!-- /Topbar -->

            <div class="container-fluid py-4">
                <!-- Header with back button -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="fw-bold mb-1">Email Details</h2>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                                        <li class="breadcrumb-item active">Email #<?php echo $email_id; ?></li>
                                    </ol>
                                </nav>
                            </div>
                            <div>
                                <a href="index.php" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Left Column - Email Details -->
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-envelope me-2"></i>
                                    Email Information
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Subject</label>
                                        <p class="form-control-plaintext"><?php echo htmlspecialchars($email['subject']); ?></p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Status</label>
                                        <div>
                                            <?php
                                            $status_class = '';
                                            $status_text = '';
                                            switch($email['status']) {
                                                case 'pending':
                                                    $status_class = 'bg-danger';
                                                    $status_text = 'Pending';
                                                    break;
                                                case 'assigned':
                                                    $status_class = 'bg-warning';
                                                    $status_text = 'Assigned';
                                                    break;
                                                case 'completed':
                                                    $status_class = 'bg-success';
                                                    $status_text = 'Completed';
                                                    break;
                                                default:
                                                    $status_class = 'bg-secondary';
                                                    $status_text = ucfirst($email['status']);
                                            }
                                            ?>
                                            <span class="badge <?php echo $status_class; ?> fs-6"><?php echo $status_text; ?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Sender</label>
                                        <p class="form-control-plaintext">
                                            <i class="fas fa-user me-2"></i>
                                            <?php echo htmlspecialchars($email['sender']); ?>
                                        </p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Sender Email</label>
                                        <p class="form-control-plaintext">
                                            <i class="fas fa-envelope me-2"></i>
                                            <a href="mailto:<?php echo htmlspecialchars($email['sender_email']); ?>">
                                                <?php echo htmlspecialchars($email['sender_email']); ?>
                                            </a>
                                        </p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Recipient</label>
                                        <p class="form-control-plaintext">
                                            <i class="fas fa-user-tie me-2"></i>
                                            <?php echo htmlspecialchars($email['recipient']); ?>
                                        </p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Email ID</label>
                                        <p class="form-control-plaintext">
                                            <i class="fas fa-hashtag me-2"></i>
                                            #<?php echo $email['id']; ?>
                                        </p>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Email Content</label>
                                    <div class="border rounded p-3 bg-light">
                                        <?php if (!empty($email['body_html'])): ?>
                                            <div class="email-content">
                                                <?php echo $email['body_html']; ?>
                                            </div>
                                        <?php elseif (!empty($email['body_text'])): ?>
                                            <div class="email-content">
                                                <pre class="mb-0"><?php echo htmlspecialchars($email['body_text']); ?></pre>
                                            </div>
                                        <?php else: ?>
                                            <p class="text-muted mb-0">No content available</p>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <?php if (!empty($email['note'])): ?>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Current Note</label>
                                    <div class="border rounded p-3 bg-info bg-opacity-10">
                                        <p class="mb-0">
                                            <i class="fas fa-sticky-note me-2 text-info"></i>
                                            <?php echo nl2br(htmlspecialchars($email['note'])); ?>
                                        </p>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Notes Display Card -->
                        <div class="card my-4">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-sticky-note me-2"></i>
                                    Notes History
                                </h6>
                            </div>
                            <div class="card-body">
                                <div id="notesContainer">
                                    <div class="text-center text-muted">
                                        <i class="fas fa-spinner fa-spin"></i> Loading notes...
                                    </div>
                                </div>
                                
                                <hr class="my-3">
                                
                                <!-- Add New Note Section -->
                                <div class="mb-3">
                                    <label for="newNoteField" class="form-label fw-bold">Add New Note</label>
                                    <textarea class="form-control" id="newNoteField" rows="3" placeholder="Enter your note here..."></textarea>
                                </div>
                                
                                <!-- Authentication Fields -->
                                <div class="row g-2 mb-3">
                                    <div class="col-md-6">
                                        <input type="email" class="form-control" id="noteAdminEmail" placeholder="Your Email" required>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="password" class="form-control" id="noteAdminPassword" placeholder="Your Password" required>
                                    </div>
                                </div>
                                
                                <button class="btn btn-primary w-100" onclick="addNewNote(<?php echo $email_id; ?>)">
                                    <i class="fas fa-plus me-2"></i>Add Note
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Status & Actions -->
                    <div class="col-lg-4">
                        <!-- Status Card -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Current Status
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="text-center mb-3">
                                    <div class="status-icon-wrapper mb-3">
                                        <?php if ($email['status'] === 'pending'): ?>
                                            <i class="fas fa-clock fa-3x text-danger"></i>
                                        <?php elseif ($email['status'] === 'assigned'): ?>
                                            <i class="fas fa-user-check fa-3x text-warning"></i>
                                        <?php elseif ($email['status'] === 'completed'): ?>
                                            <i class="fas fa-check-circle fa-3x text-success"></i>
                                        <?php endif; ?>
                                    </div>
                                    <h5 class="mb-2"><?php echo ucfirst($email['status']); ?></h5>
                                    <p class="text-muted mb-0">Email is currently <?php echo $email['status']; ?></p>
                                </div>

                                <?php if ($email['admin_id']): ?>
                                    <div class="border-top pt-3">
                                        <label class="form-label fw-bold">Assigned To</label>
                                        <p class="mb-0">
                                            <i class="fas fa-user me-2"></i>
                                            <?php echo htmlspecialchars($email['assigned_admin_name']); ?>
                                        </p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                       

                        

                         <!-- Actions Card -->
                         <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-tools me-2"></i>
                                    Actions
                                </h6>
                            </div>
                            <div class="card-body">
                                <?php if ($email['status'] === 'pending'): ?>
                                    <button class="btn btn-primary w-100 mb-2" onclick="authenticateAndAssign(<?php echo $email_id; ?>)">
                                        <i class="fas fa-user-check me-2"></i>Assign to Me
                                    </button>
                                <?php elseif ($email['status'] === 'assigned'): ?>
                                    <button class="btn btn-success w-100 mb-2" onclick="authenticateAndComplete(<?php echo $email_id; ?>)">
                                        <i class="fas fa-check me-2"></i>Mark as Completed
                                    </button>
                                <?php endif; ?>
                                
                               
                            </div>
                        </div>

                        

                        <!-- Activity Log Card -->
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-history me-2"></i>
                                    Activity Log
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="timeline">
                                    <!-- Received - Always shown -->
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-primary">
                                            <i class="fas fa-envelope text-white"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h6 class="mb-1">Email Received</h6>
                                            <p class="text-muted mb-0">
                                                <?php echo date('m/d/Y \a\t h:i A', strtotime($email['received_at'])); ?>
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Assigned - Only show if status is assigned or completed -->
                                    <?php if (in_array($email['status'], ['assigned', 'completed']) && $email['assigned_at']): ?>
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-warning">
                                            <i class="fas fa-user-check text-white"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h6 class="mb-1">Email Assigned</h6>
                                            <p class="text-muted mb-0">
                                                <?php echo date('m/d/Y \a\t h:i A', strtotime($email['assigned_at'])); ?>
                                            </p>
                                            <?php if ($email['assigned_admin_name']): ?>
                                                <small class="text-info">
                                                    Assigned to: <?php echo htmlspecialchars($email['assigned_admin_name']); ?>
                                                </small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php endif; ?>

                                    <!-- Completed - Only show if status is completed -->
                                    <?php if ($email['status'] === 'completed' && $email['completed_at']): ?>
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-success">
                                            <i class="fas fa-check-circle text-white"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h6 class="mb-1">Email Completed</h6>
                                            <p class="text-muted mb-0">
                                                <?php echo date('m/d/Y \a\t h:i A', strtotime($email['completed_at'])); ?>
                                            </p>
                                        </div>
                                    </div>
                                    <?php endif; ?>
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

    <style>
        .timeline {
            position: relative;
            padding-left: 30px;
        }
        
        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }
        
        .timeline-marker {
            position: absolute;
            left: -45px;
            top: 0;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2;
        }
        
        .timeline-item:not(:last-child)::after {
            content: '';
            position: absolute;
            left: -30px;
            top: 30px;
            width: 2px;
            height: calc(100% + 10px);
            background-color: #e9ecef;
        }
        
        .timeline-content {
            padding-left: 15px;
        }
        
        .email-content {
            max-height: 400px;
            overflow-y: auto;
        }
        
        .email-content img {
            max-width: 100%;
            height: auto;
        }
    </style>

    <script>
        function authenticateAndAssign(emailId) {
            Swal.fire({
                title: 'Authentication Required',
                html: `
                    <div class="mb-3">
                        <label for="auth_email" class="form-label">Email Address</label>
                        <input type="email" id="auth_email" class="form-control" placeholder="Enter your email">
                    </div>
                    <div class="mb-3">
                        <label for="auth_password" class="form-label">Password</label>
                        <input type="password" id="auth_password" class="form-control" placeholder="Enter your password">
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Authenticate & Assign',
                cancelButtonText: 'Cancel',
                preConfirm: () => {
                    const email = document.getElementById('auth_email').value;
                    const password = document.getElementById('auth_password').value;
                    
                    if (!email || !password) {
                        Swal.showValidationMessage('Please enter both email and password');
                        return false;
                    }
                    
                    return { email, password };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    authenticateAndUpdateStatus(emailId, 'assigned', result.value.email, result.value.password);
                }
            });
        }

        function authenticateAndComplete(emailId) {
            Swal.fire({
                title: 'Authentication Required',
                html: `
                    <div class="mb-3">
                        <label for="auth_email" class="form-label">Email Address</label>
                        <input type="email" id="auth_email" class="form-control" placeholder="Enter your email">
                    </div>
                    <div class="mb-3">
                        <label for="auth_password" class="form-label">Password</label>
                        <input type="password" id="auth_password" class="form-control" placeholder="Enter your password">
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Authenticate & Complete',
                cancelButtonText: 'Cancel',
                preConfirm: () => {
                    const email = document.getElementById('auth_email').value;
                    const password = document.getElementById('auth_password').value;
                    
                    if (!email || !password) {
                        Swal.showValidationMessage('Please enter both email and password');
                        return false;
                    }
                    
                    return { email, password };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    authenticateAndUpdateStatus(emailId, 'completed', result.value.email, result.value.password);
                }
            });
        }

        function authenticateAndUpdateStatus(emailId, newStatus, email, password) {
            const statusText = newStatus === 'assigned' ? 'Assigning...' : 'Updating...';
            
            Swal.fire({
                title: 'Authenticating...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // First authenticate the user
            fetch('query/authenticate_user.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'email=' + encodeURIComponent(email) + '&password=' + encodeURIComponent(password)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Authentication successful, now update status
                    return fetch('query/update_status.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'email_id=' + emailId + '&status=' + newStatus + '&admin_email=' + encodeURIComponent(email)
                    });
                } else {
                    throw new Error(data.message || 'Authentication failed');
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: data.message,
                        timer: 1500,
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
            .catch((error) => {
                Swal.fire({
                    icon: 'error',
                    title: 'Authentication Failed',
                    text: error.message || 'Please check your credentials and try again.'
                });
            });
        }

        function updateStatus(emailId, newStatus) {
            const statusText = newStatus === 'assigned' ? 'Assigning...' : 'Updating...';
            
            Swal.fire({
                title: statusText,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch('query/update_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'email_id=' + emailId + '&status=' + newStatus
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: data.message,
                        timer: 1500,
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
            .catch(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Request Failed',
                    text: 'Please try again.'
                });
            });
        }

        // Admin Functions
        function checkAdminStatus() {
            Swal.fire({
                title: 'Admin Access Verification',
                html: `
                    <div class="mb-3">
                        <label for="admin_email" class="form-label">Your Email Address</label>
                        <input type="email" id="admin_email" class="form-control" placeholder="Enter your email">
                    </div>
                    <div class="mb-3">
                        <label for="admin_password" class="form-label">Your Password</label>
                        <input type="password" id="admin_password" class="form-control" placeholder="Enter your password">
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Verify',
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
                    verifyAdminAndLoadUsers(result.value.email, result.value.password);
                }
            });
        }

        function verifyAdminAndLoadUsers(email, password) {
            Swal.fire({
                title: 'Verifying Admin...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // First authenticate admin
            fetch('query/authenticate_user.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'email=' + encodeURIComponent(email) + '&password=' + encodeURIComponent(password)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.user.admin_role === 'admin') {
                    // Admin verified, load users
                    return fetch('query/get_users.php');
                } else {
                    throw new Error(data.user && data.user.admin_role !== 'admin' ? 'You need admin privileges' : data.message);
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadUsersIntoSelect(data.users);
                    showAdminSection();
                    Swal.fire({
                        icon: 'success',
                        title: 'Admin Access Granted',
                        text: 'You can now assign emails to users',
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    throw new Error(data.message || 'Failed to load users');
                }
            })
            .catch((error) => {
                Swal.fire({
                    icon: 'error',
                    title: 'Access Denied',
                    text: error.message || 'Authentication failed'
                });
            });
        }

        function loadUsersIntoSelect(users) {
            const select = document.getElementById('assignToUser');
            select.innerHTML = '<option value="">Select a user...</option>';
            
            users.forEach(user => {
                const option = document.createElement('option');
                option.value = user.admin_email;
                option.textContent = `${user.admin_name} (${user.admin_email})`;
                select.appendChild(option);
            });

            // Enable/disable assign button based on selection
            select.addEventListener('change', function() {
                const assignBtn = document.getElementById('assignToUserBtn');
                assignBtn.disabled = !this.value;
            });
        }

        function showAdminSection() {
            document.getElementById('adminActionsCard').style.display = 'block';
            document.getElementById('adminAccessCard').style.display = 'none';
        }

        function assignToUser(emailId) {
            const selectedUser = document.getElementById('assignToUser').value;
            if (!selectedUser) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No User Selected',
                    text: 'Please select a user to assign the email to.'
                });
                return;
            }

            Swal.fire({
                title: 'Confirm Assignment',
                text: `Are you sure you want to assign this email to ${selectedUser}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Assign',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    performUserAssignment(emailId, selectedUser);
                }
            });
        }

        function performUserAssignment(emailId, userEmail) {
            Swal.fire({
                title: 'Assigning Email...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch('query/update_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'email_id=' + emailId + '&status=assigned&admin_email=' + encodeURIComponent(userEmail)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Email Assigned',
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Assignment Failed',
                        text: data.message
                    });
                }
            })
            .catch((error) => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to assign email. Please try again.'
                });
            });
        }

        // Load notes when page loads
        document.addEventListener('DOMContentLoaded', function() {
            loadNotes(<?php echo $email_id; ?>);
        });

        function loadNotes(emailId) {
            fetch('query/get_notes.php?email_id=' + emailId)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayNotes(data.notes);
                    } else {
                        document.getElementById('notesContainer').innerHTML = 
                            '<div class="alert alert-warning">No notes found for this email.</div>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('notesContainer').innerHTML = 
                        '<div class="alert alert-danger">Failed to load notes. Please try again.</div>';
                });
        }

        function displayNotes(notes) {
            const container = document.getElementById('notesContainer');
            
            if (notes.length === 0) {
                container.innerHTML = '<div class="alert alert-info">No notes added yet. Be the first to add a note!</div>';
                return;
            }

            let html = '';
            notes.forEach(note => {
                const noteDate = formatDateTime(note.created_at);
                const roleBadge = note.admin_role === 'admin' ? 'bg-danger' : 'bg-primary';
                
                html += `
                    <div class="border rounded p-3 mb-3 bg-light">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <span class="badge ${roleBadge} me-2">${note.admin_role}</span>
                                <strong class="text-primary">${note.admin_name}</strong>
                                <small class="text-muted ms-2">(${note.admin_email})</small>
                            </div>
                            <small class="text-muted">${noteDate}</small>
                        </div>
                        <p class="mb-0">${note.note_text}</p>
                    </div>
                `;
            });
            
            container.innerHTML = html;
        }

        function addNewNote(emailId) {
            const noteText = document.getElementById('newNoteField').value.trim();
            const adminEmail = document.getElementById('noteAdminEmail').value.trim();
            const adminPassword = document.getElementById('noteAdminPassword').value;

            if (!noteText || !adminEmail || !adminPassword) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Information',
                    text: 'Please fill in all fields: note text, email, and password.'
                });
                return;
            }

            Swal.fire({
                title: 'Adding Note...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const formData = new FormData();
            formData.append('email_id', emailId);
            formData.append('note', noteText);
            formData.append('admin_email', adminEmail);
            formData.append('admin_password', adminPassword);

            fetch('query/update_note.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Note Added Successfully',
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        // Clear the form
                        document.getElementById('newNoteField').value = '';
                        document.getElementById('noteAdminEmail').value = '';
                        document.getElementById('noteAdminPassword').value = '';
                        
                        // Reload notes
                        loadNotes(emailId);
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed to Add Note',
                        text: data.message
                    });
                }
            })
            .catch(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Request Failed',
                    text: 'Please try again.'
                });
            });
        }

        function formatDateTime(dateString) {
            if (!dateString) return 'Not set';
            const date = new Date(dateString);
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            const year = date.getFullYear();
            const hours = date.getHours();
            const minutes = String(date.getMinutes()).padStart(2, '0');
            const ampm = hours >= 12 ? 'PM' : 'AM';
            const displayHours = hours % 12 || 12;
            
            return `${month}/${day}/${year} - ${displayHours}:${minutes} ${ampm}`;
        }
    </script>
