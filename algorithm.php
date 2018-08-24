<?php
/*
 * 求指定范围内的素数(素数：只能被1或者本身整除的数，比如 3 5 7 11 ...)
 * param $start
 * param $end
 * return $arr
 */
function primeNumber($start, $end){
    $list = [];
    for ($i = $start; $i <= $end; $i++) {
        $count = 0;
        for ($j = 1; $j <= $i; $j++) {
            if ($i % $j == 0) {  // 整除1与本身，所以$count ＝ 2
                $count++;
            }
        }
        if ($count == 2) {
            $list[] = $i;
        }
    }
    return $list;
}
var_dump(primeNumber(101, 200));


/*
 * 输出国际象棋棋盘，分为8行*8列（方格要求交错显示）
 * param $int
 */
function acrossBlackWhite($int){
    for ($i = 1; $i <= $int; $i++) {
        for ($j = 1; $j <= $int; $j++) {
            if (($i + $j) % 2 != 0) {
                echo '黑';
            } else {
                echo '白';
            }
        }
        echo '<br>';
    }
}
acrossBlackWhite(8);


/*
 * 99乘法表
 * param $int
 */
function multiplicationTable($int){
    for ($i = 1; $i <= $int; $i++)
    {
        for ($j = 1; $j <= $i; $j++)
        {
            echo sprintf('%s*%s=%s'.PHP_EOL,$i,$j,$j*$i);
        }
        echo '<br>';
    }
}
multiplicationTable(9);


/*
 * 输入某年某月某日，判断这一天是这一年的第几天
 * param $y
 * param $m
 * param $d
 * return $count;
 */
$monthArr = [
    '0' => 0,
    '1' => 31,
    '2' => 28, // 闰年下29天
    '3' => 31,
    '4' => 30,
    '5' => 31,
    '6' => 30,
    '7' => 31,
    '8' => 31,
    '9' => 30,
    '10' => 31,
    '11' => 30,
    '12' => 31,
];
function compute($y, $m, $d){
    global $monthArr;
    if(($m > 2) && ($y % 4 == 0) && (($y % 100 != 0) || ($y % 400 == 0))){
        $monthArr[2] = 29;
    }
    $newArr = array_slice($monthArr,0,$m);
    $count = array_sum($newArr);
    return $count + $d;
}
echo compute(2018,1,2);


/*
 * 有1、2、3、4个数字，能组成多少个互不相同且无重复数字的三位数？都是多少？
 * param $arr
 * return $newArr;
 */
function noRepetition($arr){
    $newArr = [];
    $count = count($arr);
    for($i = 0; $i < $count; $i++)
    {
        for ($j = 0; $j < $count; $j++)
        {
            for ($k = 0; $k < $count; $k++)
            {
                if(($arr[$k] != $arr[$j]) && ($arr[$k] != $arr[$i]) && ($arr[$i] != $arr[$j]))
                {
                    $newArr[] = sprintf('%s,%s,%s',$arr[$i],$arr[$j],$arr[$k]);
                }
            }
        }
    }
    return $newArr;
}
var_dump(noRepetition([1,2,3,4]));


/*
 * 阿姆斯特朗数问题 是指一个n位数 ( n≥3 )，它的每个位上的数字的n次幂之和等于它本身
 * 假设n=3,数字为153，那么1的3次方 + 5的3次方 + 3的3次方刚好等于153，那么就符合 阿姆斯特朗数
 * 求100-999中的阿姆斯特朗数，或者1000－9999中的阿姆斯特朗数。
 * param $start
 * param $end
 * param $power
 * return $list
 */
function checkArmstrongNumber($start, $end, $power){
    if($power < 3) return die('power need ge 3');
    $list = [];
    for ($i = $start; $i <= $end; $i++) {
        $count = NULL;
        for ($j = 0; $j < $power; $j++) {
            $count += pow(strval($i)[$j],$power); // 把自己的N次幂相加
        }
        if($i == $count) {
            $list[] = $i;
        }
    }
    return $list;
}
$list = checkArmstrongNumber(1000,9999,4); // array(1634, 8208, 9474) or checkArmstrongNumber(100,999,3);


/*
 * 实现一个数的垒加自身，N次垒加后的值。(2,2)=2+2+4=8，(3,2)=3+3+6
 * param $number
 * param $count
 * return $value
 */
function baseAdd($number, $count){
    return $number << $count; // 位运算
}
echo baseAdd(3,2);
