<?php
    
        setcookie("username", "", time() - 3600, "/");
        // 清除其他cookie
        setcookie("type", "", time() - 3600, "/");
        echo "退出登录";
        header("Location:http://localhost/Login2.php");
        exit;// 清除完毕，结束脚本
    
?>