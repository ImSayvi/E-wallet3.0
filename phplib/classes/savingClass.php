<?php
require_once(__DIR__ . '/../dbConn.php');

class SayvinConfig{
    private $idSaving;
    private $savingAmount;
    private $savingDate;
    private $savingCategory;
    private $Users_idUser;
    private $isActualSaving;
    protected $conn;

    public function __construct($idSaving=0, $savingAmount=0, $savingDate='', $savingCategory='', $Users_idUser='', $isActualSaving='')
    {
        $this->idSaving = $idSaving;
        $this->savingAmount = $savingAmount;
        $this->savingDate = $savingDate;
        $this->savingCategory = $savingCategory;
        $this->Users_idUser = $Users_idUser;
        $this->isActualSaving = $isActualSaving;

        $this->conn = new PDO(DB_TYPE.':host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PWD, [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
    }

    public function setUsers_idUser($Users_idUser){
        $this->Users_idUser = $Users_idUser;
    }

    public function setIdSaving($idSaving){
        $this->idSaving = $idSaving;
    }

    public function getIdSaving(){
        return $this->idSaving;
    }

    public function setSavingAmount($savingAmount){
        $this->savingAmount = $savingAmount;
    }

    public function getSavingAmount(){
        return $this->savingAmount;
    }

    public function setSavingDate($savingDate){
        $this->savingDate = $savingDate;
    }

    public function getSavingDate(){
        return $this->savingDate;
    }
  
    public function setSavingCategory($savingCategory){
        $this->savingCategory = $savingCategory;
    }

    public function getSavingCategory(){
        return $this->savingCategory;
    }

    public function setisActualSaving($isActualSaving){
        $this->isActualSaving = $isActualSaving;
    }

    public function getisActualSaving(){
        return $this->isActualSaving;
    }


   public function insertSaving(){
        $stmt = $this->conn->prepare("INSERT INTO savings (savingAmount, savingDate, savingCategory, Users_idUser, isActualSaving) VALUES (:savingAmount, :savingDate, :savingCategory, :Users_idUser, :isActualSaving)");
        $stmt->bindParam(':savingAmount', $this->savingAmount);
        $stmt->bindParam(':savingDate', $this->savingDate);
        $stmt->bindParam(':savingCategory', $this->savingCategory);
        $stmt->bindParam(':Users_idUser', $this->Users_idUser);
        $stmt->bindParam(':isActualSaving', $this->isActualSaving);
        $stmt->execute();
    }

    public function updateSaving(){
        $stmt = $this->conn->prepare("UPDATE savings SET savingAmount = :savingAmount, savingDate = :savingDate, savingCategory = :savingCategory, Users_idUser = :Users_idUser, isActualSaving = :isActualSaving WHERE idSaving = :idSaving");
        $stmt->bindParam(':savingAmount', $this->savingAmount);
        $stmt->bindParam(':savingDate', $this->savingDate);
        $stmt->bindParam(':savingCategory', $this->savingCategory);
        $stmt->bindParam(':Users_idUser', $this->Users_idUser);
        $stmt->bindParam(':idSaving', $this->idSaving);
        $stmt->bindParam(':isActualSaving', $this->isActualSaving);
        $stmt->execute();
    }

    public function deleteSaving(){
        $stmt = $this->conn->prepare("DELETE FROM savings WHERE idSaving = :idSaving");
        $stmt->bindParam(':idSaving', $this->idSaving);
        $stmt->execute();
    }

    public function getSaving(){
        $stmt = $this->conn->prepare("SELECT * FROM savings WHERE idSaving = :idSaving");
        $stmt->bindParam(':idSaving', $this->idSaving);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getSavings(){
        $stmt = $this->conn->prepare("SELECT * FROM savings WHERE Users_idUser = :Users_idUser");
        $stmt->bindParam(':Users_idUser', $this->Users_idUser);
        $stmt->execute();
        return $stmt->fetchAll();
   }
}

?>