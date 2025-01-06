<?php
require_once(__DIR__ . '/../dbConn.php');
include_once('categoryClass.php');

class ChargeConfig{
    private $idCharge;
    private $Users_idUser;
    private $chargeAmount;
    private $chargeAddDate;
    private $chargeExpiryDate;
    private $chargeCategory;
 
    protected $conn;


    public function __construct($idCharge=0, $Users_idUser=0, $chargeAmount=0, $chargeAddDate='', $chargeExpiryDate='', $chargeCategory=''){
        $this->idCharge = $idCharge;
        $this->Users_idUser = $Users_idUser;
        $this->chargeAmount = $chargeAmount;
        $this->chargeAddDate = $chargeAddDate;
        $this->chargeExpiryDate = $chargeExpiryDate;
        $this->chargeCategory = $chargeCategory;

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

    public function setChargeCategory($chargeCategory){
        $this->chargeCategory = $chargeCategory;
    }

    public function getChargeCategory(){
        return $this->chargeCategory;
    }

    public function insertCharge(){
        try{
            $stm = $this->conn->prepare("INSERT INTO charges (Users_idUser, chargeAmount, chargeAddDate, chargeExpiryDate, chargeCategory) VALUES (?, ?, ?, ?, ?)");

            if($this->chargeExpiryDate == '' || $this->chargeExpiryDate == null){
                $this->chargeExpiryDate = null;
            }

            $stm->execute([$this->Users_idUser, $this->chargeAmount, $this->chargeAddDate, $this->chargeExpiryDate, $this->chargeCategory]);
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    public function fetchAllChargesByDate(){
        try{
            $stm = $this->conn->prepare('SELECT * FROM charges WHERE Users_idUser = ? AND (chargeExpiryDate >= ?  OR chargeExpiryDate = "0000-00-00" OR chargeExpiryDate = "NULL") ORDER BY chargeAddDate DESC');
            $stm->execute([$this->Users_idUser, date('Y-m-d')]);
            return $stm->fetchAll();
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    public function countSummaryByDate($date){
        try{
            $stm = $this->conn->prepare('SELECT SUM(chargeAmount) as total FROM charges WHERE Users_idUser = ? AND (chargeExpiryDate >= ? OR chargeExpiryDate = "0000-00-00")');
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

            $stm = $this->conn->prepare('UPDATE charges SET chargeAmount = ?, chargeAddDate = ?, chargeExpiryDate = ?, chargeCategory = ? WHERE idCharge = ?');
            $stm->execute([$this->chargeAmount, $this->chargeAddDate, $this->chargeExpiryDate, $this->chargeCategory, $this->idCharge]);
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    public function fetchCharge(){
        try{
            $stm = $this->conn->prepare('SELECT * FROM charges WHERE idCharge = ? and Users_idUser = ?');
            $stm->execute([$this->idCharge, $this->Users_idUser]);
            return $stm->fetch();
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    public function fetchSum($idCharge){
        try{
            $income = new IncomeConfig();
            $income->setUsers_idUser($this->Users_idUser);
            $dates = $income->lastIncomeDateAndPlusMonth();

            $stm = $this->conn->prepare('SELECT sum(dt.DTamount) as sum from dailytransactions dt join charges ch on dt.DTname = ch.chargeCategory where ch.idCharge = ? and dt.Users_idUser = ? and dt.DTdate >= ? and dt.DTdate <= ?');
            $stm->execute([$idCharge, $this->Users_idUser, $dates[0], $dates[1]]);
            $sum = $stm->fetch();
            return abs($sum['sum']) ?? 'Brak';
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    public function fetchDate($idCharge){
        try{
            $stm = $this->conn->prepare('SELECT dt.DTdate from dailytransactions dt join charges ch on dt.DTname = ch.chargeCategory where ch.idCharge = ? and dt.Users_idUser = ? order by dt.DTdate desc limit 1');
            $stm->execute([$idCharge, $this->Users_idUser]);
            $date = $stm->fetch();
            return $date['DTdate'] ?? 'Nie wpłacono';
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }

}

?>