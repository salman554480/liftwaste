<div class="accordion-body">
    <div class="card mail-card mb-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Assign To</th>
                            <th>Sender</th>
                            <th>Date - Time</th>
                            <th>Recepient</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $select_assigned = "SELECT * FROM assign WHERE assign_status = 'assigned' ORDER BY assign_id DESC LIMIT 5";
                        $result_assigned = mysqli_query($conn, $select_assigned);
                        if (mysqli_num_rows($result_assigned) > 0) {
                            while ($row_assigned = mysqli_fetch_assoc($result_assigned)) {
                                $assign_id = $row_assigned['assign_id'];
                                $email_id = $row_assigned['email_id'];
                                $admin_id = $row_assigned['admin_id'];
                                $assign_status = $row_assigned['assign_status'];
                                $created_at = $row_assigned['created_at'];
                                $updated_at = $row_assigned['updated_at'];

                                // $first_letter = $sender[0];

                                $date = new DateTime($created_at);
                                $formatted_date = $date->format('d.m.Y - h.i A');


                                $select_name = "SELECT * FROM admins WHERE admin_id = $admin_id";
                                $result_name = mysqli_query($conn, $select_name);
                                $admin_row = mysqli_fetch_assoc($result_name);
                                $assign_to = $admin_row['admin_name'];

                                $select_email = "SELECT * FROM email WHERE id = $email_id";
                                $result_email = mysqli_query($conn, $select_email);
                                $email_row = mysqli_fetch_assoc($result_email);
                                $sender = $email_row['sender'];
                                $recipient = $email_row['recipient'];
                                $subject = $email_row['subject'];
                                $body_html = $email_row['body_html'];

                        ?>
                                <tr>
                                    <td><?php echo $assign_to; ?></td>
                                    <td> <?php echo $sender; ?></td>
                                    <td><?php echo $formatted_date; ?></td>
                                    <td><?php echo $recipient; ?></td>
                                    <td><span class="badge bg-yellow-card text-dark">Assigned</span></td>
                                    <td>
                                        <button
                                            class="btn btn-success btn-sm update-btn"
                                            data-id="<?php echo $assign_id; ?>"
                                            data-status="completed">
                                            Complete
                                        </button>

                                    </td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center'>No assigned requests</td></tr>";
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
        document.querySelectorAll('.update-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const assignId = this.getAttribute('data-id');
                const newStatus = this.getAttribute('data-status');

                Swal.fire({
                    title: 'Updating status...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch('query/update_assign_status.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'assign_id=' + assignId + '&status=' + newStatus
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