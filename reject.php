<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <style>
        body {font-size: 12px; font-family: 'Roboto', sans-serif; color: #666666; background-color: #e7e7e7;}
        #popup-transaction {padding: 15px 15px 8px 15px; background-color: #e7e7e7; width: 620px !important; height: 400px !important; margin: 0 auto;}
        #popup-transaction .popup-content {width: 612px; height: 348px; min-height: 348px; background-color: #fff; border: 1px solid #d9d9d9; text-align: center;}
        #popup-transaction .popup-content h2 {display: block; color: #5bc100; font-size: 28px; font-weight: 300; line-height: 50px; margin: 0;}
        #popup-transaction .popup-content h2.title-error {color: #cc0000;}
        #popup-transaction .popup-content p {line-height: 18px; margin: 0;}
        .icon-error {display: block; width: 127px; height: 116px; margin: 55px auto 10px auto; background: url("/common/images/icon-error.png") center no-repeat;}
        .poweredby {float: right; padding-top: 6px;}
        .poweredby span, .poweredby i {display: inline-block; vertical-align: middle; margin: 0 5px;}
        .poweredby i {width: 57px; height: 22px;}
        .logo-vpay {background: url("/common/images/logo-vpay.png") 0 0 no-repeat;}
    </style>
</head>
<body>
<div id="popup-transaction">
    <div class="popup-content">
        <div class="icon-error"></div>
        <h2 class="title-error">Transaction Failed!</h2>
        <p><?=$_GET['res']." - ".$_GET['desc']?></p>
        <p>Sorry, your transaction could not be completed at this moment.</p>
        <p>Please contact customer service for further assistance. Thank you!</p>
    </div>
    <div class="poweredby">
        <span>Powered by</span><i class="logo-vpay"></i>
    </div>
    <div class="clear"></div>
</div>
</body>
</html>
