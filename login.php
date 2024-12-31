<?php
require_once('phplib/dbConn.php');

session_start();
if((isset($_SESSION['logged'])) && $_SESSION['logged'] == true){
    header('Location: index.php');
    exit();
}

?>    



<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>E-Wallet 3.0 - logowanie</title>

    <!-- Custom fonts for this template-->
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- bootsttrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-tNW+F6EIHwEiP4eKL/gh8IOWhfEECOoBVw8wsL3Dcz/UdomjHzCJNEkJvBJ2j1hY" crossorigin="anonymous">


</head>


<body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col d-flex justify-content-center align-items-center">
                                <div class="p-5" style="max-width: 500px; width: 100%;"> 
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Witaj!</h1>
                                    </div>
                                    <form class="user" method="post" action="phplib/loginProcess.php">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user"
                                                id="exampleInputLogin" aria-describedby="loginlHelp"
                                                name="login" placeholder="Login">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user"
                                                id="exampleInputPassword" placeholder="Hasło" name="password">
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" class="custom-control-input" id="customCheck">
                                                <label class="custom-control-label" for="customCheck">zapamiętaj mnie</label>
                                            </div>
                                        </div>
                                        <input type="submit" name="loginForm" class="btn btn-primary btn-user btn-block" value="Zaloguj">
                                        <hr>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="forgot-password.html">Zapomniałeś hasła?</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="register.php">Załóż konto!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        

                        
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="assets/js/sb-admin-2.min.js"></script>

    <!-- bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-JCXWxIDj6A9u5C0CJLykpSVe/I1CkMBB9/PC4zOCOTN2IB/IfJmfS6YYd7b07jRM" crossorigin="anonymous"></script>


</body>

</html>