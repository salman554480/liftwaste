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
                        // Show all completed emails for everyone
                        $select_pending = "SELECT * FROM email WHERE status = 'completed' ORDER BY id DESC LIMIT 5";
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
                                    <td><span class="badge bg-success"><i class="fa fa-check-double"></i> Completed</span></td>
                                    <td><?php echo $assign_to_name; ?></td>
                                    <td>
                                        <a href="email_details.php?id=<?php echo $id; ?>" class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='7' class='text-center'>No completed requests</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

