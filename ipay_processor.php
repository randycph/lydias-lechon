<?php

if(isset($_GET['receiver'])){
    print_r($_REQUEST);
}
?>
 

<form name="ePayment" action="https://payment.ipay88.com.ph/epayment/entry.asp" method="post">
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