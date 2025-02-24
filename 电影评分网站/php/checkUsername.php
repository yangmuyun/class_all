<?php
// 假设你的数据库连接配置在这里
$conn = new mysqli("localhost", "root", "yang2004020015", "userinformation");

if ($conn->connect_error) {
  die("数据库连接失败: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $newUsername = $_POST["username"];

  // 检查新的用户名是否已存在
  $existQuery = "SELECT * FROM user WHERE username = ?";
  $stmt = $conn->prepare($existQuery);
  $stmt->bind_param("s", $newUsername);
  $stmt->execute();
  $existResult = $stmt->get_result();

  if ($existResult !== false && $existResult->num_rows > 0) {
    // 用户名已存在
    echo "exist";
  } else {
    // 用户名可用
    echo "available";
  }

  $stmt->close();
}

$conn->close();
?>