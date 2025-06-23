<?php
session_start();
include 'connect.php';

if (isset($_POST['submit'])) {
    // Capture form inputs and validate them
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $position = trim($_POST['position']);
    $salary = trim($_POST['salary']);

    // Basic form validation for empty fields
    if (empty($name) || empty($email) || empty($position) || empty($salary)) {
        echo "Please fill in all the fields.";
    } else {
        // Use prepared statements to prevent SQL injection
        $stmt = $con->prepare("INSERT INTO `crud` (name, email, position, salary) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $position, $salary);

        $result = $stmt->execute();
        if ($result) {
            // Redirect to display page on successful insertion with success parameter
            header('location:display.php');
            exit;
        } else {
            die("Error inserting data: " . $con->error);
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <title>CRUD System - Add New Employee</title>
    <link rel="stylesheet" href="./CSS/index.css">
    <script>
        // javascript Function that asks a user to confirm before submitting the form 
        function confirmSubmission(event) {
            // Show confirmation dialog
            let confirmation = confirm("Are you sure you want to add this new employee?");
            if (confirmation) {
                // If confirmed, show alert and submit the form
                alert("New user has been successfully added!");
                return true; // Allow form submission
            } else {
                // if the user cancels it prevents the form from being submitted
                event.preventDefault();
                return false;
            }
        }
    </script>
</head>
<!--this code is responsible for rendering an HTML form that allows users to input employee data (name, email, position, and salary) and submit it to the server for processing -->
<body class="updatepage">
    <div class="update">
        <h2>Add New Employee</h2>
        <form method="post" onsubmit="return confirmSubmission(event)"> <!--a javascript function that shows a confirmation alert asking the user if they are sure about submitting the form --> 
            <div class="form-group">
                <label>Name</label>
                <input type="text" class="form-control" placeholder="Enter full name" name="name" autocomplete="off" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" class="form-control" placeholder="Enter email" name="email" autocomplete="off" required>
            </div>
            <div class="form-group">
                <label>Position</label>
                <input type="text" class="form-control" placeholder="Enter position" name="position" autocomplete="off" required>
            </div>
            <div class="form-group">
                <label>Salary</label>
                <input type="number" class="form-control" placeholder="Enter salary" name="salary" autocomplete="off" required>
            </div>
            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
            <a href="display.php" class="btn btn-secondary">Back</a> 
        </form>
    </div>
</body>
</html>
