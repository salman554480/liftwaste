<?php require_once('parts/top.php'); ?>
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
        <h2 class="fw-bold mb-4">Dashboard 2</h2>
        <!-- Accordion for Cards and Table -->
        <div class="accordion" id="dashboardAccordion">
          <!-- All Requests Card 
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingAll">
              <button class="accordion-button bg-white-card text-black" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAll" aria-expanded="true" aria-controls="collapseAll">
                <div class="d-flex align-items-center w-100">
                  <div class="me-3"><i class="fas fa-envelope fa-2x text-danger"></i></div>
                  <div>
                    <h6 class="mb-1">All Requests</h6>
                    <small class="text-muted">You can see all requests here</small>
                  </div>
                </div>
              </button>
            </h2>
            <div id="collapseAll" class="accordion-collapse collapse show" aria-labelledby="headingAll" data-bs-parent="#dashboardAccordion">
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
                            <th>Assigned to</th>
                            <th>Status</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td><span class="avatar bg-secondary text-white rounded-circle me-2">J</span> John</td>
                            <td>6096 Marjolaine Landing</td>
                            <td>12.09.2019 - 12.53 PM</td>
                            <td>Abc@mail.com</td>
                            <td>Abc@mail.com</td>
                            <td><span class="badge bg-success">Completed</span></td>
                          </tr>
                          <tr>
                            <td><span class="avatar bg-secondary text-white rounded-circle me-2">D</span> Doe</td>
                            <td>6096 Marjolaine Landing</td>
                            <td>12.09.2019 - 12.53 PM</td>
                            <td>Abc@mail.com</td>
                            <td>Abc@mail.com</td>
                            <td><span class="badge bg-warning text-dark">Assigned</span></td>
                          </tr>
                          <tr>
                            <td><span class="avatar bg-secondary text-white rounded-circle me-2">M</span> Mike</td>
                            <td>6096 Marjolaine Landing</td>
                            <td>12.09.2019 - 12.53 PM</td>
                            <td>Abc@mail.com</td>
                            <td>Abc@mail.com</td>
                            <td><span class="badge bg-danger">New</span></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div> -->
          <!-- New Requests Card -->
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
            <div id="collapseNew" class="accordion-collapse collapse" aria-labelledby="headingNew" data-bs-parent="#dashboardAccordion">
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