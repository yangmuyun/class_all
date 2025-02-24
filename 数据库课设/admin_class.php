<?php
session_start();
$dbname = '202202_ymy_mis';
$dbuser = 'root';
$dbpass = 'yang2004020015';
$conn = new mysqli('localhost', $dbuser, $dbpass, $dbname);

if($conn->connect_error){
    die('连接失败：'.$conn->connect_error);
}

$SchoolID = $_SESSION['SchoolID'];

// 查询当前登录学校的班级信息
$query = "
SELECT 
    c.ClassID, 
    c.ClassName, 
    c.MajorID
FROM 
    ymy_class c
JOIN 
    ymy_major m ON c.MajorID = m.MajorID
WHERE 
    m.SchoolID = '$SchoolID'
";

$result = $conn->query($query);
if (!$result) {
    die('查询错误：' . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>班级管理 - 高校成绩管理系统</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 90%; /* 表格宽度 */
            margin: 20px auto; /* 居中显示 */
            border-collapse: collapse; /* 边框合并 */
        }
        th, td {
            border: 1px solid #ddd; /* 单元格边框 */
            text-align: left; /* 文本对齐 */
            padding: 8px; /* 单元格内边距 */
        }

        th {
            background-color: #f2f2f2; /* 表头背景颜色 */
            color: #333; /* 表头文字颜色 */
        }

        tr:nth-child(even) {
            background-color: #f9f9f9; /* 偶数行背景颜色 */
        }

        tr:hover {
            background-color: #f1f1f1; /* 鼠标悬浮行背景颜色 */
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
        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px 20px;
            position: relative; /* 修改此处 */
            width: 100%;
            /* 可以添加一个最小高度，以确保页脚总是可见 */
            min-height: 50px;
        }
    </style>
</head>
<body>
    <!-- 头部导航栏 -->
    <header>
        <h1>欢迎来到教务系统</h1>
        <nav>
            <ul>
                <li><a href="admin_user.php">学生管理</a></li>
                <li><a href="admin_class.php">班级管理</a></li>
                <li><a href="admin_course.php">课程管理</a></li>
                <li><a href="admin_score.php">成绩管理</a></li>
                <li><a href="admin_teacher.php">教师管理</a></li>
            </ul>
        </nav>
    </header>

    <!-- 主体部分 -->
    <main>
        <center>
            <h2>班级信息</h2>
            <?php
            echo "<table>";
            echo "<tr><th>班级ID</th><th>班级名称</th><th>专业ID</th></tr>";
            
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['ClassID'] . "</td>";
                echo "<td>" . $row['ClassName'] . "</td>";
                echo "<td>" . $row['MajorID'] . "</td>";
                echo "</tr>";
            }
            
            echo "</table>";
            ?>
        </center>
    </main>

    <!-- 页脚部分 -->
    <footer>
        <p>版权所有 © 2024 教务系统</p>
    </footer>
</body>
</html>