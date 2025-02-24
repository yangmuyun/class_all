<?php
$dbname = '202202_ymy_mis';
$dbuser = 'root';
$dbpass = 'yang2004020015';
$conn = new mysqli('localhost', $dbuser, $dbpass, $dbname);

if ($conn->connect_error) {
    die('连接失败：' . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $StudentID = $_POST['StudentID'];
    $Sname = $_POST['Sname'];
    $Ssex = $_POST['Ssex'];
    $Sage = $_POST['Sage'];
    $Sarea = $_POST['Sarea'];
    $Scredits = $_POST['Scredits'];
    $ClassID = $_POST['ClassID'];

    // 检查班级是否存在
    $classCheck = $conn->prepare("SELECT ClassID FROM ymy_class WHERE ClassID = ?");
    $classCheck->bind_param("s", $ClassID);
    $classCheck->execute();
    $resultCheck = $classCheck->get_result();

    if ($resultCheck->num_rows == 0) {
        die("错误：所选班级不存在！");
    }

    // 插入学生数据
    $stmt = $conn->prepare("INSERT INTO ymy_student (StudentID, Sname, Ssex, Sage, Sarea, Scredits, ClassID) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssissis", $StudentID, $Sname, $Ssex, $Sage, $Sarea, $Scredits, $ClassID);

    if ($stmt->execute()) {
        echo "新学生添加成功！";
        header('location:admin_user.php');
    } else {
        echo "添加失败：" . $conn->error;
    }

    $stmt->close();
    $classCheck->close();
    $conn->close();
}
?>