<?php
namespace Maree\Sms;
use Carbon\Carbon;

class SMS {

  public function send($key = '',$user_name='',$password='',$sender_name='',$phone='', $msg='') {
    dd($key);
    switch ($key) {
    case 'yamamah':
      $this->yamamah($phone, $msg);
      break;
    case '4jawaly':
      $this->jawaly($phone, $msg);
      break;
    case 'gateway':
      $this->gateway($phone, $msg);
      break;
    case 'hisms':
      $this->hisms($phone, $msg);
      break;
    case 'msegat':
      $this->msegat($phone, $msg);
      break;
    case 'oursms':
      $this->oursms($phone, $msg);
      break;
    case 'unifonic':
      $this->unifonic($phone, $msg);
      break;
    case 'zain':
      $this->zain($phone, $msg);
      break;
    }

  }

  public function jawaly($user_name='',$password='',$sender_name='',$phone='', $msg='') {
    $text     = urlencode($msg);
    $sender   = urlencode($sender_name);
    $url    = "https://www.4jawaly.net/api/sendsms.php?username=$username&password=$password&numbers=$phone&sender=$sender&message=$text&unicode=e&Rmduplicated=1&return=string";
    $result = file_get_contents($url, true);
  }

  public function gateway($user_name='',$password='',$sender_name='',$phone='', $msg='') {
    $contextPostValues = http_build_query(array(
      'user'     => $user_name,
      'password' => $password,
      'msisdn'   => $phone,
      'sid'      => urlencode($sender_name),
      'msg'      => $msg,
      'fl'       => 0,
    ));
    $contextOptions['http'] = array(
      'method'           => 'POST',
      'header'           => 'Content-type: application/x-www-form-urlencoded',
      'content'          => $contextPostValues,
      'max_redirects'    => 0,
      'protocol_version' => 1.0,
      'timeout'          => 10,
      'ignore_errors'    => TRUE,
    );
    $contextResouce = stream_context_create($contextOptions);
    $url            = "apps.gateway.sa/vendorsms/pushsms.aspx";
    $arrayResult    = file($url, FILE_IGNORE_NEW_LINES, $contextResouce);
    $result         = $arrayResult[0];
    if ($result) {
      return true;
    } else {
      return false;
    }
  }

  public function hisms($user_name='',$password='',$sender_name='',$phone='', $msg='') {
    $sender   = urlencode($sender_name);
    $msg      = urlencode($msg);

    $url    = "https://www.hisms.ws/api.php?send_sms&username=$username&password=$password&numbers=$phone&sender=$sender&message=$msg";
    $result = file_get_contents($url, true);
    if (false === $result) {
      return false;
    } else {
      return true;
    }

  }

  public function msegat($user_name='',$password='',$sender_name='',$phone='', $msg='') {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://www.msegat.com/gw/sendsms.php");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, TRUE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    $fields = <<<EOT
        {
        "userName": "$user_name",
        "numbers": "$phone",
        "userSender": "$sender_name",
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

    return true;

  }

  public function oursms($user_name='',$password='',$sender_name='',$phone='', $msg='') {
    $sender   = urlencode($sender_name);

    $text = urlencode($msg);
    $to   = '+' . $phone;
    // auth call
    //$url = "http://www.oursms.net/api/sendsms.php?username=$user_name&password=$password&numbers=$to&message=$text&sender=$sendername&unicode=E&return=full";
    //لارجاع القيمه json
    $url = "http://www.oursms.net/api/sendsms.php?username=$user_name&password=$password&numbers=$to&message=$text&sender=$sender&unicode=E&return=json";
    // لارجاع القيمه xml
    //$url = "http://www.oursms.net/api/sendsms.php?username=$user_name&password=$password&numbers=$to&message=$text&sender=$sendername&unicode=E&return=xml";
    // لارجاع القيمه string
    //$url = "http://www.oursms.net/api/sendsms.php?username=$user_name&password=$password&numbers=$to&message=$text&sender=$sendername&unicode=E";

    // Call API and get return message
    //fopen($url,"r");
    //return $url;
    $ret = file_get_contents($url);

  }

  public function unifonic($user_name='',$password='',$sender_name='',$phone='', $msg='') {
    $sender = urlencode($sender_name);
    $numbers = explode(',', $phone);
    $text    = urlencode($msg);
    $url     = "http://api.unifonic.com/wrapper/sendSMS.php?userid=$user_name&password=$password&msg=$text&sender=$sender&to=$numbers&encoding=UTF8";
    $result  = @file_get_contents($url, true);
    if (false === $result) {
      return false;
    } else {
      return true;
    }
  }

  public function zain($user_name='',$password='',$sender_name='',$phone='', $msg='') {
    $username = $data->user_name;
    $password = $data->password;
    $sender = urlencode($sender_name);
    $to   = $phone; // Should be like 966530007039
    $text = urlencode($msg . '   ');
    $link = "https://www.zain.im/index.php/api/sendsms/?user=$user_name&pass=$password&to=$to&message=$text&sender=$sender";
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

  public function yamamah($user_name='',$password='',$sender_name='',$phone='', $msg='') {

    $url    = 'api.yamamah.com/SendSMS';
    $to     = $phone; // Should be like 966530007039
    $text   = urlencode($msg);
    $fields = array(
      "Username"        => $user_name,
      "Password"        => $password,
      "Message"         => $text,
      "RecepientNumber" => $to, //'00966'.ltrim($numbers,'0'),
      "ReplacementList" => "",
      "SendDateTime"    => "0",
      "EnableDR"        => False,
      "Tagname"         => $sender_name,
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
  }

}