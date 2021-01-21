<?php
require 'config/config.php'; // Root URL for project
require 'config/db.php'; // SQL Server

session_start();

// Check for submit
if (isset($_POST['submit'])) {
    // Get data from form
    $title = strip_tags(mysqli_real_escape_string($connection, $_POST['title']));
    $author = strip_tags(mysqli_real_escape_string($connection, $_POST['author']));
    $body = strip_tags(mysqli_real_escape_string($connection, $_POST['body']));
    $updateId = mysqli_real_escape_string($connection, $_POST['updateId']);
    // Form Validation
    if (!empty($title) && !empty($author) && !empty($body)) {
        if(preg_match('/^[a-z0-9 .\-\&]+$/i', $title)){ // A to Z, 0 to 9, period, hypen
            if(preg_match('/^[a-z\-]+$/i', $author)){ // A to Z, hypen
                // SQL Query
                $query = "UPDATE posts SET title = '$title', author = '$author', body = '$body' WHERE id = $updateId";
                if (mysqli_query($connection, $query)) {
                    header('Location: ' . ROOT_URL . '');
                } else {
                    echo 'Error: ' . mysqli_error($connection);
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
// Get ID
$id = mysqli_real_escape_string($connection, $_GET['id']);
// Create Query
$query = 'SELECT * FROM posts WHERE id = ' . $id;
// Get result
$result = mysqli_query($connection, $query);
// Fech data
$post = mysqli_fetch_assoc($result);
// Free Result
mysqli_free_result($result);
// Close connection
mysqli_close($connection);
?>

<?php include 'inc/session.php'; ?>
<?php include 'inc/header.php'; ?>
<div class="container p-3 my-3">
    <a class="btn btn-warning" href="<?php echo ROOT_URL; ?>">Back</a>
    <h2 class="text-center">Edit Post</h2>
    <form method="POST" action="<?php $_SERVER['PHP_SELF']; ?>">
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" class="form-control" value="<?php echo $post['title']; ?>"> <!-- Auto Populates title -->
        </div>
        <div class="form-group">
            <label>Author</label>
            <input type="text" name="author" class="form-control" value="<?php echo $post['author']; ?>"> <!-- Auto Populates author -->
        </div>
        <div class="form-group">
            <label>Body</label>
            <textarea name="body" class="form-control"><?php echo $post['body']; ?></textarea> <!-- Auto Populates body -->
        </div>
        <input type="hidden" name="updateId" value="<?php echo $post['id']; ?>">
        <input class="btn btn-warning" type="submit" name="submit" value="submit">
    </form>
</div>
<?php include 'inc/footer.php'; ?>