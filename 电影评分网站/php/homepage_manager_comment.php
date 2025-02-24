<?php
$conn = mysqli_connect('localhost', 'root', 'yang2004020015', 'userinformation');
$query = "SELECT * FROM comment";
$result = mysqli_query($conn, $query);
$comments = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $ID = $_POST['ID'] ?? "";
    if ($ID) {
        // 检查数据库连接是否成功
        if ($conn->connect_error) {
            die("数据库连接失败：" . $conn->connect_error);
        }
    
        $tableName = "comment"; // 数据库表名
    
        // 构建 SQL 删除语句
        $sql = "DELETE FROM $tableName WHERE ID = $ID";
    
        // 执行删除操作
        if ($conn->query($sql) === TRUE) {
            echo "<p style='margin-left: 590px;color: #3f3f3f;font-size: 15px'>成功删除记录</p>";
            header("Location: ".$_SERVER['PHP_SELF']);
        } else {
            echo "<p style='margin-left: 590px;color: #3f3f3f;font-size: 15px'>删除记录时出错：</p>" . $conn->error;
        }
    
        // 关闭数据库连接
        $conn->close();
    } else {
        echo "<p style='margin-left: 590px;color: #3f3f3f;font-size: 15px'>未提供有效的ID</p>";
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="CSS/homepage_manager_comment.css">
</head>
<body>
<header class="header">
    <div class="nav-box">
        <img style="margin-left:-250px " src="img/DouBan.png" alt=""/>
        <ul class="nav">
            <li><a href="homepage_manager_all.php">首页</a></li>
            <li><a href="homepage_manager_film.php" >更新电影</a></li>
            <li><a href='homepage_manager_comment.php' class="on" onclick='windows.location.href="user_comment.php"'>管理评论</a></li>
            <li><a href="homepage_manager_filmContent.php">管理电影内容</a></li>
            <li><a href='homepage_manager_user.php' >管理用户</a></li>
        </ul>
    </div>
</header>
<p class="title1">评论详情 · · · · · ·</p>
<?php

    
    // 显示评论列表

    foreach ($comments as $comment) {
        echo "<div class='comment-box'>";
        echo "<div class='id-name'>";
        echo "<p class='id-box'>{$comment['ID']}</p>";
        echo "<p class='name-box'>{$comment['username']}</p>";
        echo "</div>";
        echo "<p class='comments'>{$comment['comment']}</p>";
        echo "<p class='filmname-with-quote'>{$comment['filmname']}</p>";
        // echo "<a href='homepage_manager_approve.php?id={$comment['ID']}'>Approve</a>";
        // echo "<a href='homepage_manager_delete.php?id={$comment['ID']}'>Delete</a>";
        echo "</div>";
    }
?>


<form method="POST">
    <div class="submit_btn">
        <input type="text" name="ID" placeholder="要删除的评论ID">
        <input type="submit" value="删除">
    </div>
</form>
</body>
</html>