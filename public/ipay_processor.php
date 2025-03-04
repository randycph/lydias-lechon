<?php

    ob_start();

    $arr['merchantCode'] = $_REQUEST["MerchantCode"];
    $arr['paymentid'] = $_REQUEST["paymentid"];
    $arr['RefNo'] = $_REQUEST["RefNo"];
    $arr['Amount'] = $_REQUEST["Amount"];
    $arr['Currency'] = $_REQUEST["Currency"];
    $arr['Remark'] = $_REQUEST["Remark"];
    $arr['TransId'] = $_REQUEST["TransId"];
    $arr['AuthCode'] = $_REQUEST["AuthCode"];
    $arr['Status'] = $_REQUEST["Status"];
    $arr['ErrDesc'] = $_REQUEST["ErrDesc"];
    $arr['cc_name'] = $_REQUEST["CCName"];
    $arr['cc_no'] = $_REQUEST["CCNo"];
    $arr['bank_name'] = $_REQUEST["S_bankname"];
    $arr['country'] = $_REQUEST["S_country"];
    $arr['Signature'] = $_REQUEST["Signature"];
    $arr['order_completed'] = "go";

    if($arr['Status'] == 1){
        //header("location:https://beta.lydias-lechon.com/order?".http_build_query($arr));
        header("location:https://lydias-lechon.com/complete_payment?".http_build_query($arr));
    }
    else{
        //header("location:https://beta.lydias-lechon.com/account/sales?order_cancelled=1&order_no=".$arr['RefNo']);
        header("location:https://lydias-lechon.com/cancel_payment?".http_build_query($arr));
    }
    

?>
 

<form name="ePayment" action="https://sandbox.ipay88.com.ph/epayment/entry.asp" method="post" style="display:none;">
    <input type="text" name="merchantcode" value="PH00125">
    <input type="text" name="paymentid" value="1">
    <input type="text" name="RefNo" value="20200119007">
    <input type="text" name="Amount" value="1.00">
    <input type="text" name="Currency" value="PHP">
    <input type="text" name="Remark" value="Lydias Lechon Sample">
    <input type="text" name="ProdDesc" value="Lechon Large">
    <input type="text" name="UserName" value="Jundrie">
    <input type="text" name="UserEmail" value="jundrie@gmail.com">
    <input type="text" name="UserContact" value="+639176248072">
    <input type="text" name="ResponseURL" value="https://lydias-lechon.com/ipay.php?receiver=go">
    <input type="text" name="BackendURL" value="https://lydias-lechon.com/ipay.php?receiver=go">
    <input type="text" name="signature" value="OZei/KAlXSCrkG6cCIJLIH8M/ME=">
    <input type="submit" value="Go">
</form>
