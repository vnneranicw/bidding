<?php
    require_once("init.php");
    
    session_start();//need to destroy in login, regen new keys
    if(empty($_SESSION[sha1("pass_phrase")])){
        $_SESSION[sha1("pass_phrase")] = sha1("0714");//need change to random num
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
            //bind enter event in input fields
            $("#username").focus();
            $("#tblLogin input").keypress(function(e){
                var key = e.which;
                if(key == 13)
                {
                    login();
                }
            });          
        });
        
        function login(){
            //$("#dvErrMsg").hide("slow");
            var err_msg = "";
            var p = '<?php echo $_SESSION[sha1("pass_phrase")]; ?>';
            var u = $("#username").val();
            if(u.length == 0){
                err_msg += "Please fill in Username.";
            }
            GibberishAES.size(128);
            var ues = GibberishAES.enc(u, p);
            
            
            var pa = $("#password").val();
            if(pa.length == 0){
                err_msg += "Please fill in password.";
            }
            var pes = GibberishAES.enc(pa, p);
            
            GibberishAES.size(256);// Restore the default key size.
            
            $.ajax({
                type: "POST",
                url: "login.php",
                data: {'u':ues,'p':pes},
                dataType: "json",
                //contentType: "application/json; charset=utf-8",//cannot exits
                cache: false,
                success: function(data)
                {
                    //console.log(data);
                    if(data[0].ack == "1"){
                        window.location.replace("admin/");
                        //alert(data[0].msg);
                        $("#errMsg").text("");
                        $("#dvErrMsg").hide();
                    } else {
                        $("#errMsg").text(data[0].msg);
                        $("#dvErrMsg").show();
                    }
                }
            });
        }
    </script>
</head>
<body>
    <div id="body-div" class="body-div">
        <div class="row">
            <div class="large-12 columns">
                <br /><br /><br /><br /><br />
                <table id="tblLogin" border="1" frame="void" rules="rows" style="width:100%;">
                    <tr>
                        <td style="width:27%;">Username</td>
                        <td style="width:3%;">:</td>
                        <td style="width:70%;"><input type="text" id="username" name="username" value="" /></td>
                    </tr>
                    <tr>
                        <td style="width:27%;">Password</td>
                        <td style="width:3%;">:</td>
                        <td style="width:70%;"><input type="password" id="password" name="password" value="" /></td>
                    </tr>
                </table>
                <div id="dvErrMsg" style="display:none;" data-alert class="alert-box alert radius"><span id="errMsg"></span><a href="#" class="close">&times;</a></div>
                <div align="right"><input type="submit" class="button success" value="Submit" onclick="login();" /></div>
                <form id="hLogin" method="POST" action="login.php">
                    <input type="hidden" id="u" name="u" value="" />
                    <input type="hidden" id="p" name="p" value="" />
                </form>
            </div>
        </div>
    </div>
    <script>
            $(document).foundation().foundation('joyride', 'start');
    </script>
</body>
</html>