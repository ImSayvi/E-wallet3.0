<?php

class ModalCreator {

    public function createEditChargesModal($modalId, $chargeAmount, $chargeCategory, $chargeExpiry,) {

        $chargeAmount = htmlspecialchars($chargeAmount, ENT_QUOTES, 'UTF-8');
        $chargeCategory = htmlspecialchars($chargeCategory, ENT_QUOTES, 'UTF-8');
        $chargeExpiryChecked = $chargeExpiry == '0000-00-00' ? '' : 'checked';
    
        $html = '
        <div class="modal fade" id="editCharge'.$modalId.'" tabindex="-1" role="dialog" aria-labelledby="editCharge'.$modalId.'" aria-hidden="true">
            <div class="modal-dialog" role="document">  
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edycja opłaty</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" action="charges.php?idCharge='.$modalId.'&req=edit">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="chargesAmount" class="form-label float-left">Kwota</label>
                                <input type="number" class="form-control" name="chargesAmount" value="'.$chargeAmount.'" autocomplete="off" required>
                            </div>
                            <div class="mb-3">
                                <label for="chargesCategory" class="form-label float-left">Na co</label>
                                <input type="text" class="form-control" name="chargesCategory" value="'.$chargeCategory.'" autocomplete="off" required>
                            </div>
                            <div class="form-check mb-3 float-left">
                                <input class="form-check-input" type="checkbox" value="1" name="chargesExpiry" '.$chargeExpiryChecked.'>
                                <label class="form-check-label" for="chargesExpiry">
                                    Wydatek tylko na ten miesiąc
                                </label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Anuluj</button>
                            <button type="submit" class="btn btn-primary" name="edit_charge">Wprowadź</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>';
    
        return $html;
    }

    public function createEditBudgetModal($modalId, $budgetName, $budgetAmount, $budgetWhere) {
        // Zmienna $budgetWhere nie musi zawierać " checked" w tej metodzie
        $html = '
                <div class="modal fade" id="editBudget'.$modalId.'" tabindex="-1" role="dialog" aria-labelledby="editBudget'.$modalId.'"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Edytuj budżet</h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="budget.php?idBudget='.$modalId.'&req=edit" method="post">
                                    <div class="form-group">
                                        <label for="budgetName">Na co?</label>
                                        <input type="text" class="form-control" id="budgetName" name="budgetName" placeholder="Wprowadź nazwę budżetu" value="'.$budgetName.'" required autocomplete="off">
                                    </div>
                                    <div class="form-group">
                                        <label for="budgetAmount">Przeznaczona kwota</label>
                                        <input type="number" class="form-control" id="budgetAmount" name="budgetAmount" placeholder="Wprowadź kwotę budżetu" value="'.$budgetAmount.'" required autocomplete="off">
                                    </div>
                                    <div class="form-group">
                                        <label for="btn-group">Gdzie przetrzymujesz pieniądze na ten budżet?</label>
                                        
                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                            <label class="btn btn-secondary ' . ($budgetWhere == 'Na koncie głównym' ? 'active' : '') . '">
                                                <input type="radio" name="budgetWhere" value="Na koncie głównym" ' . ($budgetWhere == 'Na koncie głównym' ? 'checked' : '') . '>Na koncie głównym
                                            </label>
                                            <label class="btn btn-secondary ' . ($budgetWhere == 'Na koncie oszczędnościowym' ? 'active' : '') . '">
                                                <input type="radio" name="budgetWhere" value="Na koncie oszczędnościowym" ' . ($budgetWhere == 'Na koncie oszczędnościowym' ? 'checked' : '') . '>Na koncie oszczędnościowym
                                            </label>
                                            <label class="btn btn-secondary ' . ($budgetWhere == 'Gdzie indziej' ? 'active' : '') . '">
                                                <input type="radio" name="budgetWhere" value="Gdzie indziej" ' . ($budgetWhere == 'Gdzie indziej' ? 'checked' : '') . '>Gdzie indziej
                                            </label>
                                        </div>
                                    
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Anuluj</button>
                                        <button type="submit" class="btn btn-primary" name="edit_budget">Wprowadź</button>
                                    </div>   
                                </form>
                            </div>
                        </div>
                    </div>
                </div>';
        
        return $html;
    }
    
    
}
?>
