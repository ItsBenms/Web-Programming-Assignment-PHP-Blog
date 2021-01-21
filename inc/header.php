<?php
require 'config/config.php'; // Root URL for project
require 'config/db.php'; // SQL Server

$output = ''; // Output variable to append

if (isset($_GET['q']) && $_GET['q'] !== '') {
    $searchq = strip_tags($_GET['q']); // Remove malicious tags from input
    // Search Query
    $query = mysqli_query($connection, "SELECT * FROM posts WHERE title LIKE '%$searchq%' OR body LIKE '%$searchq%' OR created_at LIKE '%$searchq%' OR author LIKE '%$searchq%' LIMIT 3");
    $result = mysqli_num_rows($query);
    if ($result == 0) {
        $output = 'No search results for <b>"' . $searchq . '"</b>';
    } else {
        while ($row = mysqli_fetch_array($query)) {
            // Gather rows to output
            $id = $row['id'];
            $title = $row['title'];
            $body = $row['body'];
            $author = $row['author'];
            // Store in output variable
            $output .= '<a class="footer-link-color" href="' . 'post.php?id=' . $id . '">
                        <h3 style="font-family: Roboto">' . $title . '</h3>
                        <p style="font-family: Roboto">' . $body . '</p>
                        <small style="font-family: Roboto">' . $author . '</small>
                        </a><p><hr>';
        }
    }
}
mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css\style.css">
    <link rel="stylesheet" href="css\bootstrap.min.css"> <!-- Bootstrap for CSS -->
    <link rel="stylesheet" href="css\font-awesome.min.css">
    <link rel="stylesheet" href="css\roboto.css">
    <script src="js\jquery-3.5.1.min.js"></script>
    <script src="js\bootstrap.min.js"></script>
    <title>Web Programming 101</title>
</head>

<body class="d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-md navbar-dark bg-dark mb-3">
        <div class="container-fluid">
            <a href="index.php" class="navbar-brand mr-3 nav-link-color">Web Programming 101</a>
            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#collapsibleNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="collapsibleNavbar">
                <div class="navbar-nav ml-auto">
                    <a href="about.php" class="nav-item nav-link nav-link-color">About</a>
                    <a href="index.php" class="nav-item nav-link nav-link-color">Blog</a>
                    <a href="register.php" class="nav-item nav-link nav-link-color">Register</a>
                    <?php
                    session_start();
                    if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
                        echo "<a href='logout.php' class='nav-item nav-link nav-link-color'>Logout</a>"; // Displays based on login / logout
                    } else {
                        echo "<a href='login.php' class='nav-item nav-link nav-link-color'>Login</a>"; // Displays based on login / logout
                    }
                    ?>
                    <form action="<?php $_SERVER['PHP_SELF']; ?>" method="get" class="form-inline">

                        <input name="q" type="text" size="30" class="form-control bg-dark search-query" placeholder="Type to search ..."/>

                        <!-- <input type="submit" value="Search" class="form-control bg-dark search-query" />-->

                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="container text-center">
        <?php
        if ($output != '') {
            echo "<h2>Search Results ...</h2><br>"; // Output if search contains results
        }
        ?>
        <?php echo $output; ?> <!-- Displays search results -->
    </div>