<?php
require_once(__DIR__ . '/../dbConn.php');
include_once('categoryClass.php');

class BudgetConfig{
    private $idBudget;
    private $Users_idUser;
    private $budgetAddDate;
    private $budgetAmount;
    private $budgetWhere;
    private $budgetName;
    private $idCategory;
    protected $conn;

    public function __construct($idBudget=0, $Users_idUser=0, $budgetAddDate='', $budgetAmount=0, $budgetWhere='', $budgetName=''){
        $this->idBudget = $idBudget;
        $this->Users_idUser = $Users_idUser;
        $this->budgetAddDate = $budgetAddDate;
        $this->budgetAmount = $budgetAmount;
        $this->budgetWhere = $budgetWhere;
        $this->budgetName = $budgetName;

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

    public function setBudgetName($budgetName){
        $this->budgetName = $budgetName;
    }

    public function getBudgetName(){
        return $this->budgetName;
    }

    public function setIdCategory($idCategory){
        $this->idCategory = $idCategory;
    }

    public function getIdCategory(){
        return $this->idCategory;
    }

    public function insertBudget(){
        try{
            $cat = new CategoryConfig(0, $this->budgetName, $this->Users_idUser);

            if ($cat->addCategory() == false){
                return false;
            }else{
                $stm = $this->conn->prepare("INSERT INTO budget (Users_idUser, budgetAddDate, budgetAmount, budgetWhere, idCategory) VALUES (?, ?, ?, ?, ?)");

                $this->idCategory = $cat->getIdCategory($this->budgetName, $this->Users_idUser);

                $stm->execute([$this->Users_idUser, $this->budgetAddDate, $this->budgetAmount, $this->budgetWhere, $this->idCategory]);
            }
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

            $stm2 = $this->conn->prepare('DELETE FROM financecategories WHERE idCategory = ?');
            $stm2->execute([$this->idCategory]);
        }    
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    public function updateBudget(){
        try{
            $stm = $this->conn->prepare("UPDATE budget SET budgetAmount = ?, budgetWhere = ? WHERE idBudget = ?");
            $stm->execute([$this->budgetAmount, $this->budgetWhere, $this->idBudget]);

            $cat = new CategoryConfig($this->idCategory, $this->budgetName, $this->Users_idUser);
            $cat->updateCategory($this->idCategory);
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    public function putIntoBudgetHistory() {
        try {
            // Combine the logic into a single query using joins
            $query = "
                SELECT 
                    b.idBudget, 
                    dt.Users_idUser, 
                    dt.DTamount, 
                    dt.DTdate
                FROM dailytransactions dt
                INNER JOIN financecategories fc 
                    ON dt.DTname = fc.categoryName 
                    AND dt.Users_idUser = fc.Users_idUser
                INNER JOIN budget b 
                    ON fc.idCategory = b.idCategory 
                    AND b.Users_idUser = fc.Users_idUser
                WHERE dt.Users_idUser = ?
            ";
    
            // Prepare and execute the query
            $stm = $this->conn->prepare($query);
            $stm->execute([$this->Users_idUser]);
            $result = $stm->fetchAll();

            var_dump($result);
           


        } catch (PDOException $e) {
            // Handle exceptions
            error_log("Database Error: " . $e->getMessage());
            throw new Exception("An error occurred while updating the budget history.");
        }
    }

    public function getBudgetHistory(){
        $stm = $this->conn->prepare("SELECT * FROM budgethistory WHERE Users_idUser = ? AND idBudget = ?");
        $stm->execute([$this->Users_idUser, $this->idBudget]);
        $result = $stm->fetchAll();

        return $result;
    }
}

?>