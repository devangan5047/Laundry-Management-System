<?php
// This must be the very first line in your script
session_start();

// 1. Include the database connection
require_once 'db.php';

// 2. Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 3. Get username and password from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // 4. Basic validation
    if (empty($username) || empty($password)) {
        // Redirect back with an error
        header("Location: login.php?error=emptyfields");
        exit();
    }

    // 5. Prepare a statement to prevent SQL injection
    $sql = "SELECT id, username, password FROM admins WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // 6. Check if a user was found
    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();

        // 7. Verify the password
        // IMPORTANT: We are doing a simple check for now.
        // We will replace this with password_verify() in a future step.
        if ($password === $admin['password']) {
            
            // 8. Password is correct, start the session
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];

            // 9. Redirect to the admin dashboard
            header("Location: dashboard.php");
            exit();

        } else {
            // Incorrect password
            header("Location: login.php?error=invalidcred");
            exit();
        }
    } else {
        // No user found with that username
        header("Location: login.php?error=invalidcred");
        exit();
    }

    $stmt->close();
    $conn->close();

} else {
    // If someone tries to access this file directly, redirect them
    header("Location: login.php");
    exit();
}
?>