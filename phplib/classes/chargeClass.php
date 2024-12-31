<?php
require_once(__DIR__ . '/../dbConn.php');
include_once('categoryClass.php');

class ChargeConfig{
    private $idCharge;
    private $Users_idUser;
    private $chargeAmount;
    private $chargeAddDate;
    private $chargeExpiryDate;
    private $chargeName;
    private $idCategory;
    protected $conn;


    public function __construct($idCharge=0, $Users_idUser=0, $chargeAmount=0, $chargeAddDate='', $chargeExpiryDate='', $chargeName=''){
        $this->idCharge = $idCharge;
        $this->Users_idUser = $Users_idUser;
        $this->chargeAmount = $chargeAmount;
        $this->chargeAddDate = $chargeAddDate;
        $this->chargeExpiryDate = $chargeExpiryDate;
        $this->chargeName = $chargeName;

        $this->conn = new PDO(DB_TYPE.':host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PWD, [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
    }

    public function setIdCharge($idCharge){
        $this->idCharge = $idCharge;
    }

    public function getIdCharge(){
        return $this->idCharge;
    }

    public function setUsers_idUser($Users_idUser){
        $this->Users_idUser = $Users_idUser;
    }

    public function getUsers_idUser(){
        return $this->Users_idUser;
    }

    public function setChargeAmount($chargeAmount){
        $this->chargeAmount = $chargeAmount;
    }

    public function getChargeAmount(){
        return $this->chargeAmount;
    }

    public function setChargeAddDate($chargeAddDate){
        $this->chargeAddDate = $chargeAddDate;
    }

    public function getChargeAddDate(){
        return $this->chargeAddDate;
    }

    public function setChargeExpiryDate($chargeExpiryDate){
        $this->chargeExpiryDate = $chargeExpiryDate;
    }

    public function getChargeExpiryDate(){
        return $this->chargeExpiryDate;
    }

    public function setChargeName($chargeName){
        $this->chargeName = $chargeName;
    }

    public function getChargeName(){
        return $this->chargeName;
    }

    public function setIdChargeCategory($idCategory){
        $this->idCategory = $idCategory;
    }

    public function getIdChargeCategory(){
        return $this->idCategory;
    }

    public function insertCharge(){
        try{
            $cat = new CategoryConfig(0, $this->chargeName, $this->Users_idUser);

            if ($cat->addCategory() == false){
                return false;
            }else{
                $stm = $this->conn->prepare("INSERT INTO charges (Users_idUser, chargeAmount, chargeAddDate, chargeExpiryDate, idCategory) VALUES (?, ?, ?, ?, ?)");

                if($this->chargeExpiryDate == '' || $this->chargeExpiryDate == null){
                    $this->chargeExpiryDate = null;
                }
                $this->idCategory = $cat->getIdCategory($this->chargeName, $this->Users_idUser);

                $stm->execute([$this->Users_idUser, $this->chargeAmount, $this->chargeAddDate, $this->chargeExpiryDate, $this->idCategory]);
            }
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    public function fetchAllChargesByDate(){
        try{
            $stm = $this->conn->prepare('SELECT * FROM charges WHERE Users_idUser = ? AND chargeExpiryDate >= ?  OR chargeExpiryDate = "0000-00-00" OR chargeExpiryDate = "NULL" ORDER BY chargeAddDate DESC');
            $stm->execute([$this->Users_idUser, date('Y-m-d')]);
            return $stm->fetchAll();
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    public function countSummaryByDate($date){
        try{
            $stm = $this->conn->prepare('SELECT SUM(chargeAmount) as total FROM charges WHERE Users_idUser = ? AND chargeExpiryDate >= ? OR chargeExpiryDate = "0000-00-00"');
            $stm->execute([$this->Users_idUser, $date]);
            $result = $stm->fetch();
            return $result['total'];
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    public function deleteCharge(){
        try{
            $stm = $this->conn->prepare('DELETE FROM charges WHERE idCharge = ?');
            $stm->execute([$this->idCharge]);

            $stm2 = $this->conn->prepare('DELETE FROM financecategories WHERE idCategory = ?');
            $stm2->execute([$this->idCategory]);
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    public function updateCharge(){
        try{
            if($this->chargeExpiryDate == '' || $this->chargeExpiryDate == null){
                $this->chargeExpiryDate = null;
            }

            $stm = $this->conn->prepare('UPDATE charges SET chargeAmount = ?, chargeAddDate = ?, chargeExpiryDate = ?, idCategory = ? WHERE idCharge = ?');
            $stm->execute([$this->chargeAmount, $this->chargeAddDate, $this->chargeExpiryDate, $this->idCategory, $this->idCharge]);

            $cat = new CategoryConfig($this->idCategory, $this->chargeName, $this->Users_idUser);
            $cat->updateCategory($this->idCategory);
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    public function fetchCharge(){
        try{
            $stm = $this->conn->prepare('SELECT * FROM charges WHERE idCharge = ?');
            $stm->execute([$this->idCharge]);
            return $stm->fetch();
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }

}

?>