<?php
require_once(__DIR__ . '/../dbConn.php');


class IncomeConfig{
    private $idIncome;
    private $incomeAmount;
    private $incomeDate;
    private $incomeName;
    private $Users_idUser;
    protected $conn;

    public function __construct($idIncome=0, $incomeAmount=0, $incomeDate='', $incomeName='', $Users_idUser=0){
        $this->idIncome = $idIncome;
        $this->incomeAmount = $incomeAmount;
        $this->incomeDate = $incomeDate;
        $this->incomeName = $incomeName;
        $this->Users_idUser = $Users_idUser;

        $this->conn = new PDO(DB_TYPE.':host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PWD, [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
    }

    public function setIdIncome($idIncome){
        $this->idIncome = $idIncome;
    }

    public function getIdIncome(){
        return $this->idIncome;
    }

    public function setIncomeAmount($incomeAmount){
        $this->incomeAmount = $incomeAmount;
    }

    public function getIncomeAmount(){
        return $this->incomeAmount;
    }

    public function setIncomeDate($incomeDate){
        $this->incomeDate = $incomeDate;
    }

    public function getIncomeDate(){
        return $this->incomeDate;
    }

    public function setIncomeName($incomeName){
        $this->incomeName = $incomeName;
    }

    public function getIncomeName(){
        return $this->incomeName;
    }

    public function setUsers_idUser($Users_idUser){
        $this->Users_idUser = $Users_idUser;
    }

    public function getUsers_idUser(){
        return $this->Users_idUser;
    }

    public function insertIncome(){
        try{
            $stm  = $this->conn->prepare('INSERT INTO income (incomeAmount, incomeDate, incomeName, Users_idUser) VALUES (?, ?, ?, ?)');
            $stm->execute([$this->incomeAmount, $this->incomeDate, $this->incomeName, $this->Users_idUser]);
            echo "<script>alert('Dodano przychód'); document.location='income.php'</script>";
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function fetchAllIncome(){
        try{
            $stm = $this->conn->prepare('SELECT * FROM income WHERE Users_idUser = ?  ORDER BY incomeDate DESC');
            $stm->execute([$this->Users_idUser]);
            return $stm->fetchAll();
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function fetchLastIncome() {
        try {
            $stm = $this->conn->prepare('SELECT * FROM income WHERE Users_idUser = ? ORDER BY incomeDate DESC LIMIT 1');
            $stm->execute([$this->Users_idUser]);
            $result = $stm->fetchAll();
    
            return $result ?? [];
        } catch (Exception $e) {
            error_log("Błąd w fetchLastIncome: " . $e->getMessage());
            return [];
        }
    }
    

    public function fetchIncomeById(){
        try{
            $stm = $this->conn->prepare('SELECT * FROM income WHERE idIncome = ? ORDER BY incomeDate ASC');
            $stm->execute([$this->idIncome]);
            return $stm->fetchAll();
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function deleteIncome(){
        try{
            $stm = $this->conn->prepare('DELETE FROM income WHERE idIncome = ?');
            $stm->execute([$this->idIncome]);

            // return $stm->fetchAll();
            echo "<script>alert('Usunięto przychód'); document.location='income.php'</script>";
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function lastIncomeDateAndPlusMonth(){
        try{
            $stm = $this->conn->prepare('SELECT incomeDate FROM income WHERE Users_idUser = ? ORDER BY incomeDate DESC LIMIT 1');
            $stm->execute([$this->Users_idUser]);
            $result = $stm->fetch();
            $lastIncomeDate = $result['incomeDate'] ?? date('Y-m-d');
           
            $lastIncomeDateFormated = strtotime($lastIncomeDate);
            $lastIncomeDatePlusMonth = strtotime('+1 month', $lastIncomeDateFormated);
        
            $lastIncomeDateSQL = date('Y-m-d', $lastIncomeDateFormated);
            $lastIncomeDatePlusMonthSQL = date('Y-m-d', $lastIncomeDatePlusMonth);

            $lastIncomeDates = array($lastIncomeDateSQL, $lastIncomeDatePlusMonthSQL);

            return $lastIncomeDates;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
 
    }

    // public function updateIncome(){
    //     try{
    //         $stm = $this->conn->prepare('UPDATE income SET incomeAmount = ?, incomeDate = ?, incomeName = ? WHERE idIncome = ?');
    //         $stm->execute([$this->incomeAmount, $this->incomeDate, $this->incomeName, $this->idIncome]);
    //         echo "<script>alert('Zaktualizowano przychód'); document.location='../income.php'</script>";
    //     }
    //     catch(Exception $e){
    //         return $e->getMessage();
    //     }
    // }

}

?>