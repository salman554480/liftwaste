<?php require_once('parts/top.php'); 

// Require login for member view access
requireLogin();
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
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="fw-bold mb-4">Members</h2>
                    <a href="member_add.php" class="btn btn-primary">Add Member</a>
                </div>
                <?php
                $result = mysqli_query($conn, "SELECT * FROM admins ORDER BY admin_id DESC");
                ?>

                <div class="custom-card p-3">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="adminTable">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Contact</th>
                                    <th>Address</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1;
                                while ($row = mysqli_fetch_assoc($result)) : ?>
                                    <tr id="row_<?= $row['admin_id'] ?>">
                                        <td><?= $i++ ?></td>
                                        <td><?= htmlspecialchars($row['admin_name']) ?></td>
                                        <td><?= htmlspecialchars($row['admin_email']) ?></td>
                                        <td><?= htmlspecialchars($row['admin_contact']) ?></td>
                                        <td><?= htmlspecialchars($row['admin_address']) ?></td>
                                        <td><?= $row['admin_role'] ?></td>
                                        <td>
                                            <span class="badge bg-<?= $row['admin_status'] == 'active' ? 'success' : ($row['admin_status'] == 'inactive' ? 'secondary' : 'danger') ?>">
                                                <?= ucfirst($row['admin_status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-warning edit-btn"
                                                data-id="<?= $row['admin_id'] ?>"
                                                data-name="<?= htmlspecialchars($row['admin_name']) ?>"
                                                data-email="<?= htmlspecialchars($row['admin_email']) ?>"
                                                data-contact="<?= htmlspecialchars($row['admin_contact']) ?>"
                                                data-address="<?= htmlspecialchars($row['admin_address']) ?>"
                                                data-role="<?= $row['admin_role'] ?>"
                                                data-status="<?= $row['admin_status'] ?>">
                                                Edit
                                            </button>

                                            <button class="btn btn-sm btn-danger delete-btn" data-id="<?= $row['admin_id'] ?>">Delete</button>
                                        </td>


                                    </tr>
                                <?php endwhile; ?>
                            </tbody>

                        </table>
                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form id="editForm">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Admin</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body">
                                            <input type="hidden" name="admin_id" id="edit_id">

                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label>Name</label>
                                                    <input type="text" name="admin_name" id="edit_name" class="form-control" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Email</label>
                                                    <input type="email" name="admin_email" id="edit_email" class="form-control" required>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label>Contact</label>
                                                    <input type="text" name="admin_contact" id="edit_contact" class="form-control" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Address</label>
                                                    <input type="text" name="admin_address" id="edit_address" class="form-control" required>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label>New Password <small class="text-muted">(Leave blank to keep existing)</small></label>
                                                    <input type="password" name="admin_password" id="edit_password" class="form-control" placeholder="Optional">
                                                </div>
                                            </div>


                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label>Role</label>
                                                    <select name="admin_role" id="edit_role" class="form-select" required>
                                                        <option value="admin">Admin</option>
                                                        <option value="employee">Employee</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Status</label>
                                                    <select name="admin_status" id="edit_status" class="form-select" required>
                                                        <option value="active">Active</option>
                                                        <option value="inactive">Inactive</option>
                                                        <option value="suspended">Suspended</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Update</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        </div>
                                    </form>
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

</body>

</html>

<script>
    $(document).on("click", ".delete-btn", function() {
        let id = $(this).data("id");

        Swal.fire({
            title: "Are you sure?",
            text: "This admin will be deleted permanently.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "query/delete_admin.php",
                    type: "POST",
                    data: {
                        id: id
                    },
                    success: function(response) {
                        let res = JSON.parse(response);
                        if (res.success) {
                            $("#row_" + id).remove();
                            Swal.fire("Deleted!", res.message, "success");
                        } else {
                            Swal.fire("Error!", res.message, "error");
                        }
                    },
                    error: function() {
                        Swal.fire("Error!", "Something went wrong.", "error");
                    }
                });
            }
        });
    });
</script>
<script>
    $(document).on('click', '.edit-btn', function() {
        $('#edit_id').val($(this).data('id'));
        $('#edit_name').val($(this).data('name'));
        $('#edit_email').val($(this).data('email'));
        $('#edit_contact').val($(this).data('contact'));
        $('#edit_address').val($(this).data('address'));
        $('#edit_role').val($(this).data('role'));
        $('#edit_status').val($(this).data('status'));

        new bootstrap.Modal(document.getElementById('editModal')).show();
    });

    $('#editForm').submit(function(e) {
        e.preventDefault();

        $.ajax({
            url: 'query/update_admin.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                let res = JSON.parse(response);
                if (res.success) {
                    Swal.fire("Updated!", res.message, "success").then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire("Error!", res.message, "error");
                }
            },
            error: function() {
                Swal.fire("Error!", "Something went wrong.", "error");
            }
        });
    });
</script>

<script>
    $('#editModal').on('hidden.bs.modal', function() {
        $('#edit_password').val('');
    });
</script>