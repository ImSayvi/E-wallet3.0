<?php
require_once(__DIR__ . '/../dbConn.php');


class LoginConfig{
    private $id;
    private $password;
    private $login;
    private $total;
    protected $conn;

    public function __construct($id=0, $name='', $password='', $login=''){
        $this->id = $id;
        $this->password = $password;
        $this->login = $login;

        $this->conn = new PDO(DB_TYPE.':host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PWD, [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
    }

    public function setId($id){
        $this->id = $id;
    }

    public function getId(){
        return $this->id;
    }
    
    public function setTotal($total){
        $this->total = $total;
    }   

    public function getTotal(){
        return $this->total;
    }

    public function setPassword($password){
        $this->password = $password;
    }

    public function getPassword(){
        return $this->password;
    }

    public function setLogin($login){
        $this->login = $login;
    }

    public function getLogin(){
        return $this->login;
    }

    public function fetchUsers(){
        try{
            $stm = $this->conn->prepare('SELECT * FROM users');
            $stm->execute();
            return $stm->fetchAll();
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function login(){
        try{
            $stm = $this->conn->prepare('SELECT * FROM users WHERE userLogin = ? AND userPass = ?');

            $stm->execute([$this->login, md5($this->password)]);
            $user = $stm->fetchAll();
            if(count($user) > 0){
                session_start();
                $_SESSION['idUser'] = $user[0]['idUser'];
                $_SESSION['login'] = $user[0]['userLogin'];
                $_SESSION['password'] = $user[0]['userPass'];
                $_SESSION['userName'] = $user[0]['userName'];
                $_SESSION['userTotal'] = $user[0]['userTotal'];

                $_SESSION['logged'] = true;

                return true;
            }
            else{
                return false;
            }
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
}
?>