<?php

$storeKey = config('app.CMI_STORE_KEY');
$postParams = array();
foreach ($_POST as $key => $value) {
    array_push($postParams, $key);
}

natcasesort($postParams);
$hach = "";
$hashval = "";
foreach ($postParams as $param) {
    $paramValue = html_entity_decode(preg_replace("/\n$/", "", $_POST[$param]), ENT_QUOTES, 'UTF-8');

    $hach = $hach . "(!" . $param . "!:!" . $_POST[$param] . "!)";
    $escapedParamValue = str_replace("|", "\\|", str_replace("\\", "\\\\", $paramValue));

    $lowerParam = strtolower($param);
    if ($lowerParam != "hash" && $lowerParam != "encoding") {
        $hashval = $hashval . $escapedParamValue . "|";
    }
}


$escapedStoreKey = str_replace("|", "\\|", str_replace("\\", "\\\\", $storeKey));
$hashval = $hashval . $escapedStoreKey;

$calculatedHashValue = hash('sha512', $hashval);
$actualHash = base64_encode(pack('H*', $calculatedHashValue));

$retrievedHash = $_POST["HASH"];



$to = 'hamza@gmail.com';
$subject = "Does the the value sent";

$message = 'Dear Hamza ';
$message .= "We welcome you to be part of family<br><br>";
$message .= "Regards,<br>";

// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From: <enquiry@example.com>' . "\r\n";
$headers .= 'Cc: myboss@example.com' . "\r\n";




if ($retrievedHash == $actualHash) {
    if ($_POST["ProcReturnCode"] == "00") {
        echo "ACTION=POSTAUTH";
    } else {
        echo "APPROVED";
    }
} else {
    echo "FAILURE";
}

?>
