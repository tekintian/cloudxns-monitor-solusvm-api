<?php

/**
 * @Author: TekinTian  <tekintian@gmail.com>
 * @Date:   2018-10-26 16:29:27
 * @Last Modified 2018-12-21
 */

/**
 * Curl 请求
 * @param  [type]  $url    [请求的URL地址]
 * @param  [type]  $data   [发送的数据]
 * @param  integer $isjson [是否JSON格式]
 * @return [type]          [返回信息]
 */
function http_curl_request($url, $data = null, $isjson = 0)
{
    $curl = curl_init(); // 启动一个CURL会话
    curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 对认证证书来源的检查
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); // 从证书中检查SSL加密算法是否存在
    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
    if ($data != null) {
        if ($isjson == 0) {
            $data = http_build_query($data);
        }
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
    }
    curl_setopt($curl, CURLOPT_TIMEOUT, 300); // 设置超时限制防止死循环
    curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
    $data = curl_exec($curl); // 执行操作
    if (curl_errno($curl)) {
        //返回异常
        show_error('fail',curl_getinfo($curl));
        // echo 'Errno:' . curl_getinfo($curl); //捕抓异常
        // var_dump(curl_getinfo($curl));
    }
/*
匹配文本
<status>success</status>
<statusmsg>online</statusmsg>
<vmstat>online</vmstat>
<hostname>s4.tekin.cn</hostname>
<ipaddress>144.172.84.93</ipaddress>
*/
// Parse the returned data and build an array
	preg_match_all('/<(.*?)>([^<]+)<\/\\1>/i', $data, $match);
	$result = array();
	foreach ($match[1] as $x => $y)
	{
		$result[$y] = $match[2][$x];
	}
    return $result;
}
/**
 * [show_error description]
 * @param  string $status    [description]
 * @param  string $statusmsg [description]
 * @return [type]            [description]
 */
function show_error($status='fail',$statusmsg='invalid credentials') {
	$arr=array();
	$arr['status']=$status;
	$arr['statusmsg']=$statusmsg;
	return $arr;
}


/**删除字符串空格
 * @param $str
 * @return mixed
 */

function trimall($str){
    $qian=array(" ","　","\t","\n","\r");
    $hou=array("","","","","");
    return str_replace($qian,$hou,$str);
}

/**数组转换字符串
 * @param $arr
 * @param string $type
 * @return string
 */

function arr_to_str($arr,$type=','){
    if(is_array($arr)){
        foreach($arr as $k=>$v){
            if(empty($v)){
                unset($arr[$k]);
            }
        }
        $arr= implode($type,$arr);
    }
    return $arr;
}

/**
 * [strToArr 字符串转化为数组]
 * @param  [string] $str  [以固定分隔符分隔的字符串]
 * @param  string $type [分隔符是,]
 * @return [array]       [数组]
 */
function str_to_arr($str,$type=','){
    $arr = explode($type,$str);
    return $arr;
}

/**
 * [eraser 消除数组中空的元素]
 * @param  [type] $array [description]
 * @return [type]        [description]
 */
function eraser($array){
    if(is_string($array)){
        if(empty($array)){
            unset($array);
        }
    }else if(is_array($array)){
        if(count($array)>0){
            foreach ($array as $key => $value) {
                if(empty($value)){
                    unset($array[$key]);
                }
            }
            return $array;
        }
    }
}
