<?php
session_start();
include '../../config/database.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: ../index.php');
    exit();
}

$id = isset($_GET['id']) ? intval ($_GET['id']) : 0;



$sql = "DELETE FROM subjects WHERE id = $id";
$result = mysqli_query($conn, $sql);

if ($result) {
    header('Location: index.php?message=Subject deleted successfully');
} else {
    header('Location: index.php?message=Failed to delete subject');
}
exit();
?>