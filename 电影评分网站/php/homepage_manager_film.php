<?php
    $servername = "localhost";
    $username = "root";
    $password = "yang2004020015";
    $dbname = "userinformation";
    $time=date('Y-m-d H:i:s');
    $conn = new mysqli($servername, $username, $password, $dbname);

    // 检查连接是否成功
    if ($conn->connect_error) {
        die("连接数据库失败： " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $filmname=$_POST["filmname"] ;
        $editor=$_POST["editor"] ;
        $actor=$_POST["actor"] ;
        $kind=$_POST["kind"];
        $language=$_POST["language"] ;
        $ReleaseDate=$_POST["ReleaseDate"] ;
        $time=$_POST["time"] ;
        $introduce=$_POST["introduce"] ;
        $image = $_FILES["image"];    
        
        //$sql = "INSERT INTO film (filmname, editor,actor,kind,language,ReleaseDate,time,introduce,image) VALUES ('$filmname', '$editor', '$actor', '$kind','$language','$ReleaseDate','$time','$introduce','$image')";
        
        if (isset($_FILES["image"])) {
            $targetDir = "film"; // 将目标目录替换为实际的目录路径
            $targetFile = $targetDir . basename($_FILES["image"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));
        
            // 检查文件类型
            if (in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                if (move_uploaded_file($image["tmp_name"], $targetFile)) {
                    $sql = "INSERT INTO film (filmname, editor, actor, kind, language, ReleaseDate, time, introduce, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sssssssss", $filmname, $editor, $actor, $kind, $language, $ReleaseDate, $time, $introduce, $targetFile);
                    if ($stmt->execute()) {
                        echo "电影信息已成功保存到数据库。";
                    } else {
                        echo "保存失败： " . $stmt->error;
                    }
                } else {
                    echo "抱歉，文件上传失败。";
                }
            } else {
                echo "抱歉，只允许上传 JPG, JPEG, PNG 或 GIF 文件。";
            }
        } else {
            echo "请先选择要上传的文件。";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        h1{
            margin-top:3px;
            margin-left:250px;
            color: #1a9a1a;
            font-size:30px;
            font-weight: normal;
            text-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .container{
            background-color:white;
            border:2px solid green ;

            box-shadow: 0 0 10px rgba(0,0,0,0.3);
            width:800px;
            margin-left:300px;
        }
        .header ul {
            list-style: none;
        }

        .header a {
            color: green;
            text-decoration: none;
        }



        .header {
            height: 80px;
            width: 105%;
            background: #eff5ef;
            border-bottom: 1px solid seashell;
        }

        .nav-box {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            height: 40px;
            padding-top: 20px;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            color: #eff5ef;
        }

        .nav-box .nav {
            display: flex;
            font-size: 16px;
            color: #f5f8f5;

        }
        .nav-box .nav li{
            padding-left: 40px;
        }
        .nav-box .nav a.on,
        .nav-box .nav a.on:hover{
            color: #20b420;
            padding-bottom: 10px;
            border-bottom: 2px solid #72b672;
        }

    </style>
</head>
<body>
<header class="header">
    <div class="nav-box">
        <img style="margin-left:-250px " src="img/DouBan.png" alt=""/>
        <ul class="nav">
            <li><a href="homepage_manager_all.php">首页</a></li>
            <li><a href="homepage_manager_film.php" class="on">更新电影</a></li>
            <li><a href='homepage_manager_comment.php' onclick='windows.location.href="user_comment.php"'>管理评论</a></li>
            <li><a href="homepage_manager_filmContent.php">管理电影内容</a></li>
            <li><a href='homepage_manager_user.php' >管理用户</a></li>
        </ul>
    </div>
</header>
        <form method="POST" enctype="multipart/form-data" style=>
            <div id="header" style="width: 105%;height: 38px">
                <h1 style="margin-top:5px;margin-left:250px;color: #1a9a1a;font-size:30px;font-weight: normal">&emsp;&emsp;添加电影</h1><br>
            </div> <br><br>
            <div class="container">
                <br>
                <br>
                <div>
                <label>&emsp;电影名</label>
                <input type="text" id="filmname" name="filmname" width="60px"><br>
                </div>
                <br> 
                <div>
                <label>&emsp;&emsp;导演</label>
                <input type="text" id="editor" name="editor" width="60px"><br>
                </div>
                <br> 
                <div>
                <label>&emsp;&emsp;主演</label>
                <input type="text" id="actor" name="actor" width="60px"><br>
                </div>
                <br> 
                <div>
                <label>&emsp;&emsp;类型</label>
                <input type="text" id="kind" name="kind" width="60px"><br>
                </div>
                <br> 
                <div>
                <label>&emsp;&emsp;语言</label>
                <input type="text" id="language" name="language" width="60px"><br>
                </div>
                <br> 
                <div>
                <label>上映时间</label>
                <input type="text" id="ReleaseDate" name="ReleaseDate" width="60px"><br>
                </div>
                <br> 
                <div>
                <label>&emsp;&emsp;时长</label>
                <input type="text" id="time" name="time" width="60px"><br>
                </div>
                <br> 
                <div>
                <label>&emsp;&emsp;介绍</label>
                <textarea cols="100" rows="30" name="introduce" id="introduce"></textarea><br>
                </div>
                <br> 
                <div>
                <label>图片</label>
                <input type="file" name="image" id="image" accept="image/*">
                </div>
                <br>    
                <div>
                <center><input type="submit" value="提交" style="width: 100px;height:30px;"></center>
                </div>
            </div>
            <!-- <a href="homepage_user.php">查看电影主界面</a>  -->
        </form>
</body>
</html>