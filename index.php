<?php include ('includes/header.php');

$categories = new CategoryConfig();
$categories->setUsers_idUser($_SESSION['idUser']);
$categories = $categories->getAllCategories();

$daily = new DailyConfig();
$daily->setUsers_idUser($_SESSION['idUser']);

$budget = new BudgetConfig();
$budget->setUsers_idUser($_SESSION['idUser']);

$budget->insertIntoBudgetHistory();




// dodawanie wydatków
if (isset($_POST['save_daily'])) {
    $daily->setDTamount($_POST['amountInput']);
    $daily->setDTdate($_POST['dailyDate']);

    $category = (isset($_POST['dailyCategory']) && $_POST['dailyCategory'] != 'other') ? $_POST['dailyCategory'] : $_POST['otherCategory']; 
    var_dump($category);
    var_dump($_POST);
    $daily->setDTname($category);

    $daily->insertDaily();

    $budget->setBudgetCategory($category);
    $budget->setBudgetAmount($_POST['amountInput']);
    
    
}

//pobranie wydatkow i wplywow
$wydatki = $daily->getAllNegativeDaily();
$wplywy = $daily->getAllPositiveDaily();

//usuwanie wydatków
if (isset($_GET['idDT'])) {
    if ($_GET['req'] === 'delete') {
        $daily->setIdDt($_GET['idDT']);
        $daily->deleteDaily();
    }
}

$dzienne = new TotalConfig();
$dzienne->setIdUser($_SESSION['idUser']);





?>

       <!-- MODALE -->
    <!-- modal na dodanie/odejmowanie hajsu z budżetu dizennego -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tytuł modala</h5>
                    <button type="button" class="close" id="btn-close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="index.php">
                    <div class="modal-body">
                        <input type="hidden" name="leftovers" value="php">
                        <div class="form-group">
                            <label for="buttonAmount">Kwota</label>
                            <input type="text" class="form-control" id="amountInput" name="amountInput" placeholder="Wprowadź kwotę" required autocomplete="off">
                        </div>
    
                        <div class="form-group">
                            <label for="category">Kategoria</label>
                            <select class="form-control" id="category" onchange="showInput(this)" name="dailyCategory" required>
                                <option selected>wybierz kategorię</option>
                                <option value="other">Inne</option>
                                <?php foreach ($categories as $category) : ?>
                                    <option value="<?=$category['categories'];?>"><?php echo $category['categories'];?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
    
                        <div class="form-group otherCategoryDiv" id="otherCategoryDiv">
                            <label for="otherCategory">Inna kategoria</label>
                            <input type="text" class="form-control" id="otherCategory" placeholder="Wprowadź nazwę kategorii" name="otherCategory">
                        </div>
    
                        <div class="form-group">
                            <label for="date">Data</label>
                            <input type="date" class="form-control date" name="dailyDate" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Zamknij</button>
                        <button type="submit" class="btn btn-primary" name="save_daily">Wprowadź</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



        



                



                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Wydatki dzienne</h1>
   
                    </div>

                    <!-- Content Row -->
                    <div class="row justify-content-center mb-3">
                        <div class="col-1 d-flex justify-content-end mb-2">
                            <button type="button" class="btn btn-danger" id="subMoney" data-toggle="modal" data-target="#exampleModal" onclick="changeModalTittle('sub')">-</button>
                        </div>
                    
                        <div class="col-5 col-lg-2 col-md-4 text-center">
                            <p class="small">masz do wydania<br></p>
                            <h1 class="font-weight-bold text-gray-900 amount"><?php echo round($dzienne->calculateDailyActuall(),2); ?> <span> zł</span></h1>
                            <div class="small">
                                <p>dzienny przyrost wydatków: <br><?php echo round($dzienne->calculateDaily(),2); ?>zł</p>
                            </div>
                        </div>
                    
                        <div class="col-1 d-flex justify-content-start mb-2">
                            <button type="button" class="btn btn-success" id="addMoney" data-toggle="modal" data-target="#exampleModal" onclick="changeModalTittle('add')">+</button>
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
                                    <h5 class="m-0 font-weight-bold text-danger">Wydatki</h5>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                                            <thead class="bg-danger text-white">
                                                <tr>
                                                    <th>Kwota</th>
                                                    <th>Na co</th>
                                                    <th>Data</th>
                                                    <th>Usuń</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="4">Łącznie: <?php echo $daily->getAllNegativeDailySum(); ?></th>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                            <?php foreach ($wydatki as $wydatek) : ?>    
                                                <tr>
                                                    <td><?=$wydatek['DTamount']?> zł</td>
                                                    <td><?=$wydatek['DTname']?></td>
                                                    <td><?=$wydatek['DTdate']?></td>
                                                    <td class="text-center">
                                                        <a href="index.php?idDT=<?=$wydatek['idDT']?>&req=delete" class="btn btn-danger btn-circle btn-sm">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
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
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h5 class="m-0 font-weight-bold text-success">Wpływy</h5>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                                            <thead class="bg-success text-white">
                                                <tr>
                                                    <th>Kwota</th>
                                                    <th>Na co</th>
                                                    <th>Data</th>
                                                    <th>Usuń</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="4"> Łącznie: <?php echo $daily->getAllPositiveDailySum(); ?></th>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                            <?php foreach ($wplywy as $wplyw) : ?>    
                                                <tr>
                                                    <td><?=$wplyw['DTamount']?> zł</td>
                                                    <td><?=$wplyw['DTname']?></td>
                                                    <td><?=$wplyw['DTdate']?></td>
                                                    <td class="text-center">
                                                        <a href="index.php?idDT=<?=$wplyw['idDT']?>&req=delete" class="btn btn-danger btn-circle btn-sm">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach ?>  
                                    
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

    
<script>
    let modalTittle = document.getElementById('exampleModalLabel');
    let modalamountInput = document.getElementById("amountInput");
    let modalSelect = document.getElementById('category');
    document.getElementById("otherCategoryDiv").style.display = "none";
    let initialMinus = false;

    function changeModalTittle(action) {
        if (action === 'add') {
            modalTittle.innerText = "Dodawanie pieniędzy do budżetu dziennego";
            modalamountInput.value = "";
            initialMinus = false;
            modalSelect.value = 'other';
            modalSelect.setAttribute("disabled", true);
            document.getElementById("otherCategoryDiv").style.display = "block";
        }
        if (action === 'sub') {
            modalTittle.innerText = "Odejmowanie pieniędzy z budżetu dziennego";
            modalamountInput.value = "-";
            initialMinus = true;
            modalSelect.removeAttribute("disabled");
        }
    }

    modalamountInput.addEventListener('keydown', function (event) {
        if (initialMinus && (event.key === 'Backspace' || event.key === 'Delete')) {
            if (modalamountInput.value === '-') {
                event.preventDefault();
            }
        }
    });

    function setTodayDate() {
    let today = new Date().toISOString().split('T')[0];
    return today;
    };
    window.addEventListener('load', function() {
        document.querySelector(".date").value = setTodayDate();
    });

function showInput(answer){
  if (answer.value == "other"){
    document.getElementById("otherCategoryDiv").style.display = "block";
  } else{
    document.getElementById("otherCategoryDiv").style.display = "none";
  }
}
</script>

<?php include ('includes/footer.php'); ?>
        