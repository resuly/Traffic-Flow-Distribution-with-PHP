<?php

/**
 * 多路径交通流分配 - handle-cache-to-Q.php - 缓存文件
 *
 * 处理缓存文件中记录的形心之间的分配结果，整理成交通流的方式输出
 *
 * Author:resuly
 * Date: 2014-4-27
 *
 */

header("Content-type: text/html; charset=utf-8");

//关闭调试警告信息
error_reporting(0);

require './cache.php';
include './lib/PHPExcel/Classes/PHPExcel.php';
include './lib/PHPExcel/Classes/PHPExcel/Writer/Excel2007.php';
$objPHPExcel = new PHPExcel();
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

function convertUTF8($str){
   if(empty($str)) return '';
   return  iconv('gb2312', 'utf-8', $str);
}

$NodeNum = 0;

function getCetreNum() {
    global $NodeNum;
    
    // 引入类库和函数
    require './lib/PHPExcel/Classes/PHPExcel/IOFactory.php';
    require './functions.php';
    
    // 读取XLS部分
    $inputFileName = "./doc/way-CentreName.xlsx";
    $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
    $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
    
    // 节点数量
    $NodeNum = $sheetData['1']['C'];
    
    // 小区数量
    $Num = 0;
    $xiaoqunumber = array();
    foreach ($sheetData as $value) {
        if (empty($value['A'])) {
            $Num = $Num - 1;
            
            //表格的行数保存
            
        }
        $Num++;
    }
    return $Num;
}

// 小区数量
$Num = getCetreNum();

$FlowsAarry = array();
$TempAarry = array();

for ($i = 1; $i <= $Num; $i++) {
    for ($j = 1; $j <= $Num; $j++) {
        if ($i != $j) {
            $AarryName = 'Q_' . $i . '_' . $j;
            $TempAarry = $$AarryName;
            foreach ($TempAarry as $key => $value) {
                $key_new = $key;
                
                $arr = explode("-", $key);
                if ($arr['0'] > $arr['1']) {
                    $key_new = $arr['1'] . "-" . $arr['0'];
                }
                
                if (!empty($FlowsAarry[$key_new])) {
                    $FlowsAarry[$key_new] = $value + $FlowsAarry[$key_new];
                    
                    // $FlowsAarry[] = array( 'node' => $key_new,'Q' => $value + $FlowsAarry[$key_new]);
                    
                    
                } else {
                    $FlowsAarry[$key_new] = $value;
                    
                    // $FlowsAarry[] = array( 'node' => $key_new,'Q' => $value);
                    
                    
                }
            }
        }
    }
}

//定义第一行标题
$objPHPExcel->getActiveSheet()->setCellValue('A1',  "路段" );
$objPHPExcel->getActiveSheet()->setCellValue('B1', "交通量" );
//定义第二行写入数据
$hang = 2;
// 循环写入
for ($i = 1; $i <= $NodeNum; $i++) {
    foreach ($FlowsAarry as $key_show => $value_show) {
        $arr2 = explode("-", $key_show);
        if ($i == $arr2[0]) {
            // echo "$key_show $value_show <br>";
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $hang, $key_show );
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $hang, $value_show );
            $hang++;
        }
    }
}


/// 直接输出到浏览器
header("Pragma: public");
header("Expires: 0");
header("Content-Type:application/force-download");
header("Content-Type:application/vnd.ms-execl");
header("Content-Type:application/octet-stream");
header("Content-Type:application/download");;
header('Content-Disposition:attachment;filename="交流流分配汇总结果.xlsx"');
header("Content-Transfer-Encoding:binary");
$objWriter->save('php://output');
// $objWriter->save("./xxx.xlsx");
?>