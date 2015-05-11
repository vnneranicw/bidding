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
        $title = PROJECT_NAME . " - Dashboard";
        require_once("includes/head.php");
    ?>
    <script type="text/javascript" src="<?php echo HTTP_HOST; ?>/js/dashboard_js.js"></script>
    <style type="text/css">
        .item{
            border: 1px solid black;
            height: 300px;
            margin: 10px;
        }
        
        .item_label{
            font-size: 20px;
            font-style: italic;
            text-align:center;
        }
    </style>
</head>
<body>
    <div id="body-div" class="body-div">
        <br /><br /><br />
        <div class="row">
            <div id="itemArea" class="large-12 columns">
                <?php
                    $sql = "SELECT * FROM bid_product";
                    list($check, $result) = result_query($sql);
                    
                    if($check) {
                        $items_html = "";
                        $counter_3 = 0;
                        $counter = 0;
                        $row_count = mysqli_num_rows($result);
                        
                        //init encryption
                        $iOldKeySize = GibberishAES::size();
                        GibberishAES::size(128);// Also 192, 128
                        
                        while($row = mysqli_fetch_array($result)){
                            if($counter_3 == 0){
                                $items_html .= '<div class="row">';
                            }
                            
                            $token = '[{"id":"'.$row["bid_product_id"].'"}]';
                            $sEncrypted = GibberishAES::enc($token, $_SESSION[sha1("pass_phrase")]);
                            $token = $sEncrypted;
                            
                            $items_html .= '<div class="large-4 columns"><div id="p'.$row["bid_product_id"].'" data-token="'.$token.'" class="item">'.$row["name"].
                                    '<br /><button class="item_btn_bid">Bid now</button><br/><div class="item_bid_status" style="display:none;"></div></div></div>';
                            
                            if($counter_3 == 2 || ($row_count - 1) == $counter){
                                if($counter_3 < 2 && ($row_count - 1) == $counter){
                                    for($i = $counter_3; $i < 2; $i++){
                                        $items_html .= '<div class="large-4 columns"><div class="item">fill up '.$i.'</div></div>';
                                    }
                                }
                                $items_html .= '</div>';
                                $counter_3 = -1;
                            }
                            
                            $counter_3 = $counter_3 + 1;
                            $counter = $counter + 1;
                        }
                        
                        GibberishAES::size($iOldKeySize);// Restore the old key size.
                        
                        if(strlen($items_html) > 0){
                            echo $items_html;
                        } else {
                            echo 'No product found.';
                            //need to remove
                        }
                    } else {
                        echo $result;
                    }
                    
                ?>
                <div class="row">
                    <div class="large-4 columns">
                        <div class="item">
                            <div class="item_label">I am the item name</div>
                            
                            <div class="item_image">I am the image</div>
                            
                            <div class="item_counter">I am the counter</div>
                            
                            <div class="item_counter">I am the last bidder</div>
                            
                            <div class="item_current_value">RM10.00</div>
                            
                            <div class="item_current_value">i am button</div>
                            
                            <div class="item_current_value">i am bid status</div>
                        </div>

                    </div>

                    <div class="large-4 columns">
                        <div class="item">
                            
                        </div>
                    </div>

                    <div class="large-4 columns">
                        <div class="item">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!--<div class="row">
            <div class="large-12 columns">
                <div id="dvErrMsg" style="display:none;" data-alert class="alert-box alert radius"><span id="errMsg"></span><a href="#" class="close">&times;</a></div>
            </div>
        </div>-->
    </div>
    <script>
            $(document).foundation().foundation('joyride', 'start');
    </script>
</body>
</html>