<?php
require 'config/config.php'; // Root URL for project
require 'config/db.php'; // SQL Server

session_start();

// Check for submit
if (isset($_POST['delete'])) {
    // Get data from form
    $deleteId = mysqli_real_escape_string($connection, $_POST['deleteId']);
    // SQL Query
    $query = "DELETE FROM posts WHERE id = $deleteId";
    if (mysqli_query($connection, $query)) {
        header('Location: ' . ROOT_URL . '');
    } else {
        echo 'Error: ' . mysqli_error($connection);
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
// fREE RESULT
mysqli_free_result($result);
// Close connection
mysqli_close($connection);
?>

<?php include 'inc/header.php'; ?>
<div class="container p-3 my-3">
    <a class="btn btn-warning mb-2" href="<?php echo ROOT_URL; ?>">Back</a>
    <?php
    if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
        if($_SESSION['username'] == $post['author']) { // Check session is == author of post
            echo '<a class="float-right btn btn-warning mb-2" href="' . 'editpost.php?id=' . $id . '">Edit</a>'; // Only display when logged in
            echo '<form class="float-right mr-2 mb-2" method="POST" action="' . $_SERVER['PHP_SELF'] . '"> 
                <input type="hidden" name="deleteId" value="' . $id . '">
                <input class="btn btn-warning" type="submit" name="delete" value="Delete">
                </form>'; // Only display when logged in
        }
    }
    ?>
    <!-- Content Populated -->
    <h2 class="text-center"><?php echo $post['title']; ?></h2>
    <div>
        <small>Created on <?php echo $post['created_at']; ?> by
            <?php echo $post['author']; ?></small>
        <p><?php echo $post['body']; ?></p>
    </div>
    <br>
</div>
<?php include 'inc/footer.php'; ?>