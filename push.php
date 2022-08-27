<?php
class Push
{
    private $appid = '';//微信公众号appid
    private $appsecret = '';//微信公众号app_secret
    private $GaoDeKey = "";//高德 appkey
    private $adcode = "";//城市id 在高德开放平台获取 https://lbs.amap.com/api/webservice/download
    private $touser='';//微信公众号中的用户openid
    private $template_id="";//微信公众号模板id
    
    //获取距离生日天数
    public function day()
    {
        $time1 = strtotime(date("Y", time()) + 1 . "-0-0");//在这里填写生日日期
        $time2 = time();
        $diff_seconds = $time1 - $time2;
        $diff_days = floor($diff_seconds / 86400);
        return $diff_days;
    }
    public function days()
    {
        $array = array("日", "一", "二", "三", "四", "五", "六");
        $s = date('w');
        return $array[$s];
    }
    public  function send_post($url, $data)
    {
        $data_string = json_encode($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string)
        ));
        curl_setopt($ch, CURLOPT_ENCODING, 'deflate');
        $result = curl_exec($ch);
        return $result;
    }
    public function access_token()
    {
        $res = json_decode(file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appid&secret=$this->appsecret"), true);
        return $res['access_token'];
    }
    public function message()
    {
        //获取access_token
        $access_token = $this->access_token();
        $res = json_decode(file_get_contents('https://restapi.amap.com/v3/weather/weatherInfo?key=' . $this->GaoDeKey . '&city=' . $this->adcode . '&extensions=all&output=JSON'), true);
        $day = json_decode(file_get_contents('https://restapi.amap.com/v3/weather/weatherInfo?key=' . $this->GaoDeKey . '&city=' . $this->adcode . '&extensions=base&output=JSON'), true);
        //发送模板信息
        $data = array(
            "touser" => $this->touser,
            "template_id" => $this->template_id,
            "data" => array(
                "date" => array(
                    "value" => date("Y-m-d", time()) . ' 星期' . $this->days(),
                    "color" => "#3d5afe"
                ),
                "weather" => array(
                    "value" => $day['lives'][0]['weather'],
                    "color" => "#3d5afe"
                ),
                "max" => array(
                    "value" => $res['forecasts'][0]['casts'][0]['daytemp'],
                    "color" => "#c12c1f"
                ),
                "mini" => array(
                    "value" => $res['forecasts'][0]['casts'][0]['nighttemp'],
                    "color" => "#38b48b"
                ),
                "daywind" => array(
                    "value" => $day['lives'][0]['winddirection'],
                    "color" => "#3d5afe"
                ),
                "daypower" => array(
                    "value" => $day['lives'][0]['windpower'],
                    "color" => "#3d5afe"
                ),
                "birthday" => array(
                    "value" => $this->day(),
                    "color" => "#3d5afe"
                ),
                "sui" => array(
                    "value" => (int)date("Y", time()) - (int)0000,//出生年份
                    "color" => "#3d5afe"
                ),
                "temperature" => array( //温度
                    "value" => $day['lives'][0]['temperature'],
                    "color" => "#3d5afe"
                ),
                "humidity" => array( //湿度
                    "value" => $day['lives'][0]['humidity'],
                    "color" => "#3d5afe"
                ),
                "updateTime" => array(
                    "value" => $day['lives'][0]['reporttime'],
                    "color" => "#3d5afe"
                )

            )
        );
        $this->send_post('https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $access_token, $data);
    }
}
