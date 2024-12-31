<?php
include_once('categoryClass.php');
require_once(__DIR__ . '/../dbConn.php');

class DailyConfig{
    private $idDt;
    private $Users_idUser;
    private $DTname;
    private $DTamount;
    private $DTdate;
    private $idCategory;
    protected $conn;

    public function __construct($idDt=0, $Users_idUser=0, $DTname='', $DTamount=0, $DTdate=''){
        $this->idDt = $idDt;
        $this->Users_idUser = $Users_idUser;
        $this->DTname = $DTname;
        $this->DTamount = $DTamount;
        $this->DTdate = $DTdate;

        $this->conn = new PDO(DB_TYPE.':host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PWD, [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
    }

    public function setIdDt($idDt){
        $this->idDt = $idDt;
    }

    public function getIdDt(){
        return $this->idDt;
    }

    public function setUsers_idUser($Users_idUser){
        $this->Users_idUser = $Users_idUser;
    }

    public function getUsers_idUser(){
        return $this->Users_idUser;
    }

    public function setDTname($DTname){
        $this->DTname = $DTname;
    }

    public function getDTname(){
        return $this->DTname;
    }

    public function setDTamount($DTamount){
        $this->DTamount = $DTamount;
    }

    public function getDTamount(){
        return $this->DTamount;
    }

    public function setDTdate($DTdate){
        $this->DTdate = $DTdate;
    }

    public function getDTdate(){
        return $this->DTdate;
    }

    public function setIdCategory($idCategory){
        $this->idCategory = $idCategory;
    }

    public function getIdCategory($DTname, $Users_idUser){
        $stm = $this->conn->prepare("SELECT * FROM financecategories WHERE categoryName = ? AND Users_idUser = ?");
        $stm->execute([$DTname, $Users_idUser]);
        $result = $stm->fetch();

        if($result > 0){
            return $result['idCategory'];
        }else{
            return false;
        }    
    }

    public function insertDaily(){
        try{
            $stm = $this->conn->prepare("INSERT INTO dailytransactions (Users_idUser, DTname, DTamount, DTdate) VALUES (?, ?, ?, ?)");
            $stm->execute([$this->Users_idUser, $this->DTname, $this->DTamount, $this->DTdate]);
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
        
    }

    public function getAllPositiveDaily(){
        $lastIncome = new IncomeConfig();
        $lastIncome->setUsers_idUser($this->Users_idUser);
        $lastIncome->fetchLastIncome();
        $lastIncomeDate = $lastIncome[0]['incomeDate'];
        $lastIncomeDateFormated = strtotime($lastIncomeDate);
        $lastIncomeDatePlusMonth = strtotime('+1 month', $lastIncomeDateFormated);

        $stm = $this->conn->prepare("SELECT * FROM dailytransactions WHERE Users_idUser = ? AND DTamount > 0 AND DTdate >= ? AND DTdate <= ? ORDER BY DTdate DESC");
        $stm->execute([$this->Users_idUser, $lastIncomeDateFormated, $lastIncomeDatePlusMonth]);
        $result = $stm->fetchAll();

        return $result;
    }

    public function getAllNegativeDaily(){
        $lastIncome = new IncomeConfig();
        $lastIncome->setUsers_idUser($this->Users_idUser);
        $lastIncome->fetchLastIncome();
        $lastIncomeDate = $lastIncome[0]['incomeDate'];
        $lastIncomeDateFormated = strtotime($lastIncomeDate);
        $lastIncomeDatePlusMonth = strtotime('+1 month', $lastIncomeDateFormated);

        $stm = $this->conn->prepare("SELECT * FROM dailytransactions WHERE Users_idUser = ? AND DTamount < 0 AND DTdate >= ? AND DTdate <= ? ORDER BY DTdate DESC");
        $stm->execute([$this->Users_idUser, $lastIncomeDateFormated, $lastIncomeDatePlusMonth]);
        $result = $stm->fetchAll();

        return $result;
    }

    public function deleteDaily(){
        try{
            $stm = $this->conn->prepare('DELETE FROM dailytransactions WHERE idDT = ?');
            $stm->execute([$this->idDt]);
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }
}


?>