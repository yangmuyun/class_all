<?php
session_start();
$dbname = '202202_ymy_mis';
$dbuser = 'root';
$dbpass = 'yang2004020015';
$conn = new mysqli('localhost', $dbuser, $dbpass, $dbname);

if($conn->connect_error){
    die('连接失败：'.$conn->connect_error);
}

$StudentID = $_POST['StudentID'];
$CourseID = $_POST['CourseID'];
$Score = $_POST['Score'];

$query = "UPDATE ymy_grade SET Score='$Score' WHERE StudentID='$StudentID' AND CourseID='$CourseID'";

if ($conn->query($query) === TRUE) {
    echo "成绩更新成功";
} else {
    echo "更新错误: " . $conn->error;
}

$conn->close();
?>
