<html>

<head>

<title>3D PAY HOSTING</title>

<meta http-equiv="Content-Language" content="tr">


<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-9">


<meta http-equiv="Pragma" content="no-cache">


<meta http-equiv="Expires" content="now">


</head>


<body>

	<?php
	// https://sherin-old.herokuapp.com/index
		$orgClientId  =   config('app.CMI_CLIENT_ID');
  		$orgAmount = "577.25";
  		$orgOkUrl =  "https://sherin-old.herokuapp.com/Ok-Fail";
  		$orgFailUrl = "https://sherin-old.herokuapp.com/Ok-Fail";
  		$shopurl = "https://sherin-old.herokuapp.com";
  		$orgTransactionType = "PreAuth";
  		$orgRnd =  microtime();
  		$orgCallbackUrl = "https://sherin-old.herokuapp.com/callback";
  		$orgCurrency = "504";
		
	?>


	<center>

		<form method="post" action="https://sherin-old.herokuapp.com/process">
			<table>
				<tr>

					<td align="center" colspan="2"><input type="submit"
						value="Complete Payment" /></td>
				</tr>

			</table>

				<input type="hidden" name="clientid" value="<?php echo $orgClientId ?>"> 
				<input type="hidden" name="amount" value="<?php echo $orgAmount ?>">
				<input type="hidden" name="okUrl" value="<?php echo $orgOkUrl ?>">
				<input type="hidden" name="failUrl" value="<?php echo $orgFailUrl ?>">
				<input type="hidden" name="TranType" value="<?php echo $orgTransactionType ?>">
				<input type="hidden" name="callbackUrl" value="<?php echo $orgCallbackUrl ?>">
				<input type="hidden" name="shopurl" value="<?php echo $shopurl ?>">
				<input type="hidden" name="currency" value="<?php echo $orgCurrency ?>">
				<input type="hidden" name="rnd" value="<?php echo $orgRnd ?>">
				<input type="hidden" name="storetype" value="3D_PAY_HOSTING">
				<input type="hidden" name="hashAlgorithm" value="ver3">
				<input type="hidden" name="lang" value="fr">
				<input type="hidden" name="refreshtime" value="5">
				<input type="hidden" name="BillToName" value="name">
				<input type="hidden" name="BillToCompany" value="billToCompany">
				<input type="hidden" name="BillToStreet1" value="100 rue adress">
				<input type="hidden" name="BillToCity" value="casablanca">
				<input type="hidden" name="BillToStateProv" value="Maarif Casablanca">
				<input type="hidden" name="BillToPostalCode" value="20230">
				<input type="hidden" name="BillToCountry" value="504">
				<input type="hidden" name="email" value="hamzaaitsidisaid.11@gmail.com">
				<input type="hidden" name="tel" value="0021201020304">
				<input type="hidden" name="encoding" value="UTF-8">
				<input type="hidden" name="oid" value="<?php echo uniqid() ?>"> <!-- La valeur du paramètre oid doit être unique par transaction -->
				
		</form>

	</center>

</body>

</html>
