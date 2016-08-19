<?php

/**
 * 多路径交通流分配 - entrance.php - 入口文件
 *
 * Author:resuly
 * Date: 2014-4-28
 *
 */

// 设置输出头部信息
header("Content-type: text/html; charset=utf-8");

set_time_limit(0);

$GetNodesName = $_GET['name'];

$text = file_get_contents( 'http://localhost/flow/handle.php?name='.$GetNodesName );

// var_dump($text);

/**
 * 记录计算过程部分,页面执行一次增加记录一次,连续写入CountResult.txt文件
 */

//文件名
$file = "CountResult.html";

//写入文件
$file_pointer = fopen($file, "a+");
fwrite($file_pointer, $text);
fclose($file_pointer);

echo "$GetNodesName 已计算完成~ <br>";
echo "<script type=\"text/javascript\">
		setTimeout(\"closeWin()\",300);
		function closeWin() {
	    	window.opener=null;window.open('','_self');window.close();
		}
	  </script>";

?>