<?php 
include ('includes/header.php');
require_once('phplib/classes/incomeClass.php');

$income = new IncomeConfig();
$income->setUsers_idUser($_SESSION['idUser']);

// inserting
if (isset($_POST['submitIncome'])) {
    var_dump($_POST); 
    var_dump($_SESSION['idUser']);
    

    $income->setIncomeAmount($_POST['incomeAmount']);
    $income->setIncomeName($_POST['incomeName']);
    $income ->setIncomeDate($_POST['incomeDate']);
    $income->insertIncome();
}

// fetching
$allIncomes = $income->fetchAllIncome();
$lastIncome = $income->fetchLastIncome();


// deleting
if (isset($_GET['idIncome']) && isset($_GET['req'])){
    if ($_GET['req'] == 'delete'){
        $income->setIdIncome($_GET['idIncome']);
        $income->deleteIncome();
    }
}

// wyjasnienie edycji https://youtu.be/2sMZlyt3lcY?t=3660

//ustawienie total
$total = new TotalConfig();
$total->setIdUser($_SESSION['idUser']);
$total->calculateUserTotal();


?>

                    <!-- Page Heading -->
                    <div >
                        <h1 class="h3 mb-0 text-gray-800">Wypłata/wpływy</h1>
                        <p class="mb-4">Tutaj możesz dodać wypłatę lub większe wpływy. Różnica pomiędzy dodaniem ich
                            tutaj, a w wydatkach dziennych polega na tym, że wpisana tu kwota zostanie rozłożona na
                            miesiąc powiększając wpływy dzienne i bierze udział w kwocie do rozdysponownaia na budżety i
                            opłaty. Wpływy wpisane w wydatki dzienne stanowią zatrzyk gotówki widoczny do wydania
                            natychmiast. <b>Co istotne: data wypłaty stanowi punkt odniesienia dla wszystkich pozostałych funkcji strony</b></p>
                        <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
                    </div>

                    <!-- Content Row -->
                    <div class="row d-flex justify-content-center">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                OSTATNIA WYPŁATA</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $lastIncome[0]["incomeAmount"] ?? 0 ?> zł</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Content Row -->

                    <div class="row">

                        <!-- Area Chart -->
                        <div class="col col-lg-6">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Wypłata/wpływy</h6>
                                    <div class="button-container">
                                        <button type="button" class="btn btn-primary" id="incomeBtn" data-toggle="modal" data-target="#incomemodal">Dodaj wypłatę/ wpływ <i class="fa-solid fa-plus"></i></button>
                                    </div>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                                            <thead class="bg-primary text-white">
                                                <tr>
                                                    <th>Kwota</th>
                                                    <th>Skąd</th>
                                                    <th>Data</th>
                                                    <th>Usuń</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            <?php foreach($allIncomes as $singleIncome => $row): ?>
                                                <tr>
                                                    <td><?=$row['incomeAmount'] ?> zł</td>
                                                    <td><?=$row['incomeName'] ?></td>
                                                    <td><?=$row['incomeDate'] ?></td>
                                                    <td class="text-center">
                                                        <a href="income.php?idIncome=<?=$row['idIncome']?>&req=delete" class="btn btn-danger btn-circle btn-sm">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                        <!-- <a href="#" class="btn btn-danger btn-circle btn-sm">
                                                            edytuj
                                                        </a> -->
                                                    </td>
                                                </tr>
                                            <?php endforeach ?>        

                    
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Pie Chart -->
                        <div class="col col-lg-6">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Wykres wypłat</h6>
                                </div>
                                <div class="card-body">
                                    <div class="chart-bar">
                                        <canvas id="myBarChart"></canvas>
                                    </div>
                                    <hr>
                                    Wykres aktualizuje się z każdą wprowadzoną wypłatą.
                                </div>
                            </div>
                        </div>
                    </div>
                                                                   




<?php include ('includes/footer.php'); ?>

        <!-- Logout Modal-->
        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
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

        <!-- modal na dodanie wypłaty -->
        <div class="modal fade" id="incomemodal" tabindex="-1" role="dialog" aria-labelledby="incomeModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Dodawanie wypłaty</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close" onclick="clearInputs()">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form method="POST" action="income.php">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="incomeAmount" class="form-label">Kwota</label>
                                <input type="number" class="form-control" id="incomeAmount" name="incomeAmount" autocomplete="off" require>
                            </div>
                            <div class="mb-3">
                                <label for="incomeName" class="form-label">Tytuł</label>
                                <input type="text" class="form-control" id="incomeName" name="incomeName" require>
                            </div>
                            <div class="form-group">
                                <label for="incomeDate">Data</label>
                                <input type="date" class="form-control date" name="incomeDate" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Anuluj</button>
                            <button type="submit" class="btn btn-primary" name="submitIncome">Wprowadź</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>





        <script>
            function setTodayDate() {
                let today = new Date().toISOString().split('T')[0];
                return today;
            };
            window.addEventListener('load', function() {
                document.querySelector(".date").value = setTodayDate();
            });


            
        </script>

<script>
    // Przygotowanie danych do wykresu słupkowego
    var incomeData = <?php echo json_encode($allIncomes); ?>;
    var labels = incomeData.map(function(item) {
        return item.incomeName; // Nazwy źródeł jako etykiety
    });
    var data = incomeData.map(function(item) {
        return item.incomeAmount; // Kwoty jako dane
    });

    // Konfiguracja wykresu słupkowego w Chart.js
    var ctx = document.getElementById('myBarChart').getContext('2d');
    var myBarChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels, // Używamy nazw źródeł jako etykiety
            datasets: [{
                label: 'Wypłata/ wpływy',
                data: data, // Używamy kwot jako danych
                backgroundColor: 'rgba(0, 123, 255, 0.5)', // Kolor słupków
                borderColor: 'rgba(0, 123, 255, 1)', // Kolor krawędzi słupków
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false // Ukrywamy legendę
                },
            },
            scales: {
                y: {
                    beginAtZero: true // Skala osi Y zaczyna się od zera
                }
            }
        }
    });
</script>