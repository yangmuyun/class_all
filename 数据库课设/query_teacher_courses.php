<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
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

        h2 {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<?php
session_start();
$dbname = '202202_ymy_mis';
$dbuser = 'root';
$dbpass = 'yang2004020015';
$conn = new mysqli('localhost', $dbuser, $dbpass, $dbname);

if($conn->connect_error){
    die('连接失败：'.$conn->connect_error);
}

if(isset($_POST['teacherID'])) {
    $teacherID = $_POST['teacherID'];

    $query = "
    SELECT 
        c.ClassID, 
        c.ClassName, 
        m.MajorName,
        co.CourseID,
        co.CourseName,
        co.Ccredits
    FROM 
        ymy_classcourse cc
        JOIN ymy_class c ON c.ClassID = cc.ClassID
        JOIN ymy_major m ON m.MajorID = c.MajorID
        JOIN ymy_course co ON co.CourseID = cc.CourseID
    WHERE 
        cc.TeacherID = '$teacherID';
    ";

    $result = $conn->query($query);
    if (!$result) {
        die('查询错误：' . $conn->error);
    }

    echo "<h2>教师编号为 $teacherID 的任课信息：</h2>";
    echo "<table border='1'>";
    echo "<tr><th>班级编号</th><th>班级名称</th><th>专业名称</th><th>课程编号</th><th>课程名称</th><th>学分</th></tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['ClassID'] . "</td>";
        echo "<td>" . $row['ClassName'] . "</td>";
        echo "<td>" . $row['MajorName'] . "</td>";
        echo "<td>" . $row['CourseID'] . "</td>";
        echo "<td>" . $row['CourseName'] . "</td>";
        echo "<td>" . $row['Ccredits'] . "</td>";
        echo "</tr>";
    }

    echo "</table>";
}
?>
</body>
</html>

