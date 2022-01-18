

<?php

$mysqli = mysqli_connect("localhost", "root", "", "kandympesa");

if (isset($_POST['pay'])) {

    
    date_default_timezone_set('Africa/Nairobi');


    // getting input from the form
    $fullname = mysqli_real_escape_string($mysqli, $_POST['fullname']);
    $item = mysqli_real_escape_string($mysqli, $_POST['product']);
    $price = mysqli_real_escape_string($mysqli, $_POST['price']);
    // $paidamount = $_REQUEST['price'];
    echo $price;

    #access token
    $consumer_key =  'pjIhT9HvDvIwfAHiXjkv1wZJWIuTf1kf'; 
    $consumer_secret = 'b4kN7Lh47TlsRkyc'; 

    // define the variables
    $passKey = "bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919";
    $businessShortCode = 174379;

    // transaction details
    $partyA = 254795924576;
    $AccountReference = '35789';
    $TransactionDesc = 'Payment';
    $Amount = 1;

    // get the timestamp
    $Timestamp = date('YmdHis');

    # Get the base64 encoded string -> $password. The passkey is the M-PESA Public Key
    $Password = base64_encode($businessShortCode.$passKey.$Timestamp);

    // header for access token
    $headers = ['Content-Type:application/json; charset=utf8'];

    // mpesa endpoint urls
    $access_token_url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
    $init_url = "https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest";
    

    // callback url
    $callBackUrl = "http://9bfa-197-248-157-159.ngrok.io/kandy-mpesa-integration/kandy-processor.php";

    $curl = curl_init($access_token_url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    // added the line below
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    // ........................
    curl_setopt($curl, CURLOPT_HEADER, FALSE);
    curl_setopt($curl, CURLOPT_USERPWD, $consumer_key.':'.$consumer_secret);
    $result = curl_exec($curl);
    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $result = json_decode($result);
    $access_token = $result->access_token;  
    curl_close($curl);

    // header for stk push
    $stkheader = ['Authorization:Bearer '.$access_token, 'Content-Type:application/json'];

    // initiating the transaction
    $curl = curl_init($init_url);
    // curl_setopt($curl, CURLOPT_URL, $init_url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $stkheader);

    // fill in the parameters with valid values
    $curl_post_data = array (
        'BusinessShortCode' => $businessShortCode,
        'Password' => $Password,
        'Timestamp' => $Timestamp,
        'TransactionType' => 'CustomerPaybillOnline',
        'Amount' => $Amount,
        'PartyA' => $partyA,
        'PartyB' => $businessShortCode,
        'PhoneNumber' => $partyA,
        'CallBackURL' => $callBackUrl,
        'AccountReference' => $AccountReference,
        'TransactionDesc' => $TransactionDesc
    );

    $data_string = json_encode($curl_post_data);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
    $curl_response = curl_exec($curl);
    print_r($curl_response);

    echo $curl_response;


}

?>