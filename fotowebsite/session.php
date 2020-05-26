<?php
include('database.php');
session_start();

$user_check = $_SESSION['user_id'];
$user_role = $_SESSION['user_type'];

if(!isset($_SESSION['user_login'])){
    header("location:login.php");
}
?>