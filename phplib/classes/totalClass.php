<?php
require_once(__DIR__ . '/../dbConn.php');
include_once('categoryClass.php');
include_once('incomeClass.php');
include_once('chargeClass.php');
include_once('budgetClass.php');
include_once('dailyClass.php');

class TotalConfig{
    private $idUser;
    private $userTotal;
    private $dailyBudget;
    private $lastIncome;

    protected $conn;

    public function __construct($idUser=0, $userTotal=0){
        $this->idUser = $idUser;
        $this->userTotal = $userTotal;

        $this->conn = new PDO(DB_TYPE.':host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PWD, [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
    }

    public function setLastIncome($lastIncome){
        $this->lastIncome = $lastIncome;
    }

    public function getLastIncome(){
        return $this->lastIncome;
    }

    public function setDailyBudget($dailyBudget){
        $this->dailyBudget = $dailyBudget;
    }

    public function getDailyBudget(){
        return $this->dailyBudget;
    }

    public function setIdUser($idUser){
        $this->idUser = $idUser;
    }

    public function getIdUser(){
        return $this->idUser;
    }

    public function setUserTotal($userTotal){
        $this->userTotal = $userTotal;
    }

    public function getUserTotal(){
        return $this->userTotal;
    }

    // public function totalIncome(){
    //     $stm = $this->conn->prepare("SELECT SUM(incomeAmount) AS totalIncome FROM income WHERE Users_idUser = ?");
    //     $stm->execute([$this->idUser]);
    //     $result = $stm->fetch(); 
    //     return $result['totalIncome'] ?? 0; 

    //     $this->userTotal = $result['totalIncome'];
    
    // }
    
    public function totalDaily(){
        $stm = $this->conn->prepare("SELECT SUM(DTamount) AS totalDaily FROM dailyTransactions WHERE Users_idUser = ?");
        $stm->execute([$this->idUser]);
        $result = $stm->fetch(); 
        $result['totalDaily'] ?? 0; 

        return $result['totalDaily'];
    }

    public function checkLimitForCategories(){
        $stm = $this->conn->prepare("
        select budgetCategory as categories, budgetAmount as amount from budget where Users_idUser = ?
        union
        select chargeCategory, chargeAmount from charges where Users_idUser = ?
        union
        select savingCategory, savingAmount from savings where Users_idUser = ?
        ");

        $stm->execute([$this->idUser, $this->idUser, $this->idUser]);
        $result = $stm->fetchAll();
        
        return $result;
    }

    public function totalDailyWithoutCategories(){
        $categories = $this->checkLimitForCategories();

        $catAmArr = array_column($categories, 'amount');
        $totalCatAm = array_sum($catAmArr);

        $stm = $this->conn->prepare("SELECT SUM(DTamount) AS totalDaily FROM dailyTransactions WHERE Users_idUser = ?");
        $stm->execute([$this->idUser]);
        $result = $stm->fetch(); 
        $result['totalDaily'] ?? 0; 

        return $result['totalDaily'] - $totalCatAm;
    }

    public function totalCharge(){
        $stm = $this->conn->prepare("SELECT SUM(chargeAmount) AS totalCharge FROM charges WHERE Users_idUser = ?");
        $stm->execute([$this->idUser]);
        $result = $stm->fetch(); 
        $result['totalCharge'] ?? 0; 

        return $result['totalCharge'];
    }

    public function totalBudget(){
        $stm = $this->conn->prepare("SELECT SUM(budgetAmount) AS totalBudget FROM budget WHERE Users_idUser = ? AND budgetWhere = 'Na koncie głównym'");
        $stm->execute([$this->idUser]);
        $result = $stm->fetch(); 
        $result['totalBudget'] ?? 0; 

        return $result['totalBudget'];
    }

    public function calculateUserTotal(){
        $lastIncome = new IncomeConfig();
        $lastIncome->setUsers_idUser($this->idUser);
        $lastIncome = $lastIncome->fetchLastIncome();
        $lastIncomeAmount= $lastIncome[0]['incomeAmount'] ?? 0;
        $this->userTotal = $lastIncomeAmount + $this->totalDaily();
        // return $this->userTotal;

        $stm = $this->conn->prepare("update users set userTotal = ? where idUser = ?");
        $stm->execute([$this->userTotal, $this->idUser]);
    }

    public function calculateDaily() { //do naprawy
        $getDate = new IncomeConfig();
        $getDate->setUsers_idUser($this->idUser);
        $lastIncome = $getDate->fetchLastIncome();
        if (empty($lastIncome) || !isset($lastIncome[0]['incomeDate'])) {
            return $dailyBudget = 0;
        }
        
        $totalToUse = $lastIncome[0]['incomeAmount'] - $this->totalCharge() - $this->totalBudget();
    

        $lastIncomeDate = $lastIncome[0]['incomeDate'];
    
        
        $timestamp = strtotime($lastIncomeDate);
        $daysInMonth = date('t', $timestamp);

        $dailyBudget = (float)$totalToUse / (float)$daysInMonth;
        
        $this->setDailyBudget($dailyBudget);

        return $dailyBudget;  //
    }

    public function calculateDailyActuall(){
        $getDate = new IncomeConfig();
        $getDate->setUsers_idUser($this->idUser);
        $lastIncome = $getDate->fetchLastIncome();
        
        if (empty($lastIncome) || !isset($lastIncome[0]['incomeDate'])) {
            return $actuallDaily = 0;
        }
    
        $lastIncomeDate = $lastIncome[0]['incomeDate'];
        $today = date('Y-m-d');

        if($lastIncomeDate > $today){
            $today = $lastIncomeDate;
            $diff = abs(strtotime($today) - strtotime($lastIncomeDate)+1);
        }else{
            $diff = abs(strtotime($today) - strtotime($lastIncomeDate)+1);
        }

        
        $actuallDaily = ($this->calculateDaily()) * $diff; //oddaje policzoną wartość kwoty z wypłaty - budzet - wydatki

        $actuallDaily +=  $this->totalDailyWithoutCategories(); //dodaje do tej kwoty wszystkie wydatki z dnia, chyba, że to kategorie (czyli budzet, opłaty stałe itp) bo one byly wliczone wczesniej

        return $actuallDaily;
    }
}    
   


?>