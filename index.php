<?php
session_start();
include 'database.php';

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
}

if (isset($_SESSION['msg'])) {
    $msg = $_SESSION['msg'];
    echo "<script>alert('$msg');</script>";
    unset($_SESSION['msg']);
}

$email = $_SESSION['email'];

// Add Task
if (isset($_POST['add']) && !$_GET['update']) {
    $task = $_POST['task'];
    $due_date = $_POST['due_date'];

    $sql = "INSERT INTO alltodo (email, task, date) VALUES ('$email', '$task', '$due_date')";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['msg'] = "Task added successfully!";
    } else {
        $_SESSION['msg'] = "Error adding task: " . mysqli_error($conn);
    }
    header("Location: index.php");
    exit();
}

// Show Data To Update
if (isset($_GET['update'])) {
    $id = $_GET['update'];
    $sql = "SELECT * FROM alltodo WHERE id='$id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
}

// Update Task
if (isset($_POST['add']) && isset($_GET['update'])) {
    $id = $_GET['update'];
    $task = $_POST['task'];
    $due_date = $_POST['due_date'];

    $sql = "UPDATE alltodo SET task='$task', date='$due_date' WHERE id='$id'";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['msg'] = "Task updated successfully!";
    } else {
        $_SESSION['msg'] = "Error updating task: " . mysqli_error($conn);
    }
    header("Location: index.php");
    exit();
}

// Delete Task
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM alltodo WHERE id='$id'";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['msg'] = "Task deleted successfully!";
    } else {
        $_SESSION['msg'] = "Error deleting task: " . mysqli_error($conn);
    }
    header("Location: index.php");
    exit();
}

$sql = "SELECT * FROM alltodo WHERE email='$email' ORDER BY date DESC";
$result = mysqli_query($conn, $sql);
?>


<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student To-Do App</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex flex-col">
    <!-- Navbar -->
    <header class="bg-gradient-to-r from-blue-600 to-indigo-700 shadow-lg">
        <div class="max-w-6xl mx-auto flex justify-between items-center p-4">
            <h1 class="text-2xl md:text-3xl font-bold text-white">📋 Student To-Do</h1>
            <a href="logout.php"
                class="px-5 py-2 rounded-lg bg-white text-blue-600 font-medium hover:bg-gray-200 transition">
                Logout
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
        <!-- Add Task Form -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-8">
            <form method="post"
                class="flex flex-col md:flex-row gap-4 items-center justify-between">
                <input type="text"
                    value="<?php echo isset($row['task']) ? $row['task'] : ''; ?>"
                    name="task"
                    class="flex-1 w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none"
                    placeholder="✍️ Enter new task"
                    required>

                <input type="date"
                    value="<?php echo isset($row['date']) ? $row['date'] : ''; ?>"
                    name="due_date"
                    class="p-3 w-full md:w-1/3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none cursor-pointer"
                    required>

                <button type="submit" name="add"
                    class="px-6 py-3 w-full md:w-auto rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold transition">
                    <?php echo isset($_GET['update']) ? '🔄 Update Task' : '➕ Add Task'; ?>
                </button>
            </form>
        </div>

        <!-- Task Feed (Responsive Grid) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mx-auto w-full pb-12">
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <?php
                $today = date("Y-m-d");
                $due = $row['date'];
                $statusClass = ($due < $today) ? "text-red-600 font-bold mt-2" : "mt-2 text-green-600 font-semibold";
                ?>
                <div class="bg-white max-h-[300px] flex flex-col justify-between rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                    <!-- Header -->
                    <div class="p-4">
                        <h2 class="font-bold text-lg text-gray-800 break-words">
                            <?php echo $row['task']; ?>
                        </h2>
                        <p class="text-sm text-gray-500 mt-1">
                            📅 Due: <span class="<?php echo $statusClass; ?>"><?php echo $row['date']; ?></span>
                        </p>
                    </div>

                    <!-- Action Buttons (like IG footer icons) -->
                    <div class="flex justify-around border-t border-gray-200 p-3 text-sm font-medium">
                        <a href="?update=<?php echo $row['id']; ?>"
                            class="flex-1 text-center py-2 rounded-lg hover:bg-blue-50 text-blue-600 font-semibold transition">
                            ✏️ Update
                        </a>
                        <a href="?delete=<?php echo $row['id']; ?>"
                            onclick="return confirm('Are you sure you want to delete this task?');"
                            class="flex-1 text-center py-2 rounded-lg hover:bg-red-50 text-red-600 font-semibold transition">
                            🗑️ Delete
                        </a>
                    </div>
                </div>
            <?php } ?>
        </div>

    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-center py-4 mt-auto">
        <p class="text-gray-300 text-sm">©2025 Hrm Pvt Ltd. All Rights Reserved.</p>
    </footer>
</body>

</html>