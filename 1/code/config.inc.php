<?php
define('UC_CONNECT', 'mysql');

//define('UC_DBHOST', 'localhost');
//define('UC_DBUSER', '5d13_uc');
//define('UC_DBPW', '5d13_uc');
//define('UC_DBNAME', '5d13_uc');

define('UC_DBHOST', 'm'.$_SERVER['HTTP_MYSQLPORT'].'.mysql.sae.sina.com.cn:'.$_SERVER['HTTP_MYSQLPORT']);		// ���ݿ������
define('UC_DBUSER', SAE_ACCESSKEY);			// ���ݿ��û���
define('UC_DBPW', SAE_SECRETKEY);			// ���ݿ�����
define('UC_DBNAME', 'app_'.$_SERVER['HTTP_APPNAME']);			// ���ݿ���

define('UC_DBCHARSET', 'utf8');
define('UC_DBTABLEPRE', '`'.UC_DBNAME.'`.uc_');  
define('UC_DBCONNECT', '0');	

define('UC_CONNECT', 'mysql');

define('UC_KEY', '9edcl1jnwma724VL0zCIcR7YSBdo7lv+FjPf55E');
define('UC_API', 'http://5d13.sinaapp.com/uc');
define('UC_CHARSET', 'utf-8');
define('UC_IP', '');
define('UC_APPID', '3');
define('UC_PPP', '20');


//ucexample_2.php �õ���Ӧ�ó������ݿ����Ӳ���
$dbhost =UC_DBHOST;			// ���ݿ������
$dbuser =UC_DBUSER;			// ���ݿ��û���
$dbpw = UC_DBPW;				// ���ݿ�����
$dbname =UC_DBNAME;			// ���ݿ���
$pconnect = 0;				// ���ݿ�־����� 0=�ر�, 1=��
$tablepre = '`'.UC_DBNAME.'`.rec_';   		// ����ǰ׺, ͬһ���ݿⰲװ�����̳���޸Ĵ˴�
$dbcharset = 'utf8';			// MySQL �ַ���, ��ѡ 'gbk', 'big5', 'utf8', 'latin1', ����Ϊ������̳�ַ����趨

//ͬ����¼ Cookie ����
$cookiedomain = ''; 			// cookie ������
$cookiepath = '/';			// cookie ����·��