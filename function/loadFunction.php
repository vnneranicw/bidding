<?php
    session_start();
    require_once("../init.php");
    $function_name = $_GET["function_name"];
    if(empty($function_name)){
        $function_name = $_POST["function_name"];
    }
    $uid = $_SESSION[sha1("user_id")];//get user id from session to verify connection

    if(empty($uid) || !isset($uid)){
        echo '[{"ack":"0","error":"ERROR :: ACCESS DENIED"}]';
        exit();
    }

    switch($function_name){
        case "timeout":
            $sql = "SELECT (3600000-TIME_TO_SEC(TIMEDIFF(NOW(), (SELECT Time FROM login_attempts WHERE UID = $uid ORDER BY Time DESC LIMIT 1)))*1000) diff";
            list($check,$row) = query($sql);
            echo $row["diff"];
            break;
        case "timer_value":
            $key = $_SESSION[sha1("pass_phrase")];//get key from session
            $token = $_POST["token"];
            if(empty($key) || !isset($key)){
                echo '[{"ack":"0","error":"ERROR :: Invalid key."}]';//return error msg if key not defined
                exit();
            }
            if(empty($token) || !isset($token)){
                echo '[{"ack":"0","error":"ERROR :: Invalid token. '.$_POST["token"].'"}]';//return error msg if token not defined
                exit();
            }
            
            //decrypt token
            $sDecrypted = decryptAES($token, $key);
            
            if(empty($sDecrypted) || !isset($sDecrypted)){
                echo '[{"ack":"0","error":"ERROR :: Could not retrieve token value(2)."}]';//(2) could not decrypt token
                exit();
            }
            
            //decode html chars and parse json string
            $json = json_decode(html_entity_decode($sDecrypted));
            //get data from token json array string
            $id = $json[0]->id;
            
            //return current sec, current value..., last bidder...
            $sql = "SELECT NOW() - time AS sec FROM bid_history WHERE bid_product_id = $id ORDER BY time DESC LIMIT 1";
            
            $s_return = '[{"ack":"1", "curr_sec":"_sec no more than 15", "last_bid": "_name", "curr_val":"rm xxx"}]';
            
            
            
            echo $id;
            
            break;
        default:
                echo '[{"ack":"0","error":"ERROR :: UNKNOW FUNCTION"}]';
                break;	
    }
?>