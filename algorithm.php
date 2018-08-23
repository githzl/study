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
