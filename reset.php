<?php

/**
 * 多路径交通流分配 - reset.php - 重置文件
 *
 * 重置cache.php & CountResult.html
 *
 * Author:resuly
 * Date: 2014-4-27
 *
 */

// 设置输出头部信息
header("Content-type: text/html; charset=utf-8");

$text_cache = "<?php

/**
 * 多路径交通流分配 - cache.php - 缓存文件
 *
 * 记录形心之间的分配结果，以数组的形式返回写入
 *
 * Author:resuly
 * Date: 2014-4-27
 *
 */

//以上php头部信息保留

";

$text_CountResult = "<title>分配计算过程</title><meta charset=\"UTF-8\">

";

//写入文件1
$file_pointer = fopen("cache.php", "w+");
fwrite($file_pointer, $text_cache);
fclose($file_pointer);

//写入文件2
$file_pointer = fopen("CountResult.html", "w+");
fwrite($file_pointer, $text_CountResult);
fclose($file_pointer);

echo "Cache.php & CountResult.html 重置已完成~ <br>";

echo "<script type=\"text/javascript\">
		setTimeout(\"closeWin()\",300);
		function closeWin() {
	    	window.opener=null;window.open('','_self');window.close();
		}
	  </script>";
