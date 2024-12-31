<?php include ('includes/header.php'); ?>

<!-- modal na dodawanie wydatkow obowiazkowych -->
<div class="modal fade" id="expensemodal" tabindex="-1" role="dialog" aria-labelledby="expenseModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Dodawanie składowej konta oszczęsnościowego</h5>
                <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close" onclick="clearInputs()">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form method="POST" action="income.php">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="mandatoryAmount" class="form-label">Kwota</label>
                        <input type="number" class="form-control" id="mandatoryAmount" name="mandatoryAmount" autocomplete="off" required>
                    </div>
                    <div class="mb-3">
                        <label for="mandatoryCategory" class="form-label">Nazwa</label>
                        <input type="text" class="form-control" id="mandatoryCategory" name="mandatoryCategory" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                    <button type="submit" class="btn btn-primary" name="save_mandatory">Wprowadź</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- koniec modala -->

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">KONTO OSZCZĘDNOŚCIOWE</h1>
<p class="mb-4">Tu zobaczysz ile masz na koncie oszczędnościowym i ile z tego to faktyczne oszczędności</p>

<!-- Content Section -->
<div class="container-fluid">
    <!-- Row for Summary Cards -->
    <div class="row d-flex justify-content-center">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                ŁĄCZNA KWOTA NA KONCIE OSZCZĘDNOŚCIOWYM
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">40,000 zł</div>
                        </div>
                        <div class="col-auto">
                            <i class="fa-solid fa-vault fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                RZECZYWISTE OSZCZĘDNOŚCI
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">40,000 zł</div>
                        </div>
                        <div class="col-auto">
                            <i class="fa-solid fa-piggy-bank fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row for Details -->
    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <!-- Area Chart -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between">
                    <h5 class="m-0 font-weight-bold text-primary d-flex align-items-center">W skład Twoich oszczędności wchodzi:</h5>
                    <div class="button-container">
                        <button type="button" class="btn btn-primary" id="expenseBtn" data-bs-toggle="modal" data-bs-target="#expensemodal">Dodaj składową <i class="fa-solid fa-plus"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped text-center">
                            <thead>
                                <tr>
                                    <th scope="col">Składowe</th>
                                    <th scope="col">Kwota</th>
                                    <th scope="col">Akcja</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Mark</td>
                                    <td>Otto</td>
                                    <td>
                                        <a href="#" class="btn btn-warning btn-circle btn-sm">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </a>
                                        <a href="#" class="btn btn-danger btn-circle btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Thornton</td>
                                    <td>@fat</td>
                                    <td>
                                        <a href="#" class="btn btn-warning btn-circle btn-sm">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </a>
                                        <a href="#" class="btn btn-danger btn-circle btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>the Bird</td>
                                    <td>@twitter</td>
                                    <td>
                                        <a href="#" class="btn btn-warning btn-circle btn-sm">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </a>
                                        <a href="#" class="btn btn-danger btn-circle btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
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
                    <h6 class="m-0 font-weight-bold text-primary">Wykres konta oszczędnościowego</h6>
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

</div>

<?php include ('includes/footer.php'); ?>
