<?php
// 开启会话
session_start();

// 数据库配置信息
define('DB_NAME', '202202_ymy_mis');
define('DB_USER', 'root');
define('DB_PASS', 'yang2004020015');
define('DB_HOST', 'localhost');

// 创建数据库连接
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// 检查数据库连接是否成功
if ($conn->connect_error) {
    die('数据库连接失败：' . $conn->connect_error);
}

// 检查是否为POST请求
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 获取并清理用户输入的班级ID
    $ClassID = isset($_POST['ClassID']) ? trim($_POST['ClassID']) : '';

    // 准备SQL查询语句
    $stmt = $conn->prepare("SELECT co.CourseID, co.CourseName, co.Csemester, co.CreditHour, co.Ctest, co.Ccredits, t.Tname, c.ClassID FROM ymy_course co JOIN ymy_classcourse cc ON co.CourseID = cc.CourseID JOIN ymy_class c ON c.ClassID = cc.ClassID JOIN ymy_teacher t ON t.TeacherID = cc.TeacherID WHERE c.ClassID = ?");
    
    // 检查语句是否准备成功
    if (!$stmt) {
        die('查询准备失败，请稍后再试。');
    }

    // 绑定参数并执行查询
    $stmt->bind_param("s", $ClassID);
    $stmt->execute();
    $result = $stmt->get_result();

    // 检查查询结果
    if ($result->num_rows > 0) {
        echo "<table class='data-table'>";
        echo "<tr><th>课程编号</th><th>课程名称</th><th>开课学期</th><th>学时</th><th>考核方式</th><th>学分</th><th>教师姓名</th><th>上课班级</th></tr>";
        
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            // 输出课程信息
            foreach ($row as $item) {
                echo "<td>" . htmlspecialchars($item) . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
        echo "<a href='admin_course.php'>返回</a>";
    } else {
        echo "<p>没有找到该班级开设的课程。</p>";
    }

    // 关闭语句
    $stmt->close();
} else {
    // 重定向到表单页面
    header('Location: your_form_page.php');
    exit;
}

// 关闭数据库连接
$conn->close();
?>

<!-- 在HTML头部添加CSS样式 -->
<style>
    .data-table {
        width: 90%;
        margin: 20px auto;
        border-collapse: collapse;
    }
    .data-table th, .data-table td {
        border: 1px solid #ddd;
        text-align: left;
        padding: 8px;
    }
    .data-table th {
        background-color: #f2f2f2;
        color: #333;
    }
    .data-table tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    .data-table tr:hover {
        background-color: #f1f1f1;
    }
    p {
        text-align: center;
    }
</style>