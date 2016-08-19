<?php

/**
 * 多路径交通流分配 - handle.php - 处理文件
 *
 * Author:resuly
 * Date: 2014-4-27
 *
 */

// 设置输出头部信息
header("Content-type: text/html; charset=utf-8");

// 设置php配置信息
// error_reporting(0);//关闭调试警告信息
set_time_limit(0);

// 引入类库和函数
require './lib/Dijkstra/Dijkstra.php';
require './lib/PHPExcel/Classes/PHPExcel/IOFactory.php';
include './functions.php';

/**
 * 全局变量部分
 */
// 接收将计算的两个形心
$GetName = explode("-", $_GET['name']);

// 路网文件名
$Filename = 'ways.xlsx';

// 形心-节点 文件名
$Filename_CentreName = 'way-CentreName.xlsx';

// 路网文件名
$Filename_Flow = 'flows.xlsx';

// 创建路网
$way = new Graph();
CreatWays();

//小区A
$CentreAName = $GetName['0'];

//小区B
$CentreBName = $GetName['1'];

//小区A到B的交通量
$Flows = ReadFlow($CentreAName,$CentreBName);

//记录will分配路径的数组
$P_Array = array();

//记录will分配路径的临时数组 记录算过的节点
$P_ArrayTemp = array();

//记录Pij的数组
$P_Array_Pij = array();

// 记录路段流量
$Q_Arary = array();

/**
 * 形心读取相应节点
 */
$CentreA = ReadCentreName($CentreAName);
$CentreB = ReadCentreName($CentreBName);

/**
 * 分配交通量部分
 */
echo 
"<br>
************************************************<br>
 * 交通小区 $CentreAName 到交通小区 $CentreBName 的分配计算过程<br>
 * 节点为 $CentreA 到 $CentreB <br>
 * 交通量 $Flows <br>
************************************************<br>
";


// 核心计算步骤 返回交通量分配数组
$Q_result = CountRoutes($CentreA, $CentreB, $Flows);

/**
 * 记录交通量部分,页面执行一次增加记录一次,连续写入cache.php文件
 */

//缓存
$file = "cache.php";

//写入文件
$name = $CentreAName . "_" . $CentreBName;
$text = "$" . "Q_" . $name . " = " . var_export($Q_result, true) . ";";

$file_pointer = fopen($file, "a+");
fwrite($file_pointer, $text);
fclose($file_pointer);
?>