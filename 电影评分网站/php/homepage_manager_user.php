<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
.header ul {
    list-style: none;
}

.header a {
    color: green;
    text-decoration: none;
}



.header {
    height: 80px;
    width: 105%;
    background: #eff5ef;
    border-bottom: 1px solid seashell;
}

.nav-box {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    height: 40px;
    padding-top: 20px;
    align-items: center;
    justify-content: center;
    font-size: 30px;
    color: #eff5ef;
}

.nav-box .nav {
    display: flex;
    font-size: 16px;
    color: #f5f8f5;

}
.nav-box .nav li{
    padding-left: 40px;
}
.nav-box .nav a.on,
.nav-box .nav a.on:hover{
    color: #20b420;
    padding-bottom: 10px;
    border-bottom: 2px solid #72b672;
}
.title1{
    font: 23px Helvetica,Arial,sans-serif;
    color:green;
    margin-left: 320px;
    margin-top: 30px;
    margin-bottom: 30px;
}
.comment-box{
    display: flex;
    margin-left:320px;
    flex-direction: column;
    align-items: flex-start;
    background-color: #e5ece5;
    border-radius: 5px;
    width:650px ;
    padding:  0 10px;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
    margin-top: 18px;
}
.comment-box:hover{
    box-shadow: 0 0 5px rgba(0,0,0,0.5);
}
.id-name{
    display: inline-block;
    margin-top: 15px;

}
.id-box,.name-box{
    display: inline;
    margin-left: 15px;
    font-weight: bold;
    font: 18px Helvetica,Arial,sans-serif;
}
.id-box{
    color: #3377AA;
}
.comments{
    margin-top: 20px;
    margin-left: 36px;
    font: 16px Helvetica,Arial,sans-serif;
    color: #5d5d5d;

}
.filmname-with-quote{
    margin-left: 460px;
    font:15px Helvetica,Arial,sans-serif ;
    color: #3f3f3f;
}
.filmname-with-quote:before {
    content: '《';
}

.filmname-with-quote:after {
    content: '》';
}
.submit_btn{
    margin-left: 550px;
}
table {
    width: 100%;
    border-collapse: collapse;
}

table, th, td {
    border: 1px solid black;
}

th, td {
    padding: 8px;
    text-align: left;
}

th {
    background-color: #f2f2f2;
}
    </style>
</head>
<body>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = mysqli_connect("localhost", "root", "yang2004020015", "userinformation");
    if ($conn->connect_error) {
        die("连接失败: " . $conn->connect_error);
    }

    $usernameToDelete = mysqli_real_escape_string($conn, $_POST["usernameToDelete"]);

    $sql = "DELETE FROM user WHERE name = '$usernameToDelete'";

    if (mysqli_query($conn, $sql)) {
        echo "用户删除成功";
    } else {
        echo "删除失败: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>
<header class="header">
    <div class="nav-box">
        <img style="margin-left:-250px " src="img/DouBan.png" alt=""/>
        <ul class="nav">
            <li><a href="homepage_manager_all.php">首页</a></li>
            <li><a href="homepage_manager_film.php" >更新电影</a></li>
            <li><a href="homepage_manager_comment.php">管理评论</a></li>
            <li><a href="homepage_manager_filmContent.php">管理电影内容</a></li>
            <li><a href='homepage_manager_user.php' class="on" onclick='windows.location.href="homepage_manager_user.php"'>管理用户</a></li>
        </ul>
    </div>
</header>
<?php
// Connect to the database
$conn = mysqli_connect("localhost", "root", "yang2004020015", "userinformation");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch all users
$sql = "SELECT name FROM user"; 
$result = mysqli_query($conn, $sql);

if ($result) {

    if (mysqli_num_rows($result) > 0) {
        echo "<table><tr><th>Username</th></tr>";
        while($row = mysqli_fetch_assoc($result)) {
            echo "<tr><td>" . htmlspecialchars($row["name"]) . "</td><td>"; 
        }
        echo "</table>";
    } else {
        echo "No users found";
    }
} else {
    echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
<form  method="post">
    <label for="usernameToDelete">输入想要删除的用户:</label>
    <input type="text" id="usernameToDelete" name="usernameToDelete" required>
    <button type="submit">删除用户</button>
</form>
</body>
</html>