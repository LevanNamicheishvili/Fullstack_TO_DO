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

// Check if the user wants to delete all tasks
if (isset($_POST['delete_all'])) {
    // Delete all records from the 'tasks' table using TRUNCATE TABLE
    $sql = "TRUNCATE TABLE tasks";
    if ($conn->query($sql) === TRUE) {
        // Reset the auto-increment ID back to 1 using ALTER TABLE
        $sql = "ALTER TABLE tasks AUTO_INCREMENT = 1";
        if ($conn->query($sql) === TRUE) {
            // Auto-increment ID reset successfully
        } else {
            echo "Error resetting ID: " . $conn->error;
        }
    } else {
        echo "Error deleting tasks: " . $conn->error;
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
    <link rel="stylesheet" type="text/css" href="./styles/home.css?v=<?php echo time(); ?>">
</head>
<body>
    <header>
        <div class="logo"> 
            <img src="./images/hacker.png" alt="logo">
            <p>Hello, <?php echo $_SESSION['user_name']; ?></p>
        </div>
        <div class="logoutdiv">
            <a href="logout.php">Logout</a>
        </div>
    </header>
    <main>
        <div class="leftside">
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

            <!-- Separate form for the delete all button -->
            <form action="home.php" method="post">
                <input type="hidden" name="delete_all" value="1">
                <button class="delete_btn" type="submit">Delete All Tasks</button>
            </form>
        </div>

        <div class="rightside">
            <div class='container'>
                <div class='clock'>
                    <div class='date'></div>
                    <div class='hr'></div>
                    <div class='colon'>:</div>
                    <div class='min'></div>
                    <div class='colon'>:</div>
                    <div class='sec'></div>
                </div>
            </div>
        </div>
    </main>
    <!-- Link the JavaScript file -->
    <script src="./js/home.js"></script>
</body>
</html>

