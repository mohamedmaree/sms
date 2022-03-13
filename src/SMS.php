<?php
namespace maree\sms;
use Carbon\Carbon;

class SMS {

  public static function send($phone='', $msg='') {
    $key = config('sms.service_provider');
    switch ($key) {
    case 'yamamah':
      return self::yamamah($phone,$msg);
      break;
    case '4jawaly':
      return self::jawaly($phone,$msg);
      break;
    case 'hisms':
      return self::hisms($phone,$msg);
      break;
    case 'msegat':
      return self::msegat($phone,$msg);
      break;
    case 'oursms':
      return self::oursms($phone,$msg);
      break;
    case 'unifonic':
      return self::unifonic($phone,$msg);
      break;
    case 'zain':
      return self::zain($phone,$msg);
      break;
    }

  }

  public static function jawaly($phone='', $msg='') {
    $user_name = config('sms.user_name');
    $password = config('sms.password');
    $sender   = urlencode(config('sms.sender_name'));
    $msg     = urlencode($msg);
    $url    = "https://www.4jawaly.net/api/sendsms.php?username=$user_name&password=$password&numbers=$phone&sender=$sender&message=$msg&unicode=e&Rmduplicated=1&return=string";
    $result = file_get_contents($url, true);
    return $result;
  }


  public static function hisms($phone='', $msg='') {
    $user_name = config('sms.user_name');
    $password = config('sms.password');
    $sender   = urlencode(config('sms.sender_name'));
    $msg      = urlencode($msg);

    $url    = "https://www.hisms.ws/api.php?send_sms&username=$user_name&password=$password&numbers=$phone&sender=$sender&message=$msg";
    $result = file_get_contents($url, true);
    return $result;
  }

  public static function msegat($phone='', $msg='') {
    $user_name = config('sms.user_name');
    $password = config('sms.password');
    $sender   = config('sms.sender_name');
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://www.msegat.com/gw/sendsms.php");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, TRUE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    $fields = <<<EOT
        {
        "userName": "$user_name",
        "numbers": "$phone",
        "userSender": "$sender",
        "apiKey": "$password",
        "msg": "$msg"
        }
        EOT;
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      "Content-Type: application/json",
    ));

    $response = curl_exec($ch);
    $info     = curl_getinfo($ch);
    curl_close($ch);
    return $response;
  }

  public static function oursms($phone='', $msg='') {
    $user_name = config('sms.user_name');
    $password = config('sms.password');
    $sender   = urlencode(config('sms.sender_name'));
    $msg      = urlencode($msg);

    // auth call
    //$url = "http://www.oursms.net/api/sendsms.php?username=$user_name&password=$password&numbers=$to&message=$text&sender=$sendername&unicode=E&return=full";
    //لارجاع القيمه json
    $url = "http://www.oursms.net/api/sendsms.php?username=$user_name&password=$password&numbers=$phone&message=$msg&sender=$sender&unicode=E&return=json";
    // لارجاع القيمه xml
    //$url = "http://www.oursms.net/api/sendsms.php?username=$user_name&password=$password&numbers=$to&message=$text&sender=$sendername&unicode=E&return=xml";
    // لارجاع القيمه string
    //$url = "http://www.oursms.net/api/sendsms.php?username=$user_name&password=$password&numbers=$to&message=$text&sender=$sendername&unicode=E";

    // Call API and get return message
    //fopen($url,"r");
    //return $url;
    $ret = file_get_contents($url);
    return $ret;

  }

  public static function unifonic($phone='', $msg='') {
    $user_name = config('sms.user_name');
    $password = config('sms.password');
    $sender   = urlencode(config('sms.sender_name'));
    $msg      = urlencode($msg);
    $url     = "http://api.unifonic.com/wrapper/sendSMS.php?userid=$user_name&password=$password&msg=$msg&sender=$sender&to=$phone&encoding=UTF8";
    $result  = @file_get_contents($url, true);
    return $result;
  }

  public static function zain($phone='', $msg='') {
    $user_name = config('sms.user_name');
    $password = config('sms.password');
    $sender   = urlencode(config('sms.sender_name'));
    $msg      = urlencode($msg);

    $link = "https://www.zain.im/index.php/api/sendsms/?user=$user_name&pass=$password&to=$phone&message=$msg&sender=$sender";
    if (function_exists('curl_init')) {
      $curl = @curl_init($link);
      @curl_setopt($curl, CURLOPT_HEADER, FALSE);
      @curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
      @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
      @curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
      $source = @curl_exec($curl);
      @curl_close($curl);
      if ($source) {
        return $source;
      } else {
        return @file_get_contents($link);
      }
    } else {
      return @file_get_contents($link);
    }

  }

  public static function yamamah($phone='', $msg='') {
    $url    = 'api.yamamah.com/SendSMS';
    $user_name = config('sms.user_name');
    $password = config('sms.password');
    $sender   = config('sms.sender_name');
    $msg      = $msg;
    $fields = array(
      "Username"        => $user_name,
      "Password"        => $password,
      "Message"         => $msg,
      "RecepientNumber" => $phone, 
      "ReplacementList" => "",
      "SendDateTime"    => "0",
      "EnableDR"        => False,
      "Tagname"         => $sender,
      "VariableList"    => "0",
    );
    $fields_string = json_encode($fields);
    $ch = curl_init($url);
    curl_setopt_array($ch, array(
      CURLOPT_POST           => TRUE,
      CURLOPT_RETURNTRANSFER => TRUE,
      CURLOPT_HTTPHEADER     => array(
        'Content-Type: application/json',
      ),
      CURLOPT_POSTFIELDS     => $fields_string,
    ));
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
  }
   
}