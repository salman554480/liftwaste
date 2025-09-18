<div class="accordion-body">
    <div class="card mail-card mb-0">
        <div class="card-body">
            <div class="table-scroll">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Username</th>
                            <th>Message</th>
                            <th>Date - Time</th>
                            <th>Mails/Phone</th>
                            <th>Status</th>
                            <th>Assign To</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Show all assigned emails for everyone
                        $select_pending = "SELECT * FROM email WHERE status = 'assigned' ORDER BY id ASC";
                        $result_pending = mysqli_query($conn, $select_pending);
                        if (mysqli_num_rows($result_pending) > 0) {
                            while ($row_pending = mysqli_fetch_assoc($result_pending)) {
                                $id = $row_pending['id'];
                                $assign_to = $row_pending['admin_id'];
                                $sender = $row_pending['sender'];
                                $subject = $row_pending['subject'];
                                $body_html = $row_pending['body_html'];
                                $recipient = $row_pending['recipient'];
                                $received_at = $row_pending['received_at'];
                                $sender_email = $row_pending['sender_email'];

                                $first_letter = $sender[0];

                                $date = new DateTime($received_at);
                                $formatted_date = $date->format('m/d/Y - h:i A');

                                $select_assign_to = "SELECT * FROM admins WHERE admin_id = '$assign_to'";
                                $result_assign_to = mysqli_query($conn, $select_assign_to);
                                $row_assign_to = mysqli_fetch_assoc($result_assign_to);
                                $assign_to_name = $row_assign_to['admin_name'];

                        ?>
                                <tr>
                                    <td><span class="avatar bg-secondary text-white rounded-circle me-2"><?php echo $first_letter; ?></span> <?php echo $sender; ?></td>
                                    <td><?php echo $subject; ?></td>
                                    <td><?php echo $formatted_date; ?></td>
                                    <td><?php echo $sender_email; ?></td>
                                    <td><span class="badge bg-warning">Assigned</span></td>
                                    <td><?php echo $assign_to_name; ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="email_details.php?id=<?php echo $id; ?>" class="btn btn-primary btn-sm">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <button class="btn btn-success btn-sm complete-btn" data-id="<?php echo $id; ?>">
                                                <i class="fa fa-check"></i> Mark as Completed
                                            </button>
                                            <button class="btn btn-danger btn-sm delete-btn" data-id="<?php echo $id; ?>" data-subject="<?php echo htmlspecialchars($subject); ?>">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='7' class='text-center'>No assigned requests</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Complete button functionality
        document.querySelectorAll('.complete-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const emailId = this.getAttribute('data-id');
                
                // Show authentication popup
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
                        authenticateAndComplete(emailId, result.value.email, result.value.password);
                    }
                });
            });
        });

        // Delete button functionality
        document.querySelectorAll('.delete-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const emailId = this.getAttribute('data-id');
                const subject = this.getAttribute('data-subject');
                
                // Show delete confirmation popup
                Swal.fire({
                    title: 'Delete Email',
                    html: `
                        <p>Are you sure you want to delete this email?</p>
                        <div class="alert alert-warning">
                            <strong>Subject:</strong> ${subject}
                        </div>
                        <p class="text-danger"><strong>Warning:</strong> This action cannot be undone!</p>
                    `,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, Delete It!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        deleteEmail(emailId);
                    }
                });
            });
        });
    });

    function authenticateAndComplete(emailId, email, password) {
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
                // Authentication successful, now update status to completed
                return fetch('query/update_status.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'email_id=' + emailId + '&status=completed&admin_email=' + encodeURIComponent(email)
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

    function deleteEmail(emailId) {
        Swal.fire({
            title: 'Deleting Email...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch('query/delete_email.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'email_id=' + emailId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Email Deleted',
                    text: data.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Delete Failed',
                    text: data.message
                });
            }
        })
        .catch((error) => {
            Swal.fire({
                icon: 'error',
                title: 'Request Failed',
                text: 'Failed to delete email. Please try again.'
            });
        });
    }
</script>