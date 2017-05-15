<?php
  
//第一步：用户注册时输入手机号，网站首先要通过JS或者ajax+php验证这个号码是不是正确的手机号。
//第二步：用户点击发送手机验证码，通过ajax把手机号传到php，这时php生成一个随机的验证码保存在session中，然后通过短信接口把这个验证码发送到这个手机号中。
//第三步：用户输入手机收到的验证码注册。网站用session中的验证码和用户输入的验证码比较。
session_start();
if($_POST){
	//echo '<pre>';print_r($_POST);print_r($_SESSION);
	//前者全文字显得正规，档次，只是or混在其中不是很好找；后者||符号明显好找，易懂。优先级顺序是&& || and or 
	if($_POST['mobile']!=$_SESSION['mobile'] or $_POST['mobile_code']!=$_SESSION['mobile_code'] or empty($_POST['mobile']) or empty($_POST['mobile_code'])){
		exit('手机验证码输入错误。');	
	}else{
		//清掉session的存储 防止缓存
		$_SESSION['mobile'] = '';
		$_SESSION['mobile_code'] = '';	
		exit('注册成功。');	
	}
}
//empty是php内置的一个函数。 这个函数就是判断一个变量或者表达式是否为空。
//"" 双引号，表示一个空的字符串，它的数据类型为字符串类型。 0 零，表示数字0，它的数据类型为整型 null 表示表示一个变量没有值。一个变量为null有三种情况： 1.被赋值为 NULL 。 2. 尚未被赋值。 3. 被 unset() 。 
//empty  是php的一个判断变量为空的函数，如果 变量 是非空或非零的值，则     empty()  返回  FALSE 。换句话说，""、0、"0"、 NULL 、 FALSE 、array()、var $var;   以及没有任何属性的对象都将被认为是空的，如果变量 为空，则 empty() 返回 TRUE 
function random($length = 6 , $numeric = 0) {
	PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
	//srand((double)microtime()*1000000)；
	//分为4个步骤1:执行microtime(),获取当前的微秒数
	//2:把获取的微秒数转换为double类型
	//3:再用转换后的数字去乘以1000000
	//4:给随机数发生器播种,播种数为第三步得出的结果
	//rand为生成0到RAND_MAX 之间的伪随机整数,RAND_MAX的值因平台不同而不同
	//srand() 函数作用是播下随机数发生器种子

	if($numeric) {
		$hash = sprintf('%0'.$length.'d', mt_rand(0, pow(10, $length) - 1));
		//函数sprintf()用来作格式化的输出;
		//Pow是Power的简写，意思是幂 pow(10,i)的意思就是10的i次幂 
		//mt_rand()作用都是产生一个随机整数
	} else {
		$hash = '';
		$chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789abcdefghjkmnpqrstuvwxyz';
		$max = strlen($chars) - 1;
		for($i = 0; $i < $length; $i++) {
			$hash .= $chars[mt_rand(0, $max)];
			//连续定义变量！
			/*累积
			$a = 'a'; //赋值
			$b = 'b'; //赋值
			$c = 'c'; //赋值
			$c .= $a;
			$c .= $b;
			echo $c; 就会显示 cab
			*/
		}
	}
	return $hash;
}
$_SESSION['send_code'] = random(6,1);//验证赋值
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=gb2312" />
<title>demo</title>
<style>
	input{
		display: inline-block;
		height: 30px;
	}
	#zphone{
		display: inline-block;
		height: 36px;
	}
</style>
</head>
<script type="text/javascript" src="jquery.js"></script>
<script language="javascript">
	function get_mobile_code(){
        $.post('sms.php', {mobile:jQuery.trim($('#mobile').val()),send_code:<?php echo $_SESSION['send_code'];?>}, function(msg) {
            alert(jQuery.trim(unescape(msg)));
			if(msg=='提交成功'){
				RemainTime();
			}	
        });
	};
	var iTime = 59;
	var Account;
	function RemainTime(){
		document.getElementById('zphone').disabled = true;
		var iSecond,sSecond="",sTime="";
		if (iTime >= 0){
			iSecond = parseInt(iTime%60);
			iMinute = parseInt(iTime/60)
			if (iSecond >= 0){
				if(iMinute>0){
					sSecond = iMinute + "分" + iSecond + "秒";
				}else{
					sSecond = iSecond + "秒";
				}
			}
			sTime=sSecond;
			if(iTime==0){
				clearTimeout(Account);
				sTime='获取手机验证码';
				iTime = 59;
				document.getElementById('zphone').disabled = false;
			}else{
				Account = setTimeout("RemainTime()",1000);
				iTime=iTime-1;
			}
		}else{
			sTime='没有倒计时';
		}
		document.getElementById('zphone').value = sTime;
	}	
</script>
<body>
<form action="reg.php" method="post" name="formUser">
	<table width="100%"  border="0" align="left" cellpadding="5" cellspacing="5">
		<tr>
			<td align="right">手机号码:<td>
		<input id="mobile" name="mobile" type="text" size="35" class="inputBg" placeholder="请输入手机号"/></span> 
        </td>
        </tr>
		<tr>
			<td align="right">验证码:</td>
			<td><input type="text" size="20" name="mobile_code" class="inputBg" placeholder="请输入手机6位校验码" /><span style="color:#FF0000"> *<input id="zphone" type="button" value=" 获取验证码 " onClick="get_mobile_code();"></td>
		</tr>
		<tr>
			<td align="right">登录密码:<td>
		<input type="password" size="35" class="inputBg" placeholder="请输入密码"/></span> 
        </td>
        </tr>
        <tr>
			<td align="right">登录密码:<td>
		<input type="password" size="35" class="inputBg" placeholder="请再次输入密码"/></span> 
        </td>
        </tr>
        <tr>
			<td align="right">经纪人号码:<td>
		<input type="text" size="35" class="inputBg" placeholder="请输入经纪人手机号"/></span> 
        </td>
        </tr>
		<tr>
			<td align="right"></td>
			<td><input type="submit" value=" 注册 " class="button"></td>
		</tr>
	</table>
</form>
</body>
</html>