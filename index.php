<?php require_once('parts/top.php');
$page= "index";
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
      
      <?php // require_once('parts/navbar.php'); ?>
      <!-- /Topbar -->

      <div class="container-fluid py-4">
        <h2 class="fw-bold mb-4">Dashboard </h2>
        <!-- Accordion for Cards and Table -->
        <div class="accordion" id="dashboardAccordion">
          
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingNew">
              <button class="accordion-button collapsed bg-red-card text-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNew" aria-expanded="false" aria-controls="collapseNew">
                <div class="d-flex align-items-center w-100">
                  <div class="me-3"><i class="fas fa-plus-circle fa-2x"></i></div>
                  <div>
                    <h6 class="mb-1">New Requests</h6>
                    <small>New requests will appear here</small>
                  </div>
                </div>
              </button>
            </h2>
            <div id="collapseNew" class="accordion-collapse collapse show" aria-labelledby="headingNew" data-bs-parent="#dashboardAccordion">
              <?php require_once('parts/index/pending_email.php'); ?>
            </div>
          </div>
          <!-- Assigned Card -->
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingAssigned">
              <button class="accordion-button collapsed bg-yellow-card text-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAssigned" aria-expanded="false" aria-controls="collapseAssigned">
                <div class="d-flex align-items-center w-100">
                  <div class="me-3"><i class="fas fa-tasks fa-2x"></i></div>
                  <div>
                    <h6 class="mb-1">Assigned</h6>
                    <small>Pending tasks will appear here</small>
                  </div>
                </div>
              </button>
            </h2>
            <div id="collapseAssigned" class="accordion-collapse collapse" aria-labelledby="headingAssigned" data-bs-parent="#dashboardAccordion">
                 <?php require_once('parts/index/assigned.php'); ?>
            </div>
          </div>
          <!-- Disposed Off Card -->
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingDisposed">
              <button class="accordion-button collapsed bg-green-card text-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDisposed" aria-expanded="false" aria-controls="collapseDisposed">
                <div class="d-flex align-items-center w-100">
                  <div class="me-3"><i class="fas fa-check-circle fa-2x"></i></div>
                  <div>
                    <h6 class="mb-1">Disposed Off</h6>
                    <small>Completed tasks will be shown</small>
                  </div>
                </div>
              </button>
            </h2>
            <div id="collapseDisposed" class="accordion-collapse collapse" aria-labelledby="headingDisposed" data-bs-parent="#dashboardAccordion">
             <?php require_once('parts/index/completed.php'); ?>
            </div>
          </div>
        </div>
        <!-- /Accordion for Cards and Table -->
      </div>
    </div>
    <!-- /#page-content-wrapper -->
  </div>
 

  <?php require_once('parts/footer.php'); ?>

  </body>
</html> 