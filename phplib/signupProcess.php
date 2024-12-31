<?php

require_once  'classes/SignupClass.php';



if(isset($_POST['signup'])){

    $password = $_POST['password'];
    $password2 = $_POST['password2'];
    $signup = new SignupConfig();
    $signup->setLogin($_POST['login']);
    $signup->setPassword($password);
    $signup->setName($_POST['name']);
}

if($signup->checkUser($_POST['login'])){
    echo "<script>alert('Użytkownik o podanym loginie już istnieje'); document.location='../register.php'</script>";
}else{
    if($password == $password2){
        $signup->insertUser();
        echo "<script>alert('Konto zostało założone'); document.location='../login.php'</script>";
    }
    else{
        echo "<script>alert('Hasła nie są takie same'); document.location='../register.php'</script>";
    }   
    
    
}




?>