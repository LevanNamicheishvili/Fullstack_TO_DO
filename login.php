<?php 
session_start(); 
include "db_conn.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['uname'], $_POST['password'])) {
    $uname = validate($_POST['uname']);
    $pass = validate($_POST['password']);

    if (empty($uname) || empty($pass)) {
        redirectWithMessage("index.php", "User Name and Password are required");
    }

    $sql = "SELECT * FROM users WHERE user_name='$uname' AND password='$pass'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['user_name'] = $row['user_name'];
        $_SESSION['name'] = $row['name'];
        $_SESSION['id'] = $row['id'];
        redirectWithMessage("home.php", "Login successful");
    } else {
        redirectWithMessage("index.php", "Incorrect User name or password");
    }
} else {
    header("Location: index.php");
    exit();
}

function validate($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function redirectWithMessage($location, $message) {
    header("Location: $location?error=" . urlencode($message));
    exit();
}
?>
