<?php
session_start();
include '../config/database.php';

// Check if user is logged in as a student
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../index.php");
    exit();
}

$student_id = $_SESSION['user_id'];
$subject_id = $_GET['id'] ?? null;

if ($subject_id) {
    // Check if not already enrolled
    $check = mysqli_query($conn, "SELECT * FROM enrollments WHERE student_id = '$student_id' AND subject_id = '$subject_id'");
    
    if (mysqli_num_rows($check) == 0) {
        $query = "INSERT INTO enrollments (student_id, subject_id) VALUES ('$student_id', '$subject_id')";
        mysqli_query($conn, $query);
    }
}

// Redirect back to student dashboard
header("Location: dashboard.php");
exit();