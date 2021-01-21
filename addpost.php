<?php 
    require 'config/config.php'; // Root URL for project
    require 'config/db.php'; // SQL Server

    session_start();

    // Check for submit
    if(isset($_POST['submit'])){
        // Get data from form
        $title = strip_tags(mysqli_real_escape_string($connection, $_POST['title'])); // Prevent Malicious Inputs
        $author = strip_tags(mysqli_real_escape_string($connection, $_POST['author'])); // Prevent Malicious Inputs
        $body = strip_tags(mysqli_real_escape_string($connection, $_POST['body'])); // Prevent Malicious Inputs
        // Form Validation
        if(!empty($title) && !empty($author) && !empty($body)){
            if(preg_match('/^[a-z0-9 .\-\&]+$/i', $title)){ // A to Z, 0 to 9, period, hypen 
                if(preg_match('/^[a-z\-]+$/i', $author)){ // A to Z, hypen
                    // From Submited
                    // SQL Query
                    $query = "INSERT INTO posts(title, author, body) VALUES('$title', '$author', '$body')";
                    if(mysqli_query($connection, $query)){
                        header('Location: '.ROOT_URL.'');
                    } else {
                        echo 'Error: '.mysqli_error($connection);
                    }
                } else {
                    echo "Author - Please only use A to Z and hyphens";
                }
            } else {
                echo "Title - Please only use A to Z, 0 to 9, ampersand and hyphens";
            }
        } else {
            echo "Please fill in all fields";
        }
    }
    mysqli_close($connection);
?>

<?php include 'inc/session.php'; ?>
<?php include 'inc/header.php'; ?>
<div class="container p-3 my-3">
    <a class="btn btn-warning" href="<?php echo ROOT_URL; ?>">Back</a>
    <h2 class="text-center">Add Post</h2>
    <form method="POST" action="<?php $_SERVER['PHP_SELF']; ?>">
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" value="<?php echo isset($_POST['title']) ? $title : ''; ?>" class="form-control"> <!-- Sticky form -->
        </div>
        <div class="form-group">
            <label>Author</label>
            <input type="text" name="author" class="form-control" value="<?php echo $_SESSION['username']; ?>"> <!-- Auto Populate based on session username -->
        </div>
        <div class="form-group">
            <label>Body</label>
            <textarea name="body" value="<?php echo isset($_POST['body']) ? $body : ''; ?>" class="form-control" ></textarea> <!-- Sticky form -->
        </div>
        <input class="btn btn-warning" type="submit" name="submit" value="submit">
    </form>  
</div>
<?php include 'inc/footer.php'; ?>