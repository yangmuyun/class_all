<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="CSS/homepage_manger_all.css">
</head>
<body>
<div id="user-info">
    <div class="header" style="display: flex; justify-content: flex-end;">
        <?php
            if(isset($_COOKIE["username"])) {
                echo "Welcome " . $_COOKIE["username"];
            } else {
                echo "Welcome guest";
            }
        ?>
    </div>
        <a href="exitLogin.php" style="margin-right: 10px;text-decoration: none">退出登录<a>               
</div>
    <center><form style="font-size:30px;font-family:FZShuTi;color:lightblue;">管理员界面</form></center><br>
    <form class="btn-6">
    <center><a href='homepage_manager_film.php' onclick='windows.location.href=\"homepage_manager_film.php"'>更新电影</a></center>
    </form>

    <form class="btn-6">
    <center><a href='homepage_manager_comment.php' onclick='windows.location.href=\"homepage_manager_comment.php"' >管理评论</a></center>
    </form>

    <form class="btn-6">
    <center><a href='homepage_manager_filmContent.php' onclick='windows.location.href=\"homepage_manager_filmContent.php"' >管理电影内容</a></center>
    </form>

    <form class="btn-6">
    <center><a href='homepage_manager_user.php' onclick='windows.location.href=\"homepage_manager_user.php"' >管理用户</a></center>
    </form>
</body>
</html>