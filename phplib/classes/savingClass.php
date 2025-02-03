<?php
require_once(__DIR__ . '/../dbConn.php');

class SayvinConfig{
    private $idSaving;
    private $savingAmount;
    private $savingDate;
    private $savingName;
    private $Users_idUser;
    protected $conn;

    public function __construct($idSaving, $savingAmount, $savingDate, $savingName, $Users_idUser)
    {
        $this->idSaving = $idSaving;
        $this->savingAmount = $savingAmount;
        $this->savingDate = $savingDate;
        $this->savingName = $savingName;
        $this->Users_idUser = $Users_idUser;

        $this->conn = new PDO(DB_TYPE.':host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PWD, [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
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
        $this->savingDate = $idSaving;
    }

    public function getIdSaving(){
        return $this->idSaving;
    }

    public function setIdSaving($idSaving){
        $this->idSaving = $idSaving;
    }

    public function getIdSaving(){
        return $this->idSaving;
    }

    public function setIdSaving($idSaving){
        $this->idSaving = $idSaving;
    }

    public function getIdSaving(){
        return $this->idSaving;
    }
}

?>