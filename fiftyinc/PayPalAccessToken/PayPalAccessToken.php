<?

function generatePaypalAccessToken($w)
{
	$paypal_credentials=mysql($w['database'],"select * from paypal_settings where id=1");
	$paypal_credentials_result=mysql_fetch_assoc($paypal_credentials);
	if($paypal_credentials_result['mode']=="live") $url="https://api.paypal.com/v1/oauth2/token";
	else $url="https://api.sandbox.paypal.com/v1/oauth2/token";
$ch = curl_init();
 
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, "Accept: application/json, Accept-Language: en_US");
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
curl_setopt($ch, CURLOPT_SSLVERSION , 6); 
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
curl_setopt($ch, CURLOPT_USERPWD, $paypal_credentials_result['client_id'].":".$paypal_credentials_result['secret_key']);
curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
$result = curl_exec($ch);
$err = curl_error($ch);

if(empty($result)){ print_r($err); die();}
else
{
    $json = json_decode($result);
	$curdate=strtotime(date("Y-m-d H:i:s"));
 	$next=$curdate+$json->expires_in;
	mysql($w['database'],"update paypal_settings set access_token='".$json->access_token."',expires_in='".$json->expires_in."',expire_time='".date("Y-m-d H:i:s",$next)."',response='".serialize($json)."' where id=1");
    
}

curl_close($ch);
}
function generatePaypalAccessTokenTest($w)
{
	$paypal_credentials=mysql($w['database'],"select * from paypal_settings where id=2");
	$paypal_credentials_result=mysql_fetch_assoc($paypal_credentials);
	if($paypal_credentials_result['mode']=="live") $url="https://api.paypal.com/v1/oauth2/token";
	else $url="https://api.sandbox.paypal.com/v1/oauth2/token";
$ch = curl_init();
 
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, "Accept: application/json, Accept-Language: en_US");
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
curl_setopt($ch, CURLOPT_SSLVERSION , 6); 
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
curl_setopt($ch, CURLOPT_USERPWD, $paypal_credentials_result['client_id'].":".$paypal_credentials_result['secret_key']);
curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
$result = curl_exec($ch);
$err = curl_error($ch);

if(empty($result)){ print_r($err); die();}
else
{
    $json = json_decode($result);
	$curdate=strtotime(date("Y-m-d H:i:s"));
 	$next=$curdate+$json->expires_in;
	mysql($w['database'],"update paypal_settings set access_token='".$json->access_token."',expires_in='".$json->expires_in."',expire_time='".date("Y-m-d H:i:s",$next)."',response='".serialize($json)."' where id=2");
    
}

curl_close($ch);
}
function createPartnerReferrals($w)
{
	$paypal_credentials=mysql($w['database'],"select * from paypal_settings where id=1");
	$paypal_credentials_result=mysql_fetch_assoc($paypal_credentials);
	if($paypal_credentials_result['mode']=="live") $url="https://api.paypal.com/v2/customer/partner-referrals";
	else $url="https://api.sandbox.paypal.com/v2/customer/partner-referrals";
	
	$user=array("individual_owners"=>array(array("names"=>array(array("prefix"=>"Mr.",
          "given_name"=>"John",
          "surname"=>"Doe",
          "middle_name"=>"Middle",
          "suffix"=>"Jr.",
          "full_name"=>"John Middle Doe Jr.",
          "type"=>"LEGAL")),"citizenship"=>"US","addresses"=>array( array("address_line_1"=>"One Washington Square",
          "address_line_2"=>"Apt 123",
          "admin_area_2"=>"San Jose",
          "admin_area_1"=>"CA",
          "postal_code"=>"95112",
          "country_code"=>"US",
          "type"=>"HOME")),"phones"=>array(array("country_code"=>"1",
          "national_number"=>"6692468839",
          "extension_number"=>"1234",
          "type"=>"MOBILE")),"birth_details"=>array("date_of_birth"=>"1989-10-25"),"type"=>"PRIMARY")),"business_entity"=>array("business_type"=>array("type"=>"INDIVIDUAL","subtype"=>"ASSO_TYPE_INCORPORATED"),"business_industry"=>array( "category"=>"1004",
      "mcc_code"=>"2025",
      "subcategory"=>"8931"),"business_incorporation"=>array( "incorporation_country_code"=>"US",
      "incorporation_date"=>"1986-12-29"),"names"=>array(array("business_name"=>"Test Enterprise",
        "type"=>"LEGAL_NAME")),"emails"=>array( array(
        "type"=>"CUSTOMER_SERVICE",
        "email"=>"customerservice@example.com"
       )),"website"=>"https://mystore.testenterprises.com","addresses"=>array( array("address_line_1"=>"One Washington Square",
        "address_line_2"=>"Apt 123",
        "admin_area_2"=>"San Jose",
        "admin_area_1"=>"CA",
        "postal_code"=>"95112",
        "country_code"=>"US",
        "type"=>"WORK")),"phones"=>array(array( "country_code"=>"1",
        "national_number"=>"6692478833",
        "extension_number"=>"1234",
        "type"=>"CUSTOMER_SERVICE")),"beneficial_owners"=>array("individual_beneficial_owners"=>array(array("names"=>array(array("prefix"=>"Mr.",
              "given_name"=>"John",
              "surname"=>"Doe",
              "middle_name"=>"Middle",
              "suffix"=>"Jr.",
              "full_name"=>"John Middle Doe Jr.",
              "type"=>"LEGAL")),"citizenship"=>"US","addresses"=>array(array("address_line_1"=>"One Washington Square",
              "address_line_2"=>"Apt 123",
              "admin_area_2"=>"San Jose",
              "admin_area_1"=>"CA",
              "postal_code"=>"95112",
              "country_code"=>"US",
              "type"=>"HOME")),"phones"=>array(array(   "country_code"=>"1",
              "national_number"=>"6692468839",
              "extension_number"=>"1234",
              "type"=>"MOBILE")),"birth_details"=>array("date_of_birth"=>"1955-12-29"),"percentage_of_ownership"=>"50")),"business_beneficial_owners"=>array(array("business_type"=>array( "type"=>"INDIVIDUAL",
            "subtype"=>"ASSO_TYPE_INCORPORATED"),"business_industry"=>array( "category"=>"1004",
            "mcc_code"=>"2025",
            "subcategory"=>"8931"),"business_incorporation"=>array( "incorporation_country_code"=>"US",
            "incorporation_date"=>"1986-12-29"),"names"=>array(array("business_name"=>"Test Enterprise",
              "type"=>"LEGAL_NAME")),"emails"=>array(array("type"=>"CUSTOMER_SERVICE",
              "email"=>"customerservice@example.com")),"website"=>"https://mystore.testenterprises.com","addresses"=>array(array( "address_line_1"=>"One Washington Square",
              "address_line_2"=>"Apt 123",
              "admin_area_2"=>"San Jose",
              "admin_area_1"=>"CA",
              "postal_code"=>"95112",
              "country_code"=>"US",
              "type"=>"WORK")),"phones"=>array(array( "country_code"=>"1",
              "national_number"=>"6692478833",
              "extension_number"=>"1234",
              "type"=>"CUSTOMER_SERVICE")),"percentage_of_ownership"=>"50"))),"office_bearers"=>array(array("names"=>array(array("prefix"=>"Mr.",
            "given_name"=>"John",


            "surname"=>"Doe",
            "middle_name"=>"Middle",
            "suffix"=>"Jr.",
            "full_name"=>"John Middle Doe Jr.",
            "type"=>"LEGAL")),"citizenship"=>"US","addresses"=>array(array("address_line_1"=>"One Washington Square",
            "address_line_2"=>"Apt 123",
            "admin_area_2"=>"San Jose",
            "admin_area_1"=>"CA",
            "postal_code"=>"95112",
            "country_code"=>"US",
            "type"=>"HOME")),"phones"=>array(array( "country_code"=>"1",
            "national_number"=>"6692468839",
            "extension_number"=>"1234",
            "type"=>"MOBILE")),"birth_details"=>array("date_of_birth"=>"1955-12-29"),"role"=>"DIRECTOR")),"annual_sales_volume_range"=>array("minimum_amount"=>array( "currency_code"=>"USD",
        "value"=>"10000"),"maximum_amount"=>array( "currency_code"=>"USD",
        "value"=>"50000")),"average_monthly_volume_range"=>array("minimum_amount"=>array( "currency_code"=>"USD",
        "value"=>"1000"),"maximum_amount"=>array( "currency_code"=>"USD",
        "value"=>"50000")),"purpose_code"=>"P0104"),"email"=>"accountemail@example.com","preferred_language_code"=>"en-US","tracking_id"=>"testenterprices123122","partner_config_override"=>array(
    "partner_logo_url"=>"https://www.paypalobjects.com/webstatic/mktg/logo/pp_cc_mark_111x69.jpg",
    "return_url"=>"https://testenterprises.com/merchantonboarded",
    "return_url_description"=>"the url to return the merchant after the paypal onboarding process.",
    "action_renewal_url"=>"https://testenterprises.com/renew-exprired-url",
    "show_add_credit_card"=>true
  ),"operations"=>array(array( 
      "operation"=>"BANK_ADDITION"
    )),"financial_instruments"=>array("banks"=>array(array( "nick_name"=>"Bank of America",
        "account_number"=>"123405668293",
        "account_type"=>"CHECKING",
        "currency_code"=>"USD","identifiers"=>array(array( "type"=>"ROUTING_NUMBER_1",
            "value"=>"123456789"))))));
	$ch = curl_init();
 
	
	
	curl_setopt_array($ch, array(
				CURLOPT_URL => $url,
				CURLOPT_HEADER => false,
				CURLOPT_POST => true,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS => json_encode($user),
				CURLOPT_HTTPHEADER => array(
				"authorization: Bearer ".$paypal_credentials_result['access_token'],
				"content-type: application/json"
				),
				));
				
	$result = curl_exec($ch);
	$err = curl_error($ch);
	
	if(empty($result)){ print_r($err); die();}
	else
	{
		$json = json_decode($result);
		 print_r($json);
	}
	
	curl_close($ch); 
}
function makePayment($w,$users,$mode)
{
	 
	$paypal_credentials=mysql($w['database'],"select * from paypal_settings where id=$mode");
	$paypal_credentials_result=mysql_fetch_assoc($paypal_credentials);
	if($paypal_credentials_result['mode']=="live") $url="https://api.paypal.com/v1/payments/payouts";
	else $url="https://api.sandbox.paypal.com/v1/payments/payouts";
	$ch = curl_init();
 
	
	
	curl_setopt_array($ch, array(
				CURLOPT_URL => $url,
				CURLOPT_HEADER => false,
				CURLOPT_POST => true,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS => json_encode($users),
				CURLOPT_HTTPHEADER => array(
				"authorization: Bearer ".$paypal_credentials_result['access_token'],
				"content-type: application/json"
				),
				));
	$result = curl_exec($ch);
	$err = curl_error($ch);
	curl_close($ch); 
		if(empty($result)){ print_r($err); die();}
	else
	{
		echo $result;
	}
	
	
}  
 
?>