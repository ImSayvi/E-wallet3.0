<?php include ('includes/header.php'); 
include_once('phplib/classes/budgetClass.php');

$budget = new BudgetConfig();
$budget->setUsers_idUser($_SESSION['idUser']);

var_dump($_SESSION['idUser']);


//insertowanie budżetu
if(isset($_POST['save_budget'])){
    $budget->setBudgetCategory($_POST['budgetName']);
    $budget->setBudgetAmount($_POST['budgetAmount']);
    $budget->setBudgetWhere($_POST['budgetWhere']);
    $budget->setBudgetAddDate(date('Y-m-d'));
    $budget->insertBudget();
}

//pobieranie budzetow i ich nazw
$allBudgets = $budget->getAllBudgets();
$budgetName = new CategoryConfig();
$budgetName->setUsers_idUser($_SESSION['idUser']);

//usuwanie budżetów i ich nazw
if(isset($_GET['req']) && $_GET['req'] == 'delete'){
    $budget->setIdBudget($_GET['idBudget']);
    $budget->deleteBudget();
}

//edycja budżetów
if (isset($_POST['edit_budget']) && isset($_GET['req']) && $_GET['req'] == 'edit') {
   
    $budget->setIdbudget($_GET['idBudget']);
    $budget->setBudgetAmount($_POST['budgetAmount']);
    $budget->setBudgetWhere($_POST['budgetWhere']);
    $budget->setBudgetCategory($_POST['budgetName']);
    // $budget->setbudgetAddDate(date('Y-m-d'));

    $budget->updatebudget();
    
    
}


?>


                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">BUDŻET</h1>
                    <p class="mb-4">Tutaj znajdują się pieniądze, które chcesz przeznaczyć na jakiś cel. Nie są obowiązkowe, nie musisz wydać całej kwoty. Budżet znajdujący się na koncie głównym resetuje się co miesiąc (z dniem wprowadzenia nowej wypłaty). Budżet na koncie oszczędnościowym zostaje uwzględniony w kwocie oszczędności, ale jest podliczany z całego okresu. Budżet przechowywany "gdzie indziej" nie jest nigdzie uwzględniony.</p>

                    <div class="container-fluid">
                        <!-- Page Heading -->
                        <div class="d-sm-flex align-items-center justify-content-between mb-4">
                            <div class="button-container">
                                <button type="button" class="btn btn-primary" id="addBudgetBtn" data-toggle="modal" data-target="#addBudgetModal">Dodaj budżet <i class="fa-solid fa-coins"></i></button>
                            </div>
                        </div>
                    
                        <div class="row">
                           
                             <?php foreach($allBudgets as $budgets): ?>
                                <div class="col-xl-3 col-md-6 mb-4">
                                    <div class="card border-left-info shadow">
                                        <div class="card-body">
                                            <div class="row no-gutters align-items-center" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseCardExample" href="#collapseBudget<?= $budgets['idBudget'] ?>">
                                                <div class="col mr-2">
                                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1"><?= $budgets['budgetCategory'] ?><span class="float-right"><?= $budgets['budgetAmount'] ?> zł</span></div>
                                                    <div class="row no-gutters align-items-center">
                                                        <div class="col-auto">
                                                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?= $budgets['budgetAmount'] + $budget->getBudgetTotal($budgets['idBudget']) ?> zł</div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="progress progress-sm mr-2">
                                                                <div class="progress-bar bg-info" role="progressbar" style="width: <?= $budget->countPercent($budgets['budgetAmount'], -$budget->getBudgetTotal($budgets['idBudget'])) ?>%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                                </div>
                                            </div>
                                            <!-- collapsable content -->
                                            <div class="collapse" id="collapseBudget<?= $budgets['idBudget'] ?>">
                                                <div class="card-body">
                                                 
                                                    <div class="button-container d-flex justify-content-center">
                                                        <!-- <button type="button" class="btn btn-primary btn-sm mb-1 mr-2" id="addBudgetBtn" data-toggle="modal" data-target="#addBudgetModal">
                                                            Edytuj <i class="fa-regular fa-pen-to-square"></i>
                                                        </button> -->
                                                        <a href="budget.php?idBudget=<?= $budgets['idBudget']?>&req=edit" 
                                                            class="btn btn-primary btn-sm mb-1 mr-2" 
                                                            data-target="#editBudget<?= $budgets['idBudget']?>"
                                                            data-toggle = "modal"
                                                            >Edytuj
                                                            <i class="fa-regular fa-pen-to-square"></i>
                                                        </a>
                                                            


                                                        <a href="budget.php?idBudget=<?= $budgets['idBudget'] ?>&req=delete" 
                                                        class="btn btn-danger btn-sm mb-1">Usuń
                                                        <i class="fas fa-trash"></i>
                                                        </a>
                                                    </div>
                                                    
                                                    
                                                    <h6>
                                                        <div class="text-center">
                                                            <span class="badge badge-secondary text-wrap"><?= $budgets['budgetWhere'] ?></span>
                                                        </div>
                                                    </h6>
                                                      
                                                    <div class="table-responsive">
                                                        <table class="table table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">Kwota</th>
                                                                    <th scope="col">Data</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php 
                                                                $budgetHistory = $budget->fetchBudgetHistory($budgets['idBudget']);
                                                                if (!empty($budgetHistory)) {
                                                                    foreach ($budgetHistory as $history): ?>
                                                                        <tr>
                                                                            <td><?= $history['BHamount'] ?> zł</td>
                                                                            <td><?= $history['BHdate'] ?></td>
                                                                        </tr>
                                                                    <?php endforeach;
                                                                } else {
                                                                    echo "<tr><td colspan='2'>Brak historii</td></tr>";
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php 
                                    $editModal = new ModalCreator();
                                    echo $editModal->createEditBudgetModal($budgets['idBudget'], $budgets['budgetCategory'], $budgets['budgetAmount'], $budgets['budgetWhere']);
                                ?>
                            <?php endforeach ?>
                        </div>
                    </div>            
                 

    <!-- dodaj budżet Modal-->
    <div class="modal fade" id="addBudgetModal" tabindex="-1" role="dialog" aria-labelledby="addBudgetModal"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Dodaj nowy budżet miesięczny</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="budget.php" method="post">
                        <div class="form-group">
                            <label for="budgetName">Na co?</label>
                            <input type="text" class="form-control" id="budgetName" name="budgetName" placeholder="Wprowadź nazwę budżetu" required autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="budgetAmount">Przeznaczona kwota</label>
                            <input type="number" class="form-control" id="budgetAmount" name="budgetAmount" placeholder="Wprowadź kwotę budżetu" required autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="btn-group">Gdzie przetrzymujesz pieniądze na ten budżet?</label>
                            
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label class="btn btn-secondary active">
                                    <input type="radio" name="budgetWhere" id="Na koncie głównym" value="Na koncie głównym" autocomplete="off" checked>Na koncie głównym
                                </label>
                                <label class="btn btn-secondary">
                                    <input type="radio" name="budgetWhere" id="Na koncie oszczędnościowym" value="Na koncie oszczędnościowym" autocomplete="off">Na koncie oszczędnościowym
                                </label>
                                <label class="btn btn-secondary">
                                    <input type="radio" name="budgetWhere" id="Gdzie indziej" value="Gdzie indziej" autocomplete="off">Gdzie indziej
                                </label>
                            </div>
                           
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Anuluj</button>
                            <button type="submit" class="btn btn-primary" name="save_budget">Wprowadź</button>
                        </div>   
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.progress-bar').forEach(function(bar) {
            let width = parseInt(bar.style.width);  
            let parentCard = bar.closest('.card');  

            
            if (width === 100) {
                bar.classList.remove('bg-success', 'bg-warning', 'bg-danger');
                bar.classList.add('bg-info');
            } else if (width >= 70) {
                bar.classList.remove('bg-info', 'bg-warning', 'bg-danger');
                bar.classList.add('bg-success');
            } else if (width >= 40) {
                bar.classList.remove('bg-info', 'bg-success', 'bg-danger');
                bar.classList.add('bg-warning');
            } else {
                bar.classList.remove('bg-info', 'bg-success', 'bg-warning');
                bar.classList.add('bg-danger');
            }

           
            if (parentCard) {
                parentCard.classList.remove('border-left-info', 'border-left-success', 'border-left-warning', 'border-left-danger');
                if (width === 100) {
                    parentCard.classList.add('border-left-info');
                } else if (width >= 70) {
                    parentCard.classList.add('border-left-success');
                } else if (width >= 40) {
                    parentCard.classList.add('border-left-warning');
                } else {
                    parentCard.classList.add('border-left-danger');
                }
            }
        });

    </script>    

 
    <?php include ('includes/footer.php'); ?>