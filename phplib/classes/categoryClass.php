<?php
require_once(__DIR__ . '/../dbConn.php');

class CategoryConfig{
    private $categoryName;
    private $Users_idUser;
    protected $conn;

    public function __construct($categoryName='', $Users_idUser=0){
        $this->categoryName = $categoryName;
        $this->Users_idUser = $Users_idUser;

        $this->conn = new PDO(DB_TYPE.':host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PWD, [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
    }


    public function setUsers_idUser($Users_idUser){
        $this->Users_idUser = $Users_idUser;
    }

    public function getUsers_idUser(){
        return $this->Users_idUser;
    }

    public function getAllCategories(){
        $stm = $this->conn->prepare("
        select budgetCategory as categories from budget where Users_idUser = ?
        union
        select chargeCategory from charges where Users_idUser = ?
        union
        select savingCategory from savings where Users_idUser = ?");
        $stm->execute([$this->Users_idUser, $this->Users_idUser, $this->Users_idUser]);
        return $stm->fetchAll();
    }

}

?>