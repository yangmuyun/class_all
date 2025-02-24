<?php
// 连接到数据库
$servername = "localhost";
$username = "root";
$password = "yang2004020015";
$dbname = "product";

// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检查连接是否成功
if ($conn->connect_error) {
    die("连接数据库失败： " . $conn->connect_error);
}

$direction = isset($_GET['direction']) ? $_GET['direction'] : '';
$currentProductId = isset($_GET['currentProductId']) ? $_GET['currentProductId'] : '';

// 根据传入的方向和当前商品ID，从数据库中获取上一个或下一个商品的信息
if ($direction === "prev") {
    $sql = "SELECT * FROM products WHERE ID < $currentProductId ORDER BY ID DESC LIMIT 1";
} else {
    $sql = "SELECT * FROM products WHERE ID > $currentProductId ORDER BY ID ASC LIMIT 1";
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // 如果找到上一个或下一个商品，返回商品信息
    $photoDetails = $result->fetch_assoc();
    $imagePath = "film/" . $photoDetails['image'];
    
    $response = [
        'id' => $photoDetails['ID'],
        'name' => $photoDetails['name'],
        'price' => $photoDetails['price'],
        'introduce' => $photoDetails['introduce'],
        'image' => $imagePath,
        'boundary' => false
    ];

    echo json_encode($response);
} else {
    // 如果没有找到上一个或下一个商品，返回带有 'boundary' 标志的响应
    $response = [
        'boundary' => true
    ];
    echo json_encode($response);
}

$conn->close();
?>