<?
include_once $_SERVER["DOCUMENT_ROOT"] . "/incapsula.php";
if(!empty($_POST)) {
    $_POST = array_map("trim",$_POST);
    $_POST = array_map("strip_tags",$_POST);
}

/*print_r($_POST);
exit;*/

#################test data########################

//$param = array(
//    "AccountID"=>'UPLAY',
//    "Password"=>'gE2puwra',
//    "PaymentType"=>"purchase",
//    "OrderID"=>'CO_80000-11917/20151113094529',
//    "Amount"=>'100.00',
//    "Currency"=>"USD",
//    "CardHolder"=>"GiFT CARD",
//    "CardNumber"=>"4111111111111111",
//    "ExpiryMonth"=>"01",
//    "ExpiryYear"=>"2024",
//    "CVV"=>"123",
//    "BillingFirstName"=>"John",
//    "BillingLastName"=>"Doe",
//    "BillingAddress"=>"SW Jones ST",
//    "BillingCity"=> "New York",
//    "BillingZip"=>"1234",
//    "BillingCountryCode"=>'US',
//    "BillingPhone"=>"258745612",
//    "BillingEmail"=>"John@doe.com",
//);

#################intergration data########################
$param = array(
    "AccountID"=>'UPLAY',
    "Password"=>'pH6Cayud',
    "PaymentType"=>"purchase",
    "OrderID"=>$_POST["orderID"],
    "Amount"=>$_POST["purchaseAmount"],
    "Currency"=>"USD",
    "CardHolder"=>$_POST["paymentCCName"],
    "CardNumber"=>$_POST["card"],
    "ExpiryMonth"=>$_POST["expiryMonth"],
    "ExpiryYear"=>$_POST["expiryYear"],
    "CVV"=>$_POST["code"],
    "BillingFirstName"=>$_POST["paymentFName"],
    "BillingLastName"=>$_POST["paymentLName"],
    "BillingAddress"=>$_POST["paymentAddress"],
    "BillingCity"=> $_POST["paymentCity"],
    "BillingZip"=>$_POST["paymentZip"],
    "BillingCountryCode"=>'US',
    "BillingPhone"=>$_POST["paymentPhone"],
    "BillingEmail"=>$_POST["email"]
);
/*print_r($param);
exit;*/

$xmlBase ="<?xml version='1.0' encoding='UTF-8'?><PaymentRequest></PaymentRequest>";
$xmlRequest = new SimpleXMLElement($xmlBase);
if(!empty($param)){
    $parameter = $xmlRequest;
    foreach($param as $key=>$value) {
        if(trim($value) == false){
            $parameter->addChild($key,"");
        }else{
            $parameter->addChild($key,$value);
        }
    }
}

$xmlRequest = $xmlRequest->asXML();
/*var_dump($xmlRequest);
exit;*/

$headers = array();
$headers[] = "Content-Type: application/xml";
$headers[] = "Accept: application/xml";

$url = "https://www.ipaydna.net/gateway/handler.ashx";

$ch = curl_init();
curl_setopt( $ch, CURLOPT_URL, $url );
curl_setopt( $ch, CURLOPT_POST, true );
curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch, CURLOPT_POSTFIELDS, $xmlRequest);
$xmlResponse = curl_exec($ch);
//print_r($xmlResponse);

$vPay = json_decode(str_replace(':{}', ':null', json_encode((array)simplexml_load_string($xmlResponse, 'SimpleXMLElement', LIBXML_NOCDATA))));
curl_close($ch);
/*print_r($result);
exit;*/

if($vPay->ResponseCode == 00000 && $vPay->Result == "Approved"){
    $pspStatusCd = 60102;
}else{
    $pspStatusCd = 60103;
};
//echo $pspStatusCd;

//get card type based on card number
$ccType = array(
    "visa"       => "/^4[0-9]{12}(?:[0-9]{3})?$/",
    "mastercard" => "/^5[1-5][0-9]{14}$/",
);

if (preg_match($ccType['visa'],str_replace(" ", "",$_POST["card"])))
{
    $cardType = 'visa';
}
else if (preg_match($ccType['mastercard'],str_replace(" ", "",$_POST["card"])))
{
    $cardType = 'mastercard';
}

$ccHash = $cardType.str_replace(" ", "",$_POST["card"]).$_POST["expiryMonth"].$_POST["expiryYear"];

$p = array(
    "accessToken" => $_POST["accessToken"],
    "depositNo" => $_POST["depositNo"],
    "pspTransacntionNo" => $_POST["orderID"],
    "pspKey"=> $_POST['Key'],
    "pspStatusCd"=>$pspStatusCd,
    "creditCardNo"=>hash("sha256",$ccHash)
);

$jsonParameter = json_encode($p);
/*print_r($jsonParameter);
exit;*/

//#########log#################
$file_date = date("YmdHis");
$log_txt =
    "|response_page|{$file_date}|{$result->TransactionID}|{$result->OrderID}|{$result->MerchantAccount}|{$result->Amount}|{$result->Currency}|{$result->Result}|{$result->ResponseCode}|{$result->ResponseDescription}|{$_SERVER['REMOTE_ADDR']}|{$_POST["agentId"]}|{$_POST["agentPw"]}|{$_POST['serverIP']}|{$_POST["referrerDomain"]}|{$_POST['accessToken']}|{$_POST["depositNo"]}|before api|{$jsonParameter}";
$log_dir = $_SERVER["DOCUMENT_ROOT"]."log/";
$log_file = fopen($log_dir."log.txt", "a");
if (false === $log_file) {
    throw new RuntimeException('Unable to open log file for writing');
}
$bytes = fwrite($log_file, $log_txt."\r\n");
printf('Wrote %d bytes to %s', $bytes, realpath('log.txt'));
fclose($log_file);
//#########log#################

Class RestCurl{
    public static function exec($method, $url, $obj = array()) {
        if($_POST["referrerDomain"] == "demo.frontend88.com" || $_POST["referrerDomain"] == "tony.frontend88.com"){
            $url = trim("http://10.10.10.111/FrontAPI/".$url);
        }else{
            $url = trim("http://internal-imsfapi-private-816469653.ap-northeast-1.elb.amazonaws.com/FrontAPI/".$url);//real
        }

        $headers = array();
        $headers[] = "AgentId: {$_POST["agentId"]}";
        $headers[] = "AgentPw: {$_POST["agentPw"]}";
        $headers[] = "Content-Type: application/json";
        $headers[] = "Accept: application/json";
        $headers[] = "VisiterUrl: {$_POST["referrerDomain"]}";
        $headers[] = "ServerIp: {$_POST['serverIP']}";
        $headers[] = "UserIp: {$_SERVER['REMOTE_ADDR']}";
        $curl = curl_init($url);

        switch($method) {
            case 'GET':
                if(strrpos($url, "?") === FALSE) {
                    $url .= '?' . http_build_query($obj);
                }
                break;
            case 'POST':
                curl_setopt($curl, CURLOPT_POST, TRUE);
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($obj));
                break;
            case 'PUT':
            case 'DELETE':
            default:
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, strtoupper($method)); // method
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($obj)); // body
        }

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_HTTPHEADER,$headers);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 6);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, TRUE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, TRUE);

        $response = curl_exec($curl);

        $info = curl_getinfo($curl);
        curl_close($curl);

        // Data
        $header = trim(substr($response, 0, $info['header_size']));
        $body = substr($response, $info['header_size']);

        return array('status' => $info['http_code'], 'header' => $header, 'data' => json_decode($body));
    }
    public static function get($url, $obj = array()) {

        return RestCurl::exec("GET", $url, $obj);
    }
    public static function post($url, $obj = array()) {
        return RestCurl::exec("POST", $url, $obj);
    }
    public static function put($url, $obj = array()) {
        return RestCurl::exec("PUT", $url, $obj);
    }
    public static function delete($url, $obj = array()) {
        return RestCurl::exec("DELETE", $url, $obj);
    }
}

$result = RestCurl::PUT("Finance.svc/pspDepositResult", $p);
/*print_r($result);
exit;*/

/*$log_dir = $_SERVER["DOCUMENT_ROOT"]."log/";
$log = fopen($log_dir.'log.json', 'w');
fwrite($log, json_encode($result));
fclose($log);*/

//#########log#################
$log_txt =
    "|process_page|{$file_date}|{$result['status']}|{$result["data"]->responseCode}|{$result["data"]->errorMessage}|{$_SERVER['REMOTE_ADDR']}|{$_POST["agentId"]}|{$_POST["agentPw"]}|{$_POST['serverIP']}|{$_POST["referrerDomain"]}|{$_POST['accessToken']}|{$_POST["depositNo"]}|";
$log_dir = $_SERVER["DOCUMENT_ROOT"]."log/";
$log_file = fopen($log_dir."log.txt", "a");
if (false === $log_file) {
    throw new RuntimeException('Unable to open log file for writing');
}
$bytes = fwrite($log_file, $log_txt."\r\n");
printf('Wrote %d bytes to %s', $bytes, realpath('log.txt'));
fclose($log_file);
//#########log#################

if($result["status"] == 200 && $vPay->ResponseCode == 00000 && $vPay->Result == "Approved"){
    $message="Success";
    header("Location: https://".$_SERVER['HTTP_HOST']."/success.php");
    exit;
}else{
    $message=$result["data"]->errorMessage;
    header("Location: https://".$_SERVER['HTTP_HOST']."/reject.php?&res=".$vPay->Result."&desc=".$vPay->ResponseDescription);
    exit;
}

