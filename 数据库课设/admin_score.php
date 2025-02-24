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
$query = "
SELECT 
    g.StudentID, 
    g.CourseID, 
    co.CourseName,
    g.Score,
    co.Csemester
FROM 
    ymy_grade g
    JOIN ymy_course co ON co.CourseID = g.CourseID
WHERE 
    EXISTS (
        SELECT 1 
        FROM ymy_classcourse cc
        JOIN ymy_class c ON c.ClassID = cc.ClassID
        JOIN ymy_major m ON m.MajorID = c.MajorID
        WHERE m.SchoolID = '$SchoolID' AND co.CourseID = cc.CourseID
    )
ORDER BY 
    g.Score DESC, co.Csemester, g.StudentID, g.CourseID;
";

$result = $conn->query($query);
if (!$result) {
    die('查询错误：' . $conn->error);
}

// 计算学生名次
$rank = 0;
$prevScore = null;
$prevSemester = null;
while ($row = $result->fetch_assoc()) {
    if ($row['Score'] !== $prevScore || $row['Csemester'] !== $prevSemester) {
        $rank++;
        $prevScore = $row['Score'];
        $prevSemester = $row['Csemester'];
    }
    $row['Rank'] = $rank;
    $students[] = $row;
}

// 查询每门课程平均成绩
$avg_query = "
SELECT 
    g.CourseID, 
    co.CourseName,
    AVG(g.Score) AS AvgScore
FROM 
    ymy_grade g
    JOIN ymy_course co ON co.CourseID = g.CourseID
WHERE 
    g.CourseID IN (
        SELECT 
            cc.CourseID
        FROM 
            ymy_classcourse cc
            JOIN ymy_class c ON c.ClassID = cc.ClassID
            JOIN ymy_major m ON m.MajorID = c.MajorID
        WHERE 
            m.SchoolID = '$SchoolID'
    )
GROUP BY
    g.CourseID, co.CourseName;
";


$avg_result = $conn->query($avg_query);
if (!$avg_result) {
    die('查询平均成绩错误：' . $conn->error);
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
        <form action="query_yearly_score.php" method="post">
            <label for="AcademicYear">学年:</label>
            <input type="text" id="AcademicYear" name="AcademicYear" placeholder="如 大二" required>
            <input type="submit" value="查询成绩">
        </form>
    </center>
    <center>
        <form action="query_student_score.php" method="post">
            <label for="studentID">输入学生ID查询成绩:</label>
            <input type="text" id="studentID" name="studentID" required>
            <input type="submit" value="查询成绩">
        </form>
    </center>
    <center>
        <?php
        echo "<table border='1'>";
        echo "<tr><th>名次</th><th>学号</th><th>课程号</th><th>课程名</th><th>成绩</th><th>学期</th></tr>";

        foreach ($students as $student) {
            echo "<tr>";
            echo "<td>" . $student['Rank'] . "</td>";
            echo "<td>" . $student['StudentID'] . "</td>";
            echo "<td>" . $student['CourseID'] . "</td>";
            echo "<td>" . $student['CourseName'] . "</td>";
            echo "<td>" . $student['Score'] . "</td>";
            echo "<td>" . $student['Csemester'] . "</td>";
            echo "</tr>";
        }

        echo "</table>";
        ?>

        <br><br>

        <?php
        echo "<table border='1'>";
        echo "<tr><th>课程号</th><th>课程名</th><th>平均成绩</th></tr>";

        while ($avg_row = $avg_result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $avg_row['CourseID'] . "</td>";
            echo "<td>" . $avg_row['CourseName'] . "</td>";
            echo "<td>" . $avg_row['AvgScore'] . "</td>";
            echo "</tr>";
        }

        echo "</table>";
        ?>
    </center>
    <form action="update_score.php" method="post">
        <label for="StudentID">学号:</label>
        <input type="text" id="StudentID" name="StudentID" required><br><br>

        <label for="CourseID">课程号:</label>
        <input type="text" id="CourseID" name="CourseID" required><br><br>

        <label for="Score">成绩:</label>
        <input type="text" id="Score" name="Score" required><br><br>

        <label for="Csemester">学期:</label>
        <input type="text" id="Csemester" name="Csemester" required><br><br>

        <input type="submit" value="修改成绩">
    </form>
</main>
<footer>
    <p>版权所有 © 2024 教务系统</p>
</footer>
</body>
</html>