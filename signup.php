
<?php
// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connect to the MySQL database (adjust the connection details as needed)
    $mysqli = new mysqli("localhost", "username", "password", "user_accounts");

    // Check for database connection errors
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Get the form data from the POST request
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Prepare a SQL statement to insert a new user into the database
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $password);
    $stmt->execute();

    // Check if the user was successfully added
    if ($stmt->affected_rows == 1) {
        // User successfully registered, redirect to the login page
        header("Location: login.html?signup=success");
        exit();
    } else {
        // Error occurred while registering the user
        header("Location: signup.html?error=registration_failed");
        exit();
    }

    // Close statement and database connection
    $stmt->close();
    $mysqli->close();
} else {
    // Redirect back to the signup page if accessed directly
    header("Location: signup.html");
    exit();
}
?>
