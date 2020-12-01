<?php


session_start();
//var_dump($_COOKIE['username']);die;
if(!isset($_COOKIE['username']))
{
    header("location:http://www.hezhongli.cn/login.html");
}
if(!isset($_COOKIE['group'])){
   header("location:http://www.hezhongli.cn/list.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta charset="UTF-8">
 <script typet="text/javascript" src="http://libs.baidu.com/jquery/1.9.1/jquery.min.js"></script>
<script type='text/javascript'>
	function scrollToBottom(){
	$("#content").scrollTop(100000000000);
	}
</script>
    <script type="text/javascript">
        if(window.WebSocket){
            var webSocket = new WebSocket("ws://www.hezhongli.cn:9501");
            webSocket.onopen = function (event) {
//                webSocket.send("Hello,WebSocket!");
            };
            webSocket.onmessage = function (event) {
                var content = document.getElementById('content');
                content.innerHTML = content.innerHTML.concat('<p style="margin-left:20px;height:20px;line-height:20px;">'+event.data+'</p>');
	scrollToBottom(); 
           }

            var sendMessage = function(){
//		var cookie = document.cookie;
		//alert(cookie);
 //            var str = cookie.match(/username=(\S)/)[1];
             //alert(str); 
 	//var str = cookie.split(";")[1].split("=")[2];
		 var data = document.getElementById('message').value ;
                webSocket.send(data);
		document.getElementById('message').value = '';
            }
            webSocket.onclose = function (event) {
                var cookie = document.cookie;
        //        var str = cookie.split(";")[0].split("=")[1];
	
		var str = cookie.match(/username=(\S*)/)[1];
                webSocket.send(str);

            }

        }else{
            console.log("您的浏览器不支持WebSocket");
        }
    </script>
</head>
<body>
	<center>	<a href="http://www.hezhongli.cn/list.php"> 退出聊天室</a></center>
<div style="width:600px;margin:0 auto;border:1px solid #ccc;">
    <div id="content" style="overflow-y:auto;height:300px;"></div>
    <hr/>
    <div style="height:40px">
        <input type="text" id="message" style="margin-left:10px;height:25px;width:450px;">
        <button onclick="sendMessage()" style="height:28px;width:75px;">发送</button>
    </div>
</div>

</body>
</html>
