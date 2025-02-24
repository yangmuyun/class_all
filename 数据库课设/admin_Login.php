<?php
session_start();

// 连接数据库的代码（示例）
$dbname = '202202_ymy_mis';
$dbuser = 'root';
$dbpass = 'yang2004020015';
$conn = new mysqli('localhost', $dbuser, $dbpass, $dbname);

if ($conn->connect_error) {
    die('连接失败：' . $conn->connect_error);
}
// 在数据库连接成功后，添加以下代码
$sql_schools = "SELECT SchoolID, SchoolName FROM ymy_school";
$result_schools = mysqli_query($conn, $sql_schools);


if (isset($_POST['login'])) { 
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $SchoolID = $_POST['SchoolID']; // 获取SchoolID

    if ($username == '' || $password == '') {
        echo "用户名或密码不能为空";
        exit;
    }
    // 这里应该有一个真实的验证逻辑，比如查询数据库验证用户名和密码
    if ($username == 'admin' && $password == 'admin') {
        $_SESSION['username'] = $username;
        $_SESSION['islogin'] = 1;
        $_SESSION['user_type'] = 'admin';
        $_SESSION['SchoolID'] = $SchoolID; // 存储SchoolID到Session
        header('location:admin_user.php');
        exit;
    } else {
        // 登录失败
        echo "用户名或密码错误";
        header('location:admin_login.php');
        exit;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>欢迎, <?php echo htmlspecialchars($username); ?> - 高校成绩管理系统</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            text-align: center;
        }

        header h1 {
            margin: 0;
        }

        nav ul {
            list-style: none;
            padding: 0;
        }

        nav ul li {
            display: inline;
            margin-right: 20px;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
        }

        main {
            margin: 15px;
        }
        main div{
            text-align: center;
            font-size:20px;
            line-height: 50px;
        }
        main img{
            border-radius: 50%;
            width:100px;
            height: auto;
        }
        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px 20px;
            position: absolute;
            bottom: 0;
            width: 100%;
        }

        .login form {
            max-width: 300px;
            margin: 0 auto;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            background: #fff;
        }

        .login label {
            display: block;
            margin-bottom: 5px;
        }

        .login input[type="text"],
        .login input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .login button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 4px;
            background-color: #5cb85c;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .login button:hover {
            background-color: #4cae4c;
        }
        @media (max-width: 600px) {
            nav ul li {
                display: block;
                margin-right: 0;
            }

            .login form {
                width: 100%;
            }
        }

    </style>
</head>
<body>
<form action="admin_login.php" method="post">
        <header>
            <h1>欢迎来到教务系统</h1>
            <!-- <nav>
                <ul>
                    <li><a href="admin_login.php">学生管理</a></li>
                    <li><a href="admin_login.php">课程管理</a></li>
                    <li><a href="admin_login.php">成绩管理</a></li>
                    <li><a href="admin_login.php">教师管理</a></li>
                </ul>
            </nav> -->
        </header>

        <main>
            <section class="login">
                <h2>管理员登录</h2>
                <form action="admin_login.php" method="post">
                    <label for="username">用户名:</label>
                    <input type="text" id="username" name="username" required>
                    
                    <label for="password">密码:</label>
                    <input type="password" id="password" name="password" required>
                    
                    <label for="school">学校:</label>
                    <select id="school" name="SchoolID">
                        <!-- PHP代码将在这里插入学校选项 -->
                        <?php while ($row = mysqli_fetch_assoc($result_schools)) { ?>
                            <option value="<?php echo $row['SchoolID']; ?>"><?php echo $row['SchoolName']; ?></option>
                        <?php } ?>
                    </select>

                    <button type="submit" name="login">登录</button>
                    <label>
                        <input type="checkbox" name="remember" value="yes">7天自动登录
                    </label> 
                </form>
            </section>
        </main>

        <footer>
            <p>版权所有 © 2024 教务系统</p>
        </footer>
</form>
</body>
</html>