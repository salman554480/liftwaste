<?php
// No role restrictions - anyone can access all functionality
$count_user_pending_task = 0; // Set to 0 to allow unlimited assignments
?>
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
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $select_pending = "SELECT * FROM email WHERE status = 'pending' ORDER BY id ASC";
            $result_pending = mysqli_query($conn, $select_pending);
            if (mysqli_num_rows($result_pending) > 0) {
              while ($row_pending = mysqli_fetch_assoc($result_pending)) {
                $id = $row_pending['id'];
                $sender = $row_pending['sender'];
                $subject = $row_pending['subject'];
                $body_html = $row_pending['body_html'];
                $recipient = $row_pending['recipient'];
                $received_at = $row_pending['received_at'];
                $sender_email = $row_pending['sender_email'];

                $first_letter = $sender[0];

                $date = new DateTime($received_at);
                $formatted_date = $date->format('m/d/Y - h:i A');


            ?>
                <tr>
                  <td><span class="avatar bg-secondary text-white rounded-circle me-2"><?php echo $first_letter; ?></span> <?php echo $sender; ?></td>
                  <td><?php echo $subject ?></td>
                  <td><?php echo $formatted_date; ?></td>
                  <td><?php echo $sender_email; ?></td>
                  <td><span class="badge bg-danger">New</span></td>
                  <td>
                    <div class="btn-group" role="group">
                      <a href="email_details.php?id=<?php echo $id; ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-eye"></i> View
                      </a>
                      <button class="btn btn-success btn-sm assign-btn" data-id="<?php echo $id; ?>">
                        <i class="fas fa-user-check"></i> Assign
                      </button>
                    </div>
                  </td>
                </tr>
            <?php
              }
            } else {
              echo "<tr><td colspan='6' class='text-center'>No pending requests</td></tr>";
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
    document.querySelectorAll('.assign-btn').forEach(function(btn) {
      btn.addEventListener('click', function() {
        const emailId = this.getAttribute('data-id');
        
        // Show confirmation popup
        Swal.fire({
          title: 'Assign Email',
          text: 'Are you sure you want to assign this email to yourself?',
          icon: 'question',
          showCancelButton: true,
          confirmButtonText: 'Yes, Assign',
          cancelButtonText: 'Cancel'
        }).then((result) => {
          if (result.isConfirmed) {
            assignEmail(emailId);
          }
        });
      });
    });
  });

  function assignEmail(emailId) {
    Swal.fire({
      title: 'Assigning Email...',
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
      body: 'email_id=' + emailId + '&status=assigned'
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
        title: 'Assignment Failed',
        text: 'Please try again.'
      });
    });
  }
</script>