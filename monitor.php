<?php
/**
 * 
 * Cloudxns Monotor + Solusvm server api controller tools
 * 
 * @Url: https://github.com/tekintian
 * 
 * CLOUDXNS文档  http://www.cloudxns.net/Support/detail/id/340.html
 * Solusvm doc https://documentation.solusvm.com/display/DOCS/Functions
 * monitor.php?action=status
 * status 查看状态
 * reboot 重启服务器
 * boot 启动服务器
 * @Author: Tekin
 * @Date:   2018-10-26 12:18:55
 * @Last Modified 2018-12-21
 */

// 设置json格式
header('content-type:application/json;charset=utf-8');

define("URL_TOKEN", "the token of your monitor provider callback");
define("WHURL", "https://{solusvm ip address}:5656/api/client/command.php");

//cloudxns发送的POST数据
$monitor_domain = $_POST['monitor_domain'];
$monitor_dest = $_POST['monitor_dest'];
$monitor_type = $_POST['monitor_type'];
$fault_time = $_POST['fault_time'];
$msg_status = $_POST['msg_status'];
$msg_detail = $_POST['msg_detail'];
$token = $_POST['token'];

// 获取自定义 header  MHN
// $hkey = $_SERVER['HTTP_HKEY'];
// $sip = $_SERVER['HTTP_SIP'];

// 记录请求信息和服务端server信息，以方便调试，正式使用可以关闭
error_log(json_encode($_REQUEST,true)." \r\n ".json_encode($_SERVER,true)." TOKEN:".md5($monitor_domain . $monitor_dest . $monitor_type . $fault_time . URL_TOKEN), 3, "/var/logs/MonitorDebug.log");

//载入公用函数
require('helper.php');

$params = array();

// 自定义的默认动作 status 查看服务器状态  更多信息，参考 solusvm文档 
// 在callback地址上面加上的自定义参数，如 monitor.php?action=status
$params['action'] = isset($_GET["action"]) ? $_GET["action"]: 'status';

$result=array();

//如果校验成功，则说明此消息为CloudXNS发出，否则为非法请求，不予处理
if (md5($monitor_domain . $monitor_dest . $monitor_type . $fault_time . URL_TOKEN) == $token) {
    //多个服务器callback
    switch ($monitor_dest) {
        case "192.168.1.9":
            //your solusvm server key and hash code
            $params['key'] = 'XXXXX-XXXXX-XXXXX';
            $params['hash'] = '{YOUR SOLUSVM SERVER HASH CODE}';
            //send api request to solusvm server
            $result = http_curl_request(WHURL, $params);
            break;
        case "192.168.1.10":
            //your solusvm server key and hash code
            $params['key'] = 'XXXXX-XXXXX-XXXXX';
            $params['hash'] = '{YOUR SOLUSVM SERVER HASH CODE}';
            //send api request to solusvm server
            $result = http_curl_request(WHURL, $params);
            break;
        default:
            $result = show_error('fail','No dest server found!');
    }

    // 调用 error_log() 记录日志
    error_log(json_encode($_REQUEST,true)." \r\n ".json_encode($result,true), 3, "/var/logs/monitor.log");

    //输出json数据
    echo json_encode($result);

}else{
    // 调用 error_log() 记录日志
    error_log(json_encode($_REQUEST,true)." \r\n ".json_encode($result,true), 3, "/var/logs/monitor_error.log");
    echo json_encode($result);
}





