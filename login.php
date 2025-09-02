<?php
session_start();
include "database.php";

if (isset($_POST["login"])) {
  $email = $_POST["email"];
  $password = $_POST["password"];

  $sqlEmail = "SELECT * FROM user WHERE email='$email'";
  $resEmail = mysqli_query($conn, $sqlEmail);

  if (mysqli_num_rows($resEmail) <= 0) {
    $_SESSION['email'] = "User Does Not Exists. Please Signup First...";
    header("Location: signup.php");
    exit();
  }

  $sqlEmail = "SELECT * FROM user WHERE email='$email' AND password='$password'";
  $resEmail = mysqli_query($conn, $sqlEmail);

  if (mysqli_num_rows($resEmail) > 0) {
    $_SESSION['email'] = $email;
    $_SESSION['msg'] = "Login Successfull...";
    header("Location: index.php");
    exit();
  } else {
    $_SESSION['email'] = "Incorrect Password...";
    header("Location: login.php");
    exit();
  }
}

if (isset($_SESSION['email'])) {
  $msg = $_SESSION['email'];
  echo "<script>alert('$msg');</script>";
  unset($_SESSION['email']);
}

if (isset($_SESSION['username'])) {
  $msg = $_SESSION['username'];
  echo "<script>alert('$msg');</script>";
  unset($_SESSION['username']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>

<body class="min-h-screen flex items-center justify-center bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 p-4">

  <!-- Login Card -->
  <div class="bg-white shadow-2xl rounded-2xl p-8 w-full max-w-md">
    <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">Welcome Back</h1>

    <form action="login.php" method="POST" class="space-y-5">
      <!-- Email -->
      <div>
        <label for="email" class="block text-gray-700 font-medium mb-1">Email</label>
        <div class="flex items-center border border-gray-300 rounded-lg px-3 py-2 focus-within:border-indigo-500">
          <i class='bx bx-user text-gray-400 text-xl mr-2'></i>
          <input type="text" id="email" name="email" required placeholder="Enter your email"
            class="w-full outline-none text-gray-700">
        </div>
      </div>

      <!-- Password -->
      <div>
        <label for="password" class="block text-gray-700 font-medium mb-1">Password</label>
        <div class="flex items-center border border-gray-300 rounded-lg px-3 py-2 focus-within:border-indigo-500">
          <i class='bx bx-lock-alt text-gray-400 text-xl mr-2'></i>
          <input type="password" id="password" name="password" required placeholder="Enter your password"
            class="w-full outline-none text-gray-700">
        </div>
      </div>

      <!-- Submit Button -->
      <button name="login" type="submit"
        class="w-full bg-indigo-600 hover:bg-indigo-700 transition duration-300 text-white font-semibold py-3 rounded-lg shadow-lg">
        Login
      </button>
    </form>

    <!-- Divider -->
    <div class="flex items-center my-6">
      <hr class="flex-grow border-gray-300">
      <span class="mx-2 text-gray-400">or</span>
      <hr class="flex-grow border-gray-300">
    </div>


    <!-- Sign Up Link -->
    <p class="text-center text-gray-600 mt-6">Don’t have an account?
      <a href="signup.php" class="text-indigo-600 font-medium hover:underline">Sign up</a>
    </p>
  </div>

</body>

</html>