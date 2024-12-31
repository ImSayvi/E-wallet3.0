<?php
require_once('dbConn.php');
include('loginClass.php');

class SignupConfig{
    private $id;
    private $total;
    private $name;
    private $password;
    private $login;
    protected $conn;

    public function __construct($id=0, $name='', $password='', $login='', $total=0){
        $this->id = $id;
        $this->total = $total;
        $this->name = $name;
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

    public function setName($name){
        $this->name = $name;
    }

    public function getName(){
        return $this->name;
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

    public function checkUser($login){
        try{
            $stm = $this->conn->prepare('SELECT * FROM users WHERE userLogin = ?');
            $stm->execute([$login]);
          
            if($stm->fetchColumn()){
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
    
    public function insertUser(){
        try{
            $stm = $this->conn->prepare("INSERT INTO users (userName, userPass, userLogin, userTotal) VALUES (?, ?, ?, ?)");
            $stm->execute([$this->name, md5($this->password), $this->login, $this->total]);

            $login = new LoginConfig();
            $login->setLogin($_POST['login']);
            $login->setPassword($_POST['password']);

            $success = $login->login();
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
    }
};

?>