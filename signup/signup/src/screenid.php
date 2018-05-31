<?php session_start();

$_SESSION['screenID'] = $_POST['screenID'];

header('Location: ../public/create_account.php');
exit();