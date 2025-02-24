<?php
     
 
    $conn = mysqli_connect("localhost", "root", "yang2004020015", "userinformation");
    if ($conn->connect_error) {
        die("连接失败: " . $conn->connect_error);
    }
    $filmname = isset($_GET['filmname']) ? $_GET['filmname'] : '';

    // 进行数据库查询，获取相应电影的详细信息
    $sql = "SELECT * FROM film WHERE filmname='$filmname'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $movieDetails = $result->fetch_assoc();

    // 提交评论的处理逻辑
    $conn = mysqli_connect("localhost", "root", "yang2004020015", "userinformation");
    if ($conn->connect_error) {
        die("连接数据库失败: " . $conn->connect_error);
    }

    if (isset($_POST['comment']) && isset($_POST['filmname'])) {
        $comment = $_POST['comment'];
        $filmname = $_POST['filmname'];
        $time = date("Y-m-d H:i:s");
        $rating = $_POST['rating'];

        if (isset($_COOKIE['username'])) {
            // 用户已登录，保存评论到数据库
            $currentUsername = $_COOKIE['username'];
            $sql = "INSERT INTO comment (username, filmname, comment, score, time) VALUES ('$currentUsername', '$filmname', '$comment', '$rating', '$time')";
            if ($conn->query($sql) !== TRUE) {
                echo "存储评论时出错：" . $conn->error;
            }
        } else {
            // 用户未登录，跳转到登录界面
            header('Location: Login2.php');  // 假设登录界面的文件名为 login.php
            exit;
        }?>
    <?php
    } 
    // else {
    //     echo "未找到该电影的详细信息";
    // }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.bootcdn.net/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <!-- <link rel="stylesheet" href="CSS/movie_introduce.css"> -->
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
/* img{
    width:200px;
    height:auto;
} */
.left1{
    margin-left: 243px;
    width:1200px;
    display: flex;
}

div.left2{
    width:250px;
    

}
.left1 h1{
    font: 28px Helvetica,Arial,sans-serif;
    color: #494949;
    font-weight: bold;
}
.middle{
    margin-left:25px;
    width:400px;
    height:350px;
    
}
.middle p{
    font: 13px Helvetica,Arial,sans-serif;
    color: #666666;
}
.middle a{
    font:13px Helvetica,Arial,sans-serif ;
    color: #3377AA;
}
.middle span{
    font:13px Helvetica,Arial,sans-serif ;
    color: #111111;
}

.introduce{
    width: 700px;
}

.right{
    position:absolute;
    left:1000px;
    width:280px;
    background-color: #f3faef;
}
.order{
    margin-left: 3px;
    padding:10px 0;
    font: 14px Helvetica,Arial,sans-serif;

}
.title{
    width: 328px;
    padding-bottom: 5px;
    /*border-bottom:1px solid #eae8e8;*/

}
.title a {
    font: 14px Helvetica, Arial, sans-serif;
    color: #3377AA;
    margin-left: 10px;
    text-decoration: none;
}
.submit_btn{
    width:100px;
    height: 30px;
    margin-top: -40px;
    margin-left: 470px;
    line-height: 30px;
    border: none;
    border-radius: 4px;
    background-color: #348834;
    color: white;
}

.rating {
    unicode-bidi: bidi-override;
    direction: rtl;
    text-align: left;
    position: relative;
    float: left;
}
.rating .star {
    font-size: 25px;
    display: inline-block;
    color: #ccc;
    cursor: pointer;
}
.rating .star:before {
    content: "\f005";
    font-family: FontAwesome;
}
.rating .star:hover,
.rating .star:hover ~ .star {
    color: gold;
}
.rating input[type="radio"] {
    display: none;
}
.rating input[type="radio"]:checked ~ .star {
    color: gold;
}
.comment-box{
    width: 680px;
    padding-bottom:15px;
    padding-top: -5px;
    font: 15px Helvetica,Arial,sans-serif;
    color: #666666;
    padding-left: 20px;
}
.bottom{
    margin-left:230px;
}
.star {
    color: #ccc; /* 灰色星星 */
    cursor: pointer;
    font-size: 20px;
}

.filled {
    color: gold; /* 填充的星星 */
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
.time{
    font: 12px Helvetica,Arial,sans-serif;
    color: #494949;
    display: inline;
    margin-left:15px;
}

    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
        <li><a href="homepage_user.php" style="font-size: 13px;">返回</a></li>
        <?php
    }
            if(isset($_COOKIE["username"])) {
                echo "<li><a href='exitLogin.php'>退出登录</a></li>";
               ?><a id="user-info">
               <li><a href="user.php"><?php echo "Welcome " . $_COOKIE["username"];?></a></li></a><?php
                
            } else {
                echo "<li><a href='Login2.php'>登录/注册</a></li>";
                ?><a id="user-info" style="margin-top: 20px">
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

    
<br>
<div class="right">
    <p style="font: 18px Helvetica,Arial,sans-serif;color: green;margin-left: 5px;">以下片单推荐</p>
    <table>
        <tr>
            <td class="order">1</td>
            <td class="title">
                <a href="">泰勒·斯威夫特：时代巡回演唱会</a>
            </td>
        </tr>
        <tr>
            <td class="order">2</td>
            <td class="title">
                <a href="">完美的日子</a>
            </td>
        </tr>
        <tr>
            <td class="order">3</td>
            <td class="title">
                <a href="">要太期待世界末日</a>
            </td>
        </tr>
        <tr>
            <td class="order">4</td>
            <td class="title">
                <a href="">留校联盟</a>
            </td>
        </tr>
        <tr>
            <td class="order">5</td>
            <td class="title">
                <a href="">绿色边境</a>
            </td>
        </tr>
        <tr>
            <td class="order">6</td>
            <td class="title">
                <a href="">杨妮克</a>
            </td>
        </tr>
        <tr>
            <td class="order">7</td>
            <td class="title">
                <a href="">旺卡</a>
            </td>
        </tr>
        <tr>
            <td class="order">8</td>
            <td class="title">
                <a href="">花月杀手</a>
            </td>
        </tr>
        <tr>
            <td class="order">9</td>
            <td class="title">
                <a href="">五月十二月</a>
            </td>
        </tr>
        <tr>
            <td class="order">10</td>
            <td class="title">
                <a href="">金色茧房</a>
            </td>
        </tr>
    </table>
</div>   
<div class="left1">
    <div class="left2">
    <h1><?php echo $filmname; ?></h1>
    
        <img style="width:200px;height:auto" src="<?php echo $movieDetails['image']; ?>" alt="<?php echo $filmname; ?>" />
    </div>    
    <div class="middle">
        <p>导演：<a><?php echo $movieDetails['editor']; ?></a></p>
        <p>主演：<a><?php echo $movieDetails['actor']; ?></a></p>
        <p>类型：<span><?php echo $movieDetails['kind']; ?></span></p>
        <p>语言：<span><?php echo $movieDetails['language']; ?></span></p>
        <p>上映日期:<span><?php echo $movieDetails['ReleaseDate']; ?></span></p>
        <p>片长：<span><?php echo $movieDetails['time']; ?><span></span></p>
    </div>
       
    </div>
    <div class="bottom">
    

    <p style="color:green;font-size:20px">剧情简介 · · · · · ·</p>
        <p style="color: #111111;font:13px Helvetica,Arial,sans-serif;width:720px"><?php echo $movieDetails['introduce']; ?></p>
    <p style="color:green;font-size:20px">用户评论 · · · · · ·</p>

    <form method="post" action="">
        <textarea style="width: 700px;height: 100px;border:1px solid #9b9b9b" name="comment" id="comment" placeholder="输入你的评论"></textarea>
        <br>
        <input style="width:300px;height:150px;" type="hidden" name="filmname" value="<?php echo $filmname; ?>">


        <br>
        <div class="rating">
            <input type="radio" id="star5" name="rating" value="5"><label class="star" for="star5"></label>
            <input type="radio" id="star4" name="rating" value="4"><label class="star" for="star4"></label>
            <input type="radio" id="star3" name="rating" value="3"><label class="star" for="star3"></label>
            <input type="radio" id="star2" name="rating" value="2"><label class="star" for="star2"></label>
            <input type="radio" id="star1" name="rating" value="1"><label class="star" for="star1"></label>
        </div><br>
        <input class="submit_btn" type="submit" value="提交评论">
        <p style="color:green;font-size:18px;margin-top: 40px">其他用户评论 · · · · · ·</P>
    </form>

    <?php
        // 读取评论并展示的代码
        $sql = "SELECT * FROM comment WHERE filmname='$filmname'";
        $result = $conn->query($sql);

        $sql_avg = "SELECT AVG(score) AS avg_score FROM comment WHERE filmname='$filmname'";
        $result_avg = $conn->query($sql_avg);

        if (!$result_avg) {
            die("查询失败: " . $conn->error); // 如果查询失败，输出错误信息并结束脚本
        }

        // 从结果集中获取平均评分
        if ($result_avg->num_rows > 0) {
            $row_avg = $result_avg->fetch_assoc();
            $average_score = $row_avg['avg_score'];
            echo "<p style='color:green;font-size:18px;'>电影平均评分：" . round($average_score, 2) . "/5</p>";
        } else {
            echo "<p style='color:red;font-size:18px;'>暂无用户评分</p>";
        }

        // 释放结果集
        $result_avg->free();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<p style='display: inline;border-top:1px solid #eae8e8;width: 700px;padding-top: 15px'><a style='color: #3377AA;font: 16px Helvetica,Arial,sans-serif;' >" . $row["username"] . "</a></p>";
                echo "<p class='time' >" . $row['time'] . "</p>"; 
                echo "<div class='user-rating' data-rating='" . $row["score"] . "'>";
                for ($i = 1; $i <= 5; $i++) {
                    echo "<span class='star" . ($i <= $row["score"] ? " filled" : "") . "' data-value='" . $i . "'>&#9733;</span>";
                }
                echo "</div>";
                echo "<p class='comment-box'> " . $row["comment"] ."</p>";
                
            }
        } else {
            echo "暂无评论";
        }

        $result->free(); // 释放结果集

        $conn->close(); // 关闭数据库连接
    ?>
    </div>
</div>
</form>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
document.querySelectorAll('.user-rating').forEach(userRating => {
    const rating = userRating.getAttribute('data-rating'); // 获取评分
    fillStars(userRating, rating);
});
</script>

</body>
</html>