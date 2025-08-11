<div class="accordion-body">
  <div class="card mail-card mb-0">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table align-middle">
          <thead class="table-light">
            <tr>
              <th>Username</th>
              <th>Location</th>
              <th>Date - Time</th>
              <th>Mails/Phone</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $select_pending = "SELECT * FROM email WHERE status = 'pending' ORDER BY id DESC LIMIT 5";
            $result_pending = mysqli_query($conn, $select_pending);
            if (mysqli_num_rows($result_pending) > 0) {
              while ($row_pending = mysqli_fetch_assoc($result_pending)) {
                $id = $row_pending['id'];
                $sender = $row_pending['sender'];
                $subject = $row_pending['subject'];
                $body_html = $row_pending['body_html'];
                $recipient = $row_pending['recipient'];
                $received_at = $row_pending['received_at'];

                $first_letter = $sender[0];

                $date = new DateTime($received_at);
                $formatted_date = $date->format('d.m.Y - h.i A');

            ?>
                <tr>
                  <td><span class="avatar bg-secondary text-white rounded-circle me-2"><?php echo $first_letter; ?></span> <?php echo $sender; ?></td>
                  <td>6096 Marjolaine Landing</td>
                  <td><?php echo $formatted_date; ?></td>
                  <td><?php echo $recipient; ?></td>
                  <td><span class="badge bg-danger">New</span></td>
                  <td>
                    <button class="btn btn-success btn-sm assign-btn" data-id="<?php echo $id; ?>">
                      Assign
                    </button>
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.assign-btn').forEach(function(button) {
      button.addEventListener('click', function () {
        const emailId = this.getAttribute('data-id');

        Swal.fire({
          title: 'Assigning...',
          text: 'Please wait',
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading()
          }
        });

        fetch('query/assign_email.php', {
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
              title: 'Assigned!',
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
            title: 'Request failed',
            text: 'Something went wrong.'
          });
        });
      });
    });
  });
</script>
