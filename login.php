<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mysqli = new mysqli("localhost", "root", "Nisch@l1621", "user_accounts");

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    $username = $_POST["username"];
    $password = $_POST["password"];

    // Prepare a SQL statement to retrieve the user's information
    $sql = "SELECT id, username, password FROM users WHERE username = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $stored_password = $row["password"];

        // Verify the hashed password
        if (password_verify($password, $stored_password)) {
            $_SESSION["user_id"] = $row["id"];
            $_SESSION["username"] = $row["username"];

            header("Location: summarize.html");
            exit();
        } else {
            header("Location: login.html?error=invalid_credentials");
            exit();
        }
    } else {
        header("Location: login.html?error=invalid_credentials");
        exit();
    }

    $stmt->close();
    $mysqli->close();
} else {
    header("Location: login.html");
    exit();
}
?>
