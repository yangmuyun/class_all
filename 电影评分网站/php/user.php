<?php
    // Connect to your database
    $conn = mysqli_connect("localhost", "root", "yang2004020015", "userinformation");
    if ($conn->connect_error) {
        die("连接数据库失败: " . $conn->connect_error);
    }

    $username = isset($_COOKIE["username"]) ? $_COOKIE["username"] : '';

    $stmt = $conn->prepare("SELECT comment, time,filmname FROM comment WHERE username = ? ORDER BY time DESC LIMIT 2");
    if ($stmt === false) {
        die("Error preparing the statement: " . $conn->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    $comments = array();
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $comments[] = $row;
        }
    }

    mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>个人主页</title>
    <link rel="stylesheet" href="CSS/user.css">
    <style>
        .user-comment {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
<div class="top_list1">
    <ul>
        <li><a href="">豆瓣</a></li>
        <li><a href="">读书</a></li>
        <li><a href="">电影</a></li>
        <li><a href="">音乐</a></li>
        <li><a href="">同城</a></li>
        <li><a href="">小组</a></li>
        <li><a href="">阅读</a></li>
        <li><a href="">FM</a></li>
        <li><a href="">时间</a></li>
        <li><a href="">豆品</a></li>
    </ul>
    <ul style="list-style: none;float: right;margin-top:0;margin-right: 25px;">
        <li><a href="">下载豆瓣客户端</a></li>
        <li><a href="homepage_user.php" style="font-size:13px">返回</a></li>
        <?php
            if(isset($_COOKIE["username"])) {
                echo "<li><a href='exitLogin.php'>退出登录</a></li>";
               ?><a id="user-info">
               <li><a href="user.php"><?php echo "Welcome " . $_COOKIE["username"];?></a></li></a><?php
                
            } else {
                echo "<li><a href='Login2.php'>登录/注册</a></li>";
                ?><a id="user-info">
                <li><?php echo "Welcome guest";?></li></a><?php
                
            }
        ?>
    </ul>
</div>
<header class="header">
    <div class="nav-box">
        <img style="margin-left:-250px " src="img/DouBan.png" alt=""/>
        <ul class="nav">
            <li><a href="homepage_user.php">首页</a></li>
            <li><a href="user.php" class="on">个人中心</a></li>
            <li><a href='user_comment.php' onclick='windows.location.href="user_comment.php"'>个人评论</a></li>
            <li><a href="user_username.php">账号管理</a></li>
        </ul>
    </div>
</header>
<div class="banner">
    <p style="background-image: url('film/banner.bmp'); background-repeat: no-repeat; background-position: center center;">欢迎来到我的主页</p>
</div>
<div class="introduce">
    <div class="introduce-main">
        <div class="l">
            <img class="img-box" src="film/user.png" >
        </div>
        <div class="r">
            <?php if(isset($_COOKIE["username"])) {
                 echo "<p>" . $_COOKIE['username'] . "</p>";
            }else{
                echo "<p>guest</p>";
            }
                
            ?>
            
            
        </div>

    </div>

    <div>

    <p class="title1">我的最新评论 · · · · · ·</p>
        <?php foreach ($comments as $comment): ?>
            <div class="user-comment">
                <p style="color:green;">你评论了《<?php echo htmlspecialchars($comment['filmname']); ?>》</p>
                <p><?php echo htmlspecialchars($comment['comment']); ?></p>
                <p style="color:#494949;font-weight:12px"><?php echo htmlspecialchars($comment['time']); ?></p>
            </div>
        <?php endforeach; ?>
    </div>


</body>
</html>