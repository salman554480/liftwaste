 <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom px-4 py-3 flex-column flex-md-row">
        <div class="d-flex w-100 align-items-center justify-content-between">
          <button class="btn btn-outline-primary d-lg-none me-2" id="menu-toggle"><i class="fas fa-bars"></i></button>
          <form class="d-none d-md-flex flex-grow-1 me-2 search-form-custom">
            <input class="form-control" type="search" placeholder="Search" aria-label="Search">
          </form>
          <ul class="navbar-nav ms-auto align-items-center flex-row">
            <li class="nav-item me-3 position-relative" id="notification-nav-item">
              <a class="nav-link position-relative" href="#" id="notification-bell"><i class="fas fa-bell"></i><span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">1</span></a>
              <div class="notification-dropdown shadow" id="notification-dropdown" style="display: none;">
                <div class="notification-header fw-bold px-3 py-2 border-bottom">Notifications</div>
                <div class="notification-list">
                  <div class="notification-item px-3 py-2 border-bottom">
                    <div class="small text-muted">2 mins ago</div>
                    <div>New request received from <b>John</b></div>
                  </div>
                  <div class="notification-item px-3 py-2 border-bottom">
                    <div class="small text-muted">10 mins ago</div>
                    <div>Task <b>Assigned</b> to you</div>
                  </div>
                  <div class="notification-item px-3 py-2">
                    <div class="small text-muted">1 hour ago</div>
                    <div>Request <b>Completed</b> by Mike</div>
                  </div>
                </div>
                <div class="notification-footer text-center py-2">
                  <a href="#" class="small">View all</a>
                </div>
              </div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link p-0" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <!-- Changed profile image -->
                <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Profile" class="rounded-circle" width="40" height="40">
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                <li><a class="dropdown-item" href="#">Settings</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
              </ul>
            </li>
          </ul>
        </div>
      </nav>