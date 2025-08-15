<?php require_once('parts/top.php'); 
$page= "order_view";
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
                <h2 class="fw-bold mb-4">All Orders</h2>
                
                <!-- Search and Filter Section -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" id="searchInput" class="form-control" placeholder="Search emails...">
                            <button class="btn btn-outline-secondary" type="button" onclick="searchEmails()">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <select id="statusFilter" class="form-select" onchange="filterByStatus()">
                            <option value="">All Statuses</option>
                            <option value="pending">Pending</option>
                            <option value="assigned">Assigned</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                </div>

                <div id="emailRecords">
                    <!-- Email records will be loaded here -->
                </div>
            </div>
        </div>
        <!-- /#page-content-wrapper -->
    </div>

    <!-- Email Details Modal -->
    <div class="modal fade" id="emailModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Email Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="emailModalBody">
                    <!-- Email details will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <?php require_once('parts/footer.php'); ?>

    <script>
        // Load emails when page loads
        document.addEventListener('DOMContentLoaded', function() {
            loadEmails();
        });

        function loadEmails() {
            fetch('query/get_emails.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayEmails(data.emails);
                    } else {
                        document.getElementById('emailRecords').innerHTML = 
                            '<div class="alert alert-danger">Error loading emails: ' + data.message + '</div>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('emailRecords').innerHTML = 
                        '<div class="alert alert-danger">Failed to load emails. Please try again.</div>';
                });
        }

        function displayEmails(emails) {
            const container = document.getElementById('emailRecords');
            
            if (emails.length === 0) {
                container.innerHTML = '<div class="alert alert-info">No emails found.</div>';
                return;
            }

            let html = '';
            emails.forEach(email => {
                const statusClass = getStatusClass(email.status);
                const receivedDate = new Date(email.received_at).toLocaleDateString();
                const assignedDate = email.assigned_at ? new Date(email.assigned_at).toLocaleDateString() : 'Not assigned';
                const completedDate = email.completed_at ? new Date(email.completed_at).toLocaleDateString() : 'Not completed';
                
                html += `
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center" 
                             data-bs-toggle="collapse" 
                             data-bs-target="#collapse${email.id}" 
                             aria-expanded="false" 
                             aria-controls="collapse${email.id}"
                             style="cursor: pointer;">
                            <div class="d-flex align-items-center flex-grow-1">
                                <span class="badge ${statusClass} me-3">${email.status.toUpperCase()}</span>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 text-primary fw-bold">${email.subject}</h6>
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <small class="text-muted">
                                                <i class="fas fa-user me-1"></i>
                                                <strong>From:</strong> ${email.sender} (${email.sender_email})
                                            </small>
                                        </div>
                                        <div class="col-md-4">
                                            <small class="text-muted">
                                                <i class="fas fa-calendar me-1"></i>
                                                <strong>Received:</strong> ${receivedDate}
                                            </small>
                                        </div>
                                        <div class="col-md-4">
                                            <small class="text-muted">
                                                <i class="fas fa-user-check me-1"></i>
                                                <strong>Assigned to:</strong> 
                                                <span class="badge bg-secondary">${email.admin_username}</span>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <button class="btn btn-sm btn-outline-primary me-2" 
                                        onclick="viewEmailDetails(${email.id})">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <i class="fas fa-chevron-down text-muted"></i>
                            </div>
                        </div>
                        
                        <div class="collapse" id="collapse${email.id}">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Basic Information</h6>
                                        <p><strong>Recipient:</strong> ${email.recipient}</p>
                                        <p><strong>Status:</strong> <span class="badge ${statusClass}">${email.status}</span></p>
                                        <p><strong>Received:</strong> ${receivedDate}</p>
                                        <p><strong>Assigned:</strong> ${assignedDate}</p>
                                        <p><strong>Completed:</strong> ${completedDate}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Actions</h6>
                                        ${getActionButtons(email)}
                                    </div>
                                </div>
                                
                                <div class="mt-3">
                                    <h6>Email Content</h6>
                                    <div class="border rounded p-3 bg-light">
                                        <div id="emailContent${email.id}">
                                            ${email.body_html || email.body_text || 'No content available'}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            container.innerHTML = html;
        }

        function getStatusClass(status) {
            switch(status) {
                case 'pending': return 'bg-warning';
                case 'assigned': return 'bg-info';
                case 'completed': return 'bg-success';
                default: return 'bg-secondary';
            }
        }

        function getActionButtons(email) {
            let buttons = '';
            
            if (email.status === 'pending') {
                buttons += `
                    <button class="btn btn-sm btn-primary me-2" onclick="updateStatus(${email.id}, 'assigned')">
                        <i class="fas fa-user-check"></i> Assign
                    </button>
                `;
            }
            
            if (email.status === 'assigned') {
                buttons += `
                    <button class="btn btn-sm btn-success me-2" onclick="updateStatus(${email.id}, 'completed')">
                        <i class="fas fa-check"></i> Mark Complete
                    </button>
                `;
            }
            
            return buttons;
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
                        loadEmails(); // Reload the emails
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

        function viewEmailDetails(emailId) {
            // This would open a modal with full email details
            // For now, just scroll to the collapsed section
            const collapseElement = document.getElementById('collapse' + emailId);
            if (collapseElement) {
                const bsCollapse = new bootstrap.Collapse(collapseElement, {
                    show: true
                });
            }
        }

        function searchEmails() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const cards = document.querySelectorAll('.card');
            
            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        function filterByStatus() {
            const selectedStatus = document.getElementById('statusFilter').value;
            const cards = document.querySelectorAll('.card');
            
            cards.forEach(card => {
                if (!selectedStatus || card.querySelector('.badge').textContent.toLowerCase().includes(selectedStatus)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }
    </script>