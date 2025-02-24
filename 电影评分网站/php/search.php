<?php
    $conn = mysqli_connect("localhost", "root", "yang2004020015", "userinformation");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if (isset($_GET['search'])) {
        $search = mysqli_real_escape_string($conn, $_GET['search']);
        $sql = "SELECT filmname, image FROM film WHERE filmname LIKE '%$search%'";

        $result = mysqli_query($conn, $sql);
        $num_results = mysqli_num_rows($result);
        if ($num_results > 0) {
            if ($num_results == 1) {
                $row = mysqli_fetch_assoc($result);
                header("Location: movie_introduce.php?filmname=" . urlencode($row["filmname"]));
                exit;
            } 
        } else {
            
        }
    }

    mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.bootcdn.net/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <style>
        body{
            width:auto;
        }
        .top_list1{
    width: 105%;
    height: 25px;
    overflow: hidden;
    border:1px solid rgb(50, 50, 51);
    background-color:rgb(92, 92, 94);

}
.top_list1 li{
    list-style: none;
    float: left;
    margin-top: -15px;
    margin-right: 25px;
}
.top_list1 a{
    color: #d5d5d5;
    font: 12px Helvetica,Arial,sans-serif;
    text-decoration: none;
}
.tll{
    overflow:hidden;
    width: 105%;
    height: 80px;
    border:1px solid #d7e0f3;
    background-color:rgb(238, 243, 252);

}
.tll_img{
    margin-top: 15px;
    margin-left:230px;
}
.tll_sh{
    float: right;
    width: 71%;
    margin-top: -25px;
    margin-left:0;

}
.tll_sh_inp{
    width: 400px;
    height: 26px;
}
.tll_sh_img{
    position: relative;
    top: 12px;
    left: -5px;
}
.tll_sh_img2{
    position: relative;
    top: 30px;
    left: 70px;
}
.top_list2{
    width: 105%;
    height: 38px;
    overflow: auto;
    background-color:rgb(238, 243, 252);
    margin-bottom: 50px;
}
.top_list2 ul{
    margin-left: 202px;
}
.top_list2 li{
    list-style: none;
    float: left;
    margin-top: -10px;
    margin-right: 25px;
}
.top_list2 a{
    font: 14px Helvetica,Arial,sans-serif;
    text-decoration: none;
    color: #2277AA;
}
.back{
    /* 100%窗口高度 */
    width: 70%;
    height:450px ;
    /* 弹性布局 水平+垂直居中 */
    display: flex;
    justify-content: center;
    align-items: center;
    background-color:#fff;
    margin-left: 190px;
    margin-top: 50px;
    background-color: #d5d5d5;
}
/* 轮播图主体 */
.swipe{
    /* 相对定位 */
    position: relative;
    width: 100%;
    height: 450px;
    /* 溢出隐藏 */
    overflow: hidden;
}
/* 模糊背景 */
.swipe .bg{
    /* 绝对定位 */
    position: absolute;
    width: 70%;
    height: 450px;
    z-index: 1;
    background-image: url("img/swipe1.bmp");
    background-position:center center;
    /* 模糊滤镜 */
    filter:blur(140px);
}
/* 图片区域 */
.swipe section{
    position: relative;
    z-index: 2;
    width: 80%;
    max-width: 1500px;
    height: 450px;
    /* 居中 */
    margin: 0 auto;
}
/* 图片盒子 */
.swipe .img-box{
    width: 100%;
    height: 100%;
}
/* 图片 */
.swipe .img-box .img{
    width: 100%;
    height: 100%;
    /* 保持原有尺寸比例, 裁切长边 */
    object-fit: cover;
}
/* 指示器 */
.swipe .select{
    position: absolute;
    width: 100%;
    height: 30px;
    line-height: 30px;
    bottom: 20px;
    text-align: center;
}
.swipe .select .item{
    display: inline-block;
    width: 10px;
    height: 10px;
    background-color: #fff;
    border-radius: 50%;
    margin: 0 10px;
    /* 阴影 */
    box-shadow: 0 2px 5px rgba(0,0,0,0.4);
}
/* 鼠标移入指示器 */
.swipe .select .item:hover{
    background-color: #ff4400;
}
/* 指示器选中状态 */
.swipe .select .item.checked{
    background-color: #ff4400;
}
/* 两侧翻页按钮 */
.swipe .btn{
    width: 40px;
    height: 100px;
    color: #fff;
    /* 绝对定位 垂直居中 */
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    font-size: 50px;
    background-color: rgba(0,0,0,0.05);
    /* 弹性布局 居中 */
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 3;
    /* 鼠标移入光标变小手 */
    cursor: pointer;
    /* 动画过渡 */
    transition: 0.3s;
}
.swipe .btn.left{
    left: -60px;
}
.swipe .btn.right{
    right: -60px;
}
.swipe .btn:hover{
    background-color: rgba(0,0,0,0.2);
}

/* 响应式 屏幕尺寸小于1620px时以下代码生效(让两个按钮移动到图片主体内部) */
@media screen and (max-width:1620px){
    .swipe .btn.left{
        left: 20px;
    }
    .swipe .btn.right{
        right: 20px;
    }
}
.content{
    width: 105%;
    overflow: auto;
    margin-top: 50px;
    margin-right: 70px;

}
.left_content{
    margin-left: 240px;
    width: 43.8%;
}
.right_content{
    width: 32%;
    margin-top: -980px;
   float:right;
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
.slide_page{
    padding-top: 23px;

}
.slide_page .movie{
    display: inline-block;
    width:115px ;
    height:180px;
    text-align: center;
    margin-right: 10px;
}
.slide_page  a.photo{

    width: 115px;
    height: 163px;
}
.slide_page a{
    text-decoration: none;
    font: 13px Helvetica,Arial,sans-serif;
    color: #3377AA;
}
.slide_page .mv_name{
    padding-bottom: 10px;
}
.hot_tv{
    margin-top: 40px;
}

.weekly_ranking table{
    margin-top: -10px;
}
h6{
    width: 313px;
    padding-bottom: 13px;
    font: 16px Helvetica,Arial,sans-serif;
    color: #111111;
    margin-left: 10px;
    border-bottom:1px solid #dbd8d8 ;
}
h6 a{
    margin-left: 70px;
    font: 13px Helvetica,Arial,sans-serif;
    color: #3377AA;
}
.weekly_ranking{
    margin-top: -10px;
    width: 328px;
    align-items: center;
}

.weekly_ranking .order{
    margin-left: 3px;
    padding:10px 0;
    font: 14px Helvetica,Arial,sans-serif;

}
.weekly_ranking .title{
    width: 328px;
    padding-bottom: 5px;
    border-bottom:1px solid #dbd8d8;

}
.weekly_ranking .title a{
    font: 14px Helvetica,Arial,sans-serif;
    color: #3377AA;
    margin-left: 10px;
    text-decoration: none;
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
    justify-content: space-around; 
    align-items: stretch; 
    gap: 10px; 
}

.photo {
    width: 240px; 
    height: 300px; 
    object-fit: cover; 
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

#searchButton{
    background-image: url('photos/search.jpg');
    width:30px;
    height:30px;
  background-position: center;
  background-repeat: no-repeat;
}
   
#suggestionBox {
        border: 1px solid #ddd;
        background: #fff;
        position: absolute;
        z-index: 1000;
    }

    #suggestionBox p {
        margin: 0;
        padding: 8px;
        cursor: pointer;
    }

    #suggestionBox p:hover {
        background-color: #f2f2f2;
    }
       
</style>
<script>
        $(document).ready(function() {
            $("#searchBox").on("input", function() {
            var query = $(this).val();
            console.log("Query: ", query);

            if(query.length > 0) {
                $.ajax({
                    url: "search_suggestions.php",
                    method: "GET",
                    data: { search: query },
                    success: function(data) {
                        console.log("Data received: ", data); 
                        $("#suggestionBox").html(data);
                        $("#suggestionBox").show();
                    }
                });
            } else {
                $("#suggestionBox").hide();
            }
        });
        });
    </script>
<body>
  <!--顶部导航-->
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
        <li><a href="homepage_user.php" style="font-size: 13px;">返回</a></li>
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
<div class="tll">
    <img class="tll_img" src="photos\logo.jpg" alt=""/>

    <div class="tll_sh">
        <div >
        <form action="search.php" method="get">
            <label>
                <input class="tll_sh_inp" type="text" id="searchBox" name="search" placeholder="搜索电影" />
                <div id="suggestionBox" style="display:none;"></div>
            </label>
            <a class="tll_sh_img"><button type="submit" id="searchButton"></button></a>
            <img class="tll_sh_img2" src="photos\NianDuBangDan.png" alt=""/>
            </form>
        </div>
    </div>
</div>
<div class="top_list2">
    <ul>
        <li><a href="">影讯&购票</a></li>
        <li><a href="">选电影</a></li>
        <li><a href="">电视剧</a></li>
        <li><a href="">排行榜</a></li>
        <li><a href="">影评</a></li>
        <li><a href="">2022年度榜单</a></li>
        <li><a href="">2022书影音报告</a></li>
    </ul>

</div>

<div class="movie">
    <p>无此电影，你可能想看：</P>
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
            echo "<div class='image-container'>";
         // 保存查询结果到 $movies 数组
         while ($movie = mysqli_fetch_assoc($result)) {
            $filmname = $movie['filmname'];
            $image = $movie['image'];
            // Display each movie as a link with an image
            echo "<a href='movie_introduce.php?filmname=" . urlencode($filmname) . "'>";
            echo "<img class='photo' src='" . htmlspecialchars($image) . "' alt='" . htmlspecialchars($filmname) . "' /><br>";
            echo "<span class='mv_name'>" . htmlspecialchars($filmname) . "</span>";
            echo "</a>";
        }
        echo "</div>";
    }
}
?>
    
</div>
</body>
</html>
