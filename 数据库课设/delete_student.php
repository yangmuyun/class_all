<?php
$dbname = '202202_ymy_mis';
$dbuser = 'root';
$dbpass = 'yang2004020015';
$conn = new mysqli('localhost', $dbuser, $dbpass, $dbname);

if ($conn->connect_error) {
    die('连接失败：' . $conn->connect_error);
}

$StudentID = $_GET['StudentID'];

$stmt = $conn->prepare("DELETE FROM ymy_student WHERE StudentID = ?");
$stmt->bind_param("s", $StudentID);

if ($stmt->execute()) {
    echo "学生删除成功！";
    header('location:admin_user.php');
} else {
    echo "删除失败：" . $conn->error;
}

$stmt->close();
$conn->close();
?>