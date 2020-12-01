<?php
    /*第一题
    请统计域名下同一目录下的数字相加
    例如：统计$arr = array(
        array('http://www.abc.com/a/', 100, 150),
        array('http://www.abc.com/a/', 100, 100));
    返回：array('http://www.abc.com/a/', 200, 250);
    */
    $items = array(
        array('http://www.abc.com/a/', 100, 120),
        array('http://www.abc.com/b/index.php', 50, 80),
        array('http://www.abc.com/a/index.html', 90, 100),
        array('http://www.abc.com/a/?id=12345', 200, 33),
        array('http://www.abc.com/c/index.html', 10, 20),
        array('http://www.abc.com/abc/', 10, 30)
    );
    $arr = [];
    foreach ($items as $item){
        $strPos = strrpos($item[0],'/'); //获取$item[0]中'/'最后出现的位置
        $cutUrl = substr($item[0],'0',$strPos + 1); //获取裁剪后的Url地址并保留'／',所以第二个参数＋1
        if(isset($arr[$cutUrl])){
            $arr[$cutUrl][1] += $item[1];
            $arr[$cutUrl][2] += $item[2]; //如果设置了相同的key，$item[1]和$item[2]累加
        }else{
            $arr[$cutUrl] = array($cutUrl,$item[1],$item[2]);
        }
    }
    $arr = array_values($arr); //只取$arr的val值,去掉key
    //var_dump($arr);
    /*第二题
    猴子选大王问题
    一群猴子编号是1，2，3 ...n的猴子围成一圈，数到m的猴子出局，这样依次下来，求最后一只猴子编号（大王）。
    其中'n'为一共几只猴子，'m'为第几只猴子出局
    */
    function king($n,$m){
        $monkeyArr = range(1,$n);
        $i = 0;
        while(count($monkeyArr) > 1){
            if(($i + 1) % $m != 0){
                array_push($monkeyArr,$monkeyArr[$i]); //如果不能被整除代表猴子不能淘汰，所以把该猴子添加到尾部。
            }
            unset($monkeyArr[$i]); // 是否淘汰都销毁，因为不淘汰的话上一步已经把猴子添加到尾部。
            $i++;
        }
        return $monkeyArr;
    }
    //var_dump( king(4,3));
    /*第三题
    计算得分题
    总分为'total'，每小题得分为'number'，学生提交的答案'commits'，题目的真实答案'answers'
    */
    $total = 100;
    $number = 5;
    $commits = 'A,B,B,A,C,C,D,A,B,C,D,C,C,C,D,A,B,C,D,A';
    $answers = 'A,A,B,A,D,C,D,A,A,C,C,D,C,D,A,B,C,D,C,D';
    $commitsArr = explode(',', $commits);
    $answersArr = explode(',',$answers); // 以','拆分字符串为数组
    $arr = array_intersect_assoc($answersArr,$commitsArr); // 按索引取两数组交集
    $lastNumber = count($arr) * $number; // 正确的题目数 * 每题的分数
    //var_dump($lastNumber);
    /*第四题
    使用php://input接收post(raw格式)提交的参数，从DB中获取数据并使用var_export写入文件缓存，下次访问从文件中获取数据。
    */
    if(file_exists('cache.php')){
        $arr = include ('cache.php');
    }else{
        $requestStr = file_get_contents('php://input'); // username=zhangsan&password=123456
        $requestArr = explode('&',$requestStr);
        $whereStr = '';
        foreach ($requestArr as $user){
            if(empty($whereStr)) {
                $whereStr .= $user;
            }else {
                $whereStr .= ' AND ' . $user;
            }
        }
        $mysqlLink = mysqli_connect('127.0.0.1','root','pass','project');
        $res = mysqli_query($mysqlLink,'SELECT * FROM user WHERE '.$whereStr.' LIMIT 0,1');
        $user = mysqli_fetch_assoc($res);
        $fileCacheStr = '<?php return ';
        $fileCacheStr .= var_export($user,true);
        $fileCacheStr .= ';';
        file_put_contents('cache.php',$fileCacheStr); // 如果权限不够请使用chmod命令赋予权限。
        $arr = $user;
    }
    //var_dump($arr);
    /*第五题
    实现一个对象的数组式访问接口
    */
    class Obj implements Arrayaccess {
        private $container = array();
        public function __construct() {
            $this->container = array(
                "one"   => 1,
                "two"   => 2,
                "three" => 3,
            );
        }
        public function offsetSet($offset, $value) {
            if (is_null($offset)) {
                $this->container[] = $value;
            } else {
                $this->container[$offset] = $value;
            }
        }
        public function offsetExists($offset) {
            return isset($this->container[$offset]);
        }
        public function offsetUnset($offset) {
            unset($this->container[$offset]);
        }
        public function offsetGet($offset) {
            return isset($this->container[$offset]) ? $this->container[$offset] : null;
        }
        public function test(){
        }
    }
    $obj = new Obj();
    $obj[] = 'Append 1'; // 从下标为0的开始。
    $obj["two"] = "A value"; // 把为two的下标重新赋值。
    //var_dump($obj);
    /*第六题
    有1000瓶水，其中有1瓶有毒，小白鼠只要尝一点带毒的水24小时后就会死亡，问至少要多少只小白鼠才能在24小时鉴别出哪瓶水有毒？
    */
    #答案：10只。
    #解题思路：我们需要把1000转化成二进制,第1瓶转二进制为0000000001,第1000瓶转为二进制为1111101000
    #   第一只｜第2只｜第3只｜第4只｜第5只｜第6只｜第7只｜第8只｜第9只｜第10只
    #     0  ｜ 0  ｜  0 ｜  0  ｜ 0  ｜ 0  ｜  0  ｜ 0  ｜  0  ｜ 1 ｜
    #随着的增加毒水的递增（也就是二进制的递增），只要是遇见1了，对应的小老鼠就得喝，直到喝到1000瓶。
    #这样到1000瓶的时候我们判断那些老鼠去世了可以得出一个唯一的二进制值。转化为10进制就是有毒的那瓶。
    #而1000的二进制至少要10位来表达，所以最少得10只小老鼠（ T T... 心疼小白鼠三秒钟）。
    /*第七题
    使用serialize序列化一个对象，并使用__sleep和__wakeup方法。
    */
    class Test{
        public $name = '冰冰美女';
        protected $wx = 'bingbing';
        //当对象被序列化,类会调用__sleep魔术方法
        public function __sleep(){
            echo  '想要我微信没门';
        }
        public function __wakeup(){
            echo '我又回来啦！';
        }
    }
    $bingbing = new Test();
    $str = serialize($bingbing);
    $newbingbing = unserialize($str);
    /*第八题
    利用数组栈实现翻转字符串功能
    */
    $str = 'abcd';
    $arr = str_split($str, 1);
    $newStr = '';
    foreach ($arr as $v)
    {
        $newStr .= array_pop($arr);
    }
    //var_dump($newStr);
    /*第九题
    从m个数中选出n个数来 ( 0 < n <= m) ，要求n个数之间不能有重复，其和等于一个定值k，求一段程序，罗列所有的可能。
    例如：备选的数字是：11, 18, 12, 1, -2, 20, 8, 10, 7, 6 ，和k等于：18
    */
    $k = 18;
    $number_arr = [11, 18, 12, 1, -2, 20, 8, 10, 7, 6];
    $count = pow(2, count($number_arr));    // 2的10次方  $count ＝ 1024;
    $map = [];
    $sprintfStr = "%0".count($number_arr)."d";
    for ($i = 0; $i < $count; $i ++) {
        $bins = sprintf($sprintfStr, decbin($i)); //十进制转二进制，前面不够补0，比如1的二进制为0000000001(补10位的情况下)
        $bin_arr = str_split($bins, 1);
        $item = [];
        foreach ($bin_arr as $key => $bin) {
            if ('1' === $bin) {
                $item[$key] = $number_arr[$key];
            }
        }
        if ($k == array_sum($item)) {
            $map[] = $item;
        }
    }
    //var_dump($map);

