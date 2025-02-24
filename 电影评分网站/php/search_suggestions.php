<?php
// Connect to your database
$conn = mysqli_connect("localhost", "root", "yang2004020015", "userinformation");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $sql = "SELECT filmname FROM film WHERE filmname LIKE '%$search%' LIMIT 10";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            echo "<p><a href='movie_introduce.php?filmname=" . urlencode($row["filmname"]) . "'>" . htmlspecialchars($row["filmname"]) . "</a></p>";
        }
    } else {
        echo "<p>No results found</p>";
    }
}

mysqli_close($conn);
?>