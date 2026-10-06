<?php
session_start();
include '../config/database.php';

// Check if user is logged in as a student
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../index.php");
    exit();
}

$student_id     = $_SESSION['user_id'];
$student_number = $_SESSION['student_no'] ?? 'N/A';
$student_name   = $_SESSION['full_name']  ?? 'Student';

$enrolled_ids = [];
$enrollments_query = mysqli_query($conn, "SELECT subject_id FROM enrollments WHERE student_id = '$student_id'");
while ($row = mysqli_fetch_assoc($enrollments_query)) {
    $enrolled_ids[] = $row['subject_id'];
}
$subjects = mysqli_query($conn, "SELECT * FROM subjects");
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Dashboard</title>

    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>

    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <span class="navbar-brand">Student Portal</span>
            <a class="btn btn-outline-light btn-sm" href="../logout.php">Logout</a>
        </div>
    </nav>

    <div class="container py-4">

        <h2>Enroll Subjects</h2>

        <p>
            <b>Student:</b>
            <?php echo htmlspecialchars($student_number); ?> - <?php echo htmlspecialchars($student_name); ?>
        </p>

        <div class="card">
            <div class="card-body">

                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Subject</th>
                            <th>Units</th>
                            <th>Status / Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($subject = mysqli_fetch_assoc($subjects)): ?>
                            <?php $is_enrolled = in_array($subject['id'], $enrolled_ids); ?>
                            <tr>
                                <td><?php echo htmlspecialchars($subject['subject_code']); ?></td>
                                <td><?php echo htmlspecialchars($subject['subject_name']); ?></td>
                                <td><?php echo htmlspecialchars($subject['units']); ?></td>
                                <td>
                                    <?php if ($is_enrolled): ?>
                                        <span class="badge bg-success me-2">Enrolled</span>
                                        <a href="unenroll.php?id=<?php echo $subject['id']; ?>" class="btn btn-outline-danger btn-sm">Remove</a>
                                    <?php else: ?>
                                        <a href="process.php?id=<?php echo $subject['id']; ?>" class="btn btn-primary btn-sm">Enroll</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

            </div>
        </div>

    </div>

</body>
</html>