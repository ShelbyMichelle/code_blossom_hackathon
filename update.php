<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
}

// Get the 'id' from the URL 
$id = isset($_GET['updateid']) ? intval($_GET['updateid']) : 0;

// Fetch existing data to populate the form fields.
if ($id > 0) {
    $sql = "SELECT * FROM `crud` WHERE id=$id";
    $result = mysqli_query($con, $sql);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $name = $row['name'];
        $email = $row['email'];
        $position = $row['position'];
        $salary = $row['salary'];
    } else {
        die(mysqli_error($con));
    }
}

// Check if the form is submitted.
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $position = $_POST['position'];
    $salary = $_POST['salary'];

    // Update the record with prepared statements to avoid SQL injection.
    $stmt = $con->prepare("UPDATE `crud` SET name=?, email=?, position=?, salary=? WHERE id=?");
    $stmt->bind_param("ssssi", $name, $email, $position, $salary, $id);
    $result = $stmt->execute();

    if ($result) {
        header('location:display.php');
    } else {
        die("Update failed: " . $con->error);
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
    <title>CRUD System - Update Record</title>
    <link rel="stylesheet" href="./CSS/index.css">
    <script>
        // Function to show a confirmation alert before form submission
        function confirmUpdate(event) {
            // Show confirmation dialog
            let confirmation = confirm("Are you sure you want to update this record?");
            if (confirmation) {
                // If confirmed, show success message and proceed
                alert("Record has been successfully updated!");
                return true; // Allow form submission
            } else {
                // Prevent form submission if not confirmed
                event.preventDefault();
                return false;
            }
        }
    </script>
</head>
<body class="updatepage">
    <div class="update">
        <h2>Update Record</h2>
        <form method="post" onsubmit="return confirmUpdate(event)">
            <div class="form-group">
                <label>Name</label>
                <input type="text" class="form-control" name="name" value="<?php echo $name; ?>" autocomplete="off" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" class="form-control" name="email" value="<?php echo $email; ?>" autocomplete="off" required>
            </div>
            <div class="form-group">
                <label>Position</label>
                <input type="text" class="form-control" name="position" value="<?php echo $position; ?>" autocomplete="off" required>
            </div>
            <div class="form-group">
                <label>Salary</label>
                <input type="text" class="form-control" name="salary" value="<?php echo $salary; ?>" autocomplete="off" required>
            </div>
            <button type="submit" class="btn btn-primary" name="submit">Update</button>
            <a href="display.php" class="btn btn-secondary">Back</a>
        </form>
    </div>
</body>
</html>
