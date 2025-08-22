<?php 
require_once('parts/top.php'); 
$page = "email_details";

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
                                    <!-- Received -->
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-primary">
                                            <i class="fas fa-envelope text-white"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h6 class="mb-1">Email Received</h6>
                                            <p class="text-muted mb-0">
                                                <?php echo date('M d, Y \a\t h:i A', strtotime($email['received_at'])); ?>
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Assigned -->
                                    <?php if ($email['assigned_at']): ?>
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-warning">
                                            <i class="fas fa-user-check text-white"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h6 class="mb-1">Email Assigned</h6>
                                            <p class="text-muted mb-0">
                                                <?php echo date('M d, Y \a\t h:i A', strtotime($email['assigned_at'])); ?>
                                            </p>
                                            <?php if ($email['assigned_admin_name']): ?>
                                                <small class="text-info">
                                                    Assigned to: <?php echo htmlspecialchars($email['assigned_admin_name']); ?>
                                                </small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php endif; ?>

                                    <!-- Completed -->
                                    <?php if ($email['completed_at']): ?>
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-success">
                                            <i class="fas fa-check-circle text-white"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h6 class="mb-1">Email Completed</h6>
                                            <p class="text-muted mb-0">
                                                <?php echo date('M d, Y \a\t h:i A', strtotime($email['completed_at'])); ?>
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
    </script>
