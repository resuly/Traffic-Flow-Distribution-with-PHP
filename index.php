<?php

/**
 * 多路径交通流分配 - index.php - 首页文件
 *
 * Author:resuly
 * Date: 2014-4-28
 *
 */

// 引入类库和函数
require './lib/PHPExcel/Classes/PHPExcel/IOFactory.php';
require './functions.php';

// 读取XLS部分
$inputFileName = "./doc/way-CentreName.xlsx";
$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
$sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

// 小区数量
$Num = 0;
$xiaoqunumber = array();
foreach ($sheetData as $value) {
    if (empty($value['A'])) {
        $Num = $Num - 1;
    }
    $Num++;
}

// 按钮内容 & Footer Script 内容
$text = '';
$setAutoURL = '';
$time = 300;
for ($i = 1; $i <= $Num; $i++) {
    for ($j = 1; $j <= $Num; $j++) {
        if ($i != $j) {
            $name = $i . "-" . $j;
            $text = $text . "<a href=\"entrance.php?name=" . $name . "\"  onclick=\"this.href += '&' + '_=' +new Date().getTime()\"  class=\"button glow button-rounded button-flat-primary button-large\" target=\"_blank\">" . $name . "</a>";
            
            // $random = time();
            $setAutoURL = $setAutoURL . "setTimeout(\"window.open('http://localhost/flow/entrance.php?name=" . $name . "');\",$time);";
            $time+= 4000;
        }
    }
}

$script = "<script>function Start () {" . $setAutoURL . "}</script>";
?>

<!DOCTYPE html>
<html>

    <head>  
        <meta charset="utf-8">
        <title>多路径交通流分配</title>
        <meta name="author" content="resuly">
        <meta http-equiv="pragma" content="no-cache" />
        <link rel="stylesheet" href="./html/css/style.css" type="text/css"/>
        <link rel="stylesheet" href="./html/css/button.css" type="text/css"/>
    </head>

    <body>
        
        <div id="wrap">
            <header id="header_with_social" class="header">
                <div class="center remindtext">
                    <a href="./CountResult.html" target="_balank">计算过程记录</a>
                    <a href="./reset.php" target="_blank"> 重置保存的文件 </a>
                </div>
            </header>
            <section id="flow_box">
                <div class="center">

                    <form class="form-2">
                        <div class="inner">
                            <h1>
                                <span class="log-in">第一步，请您点击形心 （计算完自动显示为黑色）<a href="#" onclick="Start()" style="color:#00a1cb!important">自动执行</a></span>
                            </h1>
                            <div class="handle">
                                <?php echo $text; ?>
                            </div>
                        </div>
                    </form>

                    <form class="form-2">
                        <div class="inner">
                            <h1>
                                <span class="log-in">第二步，确定完成以上步骤后，请您点击下面按钮</span>
                            </h1>
                            <div class="handle">
                                <a href="./handle-cache-to-Q.php" style="width: 180px;color: #fff" class="button glow button-rounded button-flat-primary button-large" target="_blank">路网交通量分配汇总</a>
                            </div>
                        </div>
                    </form>

                </div>
            </section>
        </div>

            <footer id="footer">
                <div class="center">
                    <div class="copyright">
                        <p>
                            多路径交通流分配 2014 By resuly
                        </p>
                    </div>
                </div>
            </footer>

      <?php echo $script; ?>
    </body>
</html>