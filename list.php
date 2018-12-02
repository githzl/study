<?php
	if(!isset($_COOKIE['username'])){
	header('location:http://www.hezhongli.cn/login.html');
	}
	$redis = new redis();
	$redis->connect('127.0.0.1',6379);
	$redis->sadd("group1",'0');
	$redis->sadd("group2",'0');
	$redis->sadd("group3",'0');
	$redis->sadd("group4",'0');
	$redis->sadd("group5",'0');
	$redis->sadd("group6",'0');
 /*	$redis->srem("group1",'1');
	$redis->srem("group2",'1');
	$redis->srem("group3",'1');
	$redis->srem("group4",'1');
	$redis->srem("group5",'1');
	$redis->srem("group6",'1');
	*/
	$grouparr = $redis->keys("group*");
	echo "<center><h2>群组</h2>";
	for($i = 0;$i < count($grouparr);$i++){
    echo "<a href='http://www.hezhongli.cn/action.php?g=".$grouparr[$i]."'>".$grouparr[$i]."群</a><br><br>";
	}
	echo "</center>";