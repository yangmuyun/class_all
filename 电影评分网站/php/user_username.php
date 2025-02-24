<?php
ob_start(); // 开启输出缓冲

// 数据库连接
$conn = mysqli_connect("localhost", "root", "yang2004020015", "userinformation");
if ($conn->connect_error) {
    die("连接数据库失败: " . $conn->connect_error);
}
$updateMessage = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" ) {
if(isset($_COOKIE["username"])){
    if(isset($_POST['newUsername'])){
        $newUsername = $_POST['newUsername'];
        $currentUsername = $_COOKIE["username"];

        // 检查新用户名是否已经存在
        $stmt = $conn->prepare("SELECT * FROM user WHERE name = ?");
        $stmt->bind_param("s", $newUsername);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // 用户名已存在
            echo '<script>alert("用户名已存在,更新不成功");</script>';
            $updateMessage = "用户名已存在，无法更新";
        } else {
            // 用户名不存在，可以更新
            $stmt = $conn->prepare("UPDATE user SET name = ? WHERE name = ?");
            $stmt->bind_param("ss", $newUsername, $currentUsername);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                setcookie("username", $newUsername, time() + (86400 * 30), "/");
                $updateMessage = "用户名修改成功";
                echo '<script>alert("用户名更新成功");</script>';
                header("Location: ".$_SERVER['PHP_SELF']);
                exit;
            } else {
                $updateMessage = "更新失败: " . $conn->error;
            }
            $stmt->close();
        }
    }

        if(isset($_POST['newPassword'])){
            // 修改密码的逻辑
            $newPassword = isset($_POST['newPassword']) ? $_POST['newPassword'] : '';
            // 获取当前登录的用户名
            $currentUsername = $_COOKIE["username"];
            $updatePasswordSql = "UPDATE user SET password='$newPassword' WHERE name='$currentUsername'";
            if ($conn->query($updatePasswordSql) === TRUE) {
                echo '<script>alert("密码修改成功");</script>';
            } else {
                echo "更新密码失败: " . $conn->error;
            }
        }
        
    } else {
        $error_message = "未登录，无法修改用户名或密码";
    }
}
$conn->close();
ob_end_flush(); // 结束并发送输出缓冲
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="CSS/user_username.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        // 当输入框发生变化时触发事件
        $("#username").on("input", function() {
            var newUsername = $(this).val(); // 获取用户输入的新用户名
            checkUsername(newUsername); // 调用函数检查用户名是否已存在
        });

        // 检查用户名是否已存在
        function checkUsername(username) {
            $.ajax({
        url: "checkUsername.php",
        type: "POST",
        data: {
            username: username
        },
        success: function(response) {
            if (response === "exist") {
                if (username === "<?php echo $_COOKIE['username']; ?>") {
                    // 当前登录用户的用户名已存在，则不允许修改
                    $("#username-error").text("用户名已存在");
                    $("#submitButton").prop("disabled", true);
                } else {
                    // 其他用户的用户名已存在
                    $("#username-error").empty();
                    $("#submitButton").prop("disabled", false);
                }
            } else {
                // 用户名可用
                $("#username-error").empty();
                $("#submitButton").prop("disabled", false);
            }
        },
        error: function() {
            alert("请求失败");
        }
    });
        }
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
            <li><a href="user.php" >返回</a></li>
            <?php
            if (isset($_COOKIE["username"])) {
                echo "<li><a href='exitLogin.php'>退出登录</a></li>";
                ?>
                <a id="user-info">
                <li><a href="user.php"><?php echo "Welcome " . $_COOKIE["username"]; ?></a></li></a>
                <?php
            } else {
                echo "<li><a href='Login2.php'>登录/注册</a></li>";
                ?>
                <a id="user-info">
                <li><?php echo "Welcome guest"; ?></li></a>
                <?php
            }
            ?>
        </ul>
    </div> 
    <header class="header">
        <div class="nav-box">
            <img style="margin-left:-250px " src="img/DouBan.png" alt=""/>
            <ul class="nav">
                <li><a href="homepage_user.php">首页</a></li>
                <li><a href="user.php" >个人中心</a></li>
                <li><a href='user_comment.php' onclick='windows.location.href="user_comment.php"'>个人评论</a></li>
                <li><a href="user_username.php" class="on">账号管理</a></li>
            </ul>
        </div>
    </header>
    <?php
    if (isset($error_message)) {
        echo "<p>".$error_message."</p>";
    }
    ?>
    <div class="box">
    <form id="change-username-form" method="POST" >
    <div class="name-box">
        <label for="username">昵称：</label>
        <input type="text" id="newUsername" name="newUsername" width=20px onkeyup="showHint(this.value)" />
        <span id="usernameAvailability"></span>
        <span id="username-error" style="color: red;"></span><br>
        <span style="color: grey;font-weight: normal;" id="textHint"></span> <!-- 用于显示实时提示和用户名已存在提示 -->
        <label style="font-size: 12px;color: grey;font-weight: normal;">长度为4~16个字符</label><br>
        <button type="submit" id="submitButton">修改用户名</button>
    </div>      
    </form>
    <form id="change-password-form" method="POST" >
    <div class="password-box">
        <label for="password">新密码：</label>
        <input type="password" name="newPassword" id="newPassword" width=20px />
        <span id="passwordError" style="color: red;font-size: 12px;"></span><br>
        <label id="passwordtishi" style="font-size: 12px;color: grey;font-weight: normal;">至少6位密码</label><br>
        <button type="submit" id="submitPasswordButton">修改密码</button>
    </div>
    </form>
    </div>
    <script>
        function showHint(str) {
            if (str.length === 0) {
                document.getElementById("textHint").innerHTML = "";
                return;
            }
            var xmlhttp = null;
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            if (xmlhttp === null) {
                alert("您的浏览器不支持AJAX!");
                return;
            }
            var url="getHint.php";
            url = url + "?q=" + str;
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200){
                    document.getElementById("textHint").innerHTML = xmlhttp.responseText;
                }
            };
            xmlhttp.open("GET", url, true);
            xmlhttp.send(null);
        }

        var nameInput = document.getElementById("newUsername");
        var nameError = document.getElementById("nameError");
        nameInput.addEventListener("blur", validateName);

        var passwordInput=document.getElementById("newpassword");
        var passwordtishi=document.getElementById("passwordtishi");
        var passwordError = document.getElementById("passwordError");
        passwordInput.addEventListener("blur", validatePassword);

        function validateName() {
            if (nameInput.value === "") {
                nametishi.remove();
                nameError.innerText = "请输入名称";
            } else if (nameInput.value.length < 4 || nameInput.value.length > 16) {
                nametishi.remove();
                nameError.innerText = "长度只能为4~16个字符";
            } else {
                nameError.innerText = "";
            }
        }
        function validatePassword() {
            if (passwordInput.value === "") {
                passwordtishi.remove();
                passwordError.innerText = "请输入密码";
            }else{
                passwordError.innerText = "";
            }
        }

        // 通过 AJAX 请求来检查用户名是否已经存在
        function checkUsernameAvailability(username) {
            // 这里以示例直接使用 true 和 false 代表用户名是否存在，实际情况中需要根据后端的验证逻辑来判断
            return (username === "existingUser") ? true : false;
        }

        // 用户名和表单验证函数
        function validateForm2(event) {
            validateName();
            validatePassword();
            var username = nameInput.value;
            var usernameExists = checkUsernameAvailability(username);
            if (nameError.innerText !== "" || usernameExists || passwordError.innerText !== "") {
                alert("请检查确保是否填写正确");
                event.preventDefault(); // 阻止表单提交
            } else {
                alert("表单验证通过，提交成功！");
            }
        }

        // 添加提交按钮点击事件监听器                      
        //var submitButton = document.getElementsByTagName("button")[0];
        var submitButton = document.getElementById("submitButton");
        submitButton.addEventListener("click", validateForm2);
    </script>
</body>
</html>