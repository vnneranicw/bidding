<?php

function redirect($page = "index.php"){
	$url = "http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]);
	$url = rtrim($url, '/\\');
	$url .= $page;
	header("Location: $url");
	exit();
}

function send_mail($name,$to,$from,$subject,$message)
{
	// Pear Mail Library
	require(HTTP_LOC . "/mail/pearmail/Mail.php");
	
	$from = "$name <$from>";//sender
	$to = "<$to>";
	$body = $message;
	
	$headers = array('From' => $from, 'To' => $to, 'Subject' => $subject, 'Reply-to' => $from);
	
	$smtp = Mail::factory('smtp', array(
		'host' => 'ssl://smtp.gmail.com',
		'port' => '465',
		'auth' => true,
		'username' => 'petadvisorysystem@gmail.com',
		'password' => 'petas0714'));
	
	$mail = $smtp->send($to, $headers, $body);
	
	if (PEAR::isError($mail))
	{
		return FALSE;
	}
	else
	{
		return TRUE;
	}
}

function spamcheck($field) {
	// Sanitize e-mail address
	$field=filter_var($field, FILTER_SANITIZE_EMAIL);
	
	// Validate e-mail address
	if(filter_var($field, FILTER_VALIDATE_EMAIL))
	{
		return TRUE;
	}
	else
	{
		return FALSE;
	}
}

function contact_check($contact) {
	if(strlen($contact) < 7){
		return array(FALSE, 'Contact require at least 7 digits.<br />');
	}
	else if($contact == "" && $contact == null){
		return array(FALSE, 'Contact number is required.<br />');
	}
	else {
		$pattern = "/^[0-9\_-]{7,20}/";
		if (preg_match($pattern,$contact)){
			return array(TRUE, '');
		}
		else {
			return array(FALSE, 'Your contact number is invalid.<br />');
		}
	}
}

function login_query($username, $password){
    include HTTP_LOC . "/db_connect.php";
    
    if (mysqli_connect_errno())
    {
        return array(FALSE, "ERROR :: Failed to connect to MySQL: " . mysqli_connect_error());
    }
    else
    {
        $sql = "SELECT * FROM user WHERE username='$username' LIMIT 1";
        if($result = mysqli_query($con,$sql))
        {
            if(mysqli_num_rows($result) > 0){
                $sql = "SELECT * FROM user WHERE username='$username' AND password='$password' LIMIT 1";
                if($result2 = mysqli_query($con,$sql))
                {
                    if(mysqli_num_rows($result2) > 0){
                        $row = mysqli_fetch_array($result2);
                        return array(TRUE, $row);
                    } else {
                        return array(FALSE, "Incorrect password.");
                    }
                }
                else {
                    return array(FALSE, "ERROR: " . mysqli_error($con));
                }
                //$row = mysqli_fetch_array($result);
                //return array(TRUE, $row);
            } else {
                return array(FALSE, "Username doesn't exists.");
            }
        }
        else
        {
            return array(FALSE, "ERROR: " . mysqli_error($con));
        }
    }

    mysqli_close($con);
}

function query($sql){
	include HTTP_LOC . "/db_connect.php";
	
	if (mysqli_connect_errno())
	{
            return array(FALSE, "Failed to connect to MySQL: " . mysqli_connect_error());
	}
	else
	{
            if($result = mysqli_query($con,$sql))
            {
                    if(mysqli_num_rows($result) > 0){
                            $row = mysqli_fetch_array($result);
                            return array(TRUE, $row);
                    }
                    return array(FALSE, "ERROR :: NO RECORD FOUND.");
            }
            else
            {
                    return array(FALSE, "ERROR: " . mysqli_error($con));
            }
	}
	
	mysqli_close($con);
}

function result_query($sql){
	include HTTP_LOC . "/db_connect.php";
	
	if (mysqli_connect_errno())
	{
		return array(FALSE, "Failed to connect to MySQL: " . mysqli_connect_error());
	}
	else
	{
		if($result = mysqli_query($con,$sql))
		{
			if(mysqli_num_rows($result) > 0){
				return array(TRUE, $result);
			} else {
                            return array(FALSE, "NO RECORD FOUND");
                        }
		}
		else
		{
			return array(FALSE, "ERROR: " . mysqli_error($con));
		}
	}
	
	mysqli_close($con);
}

function insert_query($sql){
	include HTTP_LOC . "/db_connect.php";
	
	if (mysqli_connect_errno())
	{
		return array(FALSE, mysqli_connect_error());
	}
	else
	{
		if(mysqli_query($con,$sql))
		{
			mysqli_close($con);
			return array(TRUE, "Successful");
		}
		else{
			return array(false, mysqli_error($con));
		}
	}
	
	mysqli_close($con);
}

function insert_query_return_id($sql){
	include HTTP_LOC . "/db_connect.php";
	
	if (mysqli_connect_errno())
	{
		return array(FALSE, mysqli_connect_error(), 0);
	}
	else
	{
		if(mysqli_query($con,$sql))
		{
                        $id = mysqli_insert_id($con);
			mysqli_close($con);
			return array(TRUE, "Successful", $id);
		}
		else{
			return array(false, mysqli_error($con), 0);
		}
	}
	
	mysqli_close($con);
}

function decryptAES($encrypted, $key){
    $sEncryptedToken = $encrypted;
    $iOldKeySize = GibberishAES::size();
    GibberishAES::size(128);// Also 192, 128
    $sDecrypted = GibberishAES::dec($sEncryptedToken, $key);
    GibberishAES::size($iOldKeySize);// Restore the old key size.
    return $sDecrypted;
}

function multi_query($sql) {
	include HTTP_LOC . "/db_connect.php";
	$return = "";
	if (mysqli_connect_errno())
	{
		return array(FALSE, mysqli_connect_error());
	}
	else
	{
		if(mysqli_multi_query($con, $sql))
		{
			do {
				/* store first result set */
				if ($result = mysqli_store_result($con)) {
					while ($row = mysqli_fetch_row($result)) {
						$return .= "\n" . $row[0];
					}
					mysqli_free_result($result);
				}
				/* print divider */
				if (mysqli_more_results($con)) {
					$return .= "-----------------\n";
				}
			} while (mysqli_next_result($con));
			
			mysqli_close($con);
			return array(TRUE, "success " . $return);
		}
		else{
			return array(false, mysqli_error($con));
		}
	}
	
	mysqli_close($con);
	

}

function GetLanguageByID($LangID, $Default){
    session_start();
    //get language from database or session
    $lang = array();
    if(empty($_SESSION["LANGUAGE_PACK"])) {
        $sql = "SELECT lc.LangID, l.LangCode, l.Value, lc.Code FROM Language AS l INNER JOIN Language_Code AS lc ON lc.LangID = l.LangID";
        list($check, $result) = result_query($sql);
        if($check) {
            while($row = mysqli_fetch_array($result)){
                array_push($lang, array("LangID" => $row["LangID"], "LangCode" => $row["LangCode"], "Value" => $row["Value"], "Code" => $row["Code"]));
            }
            $_SESSION["LANGUAGE_PACK"] = $lang;
        } else {
            return $Default . " [LANG]";
            //return array(FALSE,"COULD NOT RETRIEVE DATA");
        }
    } else {
        $lang = $_SESSION["LANGUAGE_PACK"];
        //echo $arr[0]["LangID"] == mylangid; == my langcode (from user preference)..then get value and set
    }
    
    $length = count($lang);
    for($i = 0; $i < $length; $i++){
        if($lang[$i]["LangID"] == $LangID && $lang[$i]["LangCode"] == GetUserLangCode()){
            //return array(TRUE, $lang[$i]["Value"]);
            return $lang[$i]["Value"];
        }
    }
    return $Default;
    //return array(TRUE,"UNKNOWN");
}

function GetLanguageByCode($Code, $Default){
    session_start();
    //get language from database or session
    //**temp
    $_SESSION["LANGUAGE_PACK"] = "";
    $lang = array();
    if(empty($_SESSION["LANGUAGE_PACK"])) {
        $sql = "SELECT lc.LangID, l.LangCode, l.Value, lc.Code FROM Language AS l INNER JOIN Language_Code AS lc ON lc.LangID = l.LangID";
        list($check, $result) = result_query($sql);
        if($check) {
            while($row = mysqli_fetch_array($result)){
                array_push($lang, array("LangID" => $row["LangID"], "LangCode" => $row["LangCode"], "Value" => $row["Value"], "Code" => $row["Code"]));
            }
            $_SESSION["LANGUAGE_PACK"] = $lang;
        } else {
            return $Default . " [LANG]";
            //return array(FALSE,"COULD NOT RETRIEVE DATA");
        }
    } else {
        $lang = $_SESSION["LANGUAGE_PACK"];
    }
    
    $length = count($lang);
    for($i = 0; $i < $length; $i++){
        if($lang[$i]["Code"] == $Code && $lang[$i]["LangCode"] == GetUserLangCode()){
            return $lang[$i]["Value"];
        }
    }
    return $Default;
}

function GetUserLangCode(){
    session_start();
    if(empty($_SESSION["UserLangCode"])){
        return "EN";
    }
    else if(isset($_SESSION["UserLangCode"])){
        return $_SESSION["UserLangCode"];
    }
    
    return "EN";
}

function SetUserLangCode($LangCode){
    session_start();
    $_SESSION["UserLangCode"] = $LangCode;
}

function convert_number_to_words($number) {
    
    $hyphen      = '-';
    $conjunction = ' and ';
    $separator   = ', ';
    $negative    = 'negative ';
    $decimal     = ' point ';
    $dictionary  = array(
        0                   => 'zero',
        1                   => 'one',
        2                   => 'two',
        3                   => 'three',
        4                   => 'four',
        5                   => 'five',
        6                   => 'six',
        7                   => 'seven',
        8                   => 'eight',
        9                   => 'nine',
        10                  => 'ten',
        11                  => 'eleven',
        12                  => 'twelve',
        13                  => 'thirteen',
        14                  => 'fourteen',
        15                  => 'fifteen',
        16                  => 'sixteen',
        17                  => 'seventeen',
        18                  => 'eighteen',
        19                  => 'nineteen',
        20                  => 'twenty',
        30                  => 'thirty',
        40                  => 'fourty',
        50                  => 'fifty',
        60                  => 'sixty',
        70                  => 'seventy',
        80                  => 'eighty',
        90                  => 'ninety',
        100                 => 'hundred',
        1000                => 'thousand',
        1000000             => 'million',
        1000000000          => 'billion',
        1000000000000       => 'trillion',
        1000000000000000    => 'quadrillion',
        1000000000000000000 => 'quintillion'
    );
    
    if (!is_numeric($number)) {
        return false;
    }
    
    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }
    
    $string = $fraction = null;
    
    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }
    
    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
    }
    
    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }
    
    return $string;
}

function resize_image($file, $w, $h, $crop=FALSE, $image_type = "image/jpeg") {

    list($width, $height) = getimagesize($file);
	
    $r = $width / $height;
    if ($crop) {
        if ($width > $height) {
            $width = ceil($width-($width*abs($r-$w/$h)));
        } else {
            $height = ceil($height-($height*abs($r-$w/$h)));
        }
        $newwidth = $w;
        $newheight = $h;
    } else {
        if ($w/$h > $r) {
            $newwidth = $h*$r;
            $newheight = $h;
        } else {
            $newheight = $w/$r;
            $newwidth = $w;
        }
        
        if($newheight > $height && $newwidth > $width){
            $newheight = $height;
            $newwidth = $width;
        }
    }
    
    if($image_type == "image/png"){
        $src = imagecreatefrompng($file);
    }
    else if($image_type == "image/gif"){
        $src = imagecreatefromgif($file);     
    }
    else {
        $src = imagecreatefromjpeg($file);
    }
    $dst = imagecreatetruecolor($newwidth, $newheight);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
    imagedestroy($src);
    
    ob_start(); //Start output buffer.
        imagejpeg($dst, null, 85);
        $dst = ob_get_contents();//get content from buffer
    ob_end_clean(); //End the output buffer.

    return $dst;
}

function resize_image_blob($blob, $w, $h, $crop=FALSE) {
	$src = imagecreatefromstring($blob);
	$width = imagesx($src);
	$height = imagesy($src);
	
	$r = $width / $height;
    if ($crop) {
        if ($width > $height) {
            $width = ceil($width-($width*abs($r-$w/$h)));
        } else {
            $height = ceil($height-($height*abs($r-$w/$h)));
        }
        $newwidth = $w;
        $newheight = $h;
    } else {
        if ($w/$h > $r) {
            $newwidth = $h*$r;
            $newheight = $h;
        } else {
            $newheight = $w/$r;
            $newwidth = $w;
        }
    }
	
    $new = imagecreatetruecolor($newwidth, $newheight);
    imagecopyresampled($new, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
	imagedestroy($src);
	
	ob_start(); //Start output buffer.
		imagejpeg($new, null, 85);
		$new = ob_get_contents();//get content from buffer
	ob_end_clean(); //End the output buffer.
	
	return $new;
}

function genPagination($page, $pageName, $count, $pageRow = 10) {
	$pageRow = 10;
	
	$last = ceil($count/$pageRow);
	if($last < 1){
		$last = 1;
	}
	
	$pagenum = 1;
	if(isset($page)){
		$pagenum = preg_replace('#[^0-9]#', '', $page);
	}
	
	// This makes sure the page number isn't below 1, or more than our $last page
	if ($pagenum < 1) { 
		$pagenum = 1; 
	} else if ($pagenum > $last) { 
		$pagenum = $last; 
	}
	
	$paginationCtrls = '<div class="pagination-centered"><ul class="pagination">';
	
	if($last != 1){
		if ($pagenum > 1) {
			$previous = $pagenum - 1;
			$paginationCtrls .= '<li class="arrow"><a href="'.$pageName.'?page=1">&laquo;&laquo;</a></li><li class="arrow"><a href="'.$pageName.'?page='.$previous.'">Previous</a></li>';
			
			for($i = $pagenum-3; $i < $pagenum; $i++){
				if($i > 0){
					$paginationCtrls .= '<li><a href="'.$pageName.'?page='.$i.'">'.$i.'</a></li>';
				}
			}
		}
		
		$paginationCtrls .= '<li class="current"><a>'.$pagenum.'</a></li>';
		
		for($i = $pagenum+1; $i <= $last; $i++){
			$paginationCtrls .= '<li><a href="'.$pageName.'?page='.$i.'">'.$i.'</a></li>';
			if($i >= $pagenum+3){
				break;
			}
		}
		
		if ($pagenum != $last) {
			$next = $pagenum + 1;
			$paginationCtrls .= '<li class="arrow"><a href="'.$pageName.'?page='.$next.'">Next</a></li><li class="arrow"><a href="'.$pageName.'?page='.$last.'">&raquo;&raquo;</a></li>';
		}
	}
	return $paginationCtrls . '</ul></div>';
}

function genPagination2($page, $pageName, $count, $pageRow = 10) {
	//$pageRow = 10;
	
	$last = ceil($count/$pageRow);
	if($last < 1){
		$last = 1;
	}
	
	$pagenum = 1;
	if(isset($page)){
		$pagenum = preg_replace('#[^0-9]#', '', $page);
	}
	
	// This makes sure the page number isn't below 1, or more than our $last page
	if ($pagenum < 1) { 
		$pagenum = 1; 
	} else if ($pagenum > $last) { 
		$pagenum = $last; 
	}
	
	$paginationCtrls = '<div class="pagination-centered"><ul class="pagination">';
	
	if($last != 1){
		if ($pagenum > 1) {
			$previous = $pagenum - 1;
			$paginationCtrls .= '<li class="arrow"><a onclick="pagination(1);">&laquo;&laquo;</a></li><li class="arrow"><a onclick="pagination('.$previous.');">Previous</a></li>';
			
			for($i = $pagenum-3; $i < $pagenum; $i++){
				if($i > 0){
					$paginationCtrls .= '<li><a  onclick="pagination('.$i.');">'.$i.'</a></li>';
				}
			}
		}
		
		$paginationCtrls .= '<li class="current"><a>'.$pagenum.'</a></li>';
		
		for($i = $pagenum+1; $i <= $last; $i++){
			$paginationCtrls .= '<li><a  onclick="pagination('.$i.');">'.$i.'</a></li>';
			if($i >= $pagenum+3){
				break;
			}
		}
		
		if ($pagenum != $last) {
			$next = $pagenum + 1;
			$paginationCtrls .= '<li class="arrow"><a  onclick="pagination('.$next.');">Next</a></li><li class="arrow"><a  onclick="pagination('.$last.');">&raquo;&raquo;</a></li>';
		}
	}
	return $paginationCtrls . '</ul></div>';
}

function getOS() { 

    $user_agent     =   $_SERVER['HTTP_USER_AGENT'];

    $os_platform    =   "Unknown OS Platform";

    $os_array       =   array(
                            '/windows nt 6.3/i'     =>  'Windows 8.1',
                            '/windows nt 6.2/i'     =>  'Windows 8',
                            '/windows nt 6.1/i'     =>  'Windows 7',
                            '/windows nt 6.0/i'     =>  'Windows Vista',
                            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
                            '/windows nt 5.1/i'     =>  'Windows XP',
                            '/windows xp/i'         =>  'Windows XP',
                            '/windows nt 5.0/i'     =>  'Windows 2000',
                            '/windows me/i'         =>  'Windows ME',
                            '/win98/i'              =>  'Windows 98',
                            '/win95/i'              =>  'Windows 95',
                            '/win16/i'              =>  'Windows 3.11',
                            '/macintosh|mac os x/i' =>  'Mac OS X',
                            '/mac_powerpc/i'        =>  'Mac OS 9',
                            '/linux/i'              =>  'Linux',
                            '/ubuntu/i'             =>  'Ubuntu',
                            '/iphone/i'             =>  'iPhone',
                            '/ipod/i'               =>  'iPod',
                            '/ipad/i'               =>  'iPad',
                            '/android/i'            =>  'Android',
                            '/blackberry/i'         =>  'BlackBerry',
                            '/webos/i'              =>  'Mobile'
                        );

    foreach ($os_array as $regex => $value) { 

        if (preg_match($regex, $user_agent)) {
            $os_platform    =   $value;
        }

    }   

    return $os_platform;

}

function getBrowser() {

    global $user_agent;

    $browser        =   "Unknown Browser";

    $browser_array  =   array(
                            '/msie/i'       =>  'Internet Explorer',
                            '/firefox/i'    =>  'Firefox',
                            '/safari/i'     =>  'Safari',
                            '/chrome/i'     =>  'Chrome',
                            '/opera/i'      =>  'Opera',
                            '/netscape/i'   =>  'Netscape',
                            '/maxthon/i'    =>  'Maxthon',
                            '/konqueror/i'  =>  'Konqueror',
                            '/mobile/i'     =>  'Handheld Browser'
                        );

    foreach ($browser_array as $regex => $value) { 

        if (preg_match($regex, $user_agent)) {
            $browser    =   $value;
        }

    }

    return $browser;

}

?>