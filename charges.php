<?php include ('includes/header.php');


$charge = new ChargeConfig();
$charge->setUsers_idUser($_SESSION['idUser']);

// inserting charge
if(isset($_POST['save_charge'])){
    $charge->setChargeAmount($_POST['chargesAmount']);
    $charge->setChargeName($_POST['chargesCategory']);
    $charge->setChargeAddDate(date('Y-m-d'));
    if(isset($_POST['chargesExpiry']) && $_POST['chargesExpiry'] == 1){
        $charge->setChargeExpiryDate(date('Y-m-d', strtotime("+1 month", strtotime($charge->getChargeAddDate()))));
    }else{
        $charge->setChargeExpiryDate('0000-00-00');
    }
    $charge->insertCharge();
}

// fetching charges
$allChargesBydate = $charge->fetchAllChargesByDate();
$chargeName = new CategoryConfig();
$chargeName->setUsers_idUser($_SESSION['idUser']);

//deleting charge and its category
if (isset($_GET['idCharge']) && isset($_GET['req'])){
    if ($_GET['req'] == 'delete'){
        $charge->setIdCharge($_GET['idCharge']);
        $charge->setIdChargeCategory($_GET['idCategory']);
        $charge->deleteCharge();

    }
}

//edycja

if (isset($_POST['edit_charge']) && isset($_GET['req']) && $_GET['req'] == 'edit') {
   
        $charge->setIdCharge($_GET['idCharge']);
        $charge->setIdChargeCategory($_GET['idCategory']);
        $charge->setChargeAmount($_POST['chargesAmount']);
        if(isset($_POST['chargesExpiry']) && $_POST['chargesExpiry'] == 1){
            $charge->setChargeExpiryDate(date('Y-m-d', strtotime("+1 month", strtotime($charge->getChargeAddDate()))));
        }else{
            $charge->setChargeExpiryDate('0000-00-00');
        }
        $charge->setChargeName($_POST['chargesCategory']);
        $charge->setChargeAddDate(date('Y-m-d'));

        $charge->updateCharge();
        
        
}



?>



<!-- modal na dodawanie wydatków obowiązkowych -->
<div class="modal fade" id="expensemodal" tabindex="-1" role="dialog" aria-labelledby="expenseModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="expenseModalLabel">Dodawanie opłaty</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="charges.php">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="chargesAmount" class="form-label">Kwota</label>
                        <input type="number" class="form-control" id="chargesAmount" name="chargesAmount" autocomplete="off" required>
                    </div>
                    <div class="mb-3">
                        <label for="chargesCategory" class="form-label">Na co</label>
                        <input type="text" class="form-control" id="chargesCategory" name="chargesCategory" autocomplete="off" required>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" name="chargesExpiry" id="chargesExpiry">
                        <label class="form-check-label" for="chargesExpiry">
                            Wydatek tylko na ten miesiąc
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Anuluj</button>
                    <button type="submit" class="btn btn-primary" name="save_charge">Wprowadź</button>

                </div>
            </form>
        </div>
    </div>
</div>

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">OPŁATY</h1>
<p class="mb-4">Tutaj możesz dodać opłaty. Wydatki, których <b>nie wolno</b> pominąć, jak np. opłaty za mieszkanie, opłata za studia. Są one natychmiast odejmowane z wypłaty, więc nie afektują na budżet dzienny. Zwykle są to powtarzalne opłaty i występują co miesiąc, ale zdarzają się takie, których nie jesteś w stanie przewidzieć, ale pojawiają się akurat w danym miesiącu (jak np. naprawa auta). Zaznacz wtedy, że jest to wydatek tylko na ten miesiąc, dzięki czemu przy następnym miesiącu, zostanie on usunięty.</p>

<!-- Main Content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <!-- Area Chart -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <div class="button-container">
                        <button type="button" class="btn btn-primary" id="expenseBtn" data-toggle="modal" data-target="#expensemodal">Dodaj opłatę <i class="fa-solid fa-coins"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive table-striped">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <tfoot>
                                <tr>
                                    <th colspan="2">Łącznie: <?php echo $charge->countSummaryByDate(date('Y-m-d')) ?></th>
                                </tr>
                            </tfoot>
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>Na co</th>
                                    <th>Przeznaczone</th>
                                    <th>Wpłacone</th>
                                    <th>Data ostatniej wpłaty</th>
                                    <th>Akcja</th>
                                </tr>
                            </thead>
                            <tbody>

                            <?php foreach ($allChargesBydate as $charge): ?>
                                <tr>
                                    <td><?= $chargeName->getCategoryName($charge['idCategory']) ?></td>
                                    <td><?= $charge['chargeAmount'] ?></td>
                                    <td>100</td>
                                    <td>20.11.2024</td>
                                    <td class="text-center">
                                    <a href="charges.php?idCharge=<?= $charge['idCharge']?>&idCategory=<?=$charge['idCategory'] ?>&req=edit" 
                                        class="btn btn-warning btn-circle btn-sm edit-charge-btn" 
                                        data-target="#editCharge<?= $charge['idCharge']?>"
                                        data-toggle = "modal"
                                        >
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </a>
                                           <?php 
                                           $editModal = new ModalCreator();
                                           echo $editModal->createEditChargesModal($charge['idCharge'], $charge['chargeAmount'], $chargeName->getCategoryName($charge['idCategory']), $charge['chargeExpiryDate'], $charge['idCategory']);
                                           ?>
                                        
                                        <a href="charges.php?idCharge=<?= $charge['idCharge'] ?>&idCategory=<?= $charge['idCategory'] ?>&req=delete" 
                                           class="btn btn-danger btn-circle btn-sm">
                                           <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>   

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Donut Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Donut Chart</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4">
                        <canvas id="myPieChart"></canvas>
                    </div>
                    <hr>
                    Styling for the donut chart can be found in the
                    <code>/js/demo/chart-pie-demo.js</code> file.
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <!-- Bar Chart -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Bar Chart</h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar">
                        <canvas id="myBarChart"></canvas>
                    </div>
                    <hr>
                    Styling for the bar chart can be found in the
                    <code>/js/demo/chart-bar-demo.js</code> file.
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Na pewno chcesz się wylogować?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Wybierz "wyloguj" aby zakończyć sesję.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Zamknij</button>
                <a class="btn btn-primary" href="login.html">Wyloguj</a>
            </div>
        </div>
    </div>
</div>


<?php include ('includes/footer.php'); ?>
