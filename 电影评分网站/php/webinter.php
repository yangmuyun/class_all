<?php

$conn = new mysqli($localhost, $root, $yang2004020015, $userinformation);

// 检查是否连接成功
if ($conn->connect_error) {
  die("连接失败: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // 获取通过 AJAX 发送过来的用户名
  $username = $_POST["username"];

  // 在数据库中检查用户名是否已被注册
  $sql = "SELECT * FROM users WHERE username = '$username'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // 如果用户名已存在，返回消息告知用户
    echo "用户名已被注册";
  } else {
    // 如果用户名可用，返回消息告知用户
    echo "用户名可用";
  }
}

$conn->close();
?>