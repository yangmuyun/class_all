<?php 
    session_start(); // 开启会话
    $conn = new mysqli('localhost', 'root', 'yang2004020015', 'userinformation');
    if ($conn->connect_error) {
        die("数据库连接失败:" . $conn->connect_error);
    }
    $conn->set_charset("utf8");
    
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
        $username = $_POST["username"] ?? "";
        $password = $_POST["password"] ?? "";
        $passwordVerified = $_POST["password_verified"] ?? "";
        $manager = isset($_POST['manager']) ? 1 : 0;
        if (empty($_POST["username"]) || empty($_POST["password"])) {
            die("名称和密码不能为空");
        }
    
        // 查询数据库中是否已存在相同的用户名
        $query = "SELECT name FROM user WHERE name = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
    
        if ($stmt->num_rows > 0) {
            echo '<script>alert("用户名已存在");</script>';
        } else {
            $stmt = $conn->prepare("INSERT INTO user (name, password, manager) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $password, $manager);
    
            if ($stmt->execute()) {
                echo '<script>alert("注册成功");</script>';
            } else {
                echo "注册失败，请稍后重试";
            }
    
            $stmt->close();
        }
    }
        

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])){
            $username = $_POST["username"] ?? "";
            $password = $_POST["password"] ?? "";
            $manager = isset($_POST["manager"]) ? $_POST["manager"] : 0;
            
            
            $stmt = $conn->prepare("SELECT name, password, manager FROM user WHERE name = ?");
            if ($stmt === false) {
                die('查询准备阶段出错: '.$conn->error);
            }
            $stmt->bind_param("s", $username);
            $stmt->execute();

            $result = $stmt->get_result();
            // echo "接收到的用户名:" . $username . "<br>";
            // echo "接收到的密码:" . $password . "<br>";
            if ($result->num_rows === 1) {
                $row = $result->fetch_assoc();
                if ($password==$row['password']) {
                    $_SESSION['username'] = $username; // 设置用户名的 Session 变量
        
                    if ($manager && $row['manager']) {
                        $_SESSION['manager'] = true; 
                        setcookie("username",$username, time()+3600, "/");
                        setcookie("type",$manager, time()+3600, "/");
                        header("Location: homepage_manager_all.php");
                        
                        exit();
                    } else if (!$manager && !$row['manager']) {
                        $_SESSION['manager'] = false; // 设置普通用户权限的 Session 变量
                        echo "普通用户登录成功";
                        setcookie("username", $username, time()+3600, "/");
                        setcookie("type", $manager, time()+3600, "/");
                        header("Location: homepage_user.php");
                        exit();
                    } else {
                        echo '<script>alert("用户权限错误");</script>';
                  
                    }
                } else {
                    echo '<script>alert("用户名密码错误");</script>';
                
                }
            } else {
                echo '<script>alert("用户不存在");</script>';
               
            }
        ob_end_flush();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login2</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        input{
            outline: none;
        }
        html,body{
            height: 100%;
        }
        body{
            display: flex;
            background: linear-gradient(to right, rgb(203, 222, 197), rgb(237, 241, 243));
        }
        form.login-form{
            width: 1000px;
            height: 600px;
            display: flex;
            position: relative;
            /* background-color: #fff; */
            margin: auto;
            border-radius: 8px;
            border: 1px solid rgba(255,255,255,.6);
            box-shadow: 4px 4px 3px rgba(0,0,0,.1);
        }
        .box{
            width: 1000px;
            height: 600px;
            display: flex;
            position: relative;
            left:200px;
            top:100px;
            /* background-color: #fff; */
            margin: auto;
            border-radius: 8px;
            border: 1px solid rgba(255,255,255,.6);
            box-shadow: 4px 4px 3px rgba(0,0,0,.1);
        }

        .pre-box{
        
            width: calc(1050px / 2);
            height: 100%;
            left: 0;
            top: 0; 
            position: absolute;
            border-radius: 4px;
            background-color: rgb(136,189,136);
            box-shadow: 4px 4px 3px rgba(0,0,0,.1);
            transition: 0.5s ease-in-out;

        }

        .pre-box h1{
            margin-top: 130px;
            text-align: center;
            letter-spacing: 5px;
            color: white;
            text-shadow: 4px 4px 3px rgba(0,0,0,.1);
        }
        .pre-box p{
            font-weight: bold;
            text-align: center;
            letter-spacing: 5px;
            color: white;
            text-shadow: 4px 4px 3px rgba(0,0,0,.1);
        }

        .img-box{
            width: 200px;
            height: 200px;
            border-radius: 50%;
            margin: 20px auto;
            overflow: hidden;
            box-shadow: 4px 4px 3px rgba(0,0,0,.1);
        }

        .img-box img{
            width: 100%;
            transition: 0.5s;
        }


        .login-form,.register-form{
            margin-top:-100px ;
            flex: 1;
            height: 100%;
        }

        .title-box{
            height: 300px;
            line-height: 500px;
        }

        .title-box h1{
            text-align: center;
            font-size: 40px;
            color: #9fa19f;
            letter-spacing: 5px;
            text-shadow: 4px 4px 3px rgba(0,0,0,.1);
            
        }

        .input-box{
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        input{
            width: 60%;
            height: 40px;
            margin-bottom: 20px;
            text-indent: 4px;
            border: 1px solid #b0cfe9;
            border-radius: 4px;
        }
        .check-box{
            display: flex;
            justify-content: right;
        }
        .checked{
            width: 25px;
            height: 25px;
            color: #9b9b9b;

        }
        .check-box p{
            margin-left: 8px;
            margin-right: -150px;
            height: 30px;
            line-height: 30px;
            font-size: 14px;
            color: #959695;
            letter-spacing: 5px;
        }

        .btn-box{
            display: flex;
            justify-content: center;
        }

        button{
            width:100px;
            height: 30px;
            margin: 0 7px;
            line-height: 30px;
            border: none;
            border-radius: 4px;
            background-color: #6db8f5;
            color: white;
        }

        button:hover{
            cursor: pointer;
            opacity: .8;
        }

        .btn-box p{
            height: 30px;
            line-height: 30px;
            font-size: 14px;
            color: #afb0af;
        }
        .btn-box p:hover{
            cursor: pointer;
            border-bottom: 1px solid #ccd0cc;
        }
    </style>
</head>
<body>
<form id="loginForm" class="loginForm" method="post">
    <div class="box">
        <div class="pre-box">
            <h1>Welcome</h1>
            <p>豆瓣电影</p>
            <div class="img-box">
                <img src="film/login1.png" alt="">
            </div>
        </div>
        
        <!-- 注册 -->
        <form method="POST" id="myForm" >
        <div class="register-form">
                <div class="title-box">
                    <h1>注册</h1>
                </div>
                <div class="input-box">
                        <input type="text" id="username" name="username" width=20px placeholder="用户名(必填，长度为4~16个字符)" >
                        <span id="usernameAvailability"></span>
                        <span id="nameError" style="color: red;font-size: 12px;"></span>
                        <span style="font-size: 12px;color: grey;font-weight: normal;" id="txthint"></span> <!-- 用于显示实时提示和用户名已存在提示 -->
                        <label id="nametishi" style="font-size: 12px;color: grey;font-weight: normal;"></label>
                        
                        <input type="password" name="password" id="password" width=20px placeholder="请输入密码">
                        <span id="passwordError" style="color: red;font-size: 12px;"></span>
                        <label id="passwordtishi" style="font-size: 12px;color: grey;font-weight: normal;"></label>
                        
                        <input type="text" id="passwordVerified" name="passwordVerified" width=20px placeholder="请确认密码（再次输入相同密码）">
                        <span id="passwordVerifiedError" style="color: red;font-size: 12px;"></span>
                        <label id="passwordVerifiedtishi" style="font-size: 12px;color: grey;font-weight: normal;"></label>
                        
                        <div class="check-box">
                            <input class="checked" type="checkbox" id="manager" name="manager" >
                            <p>管理员</p>
                        </div>   
                        <div class="btn-box">
                            <button name="register" type="submit" onclick="validateForm2()">注册</button>
                            <p onclick="mySwitch()">已有账号？去登录</p>
                        </div>
                </div>              
            </div>
        </form>
        <!-- 登录表单 -->
        <div class="login-form">
            <form method="post">
                <div class="title-box">
                    <h1>登录</h1>
                </div>
                <div class="input-box">
                    <input type="text" id="username" name="username" placeholder="用户名">
                    <input type="text" id="password" name="password" placeholder="密码">
                    <div class="check-box">
                        <input class="checked" type="checkbox" id="manager" name="manager">
                        <p>管理员</p>
                    </div>
                </div>
                <div class="btn-box">
                    <button id="loginButton" name="login" onclick="validateLogin()">登录</button>
                    <p onclick="mySwitch()">没有账号？去注册</p>
                </div>
            </form>
        </div>
    </div>
    <script>
        let flag=true
        const mySwitch=()=>{
            if (flag) {
                $(".pre-box").css("transform","translateX(100%)")
                $(".pre-box").css("background-color","rgb(161,181,206)")
                $("img").attr("src","film/login2.png")

            }else{
                $(".pre-box").css("transform","translateX(0%)")
                $(".pre-box").css("background-color","rgb(136,189,136)")
                $("img").attr("src","film/login1.png")
            }
            flag=!flag
        }
    </script>
</form>

<div id="message"></div>
<script>
    var xmlhttp;
    var usernameExists = false;
    function checkUsername(event) {
        event.preventDefault();
        var username = document.getElementById("username").value;
        var url = "checkUsername.php?username=" + username;

        var httpRequest = new XMLHttpRequest();
        httpRequest.onreadystatechange = function() {
            if (httpRequest.readyState === 4) {
                if (httpRequest.status === 200) {
                    var response = httpRequest.responseText;
                    if (response === "exist") {
                        document.getElementById("txthint").innerHTML = "用户名已存在";
                        document.getElementById("nametishi").remove();
                        usernameExists = true; 
                    } else {
                        document.getElementById("txthint").innerHTML = "可以使用的用户名";
                        document.getElementById("txthint").style.color = "black";
                        event.target.submit(); // 提交表单
                    }
                } 
            }
        };
        httpRequest.open("GET", url, true);
        httpRequest.send();
    }

        
            var nameInput=document.getElementById("username");
            var passwordInput=document.getElementById("password");
            var passwordVerifiedInputs=document.getElementById("passwordVerified");

            var nametishi=document.getElementById("nametishi");
            var passwordtishi=document.getElementById("passwordtishi");
            var passwordVerifiedtishi=document.getElementById("passwordVerifiedtishi");

            var nameError=document.getElementById("nameError");
            var passwordError = document.getElementById("passwordError");
            var passwordVerifiedError=document.getElementById("passwordVerifiedError");

            nameInput.addEventListener("blur", validateName);
            passwordInput.addEventListener("blur", validatePassword);
            passwordVerified.addEventListener("blur",validatePasswordVerified);
            
            function validateName() {
                    if (nameInput.value === "") {
                    nametishi.remove();
                    nameError.innerText = "请输入名称";
                    }else if(nameInput.value.length<4 || nameInput.value.length>16){
                    nametishi.remove();
                    nameError.innerText="长度只能为4~16个字符";
                    }else{
                    nameError.innerText="";
                }
                }

                // 密码验证函数
            function validatePassword() {
                    
                if (passwordInput.value === "") {
                passwordtishi.remove();
                passwordError.innerText = "请输入密码";
                }else{
                passwordError.innerText = "";
                }
            }
                
            function validatePasswordVerified(){
                if(passwordVerified.value!==passwordInput.value){
                    passwordVerifiedtishi.remove();
                    passwordVerifiedError.innerText("密码输入不一致");
                    alert("密码输入不一致");
                }else{
                    passwordVerifiedError.innerText="";
                }
            }

            function validateForm2(event) {
                validateName();
                validatePassword();
                validatePasswordVerified();
                var manager=document.getElementByName("manager");

                // 判断是否有验证不通过的项
                if (nameError.innerText !== "" || passwordError.innerText !== "" || passwordVerifiedError.innerText!=="") {
                    alert("请检查表单，确保填写正确");
                    event.preventDefault(); // 阻止表单提交
                }else if(usernameExists) {
                    alert("用户名已存在，请更换用户名");
                    event.preventDefault();
                }
                else {
                    alert("表单验证通过，提交成功！");
                }
            }
            // 添加提交按钮点击事件监听器
            var submitButton = document.getElementsByTagName("button")[0];
            submitButton.addEventListener("click", validateForm2);
</script>
 
    <!-- 登录的js -->
    <script>
        var nameInput=document.getElementById("username");
        var passwordInput=document.getElementById("password");
        var passwordVerifiedInputs=document.getElementById("passwordVerified");

        var nametishi=document.getElementById("nametishi");
        var passwordtishi=document.getElementById("passwordtishi");
        var passwordVerifiedtishi=document.getElementById("passwordVerifiedtishi");

        var nameError=document.getElementById("nameError");
        var passwordError = document.getElementById("passwordError");
        var passwordVerifiedError=document.getElementById("passwordVerifiedError");

        nameInput.addEventListener("blur", validateName);
        passwordInput.addEventListener("blur", validatePassword);
        passwordVerified.addEventListener("blur",validatePasswordVerified);
        
        
        function validateName() {
                if (nameInput.value === "") {
                nametishi.remove();
                nameError.innerText = "请输入名称";
                }else if(nameInput.value.length<4 || nameInput.value.length>16){
                nametishi.remove();
                nameError.innerText="长度只能为4~16个字符";
                }else{
                nameError.innerText="";
            }
            }

            // 密码验证函数
        function validatePassword() {
                
            if (passwordInput.value === "") {
            passwordtishi.remove();
            passwordError.innerText = "请输入密码";
            }else{
            passwordError.innerText = "";
            }
        }
            
        function validatePasswordVerified(){
            if(passwordVerified.value!==passwordInput.value){
                passwordVerifiedtishi.remove();
                passwordVerifiedError.innerText("密码输入不一致");
                alert("密码输入不一致");
            }else{
                passwordVerifiedError.innerText="";
            }
        }

        function validateForm2(event) {
            validateName();
            validatePassword();
            validatePasswordVerified();
            var manager=document.getElementByName("manager");

            // 判断是否有验证不通过的项
            if (nameError.innerText !== "" || passwordError.innerText !== "" || passwordVerifiedError.innerText!=="") {
                alert("请检查表单，确保填写正确");
                event.preventDefault(); // 阻止表单提交
            } else {
                alert("表单验证通过，提交成功！");
            }
        }
        // 添加提交按钮点击事件监听器
        var submitButton = document.getElementsByTagName("button")[0];
        submitButton.addEventListener("click", validateForm2);
    </script>
</body>
</html>