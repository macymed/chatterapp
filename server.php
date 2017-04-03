<?php

//load
$api_method = isset($_POST['api_method']) ? $_POST['api_method'] : '';
$api_data = isset($_POST['api_data']) ? $_POST['api_data'] : '';

//validate
if(empty($api_method) || empty($api_data)){
  API_Resonse(true, 'Invalid Request');
}
if(!function_exists($api_method)){
  API_Response(true, 'API method not implemented');
}
$link = mysql_connect('localhost', 'root', 'Chatapp!');

if(!$link){
  die('Could not connect to mySQL: '. mysql_error());
}

mysql_select_db("chatapp");
call_user_func($api_method, $api_data);

//response functions
function API_Response($isError, $errorMessage, $responseData =''){
    exit(json_encode(array(
      'isError' => $isError,
      'ErrorMessage' => $errorMessage,
      'ResponseData' => $responseData
  )));
}


function API_Response1($isError, $errorMessage, $new_array)
{
    exit(json_encode(array(
        'IsError' => $isError,
        'ErrorMessage' => $errorMessage,
        'Array' => $new_array
    )));
}
function API_Response2($isError, $errorMessage, $messageID,
  $messageSenderRegID, $messageReceiverRegID, $messageText, $messageTimestamp)
{
    exit(json_encode(array(
        'IsError' => $isError,
        'ErrorMessage' => $errorMessage,
        'MessageID' => $messageID,
        'MessageSenderRegisID' => $messageSenderRegID,
        'MessageReceiverRegisID' => $messageReceiverRegID,
        'MessageText' => $messageText,
        'MessageTimestamp' => $messageTimestamp
    )));
}
function API_Response3($isError, $errorMessage, $preKeyID = '', $prekey)
{
    exit(json_encode(array(
        'IsError' => $isError,
        'ErrorMessage' => $errorMessage,
        'PreKeyID' => $preKeyID,
        'Prekey' => $prekey
    )));
}

//api methods
function registerUser($api_data)
{
    $userRegInfo = json_decode($api_data);

    $password = $userRegInfo->Password;
    $username = $userRegInfo->Username;
    $registrationID = $userRegInfo->RegistrationID;
    $publicIdentityKey = $userRegInfo->PublicIdentityKey;
    $publicSignedPreKeyID = $userRegInfo->PublicSignedPreKeyID;
    $publicSignedPreKeySignature = $userRegInfo->PublicSignedPreKeySignature;
    $publicSignedPreKey = $userRegInfo->PublicSignedPreKey;

    $link=mysql_connect('localhost','root','Chatapp!');

    if (!$link){
      die('Could not connect to MySQL: ' . mysql_error());
    }

    mysql_select_db("chatapp");

    $sql = "INSERT INTO `users`(`Username`, `Password`, `RegistrationID`,
      `IdentityKey`, `SignedPreKeyID`, `SignedPreKeySignature`, `SignedPreKey`)
      VALUES ('$username','$password','$registrationID','$publicIdentityKey',
      '$publicSignedPreKeyID','$publicSignedPreKeySignature','$publicSignedPreKey')";

    if(mysql_query($sql,$link)){
        API_Response(false, '', 'SUCCESS');
    }else{
        API_Response(true, '', 'FAILED');
    }
    mysql_close($link);
}
