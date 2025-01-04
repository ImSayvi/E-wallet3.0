<?php
require_once(__DIR__ . '/../dbConn.php');
include_once('categoryClass.php');

class BudgetConfig{
    private $idBudget;
    private $Users_idUser;
    private $budgetAddDate;
    private $budgetAmount;
    private $budgetWhere;
    private $budgetCategory;

    protected $conn;

    public function __construct($idBudget=0, $Users_idUser=0, $budgetAddDate='', $budgetAmount=0, $budgetWhere='', $budgetCategory=''){
        $this->idBudget = $idBudget;
        $this->Users_idUser = $Users_idUser;
        $this->budgetAddDate = $budgetAddDate;
        $this->budgetAmount = $budgetAmount;
        $this->budgetWhere = $budgetWhere;
        $this->budgetCategory = $budgetCategory;

        $this->conn = new PDO(DB_TYPE.':host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PWD, [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
    }

    public function setIdBudget($idBudget){
        $this->idBudget = $idBudget;
    }

    public function getIdBudget(){
        return $this->idBudget;
    }

    public function setUsers_idUser($Users_idUser){
        $this->Users_idUser = $Users_idUser;
    }

    public function getUsers_idUser(){
        return $this->Users_idUser;
    }

    public function setBudgetAddDate($budgetAddDate){
        $this->budgetAddDate = $budgetAddDate;
    }

    public function getBudgetAddDate(){
        return $this->budgetAddDate;
    }

    public function setBudgetAmount($budgetAmount){
        $this->budgetAmount = $budgetAmount;
    }

    public function getBudgetAmount(){
        return $this->budgetAmount;
    }

    public function setBudgetWhere($budgetWhere){
        $this->budgetWhere = $budgetWhere;
    }

    public function getBudgetWhere(){
        return $this->budgetWhere;
    }

    public function setBudgetCategory($budgetCategory){
        $this->budgetCategory = $budgetCategory;
    }

    public function getBudgetCategory(){
        return $this->budgetCategory;
    }


    public function insertBudget(){
        try{
            $stm = $this->conn->prepare("INSERT INTO budget (Users_idUser, budgetAddDate, budgetAmount, budgetWhere, budgetCategory) VALUES (?, ?, ?, ?, ?)");
            $stm->execute([$this->Users_idUser, $this->budgetAddDate, $this->budgetAmount, $this->budgetWhere, $this->budgetCategory]); 
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    public function getAllBudgets(){
        $stm = $this->conn->prepare("SELECT * FROM budget WHERE Users_idUser = ?");
        $stm->execute([$this->Users_idUser]);
        $result = $stm->fetchAll();

        return $result;
    }

    public function deleteBudget(){
        try{
            $stm = $this->conn->prepare('DELETE FROM budget WHERE idBudget = ?');
            $stm->execute([$this->idBudget]);
        }    
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    public function updateBudget(){
        try{
            $stm = $this->conn->prepare("UPDATE budget SET budgetAmount = ?, budgetWhere = ?, budgetCategory = ? WHERE idBudget = ?");
            $stm->execute([$this->budgetAmount, $this->budgetWhere, $this->budgetCategory, $this->idBudget]);

        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    public function putIntoBudgetHistory() {
       
    }

    public function getBudgetHistory(){
        $stm = $this->conn->prepare("SELECT * FROM budgethistory WHERE Users_idUser = ? AND idBudget = ?");
        $stm->execute([$this->Users_idUser, $this->idBudget]);
        $result = $stm->fetchAll();

        return $result;
    }

    public function insertIntoBudgetHistory() {
        try {
            $stm = $this->conn->prepare("SELECT idBudget, budgetCategory FROM budget WHERE Users_idUser = ?");
            $stm->execute([$this->Users_idUser]);
            $categories = $stm->fetchAll();
    
            $stm = $this->conn->prepare("SELECT * FROM dailytransactions WHERE Users_idUser = ?");
            $stm->execute([$this->Users_idUser]);
            $dailyOutcome = $stm->fetchAll();
    
            $inserted = false;
    
            foreach ($dailyOutcome as $value) {
                foreach ($categories as $category) {
                    if ($value['DTname'] == $category['budgetCategory']) {
                        // Sprawdź, czy rekord już istnieje
                        $stm = $this->conn->prepare("
                            SELECT COUNT(*) FROM budgethistory 
                            WHERE Users_idUser = ? AND Budget_idBudget = ? AND idDT = ?
                        ");
                        $stm->execute([
                            $this->Users_idUser,
                            $category['idBudget'],
                            $value['idDT']
                        ]);
    
                        $exists = $stm->fetchColumn();
    
                        if (!$exists) {
                            $stm = $this->conn->prepare("
                                INSERT INTO budgethistory (Users_idUser, Budget_idBudget, BHdate, BHamount, idDT) 
                                VALUES (?, ?, ?, ?, ?)
                            ");
                            $formattedDate = date('Y-m-d', strtotime($value['DTdate']));
                            $stm->execute([
                                $this->Users_idUser,
                                $category['idBudget'],
                                $formattedDate,
                                $value['DTamount'],
                                $value['idDT']
                            ]);
                            $inserted = true;
                        }
                    }
                }
            }
    
            return $inserted;
        } catch (PDOException $e) {
            error_log("Database error in insertIntoBudgetHistory: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    
    public function fetchBudgetHistory($idBudget) {
        try {

            $stm = $this->conn->prepare("
                SELECT 
                    b.budgetCategory, 
                    b.budgetWhere, 
                    bh.BHamount, 
                    bh.idBudgetHistory, 
                    bh.BHdate 
                FROM 
                    budgethistory bh 
                JOIN 
                    budget b 
                ON 
                    bh.budget_idbudget = b.idBudget 
                WHERE 
                    b.Users_idUser = ? 
                    AND bh.Budget_idBudget = ?
            ");
            $stm->execute([$this->Users_idUser, $idBudget]);
            $result = $stm->fetchAll();
    
            if (!$result) {
                return [];
            }

            $incomeDates = new IncomeConfig();
            $incomeDates->setUsers_idUser($this->Users_idUser);
            $lastIncomeData = $incomeDates->lastIncomeDateAndPlusMonth();
    
            // Filtrowanie wyników bez użycia array_filter
            $filteredResult = [];
            foreach ($result as $row) {
                
                if ($row['budgetWhere'] === 'Na koncie głównym') { // Jeśli 'budgetWhere' = "Na koncie głównym", sprawdzamy zakres dat
                    if ($row['BHdate'] >= $lastIncomeData[0] && $row['BHdate'] <= $lastIncomeData[1]) {
                        $filteredResult[] = $row; // Dodajemy do wyników
                    }
                } else {
                    // Jeśli 'budgetWhere' nie jest "Na koncie głównym", dodajemy bez sprawdzania dat
                    $filteredResult[] = $row;
                }
            }
    
            return $filteredResult;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }
    
    

    public function getBudgetTotal($idBudget){
        $array = $this->fetchBudgetHistory($idBudget);
        $amountArray = array_column($array, 'BHamount');
        $sum = array_sum($amountArray);

        return (float)$sum;
    }

    public function countPercent($total, $actuall){
        if($total == 0){
            return 0;
        }else{
            return 100 - round(($actuall / $total) * 100, 2);
        }
    }
}

?>