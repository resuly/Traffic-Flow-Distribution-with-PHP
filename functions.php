<?php

/**
 * 多路径交通流分配 - functions.php - 功能文件
 *
 * Author:resuly
 * Date: 2014-4-27
 *
 */

function CreatWays() {
    
    // 读取doc下的way.xls文件，并创建路网
    global $way, $Filename;
    
    // 读取XLS部分
    $inputFileName = "./doc/" . $Filename;
    $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
    $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
    
    //start define the route from xls
    foreach ($sheetData as $value) {
        $name = $value['A'];
        $distance = $value['B'];
        $name_arr = explode("-", $name);
        @$way->addedge("$name_arr[0]", "$name_arr[1]", $distance);
        @$way->addedge("$name_arr[1]", "$name_arr[0]", $distance);
    }
}

function ReadCentreName($name) {
    
    // 读取doc下的way.xls文件，并创建路网
    global $way, $Filename_CentreName;
    
    // 读取XLS部分
    $inputFileName = "./doc/" . $Filename_CentreName;
    $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
    $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
    
    //convert centre name to node name from file
    foreach ($sheetData as $value) {
        if ( $name == $value['A']) {
            $nodename = $value['B'];
        }
    }
    return $nodename;
}

function ReadFlow($CentreAName,$CentreBName) {
    
    // 读取doc下的flow.xls文件，并创建路网
    global $Filename_Flow;
    
    // 读取XLS部分
    $inputFileName = "./doc/" . $Filename_Flow;
    $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
    $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
    
    $CentreBName = ChangeNum($CentreBName);
    
    $Flows = $sheetData["$CentreAName"]["$CentreBName"];
    
    return $Flows;
}

function ChangeNum($num){
        switch ($num) {
            case 1:
                return 'A';
                break;
            case 2:
                return 'B';
                break;
            case 3:
                return 'C';
                break;
            case 4:
                return 'D';
                break;
            case 5:
                return 'E';
                break;
            case 6:
                return 'F';
                break;
            case 7:
                return 'G';
                break;
            case 8:
                return 'H';
                break;
            case 9:
                return 'I';
                break;
            case 10:
                return 'J';
                break;
            case 11:
                return 'K';
                break;
            case 12:
                return 'L';
                break;
            case 13:
                return 'K';
                break;
            default:
                break;
        }
}

function CountDistance($start, $end) {
    global $way;
    $wayArray = $way->paths_from($start);
    $distance = @$wayArray[0][$end];
    return intval($distance);
}

// 处理流量数组 合并调整a-b b-a 的重复路段流量
function HandleFlows($array) {
    foreach ($array as $key => $value) {
        $arr = explode("-", $key);
        if ($arr[0] > $arr[1]) {
            $array[$arr[1] . "-" . $arr[0]] = $array[$arr[1] . "-" . $arr[0]] + $value;
            unset($array[$arr[0] . "-" . $arr[1]]);
        }
    }
    return $array;
}

//计算有效路径无输出，返回数组
function CountValidNodes($Node1) {
    global $way, $CentreA, $CentreB, $Flows, $P_Array_Pij, $P_Array, $P_ArrayTemp;
    $wayArray = $way->paths_from($Node1);
    
    // $wayArray[0][];//Node1到各点的最短路
    // $wayArray[1][];//int部分为Node1相邻节点
    
    
    /**
     * 计算有效路段
     *
     * 有效路段：路段终点j比路段起点i更靠近出行目的地s的路段
     * L(a,b)为节点至节点的最短路权，路段[i,j],如果L(j,s)<L(i,s),则[i,j]为有效路段
     */
    
    // 取出相邻节点
    $NodeNearBy = array();
    foreach ($wayArray[1] as $key => $value) {
        if ($value == $Node1) {
            $NodeNearBy[] = $key;
        }
    }
    
    // 计算有效路段
    $ValidNodesArray = array();
    
    //定义有效径路总长度
    $Node1Node2Distance = CountDistance($Node1, $CentreB);
    
    foreach ($NodeNearBy as $value) {
        
        /**
         * 计算有效径路
         *
         * 有效径路：由有有效路段组成的径路
         * L(i-j, s) = d(i, j)+Lmin(j, s)
         */
        if (array_search($value, $P_ArrayTemp)) {
             //判断之前有没有分配过
            $CountResult = CountDistance($value, $CentreB);
            if ($CountResult <= $Node1Node2Distance) {
                $ValidNodesArray[] = array('node' => $value);
            }
        }
    }
    
    return $ValidNodesArray;
}

function CountPart1($Node1) {
    global $way, $CentreA, $CentreB, $Flows, $P_Array, $P_Array_Pij, $P_ArrayTemp, $Q_Arary;
    $wayArray = $way->paths_from($Node1);
    
    // $wayArray[0][];//Node1到各点的最短路
    // $wayArray[1][];//int部分为Node1相邻节点
    
    //定义Print 变量
    $print_ValidRoutes = '';
    $print_L = '';
    $print_Lw = '';
    $print_Nw = '';
    $print_Q = '';
    $print_Ei = '';
    
    /**
     * 计算有效路段
     *
     * 有效路段：路段终点j比路段起点i更靠近出行目的地s的路段
     * L(a,b)为节点至节点的最短路权，路段[i,j],如果L(j,s)<L(i,s),则[i,j]为有效路段
     */
    
    // 取出相邻节点
    $NodeNearBy = array();
    foreach ($wayArray[1] as $key => $value) {
        if ($value == $Node1) {
            $NodeNearBy[] = $key;
        }
    }
    
    // 计算有效路段
    $ValidNodesArray = array();
    
    //定义有效径路数组
    $L_Num = 0;
    
    //定义有效径路总数
    $L_Sum = 0;
    
    //定义有效径路总长度
    $Node1Node2Distance = CountDistance($Node1, $CentreB);
    
    foreach ($NodeNearBy as $value) {
        
        /**
         * 计算有效径路
         *
         * 有效径路：由有有效路段组成的径路
         * L(i-j, s) = d(i, j)+Lmin(j, s)
         */
        $CountResult = CountDistance($value, $CentreB);
        if (!array_search($value, $P_ArrayTemp)) {
            //判断之前有没有分配过
            if ($CountResult <= $Node1Node2Distance) {
                $L = CountDistance($Node1, $value) + $CountResult;
                $L_Sum = $L_Sum + $L;
                $L_Num+= 1;
                
                // echo "$value  $L<BR>";
                $ValidNodesArray[] = array('node' => $value, 'L' => $L, 'Lw' => '');
                $print_ValidRoutes = $print_ValidRoutes . "有效路段：$Node1-$value <br>";
                $print_L = $print_L . "L($Node1-$value,$CentreB)=d($Node1-$value)+Lmin($value,$CentreB)=$L<br>";
            }
        }
    }
    echo $print_ValidRoutes . $print_L;
    
    /**
     * Lw边权 & Nw点权计算部分
     *
     */
    $Nw = 0;
    
    // L平均值 保留4位小数
    if ($L_Num != 0) {
        $L_Average = round(($L_Sum / $L_Num), 4);
    }
    foreach ($ValidNodesArray as $key_ValidNodesArray => $value_ValidNodesArray) {
        $Lw = exp(-3.3 * $value_ValidNodesArray['L'] / $L_Average);
        $Lw = round($Lw, 4);
        $ValidNodesArray[$key_ValidNodesArray]['Lw'] = $Lw;
        $Nw = $Nw + $Lw;
        
        //点权
        $print_Lw = $print_Lw . "Lw($Node1," . $value_ValidNodesArray['node'] . ") = exp(-3.3 * " . $value_ValidNodesArray['L'] . " / $L_Average) = $Lw <br>";
    }
    $ValidNodesArray['Nw'] = $Nw;
    if (!empty($ValidNodesArray['Nw'])) {
        $print_Nw = "Nw($Node1)=$Nw<br>";
    }
    echo $print_Lw . $print_Nw;
    
    // var_dump($ValidNodesArray);
    
    
    
    /**
     * // 计算分配率 & 交通量
     * 计算效路段［i,j］的OD量分配率P(i,j)
     * P.S. j已经在数组$ValidNodesArray中,[i,j]即[Node1,CentreB]
     * 接收变量 $ValidNodesArray,$P_Array_Pij
     *
     * 全局变量 $Q_Arary 记录分配结果
     *
     */
    
    $Nw = $ValidNodesArray['Nw'];
    if ($Node1 == $CentreA) {
        foreach ($ValidNodesArray as $key => $value) {
            
            if (is_int($key)) {
                $P = round(($ValidNodesArray[$key]['Lw'] / $Nw), 4);
                echo "P($Node1," . $ValidNodesArray[$key]['node'] . ")=Lw($Node1," . $ValidNodesArray[$key]['node'] . ")/Nw($Node1)=$P<br>";
                $P_Array_Pij[] = array('node' => $Node1 . "-" . $ValidNodesArray[$key]['node'], 'Pij' => $P);
                $Q = round($P * $Flows, 0);
                $print_Q = $print_Q . "Q($Node1," . $ValidNodesArray[$key]['node'] . ")=$Q<br>";
                
                // 全局变量 $Q_Arary 记录分配结果
                $Q_Arary["$Node1-" . $ValidNodesArray[$key]['node']] = $Q;
                
                //待计算节点
                $P_Array[] = $ValidNodesArray[$key]['node'];
            }
        }
        echo $print_Q;
        
        // var_dump($ValidNodesArray);
        
    } else {
        
        // var_dump($ValidNodesArray);
        // var_dump($P_ArrayTemp);
        
        

        foreach ($ValidNodesArray as $key => $value) {
            
            if (is_int($key)) {
                
                $Ei = NULL;
                
                /*寻找EI*/
                foreach ($P_Array_Pij as $value_Ei) {
                    
                    $Magic_exploe_Ei = explode("-", $value_Ei['node']);
                    
                    if ($Node1 == $Magic_exploe_Ei['1']) {
                        
                        $Ei = $Ei + $value_Ei['Pij'];
                    }
                }
                $print_Ei = "E($Node1) = $Ei<br>";
                
                // 计算流率和流量
                $P = round(($Ei * $ValidNodesArray[$key]['Lw'] / $Nw), 4);
                echo "P($Node1," . $ValidNodesArray[$key]['node'] . ")=E($Node1)*Lw($Node1," . $ValidNodesArray[$key]['node'] . ")/Nw($Node1)=$P<br>";
                $Q = round($P * $Flows, 0);
                $print_Q = $print_Q . "Q($Node1," . $ValidNodesArray[$key]['node'] . ")=$Q<br>";
                
                // 全局变量 $Q_Arary 记录分配结果
                $Q_Arary["$Node1-" . $ValidNodesArray[$key]['node']] = $Q;
                
                /**
                 * 处理Pij
                 */
                $P_Array_Pij[] = array('node' => $Node1 . "-" . $ValidNodesArray[$key]['node'], 'Pij' => $P);
                
                //增加记录下一组待计算节点，第二次计算以后
                if (!array_search($ValidNodesArray[$key]['node'], $P_Array) && !array_search($ValidNodesArray[$key]['node'], $P_ArrayTemp)) {
                    $P_Array[] = $ValidNodesArray[$key]['node'];
                }
            }
        }
        echo $print_Ei.$print_Q;
    }
}

//主进程，计算形心交通量分配
function CountRoutes($CentreA, $CentreB, $Flows) {
    global $P_Array, $P_Array_Pij, $P_ArrayTemp, $Q_Arary ,$TotalNodeNum;
    
    echo "<br>";
    
    // 计算第一步 有效路径
    CountPart1($CentreA);
    
    // var_dump($P_Array_Pij);
    // var_dump($P_Array);计算节点 2 4
    $P_ArrayTemp[] = $CentreA;
    
    //////////////////////////////////////
    //第二步循环计算
    
    // $MaxStepNum 最大可能分配步骤 可适当调整
    $MaxStepNum = 400;

    for ($steps = 0; $steps <= $MaxStepNum; $steps++) { 

        if (count($P_Array)>1) {

            foreach ($P_Array as $value) {

                if ($value != $CentreB) {
                    
                    echo "<br>";
                    
                    // 计算过程
                    CountPart1($value);
                    
                    // 记录计算过的节点
                    if (!array_search($value, $P_ArrayTemp)) {
                        $P_ArrayTemp[] = $value;
                    }
                    
                    // 删除计算点
                    $key_P_Array = array_search($value, $P_Array);
                    unset($P_Array[$key_P_Array]);
                }
            }

        }else{
            if (@$P_Array[array_search($CentreB, $P_Array)] != $CentreB) {
            // var_dump($P_Array);
                foreach ($P_Array as $value) {

                    if ($value != $CentreB) {
                        
                        echo "<br>";
                        
                        // 计算过程
                        CountPart1($value);
                        
                        // 记录计算过的节点
                        if (!array_search($value, $P_ArrayTemp)) {
                            $P_ArrayTemp[] = $value;
                        }
                        
                        // 删除计算点
                        $key_P_Array = array_search($value, $P_Array);
                        unset($P_Array[$key_P_Array]);
                    }
                }
            }

        }



    }
    /*  测试调试内容，第二步，第三步
    
    // var_dump($P_Array_Pij);//首次计算结果
    // var_dump($P_Array);
    
    $test = $P_Array ;
    
    foreach ($test as $value) {
        if ($value != $CentreB) {
            CountPart1($value);
        }
        
    }
    
    // var_dump($P_Array_Pij);
    $test2 = $P_Array ;
    
    foreach ($test2 as $value) {
        if ($value != $CentreB) {
            CountPart1($value);
        }
        
    }
    
    */
    
    // var_dump($P_Array_Pij);
    // var_export($P_Array_Pij);
    
    // 计算结束，释放全局变量数组
    // unset($P_Array);
    // unset($P_ArrayTemp);
    // unset($P_Array_Pij);
    
    // 全局变量 $Q_Arary 记录分配结果
    return $Q_Arary;
}
?>