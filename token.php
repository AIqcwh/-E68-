<?php

    
    //查询方法
	header("Access-Control-Allow-Origin: *");
	init_data_list();
    function init_data_list(){
        
        //查询表
        $sql = "SELECT `access_token` FROM `channel_s8_b_q_p`";
        $query = query_sql($sql);
        while($row = $query->fetch_assoc()){
            $data[] = $row;
        }
        
        $json = json_encode(array(
            "resultCode"=>200,
            "message"=>"查询成功！",
            "data"=>$data
        ),JSON_UNESCAPED_UNICODE);
        //转换成字符串JSON
        echo($json);
    }
	//更新
	updata_token();
    function updata_token(){
        $time=time();
        //查询表
        $sql = "SELECT `creat_time` FROM `channel_s8_b_q_p`";
        $query = query_sql($sql);
        while($row = $query->fetch_assoc()){
            $data[] = $row;
        }
		$time2=$time-$data[0]['creat_time'];
		if($time2>7200000){
			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx691d3c6534a40b63&secret=72e32447b69eddedc741991b56e26810";
			//发送get请求，并将返回的json转化为数组，提取出token
			$ch = curl_init();//c初始化一个cURL会话
			curl_setopt($ch, CURLOPT_URL, $url);//将URL设置为我们需要的URL
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$output = curl_exec($ch);//获取json返回数据
			curl_close($ch);
			$jsonInfo = json_decode($output, true);
			$token= $jsonInfo["access_token"];
			$sql = "update channel_s8_b_q_p set access_token = '".$token."',creat_time='".$time."'";
			$query = query_sql($sql);
		}
    }
    
    /**查询服务器中的数据
     * 1、连接数据库,参数分别为 服务器地址 / 用户名 / 密码 / 数据库名称
     * 2、返回一个包含参数列表的数组
     * 3、遍历$sqls这个数组，并把返回的值赋值给 $s
     * 4、执行一条mysql的查询语句
     * 5、关闭数据库
     * 6、返回执行后的数据
     */
    function query_sql(){
        $mysqli = new mysqli("172.100.105.81:3306", "root@localhost", "2sbtv12580", "tidemedia_cms");
        $sqls = func_get_args();
        foreach($sqls as $s){
            $query = $mysqli->query($s);
        }
        $mysqli->close();
        return $query;
    }
?>
