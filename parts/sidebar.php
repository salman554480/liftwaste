    
<div class="bg-white border-end sidebar" id="sidebar-wrapper">
      <div class="d-flex align-items-center justify-content-between sidebar-heading text-primary fw-bold py-2 mx-auto">
        <a href="index.php" class="text-decoration-none"><img src="assets/img/favicon.png" height="50px" alt=""></a>
        <button class="btn btn-link d-lg-none p-0 ms-2" id="sidebar-close" aria-label="Close sidebar"><i class="fas fa-times fa-lg"></i></button>
      </div>
      <div class="list-group list-group-flush">
        <a href="index.php" class="list-group-item list-group-item-action <?php if($page == 'index') { echo 'active'; } ?>"><i
            class="fas fa-tachometer-alt me-2"></i>Dashboard</a>

            <a href="logout.php"  class="list-group-item list-group-item-action"><i
            class="fas fa-sign-out me-2"></i>Logout</a>     
      
      </div>
      <!--<div class="sidebar-footer mt-auto px-3 pb-3">
        <a href="#" class="list-group-item list-group-item-action"><i class="fas fa-cog me-2"></i>Settings</a>
      </div>-->
    </div>

