<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        
        img{
            width: 120px;
            height: 250px;
        }
        .left_content{
            margin-left: 240px;
            width: 80%;
        }
        .hot{
    font: 16px Helvetica,Arial,sans-serif;
    display: flex;
    border-bottom:1px solid #dbd8d8;
}
.hot li{
    list-style: none;
    float: left;
    margin-top: -10px;
    margin-right: 14px;
}
.hot a{
    margin-left: 10px;
    font: 13px Tahoma;
    text-decoration: none;
    color: #9b9b9b;
}
.tag_list{
    margin-left: -20px;
}
.hot .tag_list a.on,
.hot .tag_list a.on:hover{
    color: #111;
}
.tag_list_more{
    margin-left: 35px;
}

.hot .tag_list_more a{
    font: 13px Helvetica,Arial,sans-serif;
    color: #3377AA;
}
.hot_tv{
    margin-top: 40px;
}
.hot_list ul{
    margin-top: -10px;
}
.hot_list li{
    list-style: none;
    padding-bottom: 15px;
    width: 288px;

}
.hot_list .title{
    margin-left: -35px;
    padding-bottom: 20px;
    border-bottom:1px solid #dbd8d8;
}
.hot_list .title a{
    font: 14px Helvetica,Arial,sans-serif;
    color: #3377AA;
    padding: 0 5px;
    text-decoration: none;
}
.hot_list .title span{
    color: #9b9b9b;
    font: 13px Tahoma;
    float: right;
}
.contact_and_cooperation li{
    list-style: none;
    margin-left: -30px;
}
.image-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}

.photo {
    width: calc(25% - 10px);
    margin-bottom: 10px;
}

@media (max-width: 768px) {
    .photo {
        width: calc(50% - 10px);
    }
}

@media (max-width: 480px) {
    .photo {
        width: 100%;
    }
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
        <ul class="nav">
            <li><a href="homepage_manager_all.php">首页</a></li>
            <li><a href="homepage_manager_film.php" >更新电影</a></li>
            <li><a href='homepage_manager_comment.php' >管理评论</a></li>
            <li><a href='homepage_manager_filmContent.php' class="on" onclick='windows.location.href="homepage_manager_filmContent.php"'>管理电影内容</a></li>
            <li><a href='homepage_manager_user.php' >管理用户</a></li>
        </ul>
    </div>
</header> 
    <div class="left_content">
        
            <div>请选择要修改内容的电影：</div>
                <div class="movie">
                    <?php
                        // 连接到数据库
                        $conn = mysqli_connect("localhost", "root", "yang2004020015", "userinformation");

                        if (!$conn) {
                            die("连接数据库出错: " . mysqli_connect_error());
                        }
                    // 从数据库中获取图片路径
                        $sql = "SELECT filmname, image FROM film"; // 查询所有电影名称和图片路径
                        $result = mysqli_query($conn, $sql);

                        if ($result) {
                            if (mysqli_num_rows($result) > 0) {
                            // 保存查询结果到 $movies 数组
                                $movies = mysqli_fetch_all($result, MYSQLI_ASSOC);
                            // 输出数据
                                $counter = 0; // 计数器
                                echo "<div class='image-container'>"; // 开始一个图片容器
                                foreach ($movies as $movie) {
                                    $filmname = $movie['filmname'];
                                    $image = $movie['image'];
                    ?> 
                    <a href="manager_movie_introduce.php?filmname=<?php echo urlencode($filmname); ?>">
                    <img style="width:180px;" class="photo" src="<?php echo $image; ?>" alt="<?php echo $filmname; ?>" /><br>
                    <span class="mv_name"><?php echo $filmname; }}}?></span>
                    </a>
                </div>
        
    </div>
</div>
</body>
</html>