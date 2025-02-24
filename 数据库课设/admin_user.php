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
$query="
    SELECT 
        s1.StudentID, 
        c.ClassID, 
        s1.Sname, 
        s1.Ssex, 
        s1.Sage, 
        s1.Sarea, 
        SUM(co.Ccredits) AS total_credits
    FROM 
        ymy_student s1
        JOIN ymy_class c ON c.ClassID = s1.ClassID
        JOIN ymy_major m ON m.MajorID = c.MajorID
        JOIN ymy_school sch ON m.SchoolID = sch.SchoolID
        LEFT JOIN ymy_grade g ON s1.StudentID = g.StudentID
        LEFT JOIN ymy_course co ON co.CourseID = g.CourseID
    WHERE 
        sch.SchoolID = '$SchoolID'
    GROUP BY 
        s1.StudentID, 
        c.ClassID, 
        s1.Sname, 
        s1.Ssex, 
        s1.Sage, 
        s1.Sarea
";
$result = $conn->query($query);
if (!$result) {
    die('查询错误：' . $conn->error);
}

$result = $conn->query($query);
if (!$result) {
    die('查询错误：' . $conn->error);
}
$course_query = "
SELECT 
    s.StudentID,
    co.CourseID,
    co.CourseName,
    co.Ccredits
FROM 
    ymy_student s
    JOIN ymy_grade g ON s.StudentID = g.StudentID
    JOIN ymy_course co ON co.CourseID = g.CourseID
WHERE 
    s.ClassID IN (
        SELECT 
            ClassID
        FROM 
            ymy_class
        WHERE 
            MajorID IN (
                SELECT 
                    MajorID
                FROM 
                    ymy_major
                WHERE 
                    SchoolID = '$SchoolID'
            )
    )
ORDER BY 
    s.StudentID, co.CourseID;
";


$result_courses = $conn->query($course_query);
if (!$result_courses) {
    die('查询错误：' . $conn->error);
}
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
            position: relative; /* 修改此处 */
            width: 100%;
            /* 可以添加一个最小高度，以确保页脚总是可见 */
            min-height: 50px;
        }
    </style>
</head>
<body>
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
        <main>
        <center>
        <?php
        echo "<table border='1'>";
        echo "<tr><th>学号</th><th>班级</th><th>姓名</th><th>性别</th><th>年龄</th><th>地区信息</th><th>当前学分</th></tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['StudentID'] . "</td>";
            echo "<td>" . $row['ClassID'] . "</td>";
            echo "<td>" . $row['Sname'] . "</td>";
            echo "<td>" . $row['Ssex'] . "</td>";
            echo "<td>" . $row['Sage'] . "</td>";
            echo "<td>" . $row['Sarea'] . "</td>";
            echo "<td>" . $row['total_credits'] . "</td>";
            echo "<td>";
            echo "<a href='delete_student.php?StudentID=" . urlencode($row['StudentID']) . "'>";
            echo "删除";
            echo "</a>";
            echo "</td>";
            echo "</tr>";
        }

        echo "</table>";
        ?>

    </center>

        <div>
            <a href="admin_Login.php">退出登录</a>
        </div>
        <form action="add_student.php" method="post">
        <form action="add_student.php" method="post">
    <table>
        <tr>
            <td>学号:</td>
            <td><input type="text" name="StudentID" required></td>
        </tr>
        <tr>
            <td>姓名:</td>
            <td><input type="text" name="Sname" required></td>
        </tr>
        <tr>
            <td>性别:</td>
            <td><select name="Ssex" required><option value="男">男</option><option value="女">女</option></select></td>
        </tr>
        <tr>
            <td>年龄:</td>
            <td><input type="number" name="Sage" required></td>
        </tr>
        <tr>
            <td>地区信息:</td>
            <td><input type="text" name="Sarea" required></td>
        </tr>
        <tr>
            <td>已修学分总数:</td>
            <td><input type="number" name="Scredits" required></td>
        </tr>
        <tr>
            <td>班级编号:</td>
            <td><input type="text" name="ClassID" required></td>
        </tr>
        <tr>
            <td colspan="2"><input type="submit" value="添加学生"></td>
        </tr>
        
    </table>
</form>
<div>
    <h2>学生所学课程及学分统计</h2>
    <table border="1">
        <tr>
            <th>学号</th>
            <th>课程号</th>
            <th>课程名</th>
            <th>学分</th>
        </tr>
        <?php
        while ($row = $result_courses->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['StudentID'] . "</td>";
            echo "<td>" . $row['CourseID'] . "</td>";
            echo "<td>" . $row['CourseName'] . "</td>"; // 添加了课程名的显示
            echo "<td>" . $row['Ccredits'] . "</td>";
            echo "</tr>";
        }
        ?>
    </table>
</div>
</form>
        </main>
        <footer>
            <p>版权所有 © 2024 教务系统</p>
        </footer>
</body>
</html>