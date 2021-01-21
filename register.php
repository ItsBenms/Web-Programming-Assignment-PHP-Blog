<?php
require 'config/config.php'; // Root URL for project
require 'config/db.php'; // SQL Server

session_start();

// Check for submit
if (isset($_POST['submit'])) {
    // Get data from form
    $name = mysqli_real_escape_string($connection, $_POST['name']);
    $username = mysqli_real_escape_string($connection, $_POST['username']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($connection, $_POST['confirm_password']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    // Form Validation
    $name = ucfirst($name); //Upper Case first letter
    $name = rtrim($name, ' '); //Remove white space from right of name
    $password = str_replace(' ', '', $password); //Remove Spaces in Password
    $username = str_replace(' ', '', $username); //Remove Spaces in Username
    $username = ucfirst($username); //Upper Case first letter
    $username = rtrim($username, ' '); //Remove white space from right of username
    $email = filter_var($email, FILTER_SANITIZE_EMAIL); //Removes all illegal characters

    if (!empty($name) && !empty($username) && !empty($password)  && !empty($email)) {
        if ($password === $confirm_password) { // Check passwords match
            if (strlen($name) > 1 && strlen($name) < 101) {
                if (strlen($username) > 1 && strlen($username) < 101) {
                    if (strlen($password) > 7 && strlen($password) < 101) {
                        if (preg_match("#[A-Z]+#", $password)) { // Regex 1 upper case letter
                            $check = "SELECT * from users WHERE username='$username' LIMIT 1"; // Query to check username
                            $result = mysqli_query($connection, $check);
                            $userCheck = mysqli_fetch_assoc($result);
                            if ($userCheck['username'] === $username) { // Check if username alredy exists
                                echo "Username already exists";
                            } else {
                                // Ensure details are secured (hashing / encryption)
                                $encName = md5($name); // Hashed Name
                                $encEmail = md5($email); // Hashed Email
                                $pwd = md5($password); // Hash Password
                                // SQL Query
                                $query = "INSERT INTO users(name, username, password, email) VALUES('$encName', '$username', '$pwd', '$encEmail')";
                                if (mysqli_query($connection, $query)) {
                                    header('Location: login.php'); //Redirect to login
                                } else {
                                    echo 'Error: ' . mysqli_error($connection);
                                }
                            }
                        } else {
                            echo "Password needs to contain one capital letter";
                        }
                    } else {
                        echo "Password need to be between 8 and 100 characters";
                    }
                } else {
                    echo "Username needs to be between 1 and 100 characters";
                }
            } else {
                echo "Name needs to be between 1 and 100 characters";
            }
        } else {
            echo "Passwords don't match";
        }
    } else {
        echo "Please fill in all fields";
    }
}
mysqli_close($connection);
?>

<?php include 'inc/header.php'; ?>
<div class="container p-3 my-3">
    <h2 class="text-center">Register for an account</h2>
    <form method="POST" action="<?php $_SERVER['PHP_SELF']; ?>">
        <div class="form-group">
            <label>Name :</label>
            <input type="text" class="form-control" placeholder="Enter your name" name="name" value="<?php echo isset($_POST['name']) ? $name : ''; ?>" pattern="[a-zA-Z \-]{2,}" required> <!-- Sticky / Required -->
        </div>
        <div class="form-group">
            <label>Username :</label>
            <input type="text" class="form-control" placeholder="Enter username" name="username" value="<?php echo isset($_POST['username']) ? $username : ''; ?>" pattern="[a-zA-Z]{1,}" required> <!-- Sticky / Required -->
        </div>
        <div class="form-group">
            <label>Email :</label>
            <input type="email" class="form-control" placeholder="Enter email" name="email" value="<?php echo isset($_POST['email']) ? $email : ''; ?>" required> <!-- Sticky / Required -->
        </div>
        <div class="form-group">
            <label>Password :</label>
            <input type="password" class="form-control" placeholder="Enter password" name="password" value="<?php echo isset($_POST['password']) ? $password : ''; ?>" required> <!-- Sticky / Required -->
        </div>
        <div class="form-group">
            <label>Confirm Password :</label>
            <input type="password" class="form-control" placeholder="Confirm password" name="confirm_password" value="<?php echo isset($_POST['confirm_password']) ? $confirm_password : ''; ?>" required> <!-- Sticky / Required -->
        </div>
        <input type="submit" id="registerbtn" name="submit" value="submit" class="btn btn-warning"></input>
    </form>
    <div class="container p-3 my-3 text-center">
        <h5><strong>Registration Requirments</strong></h5>
        <p><strong>Name :</strong> Between 1 and 100 characters only using A to Z.</p>
        <p><strong>Username :</strong> Between 1 and 100 characters only using A to Z without spaces.</p>
        <p><strong>Password :</strong> Between 8 and 100 characters & must contain an uppercase character.</p>
    </div>
</div>
<?php include 'inc/footer.php'; ?>