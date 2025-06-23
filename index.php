<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./CSS/index.css">

    
</head>
<body>

<?php 
$db_username = "root";
$db_password = "";
$hostname = "localhost";
$database = "newcrud_db";


$conn = new mysqli($hostname, $db_username, $db_password, $database);
if ($conn->connect_error) {
    die("<p>Failed to connect to the database: " . $conn->connect_error . "</p>");
}

// Function to sanitize user input
function sanitizer($text) {
    $text = htmlspecialchars($text); // Convert special characters to HTML entities
    $text = trim($text); // Remove whitespace from the beginning and end
    $text = stripslashes($text); // Remove backslashes from the input
    return $text;
}

// Validate input length
function validate($text) {
    return strlen($text) >= 5; // Check if length is 5 or more
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get and sanitize the data
    $username = sanitizer($_POST['username']);
    $password = sanitizer($_POST['password']);

    // Validate username and password
    if (!validate($username) || !validate($password)) {
        $message = "Username and password must be at least 5 characters long.";
    } else {
        // Prepare the SQL statement
        $sql = "SELECT username FROM users WHERE username = ? AND password = ?";
        $statement = $conn->prepare($sql);

        if ($statement) {
            // Bind parameters
            $statement->bind_param("ss", $username, $password);
            // Execute the statement
            $status = $statement->execute();
            // Bind results
            $statement->bind_result($db_username);

            // Check if the user exists
            if ($statement->fetch()) {
                $_SESSION['username'] = $db_username; // Fix the typo in the session variable
                header("Location: ./display.php"); // Redirect to home.php
                exit; // Ensure no further code is executed after redirect
            } else {
                $message = "Wrong username or password";
            }
            // Close the statement
            $statement->close();
        } else {
            $message = "Database error: " . $conn->error;
        }
    }
}

// Close the connection
$conn->close();
?>

<div class="h1"><h1>Employee Management System</h1></div>

<div class='main'>
<div class="container">
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
        <p style="color: red"><?php if (isset($message)) { echo $message; } ?></p>
        <label for="username">Username</label><br>
        <input type="text" name="username" id="username" required><br>
        <label for="password">Password</label><br>
        <input type="password" name="password" id="password" required><br>
        <button type="submit">Login</button>
    </form>
</div>
</div>

</body>
</html>
