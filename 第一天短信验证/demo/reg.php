<?php
  
//��һ�����û�ע��ʱ�����ֻ��ţ���վ����Ҫͨ��JS����ajax+php��֤��������ǲ�����ȷ���ֻ��š�
//�ڶ������û���������ֻ���֤�룬ͨ��ajax���ֻ��Ŵ���php����ʱphp����һ���������֤�뱣����session�У�Ȼ��ͨ�����Žӿڰ������֤�뷢�͵�����ֻ����С�
//���������û������ֻ��յ�����֤��ע�ᡣ��վ��session�е���֤����û��������֤��Ƚϡ�
session_start();
if($_POST){
	//echo '<pre>';print_r($_POST);print_r($_SESSION);
	//ǰ��ȫ�����Ե����棬���Σ�ֻ��or�������в��Ǻܺ��ң�����||�������Ժ��ң��׶������ȼ�˳����&& || and or 
	if($_POST['mobile']!=$_SESSION['mobile'] or $_POST['mobile_code']!=$_SESSION['mobile_code'] or empty($_POST['mobile']) or empty($_POST['mobile_code'])){
		exit('�ֻ���֤���������');	
	}else{
		//���session�Ĵ洢 ��ֹ����
		$_SESSION['mobile'] = '';
		$_SESSION['mobile_code'] = '';	
		exit('ע��ɹ���');	
	}
}
//empty��php���õ�һ�������� ������������ж�һ���������߱��ʽ�Ƿ�Ϊ�ա�
//"" ˫���ţ���ʾһ���յ��ַ�����������������Ϊ�ַ������͡� 0 �㣬��ʾ����0��������������Ϊ���� null ��ʾ��ʾһ������û��ֵ��һ������Ϊnull����������� 1.����ֵΪ NULL �� 2. ��δ����ֵ�� 3. �� unset() �� 
//empty  ��php��һ���жϱ���Ϊ�յĺ�������� ���� �Ƿǿջ�����ֵ����     empty()  ����  FALSE �����仰˵��""��0��"0"�� NULL �� FALSE ��array()��var $var;   �Լ�û���κ����ԵĶ��󶼽�����Ϊ�ǿյģ�������� Ϊ�գ��� empty() ���� TRUE 
function random($length = 6 , $numeric = 0) {
	PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
	//srand((double)microtime()*1000000)��
	//��Ϊ4������1:ִ��microtime(),��ȡ��ǰ��΢����
	//2:�ѻ�ȡ��΢����ת��Ϊdouble����
	//3:����ת���������ȥ����1000000
	//4:�����������������,������Ϊ�������ó��Ľ��
	//randΪ����0��RAND_MAX ֮���α�������,RAND_MAX��ֵ��ƽ̨��ͬ����ͬ
	//srand() ���������ǲ������������������

	if($numeric) {
		$hash = sprintf('%0'.$length.'d', mt_rand(0, pow(10, $length) - 1));
		//����sprintf()��������ʽ�������;
		//Pow��Power�ļ�д����˼���� pow(10,i)����˼����10��i���� 
		//mt_rand()���ö��ǲ���һ���������
	} else {
		$hash = '';
		$chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789abcdefghjkmnpqrstuvwxyz';
		$max = strlen($chars) - 1;
		for($i = 0; $i < $length; $i++) {
			$hash .= $chars[mt_rand(0, $max)];
			//�������������
			/*�ۻ�
			$a = 'a'; //��ֵ
			$b = 'b'; //��ֵ
			$c = 'c'; //��ֵ
			$c .= $a;
			$c .= $b;
			echo $c; �ͻ���ʾ cab
			*/
		}
	}
	return $hash;
}
$_SESSION['send_code'] = random(6,1);//��֤��ֵ
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
			if(msg=='�ύ�ɹ�'){
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
					sSecond = iMinute + "��" + iSecond + "��";
				}else{
					sSecond = iSecond + "��";
				}
			}
			sTime=sSecond;
			if(iTime==0){
				clearTimeout(Account);
				sTime='��ȡ�ֻ���֤��';
				iTime = 59;
				document.getElementById('zphone').disabled = false;
			}else{
				Account = setTimeout("RemainTime()",1000);
				iTime=iTime-1;
			}
		}else{
			sTime='û�е���ʱ';
		}
		document.getElementById('zphone').value = sTime;
	}	
</script>
<body>
<form action="reg.php" method="post" name="formUser">
	<table width="100%"  border="0" align="left" cellpadding="5" cellspacing="5">
		<tr>
			<td align="right">�ֻ�����:<td>
		<input id="mobile" name="mobile" type="text" size="35" class="inputBg" placeholder="�������ֻ���"/></span> 
        </td>
        </tr>
		<tr>
			<td align="right">��֤��:</td>
			<td><input type="text" size="20" name="mobile_code" class="inputBg" placeholder="�������ֻ�6λУ����" /><span style="color:#FF0000"> *<input id="zphone" type="button" value=" ��ȡ��֤�� " onClick="get_mobile_code();"></td>
		</tr>
		<tr>
			<td align="right">��¼����:<td>
		<input type="password" size="35" class="inputBg" placeholder="����������"/></span> 
        </td>
        </tr>
        <tr>
			<td align="right">��¼����:<td>
		<input type="password" size="35" class="inputBg" placeholder="���ٴ���������"/></span> 
        </td>
        </tr>
        <tr>
			<td align="right">�����˺���:<td>
		<input type="text" size="35" class="inputBg" placeholder="�����뾭�����ֻ���"/></span> 
        </td>
        </tr>
		<tr>
			<td align="right"></td>
			<td><input type="submit" value=" ע�� " class="button"></td>
		</tr>
	</table>
</form>
</body>
</html>