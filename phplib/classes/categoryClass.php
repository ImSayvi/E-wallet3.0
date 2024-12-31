<?php
require_once(__DIR__ . '/../dbConn.php');

class CategoryConfig{
    private $idCategory;
    private $categoryName;
    private $Users_idUser;
    protected $conn;

    public function __construct($idCategory=0, $categoryName='', $Users_idUser=0){
        $this->idCategory = $idCategory;
        $this->categoryName = $categoryName;
        $this->Users_idUser = $Users_idUser;

        $this->conn = new PDO(DB_TYPE.':host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PWD, [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
    }

    public function setIdCategory($idCategory){
        $this->idCategory = $idCategory;
    }

    public function getIdCategory($categoryName, $Users_idUser){
        $stm = $this->conn->prepare("SELECT * FROM financecategories WHERE categoryName = ? AND Users_idUser = ?");
        $stm->execute([$categoryName, $Users_idUser]);
        $result = $stm->fetch();

        return $result['idCategory'];
    }

    public function setCategoryName($categoryName){
        $exist = $this->conn->prepare("SELECT * FROM financecategories WHERE categoryName = ? AND Users_idUser = ?");
        $exist->execute([$categoryName, $this->Users_idUser]);
        if($exist->rowCount() > 0 ){
            echo "<script>alert('Podana nazwa już istnieje');</script>";
            return false;
        }else{
        $this->categoryName = $categoryName;
        }
    }

    public function getCategoryName($categoryId){
        $stm = $this->conn->prepare("SELECT * FROM financecategories WHERE idCategory = ?");
        $stm->execute([$categoryId]);
        $result = $stm->fetch();
        return $result['categoryName'] ?? 'usunięto';
    }

    public function setUsers_idUser($Users_idUser){
        $this->Users_idUser = $Users_idUser;
    }

    public function getUsers_idUser(){
        return $this->Users_idUser;
    }


    public function addCategory(){
        $doExist = $this->conn->prepare("SELECT * FROM financecategories WHERE categoryName = ? AND Users_idUser = ?");
        $doExist->execute([$this->categoryName, $this->Users_idUser]);
        if($doExist->rowCount() > 0){
            echo "<script>alert('Podana nazwa już istnieje');</script>";
            return false;
        }else{
            $stm = $this->conn->prepare ("INSERT INTO financecategories (categoryName, Users_idUser) VALUES (?, ?)");
            $stm->execute([$this->categoryName, $this->Users_idUser]);
            return true;
        }
    }

    public function deleteCategory($idCategory){
        $stm = $this->conn->prepare("DELETE FROM financecategories WHERE idCategory = ?");
        $stm->execute([$idCategory]);
    }

    public function getAllCategories(){
        $stm = $this->conn->prepare("SELECT * FROM financecategories WHERE Users_idUser = ?");
        $stm->execute([$this->Users_idUser]);
        return $stm->fetchAll();
    }

    public function updateCategory($idCategory){
        $stm = $this->conn->prepare("UPDATE financecategories SET categoryName = ? WHERE idCategory = ?");
        $stm->execute([$this->categoryName, $idCategory]);
    }    

}

?>