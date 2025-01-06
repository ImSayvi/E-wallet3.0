<?php
include_once('phplib/classes/chargeClass.php');

$charge = new ChargeConfig();
$charge->setUsers_idUser($_SESSION['idUser']);
$allChargesBydate = $charge->fetchAllChargesByDate();

$budget = new BudgetConfig();
$budget->setUsers_idUser($_SESSION['idUser']);
$fetchBudgets = $budget->getAllBudgets();


?>
<style>
.text-decoration-line-through {
  text-decoration: line-through;
}
</style>    

<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

<!-- Sidebar - Brand -->
<a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
    <div class="sidebar-brand-icon">
        <i class="fa-solid fa-wallet"></i>
    </div>
    <div class="sidebar-brand-text mx-3">E-Wallet <sup>3.0</sup></div>
</a>

<!-- Divider -->
<hr class="sidebar-divider my-0">

<!-- Nav Item - Dashboard -->
<li class="nav-item">
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
        <?php foreach($allChargesBydate as $charges): ?>
            <a class="collapse-item chargeToPay" href="charges.php"><?=$charges['chargeCategory'] ?> <div class="float-right paid font-weight-bold"><?=$charge->fetchSum($charges['idCharge'])?></div><span class="d-none total"><?=$charges['chargeAmount'] ?></span></a>
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
    <div id="collapseLeftBudget" class="collapse show" aria-labelledby="headingLeftBudget"
        data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">

        <?php foreach($fetchBudgets as $budgets): ?>    
            <a class="collapse-item budget" href="budget.php"><?=$budgets['budgetCategory'] ?><div class="float-right font-weight-bold availableBudget"><?= $budgets['budgetAmount'] + $budget->getBudgetTotal($budgets['idBudget']) ?></div><span class="d-none totalBudget"><?=$budgets['budgetAmount'] ?></span></a>
        <?php endforeach; ?>      

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

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const currentPage = window.location.pathname.split("/").pop();

        const navLinks = document.querySelectorAll(".nav-item a.nav-link");

        navLinks.forEach(link => {

            const linkPage = link.getAttribute("href");

            if (linkPage === currentPage) {
                link.parentElement.classList.add("active");
            } else {
                link.parentElement.classList.remove("active");
            }
        });
    });

    document.addEventListener("DOMContentLoaded", function () {
    const elements = document.querySelectorAll(".chargeToPay");
    
        elements.forEach(element => {
            const paidElement = element.querySelector(".paid");
            const totalElement = element.querySelector(".total");

            if (paidElement && totalElement) {
                const paidValue = parseFloat(paidElement.textContent);
                const totalValue = parseFloat(totalElement.textContent);

                if (paidValue < totalValue) {
                    paidElement.classList.add("text-danger");
                } else if (paidValue >= totalValue) {
                    paidElement.classList.add("text-success");
                    element.classList.add("text-decoration-line-through");
                    paidElement.classList.add("text-decoration-line-through");
                    console.log(element);
                }
            }
        });
    });

    document.addEventListener("DOMContentLoaded", function (){
        const elements = document.querySelectorAll(".budget");
 
            elements.forEach(element => {
                const availableBudget = document.querySelector(".availableBudget");
                const totalBudget = document.querySelector(".totalBudget");

                if(availableBudget && totalBudget){
                    const availableBudgetValue = parseFloat(availableBudget.textContent);
                    const totalBudget = parseFloat(totalBudget.textContent);
                }
            })
    })

</script> 