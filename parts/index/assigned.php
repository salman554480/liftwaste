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
                
                // Show confirmation popup
                Swal.fire({
                    title: 'Mark as Completed',
                    text: 'Are you sure you want to mark this email as completed?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Complete',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        completeEmail(emailId);
                    }
                });
            });
        });

    });

    function completeEmail(emailId) {
        Swal.fire({
            title: 'Updating Status...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Use session-based authentication (no password required)
        fetch('query/update_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'email_id=' + emailId + '&status=completed'
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
                title: 'Update Failed',
                text: 'Please try again.'
            });
        });
    }

</script>