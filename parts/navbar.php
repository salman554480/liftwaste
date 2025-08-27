 <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom px-4 py-3 flex-column flex-md-row">
        <div class="d-flex w-100 align-items-center justify-content-between">
          <button class="btn btn-outline-primary d-lg-none me-2" id="menu-toggle"><i class="fas fa-bars"></i></button>
          <form class="d-none d-md-flex flex-grow-1 me-2 search-form-custom">
            <input class="form-control" type="search" placeholder="Search" aria-label="Search">
          </form>
          <ul class="navbar-nav ms-auto align-items-center flex-row">
            <li class="nav-item me-3 position-relative" id="notification-nav-item">
              <a class="nav-link position-relative" href="#" id="notification-bell"><i class="fas fa-bell"></i><span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display: none;">0</span></a>
              <div class="notification-dropdown shadow" id="notification-dropdown" style="display: none;">
                <div class="notification-header fw-bold px-3 py-2 border-bottom">Notifications</div>
                <div class="notification-list" id="notification-list">
                  <div class="notification-item px-3 py-2 text-center text-muted">
                    <div>Loading notifications...</div>
                  </div>
                </div>
               
              </div>
            </li>
           
          </ul>
        </div>
      </nav>