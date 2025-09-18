 <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom px-4 py-3 flex-column flex-md-row">
   <div class="d-flex w-100 align-items-center justify-content-between">
     <button class="btn btn-outline-primary d-lg-none me-2" id="menu-toggle"><i class="fas fa-bars"></i></button>
     <h5 class="text-primary">Welcome <?php echo $admin_name; ?></h5>
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

       <li class="nav-item dropdown">
         <a class="nav-link p-0" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown"
           aria-expanded="false">
           <img src="assets/img/favicon.png"
             alt="Profile" class="rounded-circle"
             width="40" height="40" style="object-fit: cover;">
         </a>
         <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
           <li class="dropdown-header">
             <div class="d-flex align-items-center">
               <img src="assets/img/favicon.png"
                 alt="Profile" class="rounded-circle me-2"
                 width="32" height="32" style="object-fit: cover;">
               <div>
                 <div class="fw-bold"><?php echo htmlspecialchars($admin_name); ?></div>
                 <div class="small text-muted"><?php echo ucfirst($admin_role); ?></div>
               </div>
             </div>
           </li>
           <li>
             <hr class="dropdown-divider">
           </li>
           <li><a class="dropdown-item" href="profile.php"><i class="bi bi-person me-2"></i>Profile</a></li>
           <li>
             <hr class="dropdown-divider">
           </li>
           <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
         </ul>
       </li>

     </ul>
   </div>
 </nav>