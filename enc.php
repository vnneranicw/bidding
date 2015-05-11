<?php
    require_once("init.php");
    
    session_start();//need to destroy in login, regen new keys
    if(empty($_SESSION[sha1("pass_phrase")])){
        $_SESSION[sha1("pass_phrase")] = sha1("0714");//need change to random num
    }
    
    require_once("includes/GibberishAES.php");
    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["token"])){
        //decrypt
        $sEncrypted = $_POST["token"];
        $token = $sEncrypted;
        $iOldKeySize = GibberishAES::size();
        GibberishAES::size(128);// Also 192, 128
        $sDecrypted = GibberishAES::dec($sEncrypted, $_SESSION[sha1("pass_phrase")]);
        GibberishAES::size($iOldKeySize);// Restore the old key size.
        
        //decode html chars and parse json string
        $json = json_decode(html_entity_decode($sDecrypted));
        $id = $json[0]->id;
        $name = $json[0]->name;
        
        $id2 = $json[1]->id;
        $name2 = $json[1]->name;
        
        $id3 = $json[2]->id;
        $name3 = $json[2]->name;
        
        $id4 = $json[3]->id;
        $name4 = $json[3]->name;
    } else {
        //set token & encrypt it
        $token = '[{"id":"1","name":"candy"},{"id":"2","name":"pudding"},{"id":"3","name":"mouse"},{"id":"4","name":"keyboard"}]';
        $iOldKeySize = GibberishAES::size();
        GibberishAES::size(128);// Also 192, 128
        $sEncrypted = GibberishAES::enc($token, $_SESSION[sha1("pass_phrase")]);
        $token = htmlentities($sEncrypted);
        GibberishAES::size($iOldKeySize);// Restore the old key size.
    }
?>
<!DOCTYPE html>
<html class="no-js" lang="en" data-useragent="Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Trident/6.0)">
<head>
    <?php
        $title = PROJECT_NAME . " - Home";
        require_once("includes/head.php");
    ?>
    <script>
        $(document).ready(function(){
            var pass = '<?php echo $_SESSION["pass_phrase"]; ?>';
            var secret_string = 'my secret message';
            GibberishAES.size(128);
            var encrypted_secret_string = GibberishAES.enc(secret_string, pass);
            GibberishAES.size(256);  // Restore the default key size.

            $("#encrypted").val(encrypted_secret_string);

        });
    </script>
</head>
<body>
    <?php echo $_GET["id"]; ?>
    <div id="body-div" class="body-div">
        <div class="row">
            <div class="large-12 columns">
                <br /><br /><br /><br /><br />
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <table border="1" frame="void" rules="rows" style="width:100%;">
                        <tr>
                            <td style="width:27%;">Token</td>
                            <td style="width:3%;">:</td>
                            <td style="width:70%;"><input type="text" id="token" name="token" value="<?php echo $token; ?>" /></td>
                        </tr>
                    </table>
                    <div align="right"><input type="submit" class="button secondary" value="Submit" /></div>
                </form>
            </div>
            <div class="large-12 columns">
                <table border="1" frame="void" rules="rows" style="width:100%;">
                    <tr>
                        <td style="width:27%;">ID</td>
                        <td style="width:3%;">:</td>
                        <td style="width:70%;"><?php echo $id; ?></td>
                    </tr>
                    <tr>
                        <td style="width:27%;">Name</td>
                        <td style="width:3%;">:</td>
                        <td style="width:70%;"><?php echo $name; ?></td>
                    </tr>
                </table>
                <table border="1" frame="void" rules="rows" style="width:100%;">
                    <tr>
                        <td style="width:27%;">ID2</td>
                        <td style="width:3%;">:</td>
                        <td style="width:70%;"><?php echo $id2; ?></td>
                    </tr>
                    <tr>
                        <td style="width:27%;">Name2</td>
                        <td style="width:3%;">:</td>
                        <td style="width:70%;"><?php echo $name2; ?></td>
                    </tr>
                </table>
                <table border="1" frame="void" rules="rows" style="width:100%;">
                    <tr>
                        <td style="width:27%;">ID3</td>
                        <td style="width:3%;">:</td>
                        <td style="width:70%;"><?php echo $id3; ?></td>
                    </tr>
                    <tr>
                        <td style="width:27%;">Name3</td>
                        <td style="width:3%;">:</td>
                        <td style="width:70%;"><?php echo $name3; ?></td>
                    </tr>
                </table>
                <table border="1" frame="void" rules="rows" style="width:100%;">
                    <tr>
                        <td style="width:27%;">ID4</td>
                        <td style="width:3%;">:</td>
                        <td style="width:70%;"><?php echo $id4; ?></td>
                    </tr>
                    <tr>
                        <td style="width:27%;">Name4</td>
                        <td style="width:3%;">:</td>
                        <td style="width:70%;"><?php echo $name4; ?></td>
                    </tr>
                </table>
                <?php echo print_r($json); ?>
            </div>
        </div>
    </div>
    <script>
            $(document).foundation().foundation('joyride', 'start');
    </script>
</body>
</html>