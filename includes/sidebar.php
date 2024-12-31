<?php
include_once('phplib/classes/categoryClass.php');
include_once('phplib/classes/chargeClass.php');

$charge = new ChargeConfig();
$charge->setUsers_idUser($_SESSION['idUser']);
$allChargesBydate = $charge->fetchAllChargesByDate();
$chargeName = new CategoryConfig();
$chargeName->setUsers_idUser($_SESSION['idUser']);



?>

<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

<!-- Sidebar - Brand -->
<a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
    <div class="sidebar-brand-icon">
        <i class="fa-solid fa-wallet"></i>
    </div>
    <div class="sidebar-brand-text mx-3">E-Wallet <sup>3.0</sup></div>
</a>

<!-- Divider -->
<hr class="sidebar-divider my-0">

<!-- Nav Item - Dashboard -->
<li class="nav-item active">
    <a class="nav-link" href="index.php">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>Panel główny</span></a>
</li>

<!-- Divider -->
<hr class="sidebar-divider">

<!-- Heading -->
<div class="sidebar-heading">
    Zarządzaj
</div>

<li class="nav-item">
    <a class="nav-link" href="income.php">
        <i class="fa-solid fa-sack-dollar"></i>
        <span>Wypłata</span></a>
</li>

<li class="nav-item">
    <a class="nav-link" href="charges.php">
        <i class="fa-solid fa-hand-holding-dollar"></i>
        <span>Opłaty</span></a>
</li>

<li class="nav-item">
    <a class="nav-link" href="budget.php">
        <i class="fa-solid fa-money-bill-1-wave"></i>
        <span>Budżet</span></a>
</li>


<li class="nav-item">
    <a class="nav-link" href="savings.php">
        <i class="fa-solid fa-vault"></i>
        <span>Oszczędności</span></a>
</li>

<!-- Divider -->
<hr class="sidebar-divider">

<!-- Heading -->
<div class="sidebar-heading">
    Do opłacenia
</div>

<!-- Nav Item - Charts -->
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseToPayCharges"
        aria-expanded="true" aria-controls="collapseUtilities">
        <span>Opłaty</span>
    </a>
    <div id="collapseToPayCharges" class="collapse show" aria-labelledby="headingUtilities"
        data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
        <?php foreach($allChargesBydate as $charge): ?>
            <a class="collapse-item" href="charges.php"><?=$chargeName->getCategoryName($charge['idCategory']) ?> <span class="text-danger float-right"><?=$charge['chargeAmount']?></span></a>
        <?php endforeach; ?>    
        </div>
    </div>
</li>

<!-- Nav Item - Tables -->
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLeftBudget"
        aria-expanded="true" aria-controls="collapseUtilities">
        <span>Budżet</span>
    </a>
    <div id="collapseLeftBudget" class="collapse" aria-labelledby="headingLeftBudget"
        data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="budget.php">Colors</a>
            <a class="collapse-item" href="budget.php">Borders</a>
            <a class="collapse-item" href="budget.php">Animations</a>
            <a class="collapse-item" href="budget.php">Other</a>
        </div>
    </div>
</li>

<!-- Divider -->
<hr class="sidebar-divider d-none d-md-block">

<!-- Sidebar Toggler (Sidebar) -->
<div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
</div>

</ul>
<!-- End of Sidebar -->