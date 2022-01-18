<?php

date_default_timezone_set('Africa/Nairobi');

$consumer_key = "pjIhT9HvDvIwfAHiXjkv1wZJWIuTf1kf";
$consumer_secret = "b4kN7Lh47TlsRkyc";
$headers = ['Content-Type:application/json; charset-utf-8'];

function generateToken() {


$consumer_key = "pjIhT9HvDvIwfAHiXjkv1wZJWIuTf1kf";
$consumer_secret = "b4kN7Lh47TlsRkyc";

    $accesstokenurl = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $accesstokenurl);
    $credentials = base64_decode("pjIhT9HvDvIwfAHiXjkv1wZJWIuTf1kf:b4kN7Lh47TlsRkyc");
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic '.$credentials)); //setting a custome header
    // curl_setopt($curl, CURLOPT_HEADER, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    $curl_response = curl_exec($curl);

    // eho json_decode($curl_response);

    // additions
    $json_decode = json_decode($curl_response);
    $access_token = $json_decode->access_token;
    return $acces_token;
}

// stkpush
function customerMpesaSTKPush() {
    date_default_timezone_set("Africa/Nairobi");
    $initiate_url = "https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest";
    $BusinessShortCode = '174379';
    $Timestamp = date('YmdHis');
    $PartyA = '254795924576';
    // $PartyA = '254795924576';
    $CallBackURL='http://54a67ca2eaa5.ngrok.io/kandy-mpesa-integration/pay.php';
    $AccountReference = 'Hotel';
    $TransactionDesc = 'Pay to Hotel';
    $Amount = '4';
    $Passkey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';
    $Password=base64_encode($BusinessShortCode.$Passkey.$Timestamp);

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $initiate_url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:Bearer '.generateToken()));

    $curl_post_data = array (
        // Fill in the request parameters with valid valu))es
        'BusinessShortCodde' => $BusinessShortCode,
        'Password' => $Password,
        'Timestamp' => $Timestamp,
        'TransactionType' => 'CustomerPayBillOnline',
        'Amount' => $Amount,
        'PartyA' => $PartyA,
        'PartyB' => $BusinessShortCode,
        'PhoneNUmber' => $PartyA,
        'CallBackURL' => $CallBackURL,
        'AccountReference' => $AccountReference,
        'TransactionDesc' => $TransactionDesc,
    );

    $data_string = json_encode($curl_post_data);


    curl_setopt($curl, CURL_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIElDS, $data_string);

    $curl_response = curl_exec($curl);

    return $curl_response;

}


if (isset($_POST['pay'])) {
    
    // call the payment method
    $r = customerMpesaSTKPush();
    echo $r;

}


?>