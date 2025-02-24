<?php
if (isset($_COOKIE["username"])) {
$LoginedUser = $_COOKIE["username"];

// 连接数据库
$conn = mysqli_connect('localhost', 'root', 'yang2004020015', 'userinformation');

// 使用预处理语句执行数据库查询
$query = "SELECT * FROM comment WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $LoginedUser);
$stmt->execute();
$result = $stmt->get_result();
    // 处理删除评论请求
    if (isset($_POST['deleteID'])) {
        $deleteID = $_POST['deleteID'];
        $sql = "DELETE FROM comment WHERE ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $deleteID);
        if ($stmt->execute()) {
           // echo "成功删除记录";
            // 刷新页面或执行其他操作以更新评论列表
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "删除记录时出错：" . $conn->error;
        }
    }

    // 处理保存修改评论的请求
    if (isset($_POST['editedComment']) && isset($_POST['editID'])) {
        $editedComment = $_POST['editedComment'];
        $editID = $_POST['editID'];
        $sql = "UPDATE comment SET comment = ? WHERE ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $editedComment, $editID);
        if ($stmt->execute()) {
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "更新评论时出错：" . $conn->error;
          
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editScoreID'], $_POST['newScore'])) {
        $editScoreID = $_POST['editScoreID'];
        $newScore = $_POST['newScore'];

        // Prepare and bind
        $updateStmt = $conn->prepare("UPDATE comment SET score = ? WHERE ID = ?");
        $updateStmt->bind_param("ii", $newScore, $editScoreID);
        $updateStmt->execute();

        if ($updateStmt->affected_rows > 0) {
            echo "评分已更新";
        } else {
            echo "评分更新失败";
        }
    }
    
} else {
    echo "用户未登录";
}
$stmt->close();
$conn->close();
ob_end_flush();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    
    <style>
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

.title{
    font: 25px Helvetica,Arial,sans-serif;
    color:green;
    margin-left: 240px;
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

.title1{
    font: 23px Helvetica,Arial,sans-serif;
    color:green;
    margin-left: 240px;
    margin-top: 80px;
    margin-bottom: 30px;
}
.comment-box{
    display: flex;
    margin-left:230px ;
    flex-direction: column;
    align-items: flex-start;
    background-color: #f8fcf8;
    border-radius: 5px;
    width:650px ;
    padding:  0 10px;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
    margin-top: 18px;
}
.comment-box:hover{
    box-shadow: 0 0 5px rgba(0,0,0,0.5);
}
textarea{
    border-bottom-color: #a2a1a1;
}
.comments{
    margin-top: 0;
    margin-left: 20px;
    font: 15px Helvetica,Arial,sans-serif;
    color: #5d5d5d;

}
.comment-box form input[type="submit"] {
    margin-top: 3px;
    margin-bottom: 10px;
}
.btn{
    width:65px;
    height: 22px;
    line-height: 24px;
    border: none;
    border-radius: 4px;
    background-color: #348834;
    color: white;
}
.right{
    position: absolute;
    top:200px;
    left: 960px;
    background-color: #f0f8f0;
    width: 245px;
}
.right tr {
    border-collapse: collapse;
}
.right td{
    width: 220px;
    padding: 5px;
}
.right .title{
    font: 15px Helvetica,Arial,sans-serif;
    margin-left: -0px;
    color: #3377AA;
    display: block;
}
.right .user_film{
    display: block;
    border-bottom: 1px solid #dedbdb;
}
.user_film a{
    font: 13px Helvetica,Arial,sans-serif;
    color: #666666;
    text-decoration: none;
}
.right p{
    font: 13px Helvetica,Arial,sans-serif;
    color: #111111;
}
.right p a{
    display: inline;
    font: 13px Helvetica,Arial,sans-serif;
    color: #3377AA;
}

    </style>
</head>
<body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
        <li><a href="user.php" style="font-size: 13px;">返回</a></li>
        <?php
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
<header class="header">
    <div class="nav-box">
        <img style="margin-left:-250px" src="img/DouBan.png" alt=""/>

        <ul class="nav">
            <li><a href="homepage_user.php">首页</a></li>
            <li><a href="user.php" >个人中心</a></li>
            <li><a href='user_comment.php' class="on" onclick='windows.location.href="user_comment.php"'>个人评论</a></li>
            <li><a href="user_username.php">账号管理</a></li>
        </ul>
    </div>
<p class="title">评论详情 · · · · · ·</p>

<?php
ob_start();
if (isset($_COOKIE["username"])) {
    $LoginedUser = $_COOKIE["username"];

    // 连接数据库
    $conn = mysqli_connect('localhost', 'root', 'yang2004020015', 'userinformation');

    // 使用预处理语句执行数据库查询
    $query = "SELECT * FROM comment WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $LoginedUser);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        while ($comment = $result->fetch_assoc()) {
            echo "<div class='comment-box'>";
            echo "<p>{$comment['filmname']}</p>";
            echo "<p>评分: {$comment['score']}</p>"; 

            echo "<form method='POST'>";
            echo "<input type='hidden' name='editScoreID' value='{$comment['ID']}' />";
            echo "<input type='number' name='newScore' value='{$comment['score']}' min='1' max='5' />"; // Assuming score is between 1 and 10
            echo "<p><input class='btn' type='submit' value='修改评分' /></p>";
            echo "</form>";

            if (isset($_POST['ID']) && $_POST['ID'] == $comment['ID']) {
                echo "<form method='POST'>";
                echo "<p><textarea name='editedComment' rows='4' cols='50'>{$comment['comment']}</textarea></p>";
                echo "<input type='hidden' name='editID' value='{$comment['ID']}' />";
                echo "<input  class='btn' type='submit' value='保存修改' />";
                echo "</form>";
            } else {
                echo "<p class='comments'>{$comment['comment']}</p>";
                echo "<form method='POST'>";
                echo "<input type='hidden' name='ID' value='{$comment['ID']}' />";
                echo "<input class='btn' type='submit' value='修改评论' />";
                echo "</form>";
            }

            echo "<form method='POST'>";
            echo "<input type='hidden' name='deleteID' value='{$comment['ID']}' />";
            echo "<input class='btn del_btn' type='submit' value='删除评论' />";
            echo "</form>";
            echo "<p>{$comment['time']}</p>";
            echo "</div>";


        }
    } else {
        echo "没有找到记录";
    }
    
}else {
    echo "用户未登录";
}
?>

<div class="right">
    <p style="font: 18px Helvetica,Arial,sans-serif;color: green;margin-left: 5px;">热门评论</p>
    <table>
        <tr>
            <td class="title">我理解的“拓扑学”</td>
            <td class="user_film">
                <a href="">Kyon Smith 评论 《塞壬拓扑学》</a>
                <p>本片的主线其实就是一个实境解谜游戏，或者叫平行实境游戏（ARG），女主以某次神秘体验为契机（1.接触到了一位实验音乐人，而音乐人在做即兴表演时四周自然而然聚拢起了人群，这段可以看作“塞壬”的隐喻和预演；...<a>（全文）</a></p>
            </td>
        </tr>
        <tr>
            <td class="title">霉霉经济学干翻好莱坞！</td>
            <td class="user_film">
                <a href="">从半途出发 评论 《泰勒·斯威夫特：时代巡回演唱会》</a>
                <p>12月31日，霉霉的时代巡回演唱会纪录片在中国上映了。 而早在上映的前几天，许多影院的电影票就被抢空了，还有很多影院原本只排了一两场，后来又不得不增加排片。 这也让在2023最后一天上映的本片，拿下了2023年...<a>（全文）</a></p>
            </td>
        </tr>
        <tr>
            <td class="title">承认吧，只有中国博士才是最牛的</td>
            <td class="user_film">
                <a href="">clover 评论 《海王2：失落的王国》</a>
                <p>只要看过侏罗纪宇宙电影的人都知道，整个系列最牛逼的人就是吴博士，具体参见我之前写的影评。看完《海王2》，我发现整本电影最牛的就是沈博士！ 他轻轻松松发现了一...<a>（全文）</a></p>

            </td>
        </tr>
    </table>
</body>
</html>