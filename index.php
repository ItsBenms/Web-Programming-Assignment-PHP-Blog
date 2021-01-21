<?php
require 'config/config.php'; // Root URL for project
require 'config/db.php'; // SQL Server

session_start();

// Create Query
$query = 'SELECT * FROM posts ORDER BY created_at DESC'; // Order by newest posts
// Get result
$result = mysqli_query($connection, $query);
// Fech data
$posts = mysqli_fetch_all($result, MYSQLI_ASSOC);
// Free RESULT
mysqli_free_result($result);
// Close connection
mysqli_close($connection);
?>

<?php include 'inc/header.php'; ?>
<div class="container p-3 my-3">
    <h2 class="text-center">Read all about web programming using HTML, CSS and PHP</h2>
    <p>
        <?php
        if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
            echo "<a href='addpost.php' class='btn btn-warning'>Add Post</a>"; // Only Display if logged in
        }
        ?>
        <?php foreach ($posts as $post) : ?>
            <div>
                <!-- Content Populated -->
                <h4 class="text-center"><?php echo $post['title']; ?><h4>
                        <div class="text-center">
                            <small>Created on <?php echo $post['created_at']; ?> by
                                <?php echo $post['author']; ?></small>
                        </div>
                        <p>
                            <p><?php echo substr($post['body'], 0, 255); ?></p> <!-- Show first 255 characters -->
                            <div class="text-center">
                                <a class="footer-link-color" href="<?php echo ROOT_URL; ?>post.php?id=<?php echo $post['id']; ?>">Read More --></a>
                                <hr>
                            </div>
            </div>
            <br>
        <?php endforeach; ?>
</div>
<?php include 'inc/footer.php'; ?>