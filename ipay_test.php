<?php

//         $data['merchantcode'] = 'PH00125';
//         $data['paymentid'] =1;
//         $data['RefNo'] = '20200119001';
//         $data['Amount'] = 1.00;
//         $data['Currency'] = 'PHP';
//         $data['remark'] = 'Lydias Lechon Sample';
//         $data['ProdDesc'] = 'Lechon Large';
//         $data['UserName'] = 'Jundrie';
//         $data['UserEmail'] = 'jundrie@gmail.com';
//         $data['UserContact'] = '09176248072';
//         //$data['transid'] = $request->TransId;
//         //$data['authcode'] = $request->AuthCode;
//         //$data['estatus'] = $request->Status;
//         $data['ResponseURL '] = 'https://cms4.webfocusprod.wsiph2.com/lydias/public/ipay_test.php';
//         $data['BackendURL '] = 'https://cms4.webfocusprod.wsiph2.com/lydias/public/ipay88test';
//         $data['signature'] = 'AcgJUh7IgBA7F8noK8U0qCIe/pY=';
// $content = http_build_query($data);
// // creating the context change POST to GET if that is relevant 
// $context = stream_context_create(array(
//             'http' => array(
//                 'method' => 'POST',
//                 'content' => $content, )));

// $result = file_get_contents('https://payment.ipay88.com.ph/epayment/entry.asp', null, $context);
// //dumping the reuslt
// var_dump($result);

function gensig(){
        $source['MerchantKey'] = '2amqVf04H9';
        $source['MerchantCode'] = 'PH00125';
        $source['PaymentId'] = '1';
        $source['RefNo'] = '20200119006';
        $source['Amount'] = '100';
        $source['Currency'] = 'PHP';
        $source['Status'] = '1';
        $str = '';
        foreach($source as $a){
                $str.=$a;
        }
        return iPay88_signature($str);
}
echo gensig();


function iPay88_signature($source)
{
  return base64_encode(hex2bindd(sha1($source)));
}

function hex2bindd($hexSource)
{
    for ($i=0;$i<strlen($hexSource);$i=$i+2)
    {
      $bin .= chr(hexdec(substr($hexSource,$i,2)));
    }
  return $bin;
}
?>

