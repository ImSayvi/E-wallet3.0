<?php
include ('phpLib/classes/modalCreatorClass.php');

require_once('phplib/classes/loginClass.php');
require_once('phplib/classes/incomeClass.php');
require_once('phplib/classes/chargeClass.php');
require_once('phplib/classes/budgetClass.php');
require_once('phplib/classes/dailyClass.php');
require_once('phplib/classes/totalClass.php');

session_start();

if (!isset($_SESSION['logged'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pl">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>E-wallet 3.0</title>

    <!-- Custom fonts for this template-->
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- css dla datatables -->
    <link href="assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <style>
        .amount {
            font-size: 3.5rem; /* Domyślny rozmiar */
        }

        @media (max-width: 1270px) {
            .amount {
                font-size: 2.5rem;
            }
        }

        @media (max-width: 576px) {
            .amount {
                font-size: 2rem;
            }
        }
    </style>

</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <?php include 'sidebar.php'; ?>
            <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">
            <?php include 'navbar.php'?>
            <!-- Begin Page Content -->
            <div class="container-fluid">
