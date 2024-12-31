<?php
$total = new TotalConfig();
$total->setIdUser($_SESSION['idUser']);
$total->calculateUserTotal();
?>

<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

<!-- Sidebar Toggle (Topbar) -->
<button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
    <i class="fa fa-bars"></i>
</button>


<!-- Topbar Navbar -->
<ul class="navbar-nav ml-auto">
    <li class="nav-item mx-1 my-auto">
      
            <i class="fa-solid fa-coins"></i>
             <span class="small">Na koncie powinno być około: <?php echo $total->getUserTotal() ?> zł</span> 
        
    </li>

    <div class="topbar-divider d-none d-sm-block"></div>

    <!-- Nav Item - User Information -->
    <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION['userName']; ?></span>
            <img class="img-profile rounded-circle"
                src="assets/img/undraw_profile.svg">
        </a>
        <!-- Dropdown - User Information -->
        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
            aria-labelledby="userDropdown">
            <!-- <div class="dropdown-divider"></div> -->
            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                Wyloguj
            </a>
        </div>
    </li>

</ul>

</nav>
<!-- End of Topbar -->