<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>

        img {
            width: 200px;
            height: auto;
        }
        h1{
            margin-top:3px;
            margin-left:250px;
            color: #1a9a1a;
            font-size:30px;
            font-weight: normal;
            text-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .container{
            background-color:white;
            border:2px solid green ;

            box-shadow: 0 0 10px rgba(0,0,0,0.3);
            width:800px;
            margin-left:300px;
        }
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


    </style>
</head>
<body>
<header class="header">
    <div class="nav-box">
        <img style="margin-left:-250px " src="img/DouBan.png" alt=""/>
        <ul class="nav">
            <li><a href="homepage_manager_all.php">首页</a></li>
            <li><a href="homepage_manager_film.php" >更新电影</a></li>
            <li><a href='homepage_manager_comment.php' >管理评论</a></li>
            <li><a href='homepage_manager_filmContent.php' class="on" onclick='windows.location.href="homepage_manager_filmContent.php"'>管理电影内容</a></li>
            <li><a href='homepage_manager_user.php' >管理用户</a></li>
        </ul>
    </div>
</header>
<div>
<?php
$conn = mysqli_connect("localhost", "root", "yang2004020015", "userinformation");
if ($conn->connect_error) {
    die("连接数据库失败: " . $conn->connect_error);
}

$filmname = isset($_GET['filmname']) ? $_GET['filmname'] : '';

$sql = "SELECT * FROM film WHERE filmname LIKE '%$filmname%'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $movieDetails = $result->fetch_assoc();
    ?>
    <img src="<?php echo $movieDetails['image']; ?>" alt="<?php echo $filmname; ?>" />
    <p>导演：<?php echo $movieDetails['editor']; ?></p>
    <p>主演：<?php echo $movieDetails['actor']; ?></p>
    <p>类型：<?php echo $movieDetails['kind']; ?></p>
    <p>语言：<?php echo $movieDetails['language']; ?></p>
    <p>上映日期：<?php echo $movieDetails['ReleaseDate']; ?></p>
    <p>片长：<?php echo $movieDetails['time']; ?></p>
    </div>
    <p style="color: green; font-size: 20px;">剧情简介------</p>
    <p><?php echo $movieDetails['introduce']; ?></p>

    <form method="post" action="<?php echo $_SERVER['PHP_SELF'] . '?filmname=' . $filmname; ?>">
        <textarea name="newIntroduce" rows="4" cols="50"></textarea>
        <input type="submit" value="更新电影介绍">
    </form>

    <?php
} else {
    echo "未找到该电影的详细信息";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newIntroduce = isset($_POST['newIntroduce']) ? $_POST['newIntroduce'] : '';
    $updateSql = "UPDATE film SET introduce='$newIntroduce' WHERE filmname LIKE '%$filmname%'";
    if ($conn->query($updateSql) === TRUE) {
        echo "电影介绍内容更新成功";
        // 更新成功后，刷新页面以显示更新后的电影介绍内容
        echo "<meta http-equiv='refresh' content='0'>";
    } else {
        echo "电影介绍内容更新失败: " . $conn->error;
    }
}

$conn->close();
?>
</div>
</body>
</html>