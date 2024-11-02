<?php
/**
 * @package ClickBank Sale Notification
 * @version 0.120106
 */
include('wp-load.php');

/**
 * verify transaction, return 0 or 1
**/
function pk_cbsn_ipnVerification() {
	// base on http://www.ClickBank.com/help/affiliate-help/affiliate-tools/instant-notification-service/#CODE
    $secretKey = get_option('cbsn_secret_key');
    $pop = "";
    $ipnFields = array();
    foreach ($_POST as $key => $value) {
        if ($key == "cverify") {
            continue;
        }
        $ipnFields[] = $key;
    }
    sort($ipnFields);
    foreach ($ipnFields as $field) {
                // if Magic Quotes are enabled $_POST[$field] will need to be
        // un-escaped before being appended to $pop
        $pop = $pop . $_POST[$field] . "|";
    }
    $pop = $pop . $secretKey;
    $calcedVerify = sha1(mb_convert_encoding($pop, "UTF-8"));
    $calcedVerify = strtoupper(substr($calcedVerify,0,8));
    return $calcedVerify == $_POST["cverify"];
}
/**
 * send sale notification email
**/
	
	$headers = "From: ".get_bloginfo("admin_email")."\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	
	$to = get_option('cbsn_email');
	$messages= '<html><body>';	
	
	if(pk_cbsn_ipnVerification()||'TEST'==$_POST['ctransaction'])
	{
		$subject = "[".ucfirst(strtolower($_POST['ctransaction']))." Notification] $" . $_POST['caccountamount']/100 . " for Item #" . $_POST['cproditem'] . " - " . $_POST['cprodtitle'];
		
		$messages = "<p>You received a payment from '". $_POST['ccustfullname']; 
		$messages .= "' for '". $_POST['cprodtitle']; 
		$messages .= "' through the ClickBank marketplace."."</p>";
		
	}
	 else
	{
		$subject = "[Sale Notification] Verification Failed";
		
		$messages = "<p>The verification of ClickBank transaction was failed. ";
		$messages .= "Please make sure that you use the correct ClickBank Secret Key in the plugin settings.</p>";
		$messages .= "<p>If you don't have one, follow this <a href=\"http://www.youtube.com/watch?v=8Mxeq1P3Ieo\">video guide</a> to create one. ";
		$messages .= "Remember, the secret key can be up to 16 letters or digits, and must be in ALL CAPS.</p>";
	}
	
	$messages.= '<table rules="all" style="border-color: #666;" cellpadding="5">';
	
	$messages.= "<tr style='background: #f6f6f6;'><td><strong>ClickBank Receipt:</strong> </td>
		<td>" . strip_tags($_POST['ctransreceipt']) . "</td></tr>";
	$messages.= "<tr><td><strong>Transaction Types:</strong> </td>
		<td>" . strip_tags($_POST['ctransaction']) . "</td></tr>";			
	$messages.= "<tr><td><strong>Item #:</strong> </td>
		<td>" . strip_tags($_POST['cproditem']) . "</td></tr>";
	$messages.= "<tr><td><strong>Amount:</strong> </td>
		<td>$" . strip_tags($_POST['caccountamount']/100) . "</td></tr>";				
	$messages.= "<tr><td><strong>Product Title:</strong> </td>
		<td>" . strip_tags($_POST['cprodtitle']) . "</td></tr>";			
	$messages.= "<tr><td><strong>Product Type:</strong> </td>
		<td>" . strip_tags($_POST['cprodtype']) . "</td></tr>";				
	$messages.= "<tr><td><strong>Vendor:</strong> </td>
		<td>" . strip_tags($_POST['ctranspublisher']) . "</td></tr>";						
	$messages.= "<tr><td><strong>Affiliate:</strong> </td>
		<td>" . strip_tags($_POST['ctransaffiliate']) . "</td></tr>";	
	$messages.= "<tr><td><strong>Payment Method:</strong> </td>
		<td>" . strip_tags($_POST['ctranspaymentmethod']) . "</td></tr>";	
	$messages.= "<tr><td><strong>TID:</strong> </td>
		<td>" . strip_tags($_POST['ctid']) . "</td></tr>";	
	$messages.= "<tr><td><strong>Customer Name:</strong> </td>
		<td>" . strip_tags($_POST['ccustfullname']) . "</td></tr>";				
	$messages.= "<tr><td><strong>Customer Email:</strong> </td>
		<td>" . strip_tags($_POST['ccustemail']) . "</td></tr>";		
		
	$messages.= '<tr><td colspan="2"><small>Powered by <a href="http://exclusivewp.com/clickbank-sale-notification">Clicbank Sale Notification plugin</a></small>.</td></tr>';
	$messages.= "</table>";
	$messages.= "<p>&nbsp;</p></body></html>";				
	
	wp_mail( $to, $subject, $messages, $headers ); 
?>