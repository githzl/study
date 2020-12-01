<?php
	$redis = new redis();
	$redis->connect("127.0.0.1",6379);

	$server = new swoole_websocket_server("0.0.0.0", 9501);
	$server->set(array(
		'worker_num' => 4,   //工作进程数量
    		'daemonize' => false, //是否作为守护进程
	));	
	$server->on('open',function($server,$request){
		global $redis;
		$redis->sadd($request->cookie['group'],$request->fd);
		$redis->hmset("hfd".$request->fd,array("username" => $request->cookie['username'] ,"group" => $request->cookie['group']));
		$info = $redis->smembers($request->cookie['group']);
		$obj = json_decode(substr(base64_decode($request->cookie['username']),0,-3));
	
		foreach($redis->smembers("lixian".$request->cookie['group']) as $user){
			if($user == $obj->user){
			foreach($redis->lrange("xiaoxi".$request->cookie['group'],0,-1) as $message){
				$server->push($request->fd,htmlspecialchars($message));
			}
			}
		
		}

		for($i = 1;$i < count($info);$i++){
		$server->push($info[$i],$obj->user."加入聊天室");
		}
	});

	$server->on('message',function($server,$request){
		global $redis;
		$info = $redis->hgetall('hfd'.$request->fd);
		$obj = json_decode(substr(base64_decode($info['username']),0,-3));
	foreach($redis->smembers($info['group']) as $id){
		if($id != 0){
		$server->push($id,$obj->user."说".htmlspecialchars($request->data));
		}
	}
	$redis->rpush('xiaoxi'.$info['group'],$obj->user."说".$request->data);
	});

	$server->on('close',function($server,$request){
		global $redis;
		$info = $redis->hgetall("hfd".$request);
		$obj = json_decode(substr(base64_decode($info['username']),0,-3));
		foreach($redis->smembers($info['group']) as $id){
			if($id != 0 && $id != $request){
			$server->push($id,$obj->user."离开聊天室");
		}
		}
		$redis->sadd('lixian'.$info['group'],$obj->user);
		$redis->srem($info['group'],$request);
	});
	$server->start();
