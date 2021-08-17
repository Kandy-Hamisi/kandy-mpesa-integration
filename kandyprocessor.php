<?php

require 'vendor/autoload.php';

use Carbon\Carbon;

// creating a database connection
$mysqli = mysqli_connect("localhost", "root", "", "kandympesa");

// initializig variables

$fullname = "";
$price = "";
$item = "";

if (isset($_POST['price'])) {
    // getting input from the form
    $fullname = mysqli_real_escape_string($mysqli, $_POST['fullname']);
    $item = mysqli_real_escape_string($mysqli, $_POST['product']);
    $price = mysqli_real_escape_string($mysqli, $_POST['price']);
    $paidamount = $_REQUEST['price'];

    if(empty($fullname) || empty($item) || empty($price)){
        echo "<script>window.alert('Please fill in all the fields')</script>";
    }else{
        // insert items into the database
        $sql = "INSERT into payment(Fullname, Item, Price) VALUES('$fullname', '$item', $price)";
        $res = mysqli_query($mysqli, $sql);
        if($res){
            echo "<script>window.alert('Records inserted successfully into the databse')</script>";
            stkPush($paidamount);
        }else{
            echo "<script>window.alert('There was an error during the insertion to the database')</script>";
        }
    }
    
}

function lipaNaMpesaPassword(){
    // timestamp
    $timestamp = Carbon::rawParse('now')->format('YmdHms');
    $passKey = "bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919";
    $businessShortCode = 174379;
    
    //
    $mpesaPassword = base64_encode($businessShortCode.$passKey.$timestamp);

    return $mpesaPassword;
}

function newAccessToken(){

    $consumer_key = "cfljmEgFA8ylPUPA3lqmcQpOV8ChR2lq";
    $consumer_secret = "5zXs9HhQPFEbqYDR";
    $credentials = base64_encode($consumer_key. ":".$consumer_secret);
    
    // $url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

    // $curl = curl_init();
    // curl_setopt($curl, CURLOPT_URL, $url);
    // curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Basic ".$credentials, "Content-Type:application/json"));
    // curl_setopt($curl, CURLOPT_HEADER, false);
    // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    // $curl_response = curl_exec($curl);
    // $access_token = json_decode($curl_response);
    // curl_close($curl);

    // return $access_token->access_token;

    $ch = curl_init('https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer ".$credentials, "Content-Type:application/json"));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    $access_token = json_decode($response, JSON_PRETTY_PRINT);
    curl_close($ch);
    return  $access_token;

}

function stkPush($paidamount){
    $ch = curl_init("https://sandbox.safaricom.co.ke/mpesa/b2c/v1/payment-request");

    $curl_post_data = [
        "InitiatorName"=> "testapi",
        "SecurityCredential"=> "r6VI60prlfy+/zOBSqASA+aG7x37aLH3QPwdnOMFMA77tdC8AHCOypG67ap3HpY1hsLVLFK5hOk48yfH7AW3GK8ZNimeAz8Oc5VeTLzzIku7wyhcMSXgq/VeGyjBGDD2Npu6rGSFau+u4oBiT9/yf45NC5K4B+48RybGzN03nXDa2klc1RvEMuS2XU70yycLS+Qk02XCGtX6+BsrRgjwHsBBcuc+ZJmn4mOfIns5BatH/gIEBN9kB/Sj4OZjqnI1nnXYVqHCTEe/u4UhZa8ErxAxXOj59M/03PaoDiAV49YtUMdyrJWntvD6uQrLrtStv44FuFOl4veSB2Nzwiv9+Q==",
        "CommandID"=> "BusinessPayment",
        "Amount"=> $paidamount,
        "PartyA"=> 600992,
        "PartyB"=> 254795924576,
        "Remarks"=> "Test remarks",
        "QueueTimeOutURL"=> "https://127.0.0.1:8080/Kandy-Mpesa/b2c/queue",
        "ResultURL"=> "https://127.0.0.1:8080/Kandy-Mpesa/b2c/result",
        "Occassion"=> "Lipa na Mpesa",
    ];

    $data_string = json_encode($curl_post_data);

    curl_setopt($ch, CURLOPT_HEADER, [
        'Authorization: Bearer '.newAccessToken(),
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    curl_close($ch);
    echo $response;
}
?>
