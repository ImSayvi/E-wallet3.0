<?php
    session_start();

    if(isset($_POST['loginForm'])){
        require_once 'classes/LoginClass.php';
        
        $info = new LoginConfig();
        $info->setLogin($_POST['login']);
        $info->setPassword($_POST['password']);

        $login= $info->login();

        if($login){
            header('Location: ../index.php');
        }
        else{
            echo "<script>alert('Niepoprawne dane'); document.location='../login.php'</script>";
        }
    }
?>