	<script><!--
	function trim(str){
		var n = str;
		while ( n.length>0 && n.charAt(0)==' ' ) 
			n = n.substring(1,n.length);
		while( n.length>0 && n.charAt(n.length-1)==' ' )	
			n = n.substring(0,n.length-1);
		return n;
	}
	function pk_cbsn_validate_form_0() {
		var name = document.cbsn_register.name;
		var email = document.cbsn_register.from;
		var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
		var err = ''
		if ( trim(name.value) == '' )
			err += '- Name Required\n';
		if ( reg.test(email.value) == false )
			err += '- Valid Email Required\n';
		if ( err != '' ) {
			alert(err);
			return false;
		}
		return true;
	}
	//-->
	</script>
	
	 <center>
	 <table width="620" cellpadding="10" cellspacing="1" bgcolor="#ffffff" style="border:1px solid #e9e9e9; padding: 0 14px 14px;">
	  <tr><td align="center"><h3>Please Register the Plugin...</h3></td></tr>
	  <tr><td align="center">Registration is <strong>Free</strong> and only has to be done once. If you've registered before (in this blog or another) or just don't want to register, please click the "No Thank You!" button.</td></tr>    
	  <tr><td align="center">In addition, you will subscribe to our email newsletter, which will give you lots of news and tips about WordPress, ClickBank and Internet Marketing. Of course, you can unsubscribe anytime you want.</td></tr>  
	  <tr><td align="center">	
	
	<table align="center">
	<form name="cbsn_register" method="post" action="http://www.aweber.com/scripts/addlead.pl" onsubmit="return pk_cbsn_validate_form_0()">
	 <input type="hidden" name="meta_web_form_id" value="916944327" />	
	 <input type="hidden" name="listname" value="cbnotification" />
	 <input type="hidden" name="redirect" value="<?php echo get_bloginfo("siteurl"); ?>/wp-admin/options-general.php?page=clickbank-sale-notification&onlist=1" id="redirect_15809c1fdf4e643f7ac540ea587051c3" />
	 <input type="hidden" name="meta_redirect_onlist" value="<?php echo get_bloginfo("siteurl"); ?>/wp-admin/options-general.php?page=clickbank-sale-notification&onlist=1">
	 <input type="hidden" name="meta_adtracking" value="plugin_registration" />
	 <input type="hidden" name="meta_message" value="1">
	 <input type="hidden" name="meta_required" value="from,name">
	 <input type="hidden" name="meta_forward_vars" value="1">	
	 <?php global $current_user;
      get_currentuserinfo();
	  $full_name = trim($current_user->user_firstname.' '.$current_user->user_lastname);
	  ?>
	 <tr><td>Name: </td><td><input type="text" name="name" value="<?php echo $full_name; ?>" size="25" maxlength="150" /></td></tr>
	 <tr><td>Email: </td><td><input type="text" name="from" value="<?php echo $current_user->user_email; ?>" size="25" maxlength="150" /></td></tr>
	 <tr><td>&nbsp;</td><td><input class="button-primary" type="submit" name="activate" value="Register" /> </td></tr>
	 </form>
     <form name="nothankyou" method="post" action="options-general.php?page=clickbank-sale-notification&nothankyou=1">
     <tr><td>&nbsp;</td><td><input class="button" type="submit" name="nothankyou" value="No Thank You!" /></td></tr>
     </form>
	</table>
	
	</td></tr>
	  <tr><td align="center"><small>Disclaimer: Your contact information will be handled with the strictest confidence and will never be sold or shared with third parties.</small></td></td></tr>
	 </table>
	 </center>	