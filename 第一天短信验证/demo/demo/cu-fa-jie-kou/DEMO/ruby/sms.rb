#�ӿ����ͣ��������ߴ������Žӿڣ�֧�ַ�����֤����š�����֪ͨ���ŵȡ�
#�˻�ע�᣺��ͨ���õ�ַ��ͨ�˻�http://sms.ihuyi.com/register.html
#ע�����
#��1�������ڼ䣬����Ĭ�ϵ�ģ����в��ԣ�Ĭ��ģ������ӿ��ĵ���
#��2����ʹ��APIID���鿴APIID���¼�û�����->��֤�롢֪ͨ����->�ʻ���ǩ������->APIID���� APIkey�����ýӿڣ�
#��3���ô���������뻥�����߶��Žӿڲο�ʹ�ã��ͻ��ɸ���ʵ����Ҫ���б�д��
# �ô������ѧϰ���о��ӿ�ʹ�ã�ֻ���ṩ��һ���ο�

require 'typhoeus'

# �ӿڵ�ַ
url="http://106.ihuyi.com/webservice/sms.php?method=Submit"

#�û��� �鿴�û������¼�û�����->��֤�롢֪ͨ����->�ʻ���ǩ������->APIID
account="�û���"
#���� �鿴�������¼�û�����->��֤�롢֪ͨ����->�ʻ���ǩ������->APIKEY
password="����"

body={account:account,password:password,mobile:"138xxxxxxxx",content:"������֤���ǣ�1212���벻Ҫ����֤��й¶�������ˡ�"}

resp=Typhoeus::Request.post(api_send_url,body:body)
puts resp.body