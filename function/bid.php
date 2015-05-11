<?php
    
    //echo '[{"ack":"0","error":"ERROR :: test error"}]';//return error msg if user id not defined in session
    //exit();
        

    session_start();
    require_once("../init.php");
    
    $uid = $_SESSION[sha1("user_id")];//get user id from session to verify connection
    $key = $_SESSION[sha1("pass_phrase")];//get key from session
    $token = $_POST["token"];//get token from post data
    
    if(empty($uid) || !isset($uid)){
        echo '[{"ack":"0","error":"ERROR :: ACCESS DENIED"}]';//return error msg if user id not defined in session
        exit();
    }
    
    if(empty($key) || !isset($key)){
        echo '[{"ack":"0","error":"ERROR :: Invalid key."}]';//return error msg if key not defined
        exit();
    }
    
    if(empty($token) || !isset($token)){
        echo '[{"ack":"0","error":"ERROR :: Invalid token."}]';//return error msg if token not defined
        exit();
    }
    
    //decrypt token
    $sDecrypted = decryptAES($token, $key);
    
    if(empty($sDecrypted) || !isset($sDecrypted)){
        echo '[{"ack":"0","error":"ERROR :: Could not retrieve token value(2)."}]';//could not decrypt token
        exit();
    }
    
    //decode html chars and parse json string
    $json = json_decode(html_entity_decode($sDecrypted));
    //get data from token json array string
    $id = $json[0]->id;
    //$name = $json[0]->name;
    
    if(empty($id) || !isset($id)){
        echo '[{"ack":"0","error":"ERROR :: Invalid token. id :: '.$sDecrypted.'"}]';//return error msg if empty value
        exit();
    }
    
    //do insertion of bidding
    $sql = "INSERT INTO bid_history (`user_id`,`bid_product_id`,`locationIP`) VALUES ($uid, $id, '".$_SERVER['REMOTE_ADDR']."')";
    list($check, $s) = insert_query($sql);
    if($check){
        //need to implement check on credit & etc..
        echo '[{"ack":"1","success":"successful"}]';
    } else {
        echo '[{"ack":"0","error":"ERROR :: Bid failed. \nDEBUG MSG **NEED TO REMOVE IN PRODUCTION** :: '.$s.$sql.'"}]';//return error msg if insertion failed
        exit();
    }
    
    
    
?>