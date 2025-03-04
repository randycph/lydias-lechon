<form style="display:none;" action="https://payment.ipay88.com.ph/epayment/enquiry.asp" method="post">
	<input type="text" name="MerchantCode" id="MerchantCode" value="PH00125">
	<input type="text" name="RefNo" id="RefNo" value="0000602">
	<input type="text" name="Amount" id="Amount" value="18130.00">
	<input type="submit">
</form>

<?php
/*
echo Requery();
function Requery(){
$MerchantCode = 'PH00125';
$RefNo = '0000602';
$Amount = '18130.00';
$query = "https://payment.ipay88.com.ph/epayment/enquiry.asp?MerchantCode=" . $MerchantCode . "&RefNo=" . str_replace(" ","%20",$RefNo) . "&Amount=" . $Amount;

$url = parse_url($query);
$host = $url["host"];
$path = $url["path"] . "?" . $url["query"];
$timeout = 1;
$fp = fsockopen ($host, 80, $errno, $errstr, $timeout);
if ($fp) {
  fputs ($fp, "GET $path HTTP/1.0\nHost: " . $host . "\n\n");
  while (!feof($fp)) {
    $buf .= fgets($fp, 128);
  }
  $lines = split("\n", $buf);
  $Result = $lines[count($lines)-1];
  fclose($fp);
} else {
  # enter error handing code here
}
return $Result;

}
*/
// $merchantCode = 'PH00125';
// $ref = '0000602';
// $amount = '18130.00';
// $q = 'https://payment.ipay88.com.ph/epayment/enquiry.asp?MerchantCode='.$merchantCode.'&RefNo='.$ref.'&Amount='.$amount;
// $url = parse_url($q);
// //print_r($url);
// $host = $url["host"];
// $path = $url["path"]."?".$url["query"];
// $timeout=1;
// $fp=fsockopen($host,80,$errno=null,$errstr=null,$timeout);
// var_dump($fp);
// if($fp){
// 	fputs($fp,"GET $path HTTP/1.0\nHost:".$host."\n\n");
// 	while(!feof($fp)){
// 		$buf.=fgets($fp,128);
// 	}
// 	$lines = split("\n",$buf);
// 	$Result = $lines[count($lines)-1];
// 	fclose($fp);
// }
// else{
// 	echo "error tsk";
// }
// echo $Result;
