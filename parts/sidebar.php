    
<div class="bg-white border-end sidebar" id="sidebar-wrapper">
      <div class="d-flex align-items-center justify-content-between sidebar-heading text-primary fw-bold py-4 px-3">
        <a href="index.php" class="text-decoration-none">Lift/CB&E</a>
        <button class="btn btn-link d-lg-none p-0 ms-2" id="sidebar-close" aria-label="Close sidebar"><i class="fas fa-times fa-lg"></i></button>
      </div>
      <div class="list-group list-group-flush">
        <a href="index.php" class="list-group-item list-group-item-action <?php if($page == 'index') { echo 'active'; } ?>"><i
            class="fas fa-tachometer-alt me-2"></i>Dashboard</a>

           <?php if($admin_role == 'admin') { ?>
        <a href="order_view.php" class="list-group-item list-group-item-action <?php if($page == 'order_view') { echo 'active'; } ?>"><i class="fas fa-list me-2"></i>All Orders</a>
        <a href="member_view.php" class="list-group-item list-group-item-action"><i class="fas fa-user me-2"></i>Members</a>
        <?php } ?>
      </div>
      <div class="sidebar-footer mt-auto px-3 pb-3">
        <a href="#" class="list-group-item list-group-item-action"><i class="fas fa-cog me-2"></i>Settings</a>
        <a href="logout.php" class="list-group-item list-group-item-action text-danger"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
      </div>
    </div>