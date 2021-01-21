<?php
require 'config/config.php'; // Root URL for project
require 'config/db.php'; // SQL Server

session_start();

if (isset($_POST['submit'])) {
    // Get data from form
    $username = mysqli_real_escape_string($connection, $_POST['username']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);
    // Ensure first char is uppercase
    $username = ucfirst($username);
    // SQL Query
    $query = "SELECT id FROM users  WHERE  password ='" . md5($password) . "' AND username = '$username'";
    $result = mysqli_query($connection, $query);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $active = $row['active'];

    $count = mysqli_num_rows($result);
    if ($count == 1) {
        $_SESSION['username'] = $username; // Store username in session
        header('Location: index.php'); // Redirect
    } else {
        echo "Your Login Name or Password is invalid";
    }
mysqli_close($connection); // Close server connection
}
?>

<?php include 'inc/header.php'; ?>
<div class="container p-3 my-3">
    <h2 class="text-center">Enter your details to login</h2>
    <form method="POST" action="<?php $_SERVER['PHP_SELF']; ?>">
        <div class="form-group">
            <label>Username :</label>
            <input type="text" class="form-control" id="username" placeholder="Enter username" name="username" value="<?php echo isset($_POST['username']) ? $username : ''; ?>" required> <!-- Sticky / Required -->
        </div>
        <div class="form-group">
            <label>Password :</label>
            <input type="password" class="form-control" id="password" placeholder="Enter password" name="password" required> <!-- Required -->
        </div>
        <button type="submit" id="loginbtn" name="submit" class="btn btn-warning">Login</button>
        <a href="register.php" class="btn btn-warning float-right">Register</a>
    </form>
</div>
<?php include 'inc/footer.php'; ?>