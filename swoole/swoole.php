<?php
class Swoole {
	
	private $server = null; 

	public function __construct($host = '0.0.0.0', $port = 0){
		$this->server =  new \Swoole\Server($host, $port, SWOOLE_PROCESS, SWOOLE_SOCK_TCP);	
		$this->server->on('Receive', $this->onReceive);	
	}
	
	public function addListener($host, $port){
		$this->server->addListener($host, $port, SWOOLE_SOCK_TCP);
	}
	public function onReceive(){
	}
	public function onPacket(){
	
	}					
	public function start() {
		$this->server->start();
	}
}
$swooler = new Swoole();
$swooler->addListener('0.0.0.0', 9501); // http
$swooler->addListener('0.0.0.0', 9502); // websocket
$swooler->start();
var_dump($swooler);
