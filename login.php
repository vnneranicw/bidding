<?php
    require_once("init.php");
    session_start();//need to destroy in login, regen new keys
    
    if(empty($_SESSION[sha1("pass_phrase")])){
        echo '[{"ack":"0","msg":"Invalid key."}]';
        exit();
    }
    
    require_once("includes/GibberishAES.php");
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        if(isset($_POST["u"]) && isset($_POST["p"])){
            //decrypt
            $sEncryptedUsername = $_POST["u"];
            $iOldKeySize = GibberishAES::size();
            GibberishAES::size(128);// Also 192, 128
            $username = GibberishAES::dec($sEncryptedUsername, $_SESSION[sha1("pass_phrase")]);
            
            $sEncrypted = $_POST["p"];
            $password = GibberishAES::dec($sEncrypted, $_SESSION[sha1("pass_phrase")]);
            
            $password = sha1($password);
            GibberishAES::size($iOldKeySize);// Restore the old key size.
            
            //match record from db
            list($check,$row) = login_query($username, $password);//need to create query for login
            
            if($check){
                $sql = "INSERT INTO login_attempt (user_id,locationIP) VALUES (".$row["user_id"].",'".$_SERVER['REMOTE_ADDR']."')";
                list($check, $s) = insert_query($sql);
                if($check){
                    $_SESSION[sha1("user_id")] = $row["user_id"];
                }
                
                echo '[{"ack":"1","msg":"matched :: '.$s.'"}]';
                exit();
            } else {
                //redirect("index.php?err_msg=" . html_entities($check));
                echo '[{"ack":"0","msg":"'.$row.'"}]';
                //echo $row;
                exit();
            }
            
        }
        else{
            //redirect("index.php?err_msg=" . html_entities('Please fill out all fields.'));
            echo '[{"ack":"0","msg":"Please fill out all fields.'.$_POST["u"].'"}]';
            exit();
        }
        
    } else {
        echo 'not posted';
        
    }
?>
