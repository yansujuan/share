<?php
//开启SESSION
session_start();

header("Content-type:text/html; charset=UTF-8");

//请求数据到短信接口，检查环境是否 开启 curl init。
function Post($curlPost,$url){
		$curl = curl_init();//函数将初始化一个新的会话，返回一个CURL句柄供curl_setopt(), curl_exec(),和 curl_close() 函数使用
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_NOBODY, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
		$return_str = curl_exec($curl);
		curl_close($curl);// 关闭cURL 会话并且释放所有资源。cURL 句柄 ch 也会被删除
		return $return_str;
		//通过curl_setopt()函数可以方便快捷的抓取网页(采集很方便大笑)，curl_setopt 是PHP的一个扩展库
     	//使用条件：需要在php.ini 中配置开启。(PHP 4 >= 4.0.2)
       	//取消下面的注释
}

//将 xml数据转换为数组格式。
function xml_to_array($xml){
	$reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
	if(preg_match_all($reg, $xml, $matches)){// 函数用于进行正则表达式匹配 没找到返回false
		$count = count($matches[0]);//count 返回数组中元素的数目
		for($i = 0; $i < $count; $i++){
		$subxml= $matches[2][$i];
		$key = $matches[1][$i];
			if(preg_match( $reg, $subxml )){//preg_match只匹配一次，preg_match_all是全文匹配，即所有跟表达式一致的都找出来。
				$arr[$key] = xml_to_array( $subxml );
			}else{
				$arr[$key] = $subxml;
			}
		}
	}
	return $arr;
}

//random() 函数返回随机整数。
function random($length = 6 , $numeric = 0) {
	PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
	if($numeric) {
		$hash = sprintf('%0'.$length.'d', mt_rand(0, pow(10, $length) - 1));
	} else {
		$hash = '';
		$chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789abcdefghjkmnpqrstuvwxyz';
		$max = strlen($chars) - 1;
		for($i = 0; $i < $length; $i++) {
			$hash .= $chars[mt_rand(0, $max)];
		}
	}
	return $hash;
}
//短信接口地址
$target = "http://106.ihuyi.cn/webservice/sms.php?method=Submit";
//获取手机号
$mobile = $_POST['mobile'];
//获取验证码
$send_code = $_POST['send_code'];
//生成的随机数
$mobile_code = random(6,1);
if(empty($mobile)){
	exit('手机号码不能为空');
}
//防用户恶意请求
if(empty($_SESSION['send_code']) or $send_code!=$_SESSION['send_code']){
	exit('请求超时，请刷新页面后重试');
}

$post_data = "account=C56241407&password=4316b12b5a0f33d9dd153befeb70a980&mobile=".$mobile."&content=".rawurlencode("您的验证码是：".$mobile_code."。请不要把验证码泄露给其他人。");
//用户名请登录用户中心->验证码、通知短信->帐户及签名设置->APIID
//查看密码请登录用户中心->验证码、通知短信->帐户及签名设置->APIKEY
$gets =  xml_to_array(Post($post_data, $target));
if($gets['SubmitResult']['code']==2){
	$_SESSION['mobile'] = $mobile;
	$_SESSION['mobile_code'] = $mobile_code;
}
echo $gets['SubmitResult']['msg'];
?>