<div class="accordion-body">
    <div class="card mail-card mb-0">
        <div class="card-body">
            <div class="table-responsive">
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
                        $select_pending = "SELECT * FROM email WHERE status = 'assigned' ORDER BY id DESC LIMIT 5";
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

                                $first_letter = $sender[0];

                                $date = new DateTime($received_at);
                                $formatted_date = $date->format('d.m.Y - h.i A');

                                $select_assign_to = "SELECT * FROM admins WHERE admin_id = '$assign_to'";
                                $result_assign_to = mysqli_query($conn, $select_assign_to);
                                $row_assign_to = mysqli_fetch_assoc($result_assign_to);
                                $assign_to_name = $row_assign_to['admin_name'];

                        ?>
                                <tr>
                                    <td><span class="avatar bg-secondary text-white rounded-circle me-2"><?php echo $first_letter; ?></span> <?php echo $sender; ?></td>
                                    <td><?php echo $subject; ?></td>
                                    <td><?php echo $formatted_date; ?></td>
                                    <td><?php echo $recipient; ?></td>
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
        document.querySelectorAll('.complete-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const emailId = this.getAttribute('data-id');
                const newStatus = 'completed';

                Swal.fire({
                    title: 'Updating status...',
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
            });
        });
    });
</script>