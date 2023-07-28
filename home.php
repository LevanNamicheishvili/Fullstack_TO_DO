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
    $newTask = trim($_POST['task']);

    // Add the new task to the 'tasks' table using prepared statements
    if (!empty($newTask)) {
        $userId = $_SESSION['id'];
        $sql = "INSERT INTO tasks (user_id, task_description) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $userId, $newTask);

        if ($stmt->execute()) {
            // Task inserted successfully

            // Redirect to refresh the page and prevent re-submitting the form on refresh
            header("Location: home.php");
            exit();
        } else {
            echo "Error inserting task: " . $stmt->error;
        }
        $stmt->close();
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

            // Redirect to refresh the page after resetting
            header("Location: home.php");
            exit();
        } else {
            echo "Error resetting ID: " . $conn->error;
        }
    } else {
        echo "Error deleting tasks: " . $conn->error;
    }
}

// Check if the user wants to delete an individual task
if (isset($_POST['delete_task']) && isset($_POST['task_id'])) {
    $taskId = $_POST['task_id'];

    // Delete the task from the 'tasks' table using prepared statements
    $sql = "DELETE FROM tasks WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $taskId);

    if ($stmt->execute()) {
        // Task deleted successfully

        // Renumber the tasks and update IDs in the 'tasks' table
        $sql = "SET @count = 0";
        $conn->query($sql);
        $sql = "UPDATE tasks SET tasks.id = @count:= @count + 1";
        $conn->query($sql);
        $sql = "ALTER TABLE tasks AUTO_INCREMENT = 1";
        $conn->query($sql);

        // Redirect back to the 'home.php' page to update the task list
        header("Location: home.php");
        exit();
    } else {
        echo "Error deleting task: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch the user's tasks from the 'tasks' table using prepared statements
$userId = $_SESSION['id'];
$sql = "SELECT * FROM tasks WHERE user_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

// Initialize the to-do list array
$todoList = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $todoList[] = $row;
    }
} else {
    // If no tasks are present, reset the auto-increment ID to 1
    $sql = "ALTER TABLE tasks AUTO_INCREMENT = 1";
    if ($conn->query($sql) === FALSE) {
        echo "Error resetting ID: " . $conn->error;
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
            <!-- <img src="./images/hacker.png" alt="logo"> -->
            <p>Hello, <?php echo $_SESSION['user_name']; ?></p>
        </div>
        <span>To Do App</span>
        <div class="logoutdiv">
            <a href="logout.php">Logout</a>
        </div>
    </header>
    <main>
        <div class="leftside">
            <form action="home.php" method="post">
                <input type="text" name="task" placeholder="Enter new task" class="task_input" required>
                <button type="submit" class="submit_btn">Add Task</button>
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
    <div class="taskplace">
        <ul>
            <?php 
            // Display the user's tasks with numbering and delete buttons
            foreach ($todoList as $index => $task) {
                $taskNumber = $index + 1;
                echo "<li>{$taskNumber}. {$task['task_description']} 
                    <form action='home.php' method='post' style='display:inline;'>
                        <input type='hidden' name='task_id' value='{$task['id']}'>
                        <button type='submit' name='delete_task' class='delete-task'>Delete</button>
                    </form>
                </li>";
            }
            ?>
        </ul>
    </div>

    <!-- Link the JavaScript file -->
    <script src="./js/home.js"></script>
</body>
</html>
                