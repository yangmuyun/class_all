<?php
session_start();
$dbname = '202202_ymy_mis';
$dbuser = 'root';
$dbpass = 'yang2004020015';
$conn = new mysqli('localhost', $dbuser, $dbpass, $dbname);

if($conn->connect_error){
    die('连接失败：'.$conn->connect_error);
}

// 检查是否有班级ID的输入
if (isset($_POST['ClassID'])) {
    $ClassID = $_POST['ClassID'];
    $query = "
    SELECT 
        co.CourseID, 
        co.CourseName, 
        co.Csemester,
        co.CreditHour,
        co.Ctest,
        co.Ccredits,
        t.Tname,
        c.ClassID
    FROM 
        ymy_course co
        JOIN ymy_classcourse cc ON co.CourseID = cc.CourseID
        JOIN ymy_class c ON c.ClassID = cc.ClassID
        JOIN ymy_teacher t ON t.TeacherID = cc.TeacherID
    WHERE 
        c.ClassID = '$ClassID'
    ";
    $result = $conn->query($query);
    if (!$result) {
        die('查询错误：' . $conn->error);
    }
}
$SchoolID = $_SESSION['SchoolID'];
$query="
SELECT 
    co.CourseID, 
    co.CourseName, 
    co.Csemester,
    co.CreditHour,
    co.Ctest,
    co.Ccredits,
    t.Tname,
    c.ClassID
FROM 
    ymy_course co
    JOIN ymy_classcourse cc ON co.CourseID = cc.CourseID
    JOIN ymy_class c ON c.ClassID = cc.ClassID
    JOIN ymy_major m ON m.MajorID = c.MajorID
    JOIN ymy_school sch ON m.SchoolID = sch.SchoolID
    JOIN ymy_teacher t ON t.TeacherID = cc.TeacherID
WHERE 
    sch.SchoolID = '$SchoolID' 
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
            position: absolute;
            bottom: 0;
            width: 100%;
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
        <div>
            <form action="query_students_scores.php" method="post">
                <label for="ClassID">输入班级ID:</label>
                <input type="text" id="ClassID" name="ClassID" required>
                <button type="submit">查询班级课程</button>
            </form>
        </div>
            <center>
                <?php
                echo "<table border='1'>";
                echo "<tr><th>课程编号</th><th>课程名称</th><th>开课学期</th><th>学时</th><th>考核方式</th><th>学分</th><th>教师姓名</th><th>上课班级</th></tr>"; // 添加教师姓名列

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['CourseID'] . "</td>";
                    echo "<td>" . $row['CourseName'] . "</td>";
                    echo "<td>" . $row['Csemester'] . "</td>";
                    echo "<td>" . $row['CreditHour'] . "</td>";
                    echo "<td>" . $row['Ctest'] . "</td>";
                    echo "<td>" . $row['Ccredits'] . "</td>";
                    echo "<td>" . $row['Tname'] . "</td>"; 
                    echo "<td>" . $row['ClassID'] . "</td>";
                    echo "</tr>";
                }

                echo "</table>";
                ?>
            </center>
</main>
        <footer>
            <p>版权所有 © 2024 教务系统</p>
        </footer>
</body>
</html>