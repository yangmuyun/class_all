<?php
session_start();
$dbname = '202202_ymy_mis';
$dbuser = 'root';
$dbpass = 'yang2004020015';
$conn = new mysqli('localhost', $dbuser, $dbpass, $dbname);

if($conn->connect_error){
    die('连接失败：'.$conn->connect_error);
}

$AcademicYear = $_POST['AcademicYear'];

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
    co.Csemester LIKE CONCAT('%', ?, '%')
ORDER BY 
    g.StudentID, co.CourseID;
";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $AcademicYear);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die('查询错误：' . $conn->error);
}

$studentScores = [];
while ($row = $result->fetch_assoc()) {
    $studentScores[$row['StudentID']]['courses'][] = $row;
    $studentScores[$row['StudentID']]['totalScore'] = ($studentScores[$row['StudentID']]['totalScore'] ?? 0) + $row['Score'];
    $studentScores[$row['StudentID']]['courseCount'] = ($studentScores[$row['StudentID']]['courseCount'] ?? 0) + 1;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>学年成绩统计</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
         }
         th, td {
            border: 1px solid #ddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
            color: #333;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
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
            position: relative;
            width: 100%;
            min-height: 50px;
        }
    </style>
</head>
<body>
    <header>
        <h1>学年成绩统计</h1>
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
            foreach ($studentScores as $studentID => $data) {
                $avgScore = $data['totalScore'] / $data['courseCount'];
                echo "<h2>学号: $studentID</h2>";
                echo "<table>";
                echo "<tr><th>课程号</th><th>课程名</th><th>成绩</th><th>学期</th></tr>";
                foreach ($data['courses'] as $course) {
                    echo "<tr>";
                    echo "<td>" . $course['CourseID'] . "</td>";
                    echo "<td>" . $course['CourseName'] . "</td>";
                    echo "<td>" . $course['Score'] . "</td>";
                    echo "<td>" . $course['Csemester'] . "</td>";
                    echo "</tr>";
                }
                echo "<tr><td colspan='4'><b>学年平均成绩: " . number_format($avgScore, 2) . "</b></td></tr>";
                echo "</table>";
            }
            ?>
        </center>
    </main>
    <footer>
        <p>版权所有 © 2024 教务系统</p>
    </footer>
</body>
</html>
