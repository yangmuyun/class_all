<?php
    if (isset($_GET["filmname"])) {
        $conn = mysqli_connect("localhost", "root", "yang2004020015", "userinformation");
        if ($conn->connect_error) {
            die("连接数据库失败: " . $conn->connect_error);
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>豆瓣电影</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- 引入字体图标 -->
    <link href="https://cdn.bootcdn.net/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <style>
        body{
            width:auto;
        }
        .top_list1{
    width: 105%;
    height: 25px;
    overflow: hidden;
    border:1px solid rgb(50, 50, 51);
    background-color:rgb(92, 92, 94);

}
.top_list1 li{
    list-style: none;
    float: left;
    margin-top: -15px;
    margin-right: 25px;
}
.top_list1 a{
    color: #d5d5d5;
    font: 12px Helvetica,Arial,sans-serif;
    text-decoration: none;
}
.tll{
    overflow:hidden;
    width: 105%;
    height: 80px;
    border:1px solid #d7e0f3;
    background-color:rgb(238, 243, 252);

}
.tll_img{
    margin-top: 15px;
    margin-left:230px;
}
.tll_sh{
    float: right;
    width: 71%;
    margin-top: -25px;
    margin-left:0;

}
.tll_sh_inp{
    width: 400px;
    height: 26px;
}
.tll_sh_img{
    position: relative;
    top: 12px;
    left: -5px;
}
.tll_sh_img2{
    position: relative;
    top: 30px;
    left: 70px;
}
.top_list2{
    width: 105%;
    height: 38px;
    overflow: auto;
    background-color:rgb(238, 243, 252);
    margin-bottom: 50px;
}
.top_list2 ul{
    margin-left: 202px;
}
.top_list2 li{
    list-style: none;
    float: left;
    margin-top: -10px;
    margin-right: 25px;
}
.top_list2 a{
    font: 14px Helvetica,Arial,sans-serif;
    text-decoration: none;
    color: #2277AA;
}
.back{
    /* 100%窗口高度 */
    width: 70%;
    height:450px ;
    /* 弹性布局 水平+垂直居中 */
    display: flex;
    justify-content: center;
    align-items: center;
    background-color:#fff;
    margin-left: 190px;
    margin-top: 50px;
    background-color: #d5d5d5;
}
/* 轮播图主体 */
.swipe{
    /* 相对定位 */
    position: relative;
    width: 100%;
    height: 450px;
    /* 溢出隐藏 */
    overflow: hidden;
}
/* 模糊背景 */
.swipe .bg{
    /* 绝对定位 */
    position: absolute;
    width: 70%;
    height: 450px;
    z-index: 1;
    background-image: url("img/swipe1.bmp");
    background-position:center center;
    /* 模糊滤镜 */
    filter:blur(140px);
}
/* 图片区域 */
.swipe section{
    position: relative;
    z-index: 2;
    width: 80%;
    max-width: 1500px;
    height: 450px;
    /* 居中 */
    margin: 0 auto;
}
/* 图片盒子 */
.swipe .img-box{
    width: 100%;
    height: 100%;
}
/* 图片 */
.swipe .img-box .img{
    width: 100%;
    height: 100%;
    /* 保持原有尺寸比例, 裁切长边 */
    object-fit: cover;
}
/* 指示器 */
.swipe .select{
    position: absolute;
    width: 100%;
    height: 30px;
    line-height: 30px;
    bottom: 20px;
    text-align: center;
}
.swipe .select .item{
    display: inline-block;
    width: 10px;
    height: 10px;
    background-color: #fff;
    border-radius: 50%;
    margin: 0 10px;
    /* 阴影 */
    box-shadow: 0 2px 5px rgba(0,0,0,0.4);
}
/* 鼠标移入指示器 */
.swipe .select .item:hover{
    background-color: #ff4400;
}
/* 指示器选中状态 */
.swipe .select .item.checked{
    background-color: #ff4400;
}
/* 两侧翻页按钮 */
.swipe .btn{
    width: 40px;
    height: 100px;
    color: #fff;
    /* 绝对定位 垂直居中 */
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    font-size: 50px;
    background-color: rgba(0,0,0,0.05);
    /* 弹性布局 居中 */
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 3;
    /* 鼠标移入光标变小手 */
    cursor: pointer;
    /* 动画过渡 */
    transition: 0.3s;
}
.swipe .btn.left{
    left: -60px;
}
.swipe .btn.right{
    right: -60px;
}
.swipe .btn:hover{
    background-color: rgba(0,0,0,0.2);
}

/* 响应式 屏幕尺寸小于1620px时以下代码生效(让两个按钮移动到图片主体内部) */
@media screen and (max-width:1620px){
    .swipe .btn.left{
        left: 20px;
    }
    .swipe .btn.right{
        right: 20px;
    }
}
.content{
    width: 105%;
    overflow: auto;
    margin-top: 50px;
    margin-right: 70px;

}
.left_content{
    margin-left: 240px;
    width: 43.8%;
}
.right_content{
    width: 32%;
    margin-top: -980px;
   float:right;
}

.hot{
    font: 16px Helvetica,Arial,sans-serif;
    display: flex;
    border-bottom:1px solid #dbd8d8;
}
.hot li{
    list-style: none;
    float: left;
    margin-top: -10px;
    margin-right: 14px;
}
.hot a{
    margin-left: 10px;
    font: 13px Tahoma;
    text-decoration: none;
    color: #9b9b9b;
}
.tag_list{
    margin-left: -20px;
}
.hot .tag_list a.on,
.hot .tag_list a.on:hover{
    color: #111;
}
.tag_list_more{
    margin-left: 35px;
}

.hot .tag_list_more a{
    font: 13px Helvetica,Arial,sans-serif;
    color: #3377AA;
}
.slide_page{
    padding-top: 23px;

}
.slide_page .movie{
    display: inline-block;
    width:115px ;
    height:180px;
    text-align: center;
    margin-right: 10px;
}
.slide_page  a.photo{

    width: 115px;
    height: 163px;
}
.slide_page a{
    text-decoration: none;
    font: 13px Helvetica,Arial,sans-serif;
    color: #3377AA;
}
.slide_page .mv_name{
    padding-bottom: 10px;
}
.hot_tv{
    margin-top: 40px;
}

.weekly_ranking table{
    margin-top: -10px;
}
h6{
    width: 313px;
    padding-bottom: 13px;
    font: 16px Helvetica,Arial,sans-serif;
    color: #111111;
    margin-left: 10px;
    border-bottom:1px solid #dbd8d8 ;
}
h6 a{
    margin-left: 70px;
    font: 13px Helvetica,Arial,sans-serif;
    color: #3377AA;
}
.weekly_ranking{
    margin-top: -10px;
    width: 328px;
    align-items: center;
}

.weekly_ranking .order{
    margin-left: 3px;
    padding:10px 0;
    font: 14px Helvetica,Arial,sans-serif;

}
.weekly_ranking .title{
    width: 328px;
    padding-bottom: 5px;
    border-bottom:1px solid #dbd8d8;

}
.weekly_ranking .title a{
    font: 14px Helvetica,Arial,sans-serif;
    color: #3377AA;
    margin-left: 10px;
    text-decoration: none;
}
.hot_list ul{
    margin-top: -10px;
}
.hot_list li{
    list-style: none;
    padding-bottom: 15px;
    width: 288px;

}
.hot_list .title{
    margin-left: -35px;
    padding-bottom: 20px;
    border-bottom:1px solid #dbd8d8;
}
.hot_list .title a{
    font: 14px Helvetica,Arial,sans-serif;
    color: #3377AA;
    padding: 0 5px;
    text-decoration: none;
}
.hot_list .title span{
    color: #9b9b9b;
    font: 13px Tahoma;
    float: right;
}
.contact_and_cooperation li{
    list-style: none;
    margin-left: -30px;
}
.image-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}

.photo {
    width: calc(25% - 10px);
    margin-bottom: 10px;
}

@media (max-width: 768px) {
    .photo {
        width: calc(50% - 10px);
    }
}

@media (max-width: 480px) {
    .photo {
        width: 100%;
    }
}

#searchButton{
    background-image: url('photos/search.jpg');
    width:30px;
    height:30px;
  background-position: center;
  background-repeat: no-repeat;
}
   
#suggestionBox {
        border: 1px solid #ddd;
        background: #fff;
        position: absolute;
        z-index: 1000;
    }

    #suggestionBox p {
        margin: 0;
        padding: 8px;
        cursor: pointer;
    }

    #suggestionBox p:hover {
        background-color: #f2f2f2;
    }
    </style>
    <script>
        $(document).ready(function() {
            $("#searchBox").on("input", function() {
            var query = $(this).val();
            console.log("Query: ", query); 

            if(query.length > 0) {
                $.ajax({
                    url: "search_suggestions.php",
                    method: "GET",
                    data: { search: query },
                    success: function(data) {
                        console.log("Data received: ", data);
                        $("#suggestionBox").html(data);
                        $("#suggestionBox").show();
                    }
                });
            } else {
                $("#suggestionBox").hide();
            }
        });
        });
    </script>
</head>
<body>
    <!--顶部导航-->
<div class="top_list1">
    <ul>
        <li><a href="">豆瓣</a></li>
        <li><a href="">读书</a></li>
        <li><a href="">电影</a></li>
        <li><a href="">音乐</a></li>
        <li><a href="">同城</a></li>
        <li><a href="">小组</a></li>
        <li><a href="">阅读</a></li>
        <li><a href="">FM</a></li>
        <li><a href="">时间</a></li>
        <li><a href="">豆品</a></li>
    </ul>
    <ul style="list-style: none;float: right;margin-top:0;margin-right: 25px;">
        <li><a href="">下载豆瓣客户端</a></li>
        <?php
            if(isset($_COOKIE["username"])) {
                echo "<li><a href='exitLogin.php'>退出登录</a></li>";
               ?><a id="user-info">
               <li><a href="user.php"><?php echo "Welcome " . $_COOKIE["username"];?></a></li></a><?php
                
            } else {
                echo "<li><a href='Login2.php'>登录/注册</a></li>";
                ?><a id="user-info">
                <li><?php echo "Welcome guest";?></li></a><?php
                
            }
        ?>
    </ul>
</div>
<div class="tll">
    <img class="tll_img" src="photos\logo.jpg" alt=""/>

    <div class="tll_sh">
        <div >
        <form action="search.php" method="get">
            <label>
                <input class="tll_sh_inp" type="text" id="searchBox" name="search" placeholder="搜索电影" />
                <div id="suggestionBox" style="display:none;"></div>
            </label>
            <a class="tll_sh_img"><button type="submit" id="searchButton"></button></a>
            <img class="tll_sh_img2" src="photos\NianDuBangDan.png" alt=""/>
            </form>
        </div>
    </div>
</div>
<div class="top_list2">
    <ul>
        <li><a href="">影讯&购票</a></li>
        <li><a href="">选电影</a></li>
        <li><a href="">电视剧</a></li>
        <li><a href="">排行榜</a></li>
        <li><a href="">影评</a></li>
        <li><a href="">2022年度榜单</a></li>
        <li><a href="">2022书影音报告</a></li>
    </ul>

</div>
            <div class="back">
    <!-- 轮播图主体 -->
    <div class="swipe" id="swipe">
        <!-- 模糊背景 -->
        <div class="bg" id="swipe_bg"></div>
        <!-- 图片区域 -->
        <section>
            <!-- 图片显示 -->
            <div class="img-box" id="swipe_img_box">
                <a href="#" class="link" id="swipe_link">
                    <img src="film\swipe1.bmp" alt="" class="img" id="swipe_img">
                    <img src="film\swipe2.bmp" alt="" class="img" id="swipe_img">
                    <img src="film\swipe3.bmp" alt="" class="img" id="swipe_img">
                    <img src="film\swipe4.bmp" alt="" class="img" id="swipe_img">
                    <img src="film\swipe5.bmp" alt="" class="img" id="swipe_img">
                </a>
            </div>
            <!-- 指示点 -->
            <div class="select" id="swipe_select">
            </div>
            <!-- 左侧翻页按钮 -->
            <div class="btn left" id="swipe_btn_left">
                <!-- 字体图标：左箭头 -->
                <i class="fa fa-angle-left" aria-hidden="true"></i>
            </div>
            <!-- 右侧翻页按钮 -->
            <div class="btn right" id="swipe_btn_right">
                <!-- 字体图标：右箭头 -->
                <i class="fa fa-angle-right" aria-hidden="true"></i>
            </div>
        </section>
    </div>
</div>
<script>
    // 当前轮播图编号
    let current_index=-1;
    // 自动轮播定时器
    let swipe_timer=null;
    // 轮播图的图片地址与跳转链接
    let links=[
        {'image':'film/swipe1.bmp','target':'#1'},
        {'image':'film/swipe2.bmp','target':'#2'},
        {'image':'film/swipe3.bmp','target':'#3'},
        {'image':'film/swipe4.bmp','target':'#4'},
        {'image':'film/swipe5.bmp','target':'#5'},
       // {'image':'film/swipe6.bmp','target':'#6'}
    ];

    // 需要操作到的元素
    let swipe=document.getElementById('swipe');
    let swipe_bg=document.getElementById('swipe_bg');
    let swipe_img_box=document.getElementById('swipe_img_box');
    let swipe_link=document.getElementById('swipe_link');
    let swipe_img=document.getElementById('swipe_img');
    let swipe_select=document.getElementById('swipe_select');
    let swipe_btn_left=document.getElementById('swipe_btn_left');
    let swipe_btn_right=document.getElementById('swipe_btn_right');

    // 事件
    // 切换图片
    let select=(index)=>{
        // 停止播放
        stop();
        // 转数字
        index=Number(index);
        // 越界超过最大数量,直接返回
        if(index>=links.length){
            return;
        }
        // 选中当前已选中的,直接返回
        if(current_index==index){
            return;
        }
        // 取消当前指示点的选中状态
        if(current_index>-1){
            swipe_select.children[current_index].classList.remove('checked');
        }
        // 变更当前轮播图的编号
        current_index=index;
        // 找到当前元素
        let current_link=links[current_index];
        // 背景变化
        swipe_bg.style.backgroundImage='url('+current_link.image+')';
        // 前景变化
        swipe_img.setAttribute('src',current_link.image);
        // 链接变化
        swipe_link.setAttribute('href',current_link.target);
        // 增加新的指示点的选中状态
        swipe_select.children[current_index].classList.add('checked');
    };
    // 自动切换图片
    let autoSelect=(index)=>{
        // 转数字
        index=Number(index);
        // 越界超过最大数量，直接返回
        if(index>=links.length){
            return;
        }
        // 选中当前已选中的，直接返回
        if(current_index==index){
            return;
        }
        // 取消当前指示点的选中状态
        swipe_select.children[current_index].classList.remove('checked');
        // 变更当前轮播图的编号
        current_index=index;
        // 找到当前元素
        let current_link=links[current_index];
        // 前景图片
        // 第一步调整过渡时间
        swipe_img.style.transition='opacity 0.5s ease-in 0s';
        // 第二步调整不透明度为0.2
        swipe_img.style.opacity=0.2;
        // 第三步延迟变换img图片，并重新定义透明度以及过渡时间和过渡方式
        setTimeout(() => {
            // 背景变化
            swipe_bg.style.backgroundImage='url('+current_link.image+')';
            // 前景变化
            swipe_img.setAttribute('src',current_link.image);
            // 链接变化
            swipe_link.setAttribute('href',current_link.target);
            // 不透明度变化
            swipe_img.style.transition='opacity 0.7s ease-out 0s';
            swipe_img.style.opacity=1;
            // 增加新的指示点选中状态
            // 如果已经通过手动点击了，选中则此处不再执行
            if(!document.querySelector('.swipe .checked')){
                swipe_select.children[current_index].style.transition='background-color 0.5s';
                swipe_select.children[current_index].classList.add('checked');
            }
        }, 500);
    };
    // 播放
    let play=()=>{
        // 3秒切换一次
        swipe_timer=setInterval(()=>{
            // 设置新的index
            let index=current_index+1;
            // 右翻越界，切到第一张
            if(index>=links.length){
                index=0;
            }
            // 加载新图片（这里选择自动，增加切换效果）
            autoSelect(index);
        },3000);
    };
    // 停止
    let stop=()=>{
        if(swipe_timer){
            clearInterval(swipe_timer);
            swipe_timer=null;
        }
    };
    // 初始化
    let init=()=>{
        for(let i=0;i<links.length;i++){
            // 创建a元素
            let item=document.createElement('a');
            // 修改属性
            item.setAttribute('class','item');
            item.setAttribute('href','#');
            item.setAttribute('data-index',i);
            // 追加元素
            swipe_select.appendChild(item);
        }
        // 默认第一张
        select(0);
        // 绑定各个事件并开始轮播
        bind();
        play();
    };
    // 绑定
    let bind=()=>{
        // 左翻事件监听
        swipe_btn_left.addEventListener('click',()=>{
            // 设置新的index
            let index=current_index-1;
            // 左翻越界，切到最后一张
            if(index<0){
                index=links.length-1;
            }
            // 加载新图片
            select(index);
        });
        // 右翻事件监听
        swipe_btn_right.addEventListener('click',()=>{
            // 设置新的index
            let index=current_index+1;
            // 右翻越界，切到第一张
            if(index>=links.length){
                index=0;
            }
            // 加载新图片
            select(index);
        });
        // 循环绑定指示器点击事件
        for(const key in swipe_select.children){
            if(swipe_select.children.hasOwnProperty(key)){
                const element=swipe_select.children[key];
                element.addEventListener('click',(e)=>{
                    // 取消默认点击跳转
                    e.preventDefault();
                    // 跳转到当前指示点中data-index所指定的图片
                    select(e.target.dataset.index);
                });
            }
        }
        // 绑定鼠标移入事件
        swipe.addEventListener('mouseover',(e)=>{
            // 防止鼠标从子元素移出时触发
            if(e.relatedTarget&&swipe.compareDocumentPosition(e.relatedTarget)==10){
                stop();
            }
        });
        // 绑定鼠标移出事件
        swipe.addEventListener('mouseout',(e)=>{
            // 防止鼠标从子元素移出时触发
            if(e.relatedTarget&&swipe.compareDocumentPosition(e.relatedTarget)==10){
                play();
            }
        });
        // 绑定鼠标移动事件
        swipe.addEventListener('mousemove',(e)=>{
            stop();
        });
    };

    // 页面加载完毕，执行初始化
    window.addEventListener('load',()=>{
        init();
    })
</script>

<!--电影内容-->
<div class="content">
   
    <!--左边栏-->
    <div class="left_content">
        <div class="hot_movie">
            <div class="hot">
                <span>最近热门电影</span>
                <ul class="tag_list">
                    <li><a href="" class="on">热门</a></li>
                </ul>
            </div>

                <div class="movie">
                    <?php
                        // 连接到数据库
                        $conn = mysqli_connect("localhost", "root", "yang2004020015", "userinformation");

                        if (!$conn) {
                            die("连接数据库出错: " . mysqli_connect_error());
                        }
                    // 从数据库中获取图片路径
                        $sql = "SELECT filmname, image FROM film"; // 查询所有电影名称和图片路径
                        $result = mysqli_query($conn, $sql);

                        if ($result) {
                            if (mysqli_num_rows($result) > 0) {
                            // 保存查询结果到 $movies 数组
                                $movies = mysqli_fetch_all($result, MYSQLI_ASSOC);
                            // 输出数据
                                $counter = 0; // 计数器
                                echo "<div class='image-container'>"; // 开始一个图片容器
                                foreach ($movies as $movie) {
                                    $filmname = $movie['filmname'];
                                    $image = $movie['image'];
                    ?> 
                    <a href="movie_introduce.php?filmname=<?php echo urlencode($filmname); ?>">
                    <img style="width:180px;height:240px;" class="photo" src="<?php echo $image; ?>" alt="<?php echo $filmname; ?>" /><br>
                    <span class="mv_name"><?php echo $filmname; }}}?></span>
                    </a>
                </div>
        </div>
    </div>
</div>
 <!--右边栏-->
 <div class="right_content">
    <div class="weekly_ranking">
        <h6>一周口碑榜
            <span><a>更多榜单>></a></span>
        </h6>
        <table>
            <tr>
                <td class="order">1</td>
                <td class="title">
                    <a href="">泰勒·斯威夫特：时代巡回演唱会</a>
                </td>
            </tr>
            <tr>
                <td class="order">2</td>
                <td class="title">
                    <a href="">完美的日子</a>
                </td>
            </tr>
            <tr>
                <td class="order">3</td>
                <td class="title">
                    <a href="">要太期待世界末日</a>
                </td>
            </tr>
            <tr>
                <td class="order">4</td>
                <td class="title">
                    <a href="">留校联盟</a>
                </td>
            </tr>
            <tr>
                <td class="order">5</td>
                <td class="title">
                    <a href="">绿色边境</a>
                </td>
            </tr>
            <tr>
                <td class="order">6</td>
                <td class="title">
                    <a href="">杨妮克</a>
                </td>
            </tr>
            <tr>
                <td class="order">7</td>
                <td class="title">
                    <a href="">旺卡</a>
                </td>
            </tr>
            <tr>
                <td class="order">8</td>
                <td class="title">
                    <a href="">花月杀手</a>
                </td>
            </tr>
            <tr>
                <td class="order">9</td>
                <td class="title">
                    <a href="">五月十二月</a>
                </td>
            </tr>
            <tr>
                <td class="order">10</td>
                <td class="title">
                    <a href="">金色茧房</a>
                </td>
            </tr>
        </table>
    </div>
    <div class="hot_list">
        <h6>热门片单</h6>
        <ul>
            <li>

                <div class="title">
                    <a href="">自从得了神经病后整个人精神多了</a>
                    <span>347推荐</span>
                </div>
            </li>
            <li>
                <div class="title">

                    <a href="">古典名著小剧集(BBC CLASSIC DRAMA)</a>
                    <span>4855推荐</span>
                </div>

            </li>
        </ul>
    </div>
    <div class="contact_and_cooperation">
        <h6>合作联系</h6>
        <ul>
            <li>
                <div class="title">
                    <span>电影合作邮箱：movie@douban.com</span>
                    <br><br>
                    <span>电视剧合作邮箱：tv@douban.com</span>
                </div>
            </li>
        </ul>
    </div>
</div>


    </body>
</html>