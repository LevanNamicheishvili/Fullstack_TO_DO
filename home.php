<?php
session_start();
include "db_conn.php";

// Check if the user is logged in, if not, redirect to the login page
if (!isset($_SESSION['id']) || !isset($_SESSION['user_name'])) {
    header("Location: index.php");
    exit();
}

// Create the 'tasks' table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    task_description TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
)";

if ($conn->query($sql) === TRUE) {
    // Table created successfully or already exists
} else {
    echo "Error creating table: " . $conn->error;
}

// Check if the user has submitted a new to-do item
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['task'])) {
    // Get the new task from the form
    $newTask = htmlspecialchars(trim($_POST['task']));

    // Add the new task to the 'tasks' table
    if (!empty($newTask)) {
        $userId = $_SESSION['id'];
        $sql = "INSERT INTO tasks (user_id, task_description) VALUES ('$userId', '$newTask')";
        if ($conn->query($sql) === TRUE) {
            // Task inserted successfully
        } else {
            echo "Error inserting task: " . $conn->error;
        }
    }
}

// Fetch the user's tasks from the 'tasks' table
$userId = $_SESSION['id'];
$sql = "SELECT * FROM tasks WHERE user_id='$userId'";
$result = $conn->query($sql);

// Initialize the to-do list array
$todoList = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $todoList[] = $row['task_description'];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>HOME</title>
    <link rel="stylesheet" type="text/css" href="style.css?v=<?php echo time(); ?>">
</head>
<body>
    <h1>Hello, <?php echo $_SESSION['user_name']; ?></h1>
    <a href="logout.php">Logout</a>

    <h2>To-Do List</h2>
    <ul>
        <?php 
        // Display the user's tasks with numbering
        $taskNumber = 1;
        foreach ($todoList as $task) {
            echo "<li>{$taskNumber}. {$task}</li>";
            $taskNumber++;
        }
        ?>
    </ul>

    <form action="home.php" method="post">
        <input type="text" name="task" placeholder="Enter new task" required>
        <button type="submit">Add Task</button>
    </form>
</body>
</html>
