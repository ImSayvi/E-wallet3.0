<?php include ('includes/header.php');


$charge = new ChargeConfig();
$charge->setUsers_idUser($_SESSION['idUser']);

$total = new TotalConfig();
$total->setIdUser($_SESSION['idUser']);

$income = new IncomeConfig();
$income->setUsers_idUser($_SESSION['idUser']);
$lastIncome = $income->fetchLastIncome();



// inserting charge
if(isset($_POST['save_charge'])){
    $charge->setChargeAmount($_POST['chargesAmount']);
    $charge->setChargeCategory($_POST['chargesCategory']);
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

//deleting charge 
if (isset($_GET['idCharge']) && isset($_GET['req'])){
    if ($_GET['req'] == 'delete'){
        $charge->setIdCharge($_GET['idCharge']);
        $charge->deleteCharge();

    }
}

//edycja

if (isset($_POST['edit_charge']) && isset($_GET['req']) && $_GET['req'] == 'edit') {
   
        $charge->setIdCharge($_GET['idCharge']);
        $charge->setChargeAmount($_POST['chargesAmount']);
        if(isset($_POST['chargesExpiry']) && $_POST['chargesExpiry'] == 1){
            $charge->setChargeExpiryDate(date('Y-m-d', strtotime("+1 month", strtotime($charge->getChargeAddDate()))));
        }else{
            $charge->setChargeExpiryDate('0000-00-00');
        }
        $charge->setChargeCategory($_POST['chargesCategory']);
        $charge->setChargeAddDate(date('Y-m-d'));

        $charge->updateCharge();
}

//data for charts:
$budgetSum = $total->totalBudget();
$lastIncNum = $lastIncome[0]['incomeAmount'];



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

                            <?php foreach ($allChargesBydate as $charges): ?>
                                <tr>
                                    <td><?= $charges['chargeCategory'] ?></td>
                                    <td class="charge-amount"><?= $charges['chargeAmount'] ?></td>
                                    <td><?= $charge->fetchSum($charges['idCharge']) ?></td>
                                    <td><?= $charge->fetchDate($charges['idCharge']) ?></td>
                                    <td class="text-center">
                                        <a href="charges.php?idCharge=<?= $charges['idCharge']?>&req=edit" 
                                           class="btn btn-warning btn-circle btn-sm edit-charge-btn" 
                                           data-target="#editCharge<?= $charges['idCharge']?>"
                                           data-toggle="modal">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </a>
                                        <a href="charges.php?idCharge=<?= $charges['idCharge'] ?>&req=delete" 
                                           class="btn btn-danger btn-circle btn-sm">
                                           <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php 
                                    $editModal = new ModalCreator();
                                    echo $editModal->createEditChargesModal($charges['idCharge'], $charges['chargeAmount'], $charges['chargeCategory'], $charges['chargeExpiryDate']);
                                ?>
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
                    <h6 class="m-0 font-weight-bold text-primary">Wykres</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie d-flex justify-content-center h-100">
                        <canvas id="myPieChart"></canvas> 
                    </div>
                    <hr>
                    "Budżety" ujmuje jedynie te, które są oznaczone jako "przechowywane na koncie głównym".
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Przygotowanie danych do wykresu donut
    var chargeCategories = [];
    var chargeAmounts = [];
    let lastInc = <?= $lastIncNum ?>;
    let budgetSum = <?= $budgetSum ?>;
    let incLeft = lastInc - budgetSum - <?= $charge->countSummaryByDate(date('Y-m-d')) ?>;

    // Pobranie danych z tabeli HTML (zmieniony selektor na #dataTable tbody)
    document.querySelectorAll('#dataTable tbody tr').forEach(function(row) {
        var category = row.cells[0].innerText.trim();  // Kategoria (Na co)
        var amount = parseFloat(row.cells[1].innerText.trim());  // Przeznaczone (Kwota)

        chargeCategories.push(category);
        chargeAmounts.push(amount);
    });

    chargeCategories.push('dla mnie');
    chargeCategories.push('budżety');

    chargeAmounts.push(incLeft);
    chargeAmounts.push(budgetSum);

    var ctx = document.getElementById('myPieChart').getContext('2d');
    var myDonutChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: chargeCategories,  // Etykiety (Kategorie)
        datasets: [{
            label: 'Przeznaczone na opłaty',
            data: chargeAmounts,  // Dane (Kwoty)
            backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'],  // Kolory segmentów wykresu
            hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf', '#f1b91e', '#d13c2f'], // Kolory przy najechaniu
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: true, // Wyświetlanie legendy
                position: 'right', 
            },
        },
        cutout: '50%', 
    }
});


</script>



<?php include ('includes/footer.php'); ?>
