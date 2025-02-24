<?php

$host = "localhost"; 
$username = "root"; 
$password = "yang2004020015"; 
$dbname = "userinformation"; 
$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("连接失败：" . $conn->connect_error);
}

// 获取前端传递的新用户名
$newUsername = $_POST['newUsername'];

// 验证新用户名是否可用
$stmt = $conn->prepare("SELECT id FROM user WHERE username = ?");
$stmt->bind_param("s", $newUsername);
$stmt->execute();
$stmt->store_result();

// 检查用户名是否已存在
if ($stmt->num_rows > 0) {
    echo "用户名已存在，请选择其他用户名";
} else {
    // 更新用户信息
    // 这里可以根据具体的业务需求进行相应处理，例如更新数据库中的用户名字段
    
    // 返回相应的成功响应
    echo "用户名已成功更新";
}

// 关闭连接和清理结果
$stmt->close();
$conn->close();
?>