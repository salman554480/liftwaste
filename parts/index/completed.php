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
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $select_completed = "SELECT * FROM assign WHERE assign_status = 'completed' ORDER BY assign_id DESC LIMIT 5";
                        $result_completed = mysqli_query($conn, $select_completed);
                        if (mysqli_num_rows($result_completed) > 0) {
                            while ($row_completed = mysqli_fetch_assoc($result_completed)) {
                                $assign_id = $row_completed['assign_id'];
                                $email_id = $row_completed['email_id'];
                                $admin_id = $row_completed['admin_id'];
                                $assign_status = $row_completed['assign_status'];
                                $created_at = $row_completed['created_at'];
                                $updated_at = $row_completed['updated_at'];

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
                                    <td><span class="badge bg-success text-dark">completed</span></td>
                                   
                                </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center'>No completed requests</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

