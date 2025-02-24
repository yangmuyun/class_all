<?php
    $username = $_GET['q'];

    $conn = new mysqli("localhost", "root", "yang2004020015", "userinformation");

    // 检查连接是否成功
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // 使用预处理语句检查用户名是否存在
    $sql = "SELECT name FROM user WHERE name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // 判断结果集中是否存在记录
    if ($result->num_rows > 0) {
        $response = "用户名已存在";
    } else {
        $response = ""; // 为空表示用户名可用
    }

    // 关闭数据库连接
    $stmt->close();
    $conn->close();

    // 返回结果
    echo $response;
?>