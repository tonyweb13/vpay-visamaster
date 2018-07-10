<?
include_once $_SERVER["DOCUMENT_ROOT"] . "/incapsula.php";
$referrerDomain = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
if(!empty($_POST)) {
    $_POST = array_map("trim",$_POST);
    $_POST = array_map("strip_tags",$_POST);
}

$test="https://cmtech669134302:7u5tm3b7f60kk4db02hntlkjjn@xecdapi.xe.com/v1/convert_from.json/?from={$_POST["currencyText"]}&to=USD&amount={$_POST["purchaseAmount"]}";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $test);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
$response = curl_exec($ch);
$info = curl_getinfo($ch);
$body = substr($response, $info['header_size']);
$data = json_decode($response);
curl_close($ch);
//var_dump($data);
$convertPurchaseAmount=0;
$convertPurchaseAmountFee=0;
foreach($data->to as $convertCurrency){
    if($convertCurrency->quotecurrency == "USD"){
        $convertPurchaseAmount = round($convertCurrency->mid,2);
//        $convertPurchaseAmountFee = round($convertCurrency->mid,2);
        $purchaseAmount = round(($convertCurrency->mid),2);
    }
}

//echo  "Base Amount is ={$_POST["currencyText"]} {$_POST["purchaseAmount"]}<br>";
//echo  "Convert Amount is USD {$purchaseAmount}<br>";
//echo  "Convert Amount+1.5% is USD {$purchaseAmountx015}";
//exit;

//echo "visa";
//exit;
//MID = BF-JM-0916
//TID = frend88-BF-CB
//merchantKey =0DF7F6E328006CB516814EE3BC2EA685
#################intergration data########################
$dateTimeKey=date('YmdHis');
$customerPaymentPageText="frend88-UV";
$orderDescription="{$_POST["agentId"]}-{$_POST["depositNo"]}/{$dateTimeKey}";
$currencyText="USD";
$merchantKey="7515AB27DAB369012C13A22E77EC62B4";
$md5Sign=md5($customerPaymentPageText.$orderDescription.$currencyText.$purchaseAmount.$merchantKey);

#################test data########################
//$dateTimeKey=date('YmdHis');
//$customerPaymentPageText="frend88-UV";
//$orderDescription="INV-001/{$dateTimeKey}";
//$currencyText="USD";
//$purchaseAmount="1.00";
//$merchantKey="7515AB27DAB369012C13A22E77EC62B4";
//$md5Sign=md5($customerPaymentPageText.$orderDescription.$currencyText.$purchaseAmount.$merchantKey);

//#####log#####//
$log_dir = $_SERVER["DOCUMENT_ROOT"]."log/";
$file_date = date("YmdHis");
$log_txt =
    "|started_page|{$file_date}|{$merchantKey}|{$currencyText}|{$purchaseAmount}|{$orderDescription}|{$dateTimeKey}|{$md5Sign}|run|{$_SERVER['REMOTE_ADDR']}|{$_POST["agentId"]}|{$_POST["agentPw"]}|{$_POST['serverIP']}|{$referrerDomain}|{$_POST['accessToken']}|{$_POST["depositNo"]}";
$log_file = fopen($log_dir."log.txt", "a");
fwrite($log_file, $log_txt."\r\n");
fclose($log_file);
//#####log#####//

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Payment Process</title>
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
    <style>
        @font-face {
            font-family:'Roboto';
            font-style: normal;
            font-weight: 400;
            src:local('Roboto');
            src: url('/common/fonts/en/Roboto-Regular-webfont.eot');
            src: url('/common/fonts/en/Roboto-Regular-webfont.eot?#iefix') format('embedded-opentype'),
            url('/common/fonts/en/Roboto-Regular-webfont.woff') format('woff'),
            url('/common/fonts/en/Roboto-Regular-webfont.ttf') format('ttf');
        }
        @font-face {
            font-family:'Roboto';
            font-style: normal;
            font-weight: 700;
            src:local('Roboto');
            src: url('/common/fonts/en/Roboto-Bold-webfont.eot');
            src: url('/common/fonts/en/Roboto-Bold-webfont.eot?#iefix') format('embedded-opentype'),
            url('/common/fonts/en/Roboto-Bold-webfont.woff') format('woff'),
            url('/common/fonts/en/Roboto-Bold-webfont.ttf') format('ttf');
        }
        html, body, p, em, a, strong {margin: 0; padding: 0; border: 0; font-size: 100%; font: inherit; vertical-align: baseline;}
        body {line-height: 1; overflow: hidden; font-size: 12px; font-weight: 400; font-family: 'Roboto', sans-serif; color: #666666; background: #ffffff;}
        * {-webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;}
        .inputField {border: 1px solid #d6d6d6; background: #fafafa; color: #454545; padding: 0 6px; height: 30px; line-height: 31px;}
        input:focus, input:hover, select:focus, select:hover, textarea:focus, textarea:hover {border-color: #888888; outline: none;}
        .editFormBox {width: 401px;}
        .float-right {float: right;}
        .float-left {float: left;}
        .text-center {text-align: center;}
        .margin-top {margin-top: 5px;}
        .clear {clear: both;}
        button {border: none;}
        .btn {display: block; color: #fff; cursor: pointer; font: inherit; font-size: 11px; outline: 0; padding: 4px 0;
            transition: all .1s linear; text-shadow: 0 -1px 1px rgba(0,0,0,0.25); text-transform: uppercase;
            border-radius: 4px; border-top: 1px solid rgba(0,0,0,0.02); border-left: 1px solid rgba(0,0,0,0.15); border-right: 1px solid rgba(0,0,0,0.15); border-bottom: 1px solid rgba(0,0,0,0.25);
            -moz-box-shadow: inset 0 1px 0 0 rgba(255,255,255,0.25); -webkit-box-shadow: inset 0 1px 0 0 rgba(255,255,255,0.25);
        }
        button.btn-submit {display: inline-block; background-color: #0099cc !important; padding: 8px 20px; font-size: 14px; font-weight: 700;}
        button.btn-submit:before, button.btn-submit:hover {background-color: #4fabca !important;}

        .payment-process {width: 880px; padding: 10px; margin: 0 auto; background: #ffffff;}

        .payment-summary {display: block; padding: 5px 14px; border-radius: 10px; background: url("/common/images/bg-payment-summary.png") top repeat-x #404045; color: #aeaeb2;}
        .payment-summary label {text-align: right; display: inline-block; line-height: 21px;}
        .payment-summary label strong {color: #ffffff; margin: 0 0 0 10px;}

        .payment-summary span {display: inline-block; line-height: 22px; font-size: 10px;}
        .payment-summary span strong {color: #80dfea;}
        .payment-summary span i.icon-info {width: 20px; height: 20px; margin: 1px 5px 0 0; float: left; background: url("/common/images/icon-info-white.png") 0 0 no-repeat; display: inline-block; position: relative; top: 1px; left: 0;}

        .payment-summary-item {display: block; margin-top: 8px; padding: 7px 0 15px 0;}
        .payment-summary-item p {width: 33%; display: inline-block; border-right: 1px solid #3b3c3f; text-align: center;}
        .payment-summary-item label {color: #8e8e8e; display: block; float: none; text-align: center; font-size: 11px; line-height: 20px !important;}
        .payment-summary-item strong {color: #ffffff; display: block; font-size: 18px; font-weight: 700; line-height: 22px;}
        .totalAmount {border: none !important;}
        .totalAmount strong {color: #ff9c00;}

        .payment-form {padding: 0 10px 10px 10px;}
        .payment-form form h2 {color: #3e4052; font-size: 14px; line-height: 30px; border-bottom: 1px solid #c1c1c4; padding: 0 5px; margin: 7px 0 16px 0;}
        .payment-form form div.form-group {width: 406px; display: inline-block; padding: 0px 3px 18px 3px; vertical-align: middle; position: relative;}
        .payment-form form label {width: 158px; line-height: 30px; padding-left: 28px; display: inline-block;}
        .payment-form form .inputField {width: 233px;}

        .inputField-md {width: 91px !important;}
        .inputField-xs {width: 68px !important;}
        .inputField-xxs {width: 40px !important;}

        em.required-fields {display: inline; float: left; line-height: 20px; color: red; margin: 0; padding: 0; width: 11px; text-align: center;}

        .icon-info {width: 20px; height: 20px; background: url("/common/images/icon-info.png") 0 0 no-repeat; position: absolute; top: -2px; left: -2px;}
        .icon-tip-sec-code {top: 5px !important; left: 4px !important; position: relative !important; display: inline-block;}
        .tooltipSecCode {display: none; border: 2px solid #0099cc; background: #fff; position: absolute; z-index: 99999; width: 180px; padding: 7px 10px; top: -139px; left: 239px; box-shadow: 2px 2px 10px rgba(0,0,0,0.2) !important; -moz-border-radius: 5px; -webkit-border-radius: 5px; border-radius: 5px;}

        .poweredby {float: none; padding-top: 11px; text-align: right; border-top: 1px dotted #e7e7e7;}
        .logo-vpay {width: 57px; height: 22px; display: inline-block; margin: 0 6px; vertical-align: middle; background: url("/common/images/logo-vpay.png") 0 0 no-repeat;}

        i.icon-credit-card {width: 53px; height: 28px; display: inline-block; position: absolute; right: 10px; top: 1px; background: url("/common/images/card-default.png") no-repeat;}
        .visa i.icon-credit-card {background: url("/common/images/card-visa.png") no-repeat;}
        .master i.icon-credit-card {background: url("/common/images/card-mastercard.png") no-repeat;}
        .amex i.icon-credit-card {background: url("/common/images/card-amex.png") no-repeat;}

        .error input, .error input:focus {background: rgb(255, 250, 250); border-color: #ef4b4c !important; -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,0.075); box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);}
        .error-message {position: absolute; z-index: 9999; color: #ef4b4c; display: block; margin-left: 161px; font-size: 9px; width: 233px; padding: 4px 4px; margin-top: -1px;}
        .btn:disabled, .btn:disabled:hover {
            background-color: #b5b5b5 !important;
            cursor: default !important;
        }

        .txtAmount {font-weight: bold; font-size: 18px; color: #0d69db; vertical-align: middle; display: inline-block;}
        em.note {font-size: 9px; line-height: 22px; color: #bdbdbd; vertical-align: middle; display: inline-block; font-weight: normal; font-style: normal; margin-left: 5px;}
    </style>
</head>
<body ng-app="paymentApp">
<div class="payment-process" ng-controller="PaymentController">
    <div class="payment-summary">
        <label class="float-left">Invoice No. <strong><?=$orderDescription?></strong></label>
        <label class="float-right">Cardholder IP <strong><?=$_SERVER["REMOTE_ADDR"]?></strong></label>
        <div class="clear"></div>
        <div class="payment-summary-item">
            <p>
                <label>Requested Amount</label>
                <strong><?=$_POST["currencyText"]?> <?=$_POST["purchaseAmount"]?></strong>
            </p>
            <p>
                <label>Converted Amount</label>
                <strong>USD <?=$convertPurchaseAmount?></strong>
            </p>
<!--            <p>-->
<!--                <label>Fee (2%)</label>-->
<!--                <strong>USD --><?//=$convertPurchaseAmountFee?><!--</strong>-->
<!--            </p>-->
            <p class="totalAmount">
                <label>TOTAL AMOUNT</label>
                <strong>USD <?=$purchaseAmount?></strong>
            </p>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
<!--        <span>-->
<!--            <i class="icon-info"></i>-->
<!--            <strong>THB to USD</strong> will be charged as deposit upon the occurence of a <strong>2% fee</strong>.-->
<!--        </span>-->
    </div>

    <div class="payment-form">
        <form name="paymentForm" method="POST" action="/process.php"  novalidate>
            <h2>Cardholder Information</h2>
            <div class="editFormBox float-left">
                <div class="form-group" ng-class="{'error' : paymentForm.paymentFName.$invalid && !paymentForm.paymentFName.$pristine}">
                    <label><em class="required-fields">*</em> First Name</label>
                    <input type="text" class="inputField"
                           name="paymentFName"
                           ng-model="paymentVpay.paymentFName"
                           maxlength="30"
                           ng-minlength="2"
                           ng-maxlength="30"
                           required />
                    <span ng-show="paymentForm.paymentFName.$invalid && paymentForm.paymentFName.$pristine && paymentForm.paymentFName.$dirty" class="error-message">Please enter your first name.</span>
                    <span ng-show="paymentForm.paymentFName.$error.minlength" class="error-message">First name too short.</span>
                    <span ng-show="paymentForm.paymentFName.$error.maxlength" class="error-message">First name too long.</span>
                </div>

                <div class="form-group" ng-class="{'error' : paymentForm.paymentLName.$invalid && !paymentForm.paymentLName.$pristine}">
                    <label><em class="required-fields">*</em> Last Name</label>
                    <input type="text" class="inputField"
                           name="paymentLName"
                           ng-model="paymentVpay.paymentLName"
                           maxlength="30"
                           ng-minlength="2"
                           ng-maxlength="30"
                           required />
                    <span ng-show="paymentForm.paymentLName.$invalid && paymentForm.paymentLName.$pristine && paymentForm.paymentLName.$dirty" class="error-message">Please enter your last name.</span>
                    <span ng-show="paymentForm.paymentLName.$error.minlength" class="error-message">Last name too short.</span>
                    <span ng-show="paymentForm.paymentLName.$error.maxlength" class="error-message">Last name too long.</span>
                </div>

                <div class="form-group" ng-class="{'error' : paymentForm.email.$invalid && !paymentForm.email.$pristine}">
                    <label><em class="required-fields">*</em> Email Address</label>
                    <input type="email" class="inputField"
                           validate-email
                           name="email"
                           ng-model="paymentVpay.email"
                           required />
                    <span ng-show="paymentForm.email.$error.email && paymentForm.email.$invalid && !paymentForm.email.$pristine" class="error-message">Invalid email address.</span>
                </div>
                <div class="form-group" ng-class="{'error' : paymentForm.paymentPhone.$invalid && !paymentForm.paymentPhone.$pristine}">
                    <label><em class="required-fields">*</em> Phone Number</label>
                    <input type="text" class="inputField"
                           name="paymentPhone"
                           ng-model="paymentVpay.paymentPhone"
                           maxlength="20"
                           ng-minlength="6"
                           ng-maxlength="20"
                           required />
                    <span ng-show="paymentForm.paymentPhone.$invalid && paymentForm.paymentPhone.$pristine && paymentForm.paymentPhone.$dirty" class="error-message">Please enter phone number.</span>
                    <span ng-show="paymentForm.paymentPhone.$error.minlength" class="error-message">Phone number too short.</span>
                    <span ng-show="paymentForm.paymentPhone.$error.maxlength" class="error-message">Phone number too long.</span>
                </div>
                <div class="form-group" style="padding-bottom: 0;"
                     ng-class="{'error' : paymentForm.birthYear.$error.required && paymentForm.birthYear.$dirty
                                        || paymentForm.birthMonth.$error.required && paymentForm.birthMonth.$dirty
                                        || paymentForm.birthDay.$error.required && paymentForm.birthDay.$dirty}">
                    <label><em class="required-fields">*</em> Birthdate</label>
                    <select id="birthYear" class="select_dateYear form-control inputField inputField-xs"
                            name="birthYear" ng-model="paymentVpay.birthYear"
                            ng-options="year for year in Years" ng-change="UpdateNumberOfDays()">
                        <option value="" selected="selected" ng-bind="Year"></option>
                    </select>
                    <select id="birthMonth" class="select_dateMonth form-control inputField inputField-md"
                            name="birthMonth" ng-model="paymentVpay.birthMonth"
                            ng-options="month for month in Months" ng-change="UpdateNumberOfDays()">
                        <option value="" selected="selected" ng-bind="Month"></option>
                    </select>
                    <select id="birthDay" class="select_dateDay form-control inputField inputField-xs"
                            name="birthDay" ng-model="paymentVpay.birthDay"
                            ng-options="day for day in Days | limitTo:NumberOfDays">
                        <option value="" selected="selected" ng-bind="Day"></option>
                    </select>
                    <span ng-show="paymentForm.birthYear.$error.required && paymentForm.birthYear.$dirty" class="error-message">Please enter birth year.</span>
                    <span ng-show="paymentForm.birthMonth.$error.required && paymentForm.birthMonth.$dirty" class="error-message">Please enter birth month.</span>
                    <span ng-show="paymentForm.birthDay.$error.required && paymentForm.birthDay.$dirty" class="error-message">Please enter birth date.</span>
                </div>
            </div>

            <div class="editFormBox float-left">

                <div class="form-group" ng-class="{'error' : paymentForm.paymentAddress.$invalid && !paymentForm.paymentAddress.$pristine}">
                    <label><em class="required-fields">*</em> Address</label>
                    <input type="text" class="inputField"
                           name="paymentAddress"
                           ng-model="paymentVpay.paymentAddress"
                           required />
                    <span ng-show="paymentForm.paymentAddress.$invalid && !paymentForm.paymentAddress.$pristine" class="error-message">Please enter address.</span>
                </div>

                <div class="form-group" ng-class="{'error' : paymentForm.paymentCity.$invalid && !paymentForm.paymentCity.$pristine}">
                    <label><em class="required-fields">*</em> City</label>
                    <input type="text" class="inputField"
                           name="paymentCity"
                           ng-model="paymentVpay.paymentCity"
                           required />
                    <span ng-show="paymentForm.paymentCity.$invalid && !paymentForm.paymentCity.$pristine" class="error-message">Please enter city.</span>
                </div>

                <div class="form-group" ng-class="{'error' : paymentForm.country.$invalid && !paymentForm.country.$pristine}">
                    <label><em class="required-fields">*</em> Country</label>
                    <select class="inputField" ng-model="paymentVpay.currencyNo" ng-options="c.countryCode as c.countryName for c in countryCodes">
                        <option selected value="<?=$_POST["country"]?>"></option>
                    </select>
                    <span ng-show="paymentForm.country.$error.required && paymentForm.country.$dirty" class="error-message">Please enter birth year.</span>
                </div>

                <div class="form-group" ng-class="{'error' : paymentForm.paymentZip.$invalid && !paymentForm.paymentZip.$pristine}">
                    <label><em class="required-fields">*</em> Zip</label>
                    <input type="text" class="inputField inputField-xs"
                           name="paymentZip"
                           ng-model="paymentVpay.paymentZip"
                           required />
                    <span ng-show="paymentForm.paymentZip.$invalid && !paymentForm.paymentZip.$pristine" class="error-message">Please enter ZIP code.</span>
                </div>

            </div>

            <div class="clear"></div><br />
            <h2>Card Information</h2>

            <div class="editFormBox float-left">
                <div class="form-group" ng-class="{'error' : paymentForm.paymentCCName.$error.required && paymentForm.paymentCCName.$invalid && paymentForm.paymentCCName.$dirty }">
                    <label><em class="required-fields">*</em> Cardholder Name</label>
                    <input type="text" class="inputField"
                           name="paymentCCName"
                           ng-model="paymentCCName"
                           maxlength="30"
                           ng-minlength="4"
                           ng-maxlength="30"
                           required />
                    <span ng-show="paymentForm.paymentCCName.$invalid && paymentForm.paymentCCName.$dirty && paymentForm.paymentCCName.$error.required" class="error-message">Please enter cardholder name.</span>
                    <span ng-show="paymentForm.paymentCCName.$error.minlength" class="error-message">Name too short.</span>
                    <span ng-show="paymentForm.paymentCCName.$error.maxlength" class="error-message">Name too long.</span>
                </div>
                <div class="form-group" ng-class="{error: mod10Error || ( paymentForm.card.$error.required && paymentForm.card.$invalid && paymentForm.card.$dirty ) }">
                    <label><em class="required-fields">*</em> Credit Card</label>
                    <input type="credit card" class="inputField"
                           ng-model="card"
                           name="card"
                           ng-blur="mod10Error = !!paymentForm.card.$error.mod10"
                        required/>
                    <i class="icon-credit-card"></i>
                    <span ng-show="paymentForm.card.$error.mod10 || ( paymentForm.card.$error.required && paymentForm.card.$invalid && paymentForm.card.$dirty) " class="error-message">This does not appear to be a valid credit card.</span>
                </div>
                <div class="form-group" style="padding-bottom: 0;" ng-class="{'error' : paymentForm.code.$error.required && paymentForm.code.$invalid && paymentForm.code.$dirty}">
                    <label><em class="required-fields">*</em>Amount</label>
                    <span class="txtAmount"><?=$purchaseAmount?> <em class="note">(USD 25 minimum deposit required)</em></span>
                </div>
            </div>
            <div class="editFormBox float-left">
                <div class="form-group"
                     ng-class="{'error' : paymentForm.expiryMonth.$error.required && paymentForm.expiryMonth.$invalid && paymentForm.expiryMonth.$dirty ||
                                        paymentForm.expiryYear.$error.required && paymentForm.expiryYear.$invalid && paymentForm.expiryYear.$dirty}">
                    <label><em class="required-fields">*</em> Expiration Date</label>
                    <input type="text" class="inputField inputField-xs"
                           placeholder="mm"
                           name="expiryMonth"
                           ng-model="expiryMonth"
                           min="1"
                           max="12"
                           maxlength="2"
                           ng-minlength="2"
                           ng-maxlength="2"
                           required />
                    <span>/</span>
                    <input type="text" class="inputField inputField-xs"
                           placeholder="yyyy"
                           name="expiryYear"
                           ng-model="expiryYear"
                           maxlength="4"
                           ng-minlength="4"
                           ng-maxlength="4"
                           required />
                    <div class="clear"></div>
                    <span ng-show="paymentForm.expiryMonth.$error.required && paymentForm.expiryYear.$error.required && paymentForm.expiryMonth.$invalid && paymentForm.expiryYear.$invalid && paymentForm.expiryMonth.$dirty && paymentForm.expiryYear.$dirty" class="error-message">Please enter expiry date.</span>
                    <span ng-show="paymentForm.expiryMonth.$error.minlength || paymentForm.expiryMonth.$invalid && paymentForm.expiryYear.$dirty" class="error-message">Invalid Month.</span>
                    <span ng-show="paymentForm.expiryYear.$error.minlength || paymentForm.expiryYear.$invalid && paymentForm.expiryYear.$dirty" class="error-message">Invalid Year.</span>
                </div>
                <div class="form-group" style="padding-bottom: 0;" ng-class="{'error' : paymentForm.code.$error.required && paymentForm.code.$invalid && paymentForm.code.$dirty}">
                    <label><em class="required-fields">*</em> CCV</label>
                    <input type="cvc" class="inputField inputField-xs"
                           ng-model="code"
                           name="code"
                           required />
                    <i class="icon-info icon-tip-sec-code"></i>
                    <div class="tooltip tooltipSecCode">
                        <img src="/common/images/cc-sec-code.png" />
                    </div>
                    <span ng-show="paymentForm.code.$invalid && paymentForm.code.$dirty && paymentForm.code.$error.required" class="error-message">Please enter card security code.</span>
                </div>

            </div>
            <input type="hidden" value="<?=$orderDescription?>" name="orderID" />
            <input type="hidden" value="<?=$purchaseAmount?>" name="purchaseAmount" />
            <input type="hidden" value="<?=$md5Sign?>" name="Key" />
            <input type="hidden" value="<?=$_POST["accessToken"]?>" name="accessToken" />
            <input type="hidden" value="<?=$_POST["depositNo"]?>" name="depositNo" />
            <input type="hidden" value="<?=$_POST["agentId"]?>" name="agentId" />
            <input type="hidden" value="<?=$_POST["agentPw"]?>" name="agentPw" />
            <input type="hidden" value="<?=$_POST["serverIP"]?>" name="serverIP" />
            <input type="hidden" value="<?=$referrerDomain?>" name="referrerDomain" />

            <div class="clear"></div>
            <div class="text-center margin-top">
                <button type="submit" class="btn btn-submit" ng-disabled="paymentForm.$invalid || paymentForm.$pristine || paymentForm.$error.required || isProcessing" >Process</button>
            </div>
        </form>
    </div>
    <div class="poweredby">
        <span style="float: left; font-size: 10px; padding: 0 5px;">* Required Fields</span>
        <span>Powered by</span><i class="logo-vpay"></i>
    </div>
</div>
<script type="text/javascript" src="/common/js/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="/common/js/angular.min.js"></script>
<script type="text/javascript" src="/common/js/angular-card-input.js"></script>
<script type="text/javascript">

    var app = angular.module('paymentApp', ['creditCardInput']);
    app.controller("PaymentController",function($scope,$filter){
        $scope.isProcessing=false;

        $scope.paymentVpay={};
        $scope.paymentVpay.paymentFName ="<?=$_POST["firstName"]?>";
        $scope.paymentVpay.paymentLName ="<?=$_POST["lastName"]?>";
        $scope.paymentVpay.email ="<?=$_POST["email"]?>";
        $scope.paymentVpay.paymentPhone ="<?=$_POST["phone"]?>";
        $scope.paymentVpay.paymentAddress ="<?=$_POST["address"]?>";
        $scope.paymentVpay.paymentCity ="<?=$_POST["city"]?>";
        $scope.paymentVpay.paymentZip ="<?=$_POST["zip"]?>";
        $scope.paymentVpay.currencyNo="<?=$_POST["country"]?>";

        $scope.paymentVpay.paymentCCName ="";
        $scope.paymentVpay.card ="";
        $scope.paymentVpay.expiryMonth ="";
        $scope.paymentVpay.expiryYear ="";
        $scope.paymentVpay.code ="";

        $scope.countryCodes=
            [{"countryCode":"TH","countryName":"THAILAND"},
                {"countryCode":"IN","countryName":"INDIA"},
                {"countryCode":"ID","countryName":"INDONESIA"},
                {"countryCode":"MY","countryName":"MALAYSIA"},
                {"countryCode":"TW","countryName":"TAIWAN"},
                {"countryCode":"KR","countryName":"KOREA"},
                {"countryCode":"US","countryName":"UNITED STATES"}];

        var numberOfYears = new Date().getFullYear() - 18;
        var numberOfRange = 90;
        var years = $.map($(Array(numberOfRange)), function(val, i) {
            return numberOfYears - i;
        });

        var months = $.map($(Array(12)), function(val, i) {
            return i + 1;
        });
        var days = $.map($(Array(31)), function(val, i) {
            return i + 1;
        });

        var isLeapYear = function() {
            var year = $scope.paymentVpay.birthYear || 0;
            return ((year % 400 === 0 || year % 100 !== 0) && (year % 4 === 0)) ? 1 : 0;
        };

        var getNumberOfDaysInMonth = function() {
            var selectMonths = $scope.paymentVpay.birthMonth || 0;
            return 31 - ((selectMonths === 2) ? (3 - isLeapYear()) : ((selectMonths - 1) % 7 % 2));
        };

        $scope.UpdateNumberOfDays = function() {
            $scope.NumberOfDays = getNumberOfDaysInMonth();
        };

        $scope.Years = years;
        $scope.Months = months;
        $scope.Days = days;
        $scope.NumberOfDays = 31;

        $scope.paymentVpay.birthYear=<?=date("Y",strtotime($_POST["birthDate"]))?>;
        $scope.paymentVpay.birthMonth=<?=date("m",strtotime($_POST["birthDate"]))?>;
        $scope.paymentVpay.birthDay=<?=date("d",strtotime($_POST["birthDate"]))?>;

        $scope.birthDate = $filter('date')(Date.parse($scope.paymentVpay.birthYear+"-"+$scope.paymentVpay.birthMonth+"-"+$scope.paymentVpay.birthDay), "yyyyMMdd");
        //console.log($scope.birthDate);

    });

    //Email Validation
    app.directive('validateEmail', function() {
        var EMAIL_REGEXP = /^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|name|net|org|pro|travel|mobi|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i;
        return {
            require: 'ngModel',
            restrict: '',
            link: function(scope, elm, attrs, ctrl) {
                if (ctrl && ctrl.$validators.email) {

                    ctrl.$validators.email = function(modelValue) {
                        return ctrl.$isEmpty(modelValue) || EMAIL_REGEXP.test(modelValue);
                    };
                }
            }
        };
    });

    $('.tooltip').hide();
    $('.icon-tip-sec-code').hover(function(){ $('.tooltipSecCode').show();}, function() {$('.tooltipSecCode').hide(); });

</script>

</body>
</html>