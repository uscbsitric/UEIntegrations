<?php
	function getCurlRequest($url, $params)
	{
		$postVars = http_build_query($params);

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postVars);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/x-www-form-urlencoded"));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false );
		//curl_setopt($ch, CURLOPT_USERPWD, "UndergroundElephantHostPost:Tj57QnWr");
	
		$response = curl_exec($ch);
		if(curl_errno($ch))
		{
			$curlError = 'Curl error: ' . curl_error($ch);
			return $curlError;
		}
	
		curl_close($ch);
	
		return $response;
	}
	
	// health insurance post script
	$lmsData = '{"vertical":"hins",
				 "city":"San Francisco",
				 "_City":"San Francisco",
				 "state":"AR",
				 "st":"AR",
				 "_State":"AR",
				 "zip":"94133",
				 "_PostalCode":"94133",
				 "ueid":"glsr_051ec73374d383_G_EXed=anthanthem com register&t=anthem com register",
				 "country_code":"1",
				 "cam":"G_EXed=anthanthem com register&t=anthem com register",
				 "useragent":"Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; WOW64; Trident/6.0)",
				 "ipaddress":"50.136.226.91",
				 "sid":"wholesale-health-insurance.com",
				 "AFID":"43074",
				 "referer":"http://www.google.com/aclk?sa=L&ai=Ci8OUEjMVUvTAKa39yAGp6YDQAvqLkv8D-uKcplr3jtH4jwEIABABIImq2R4oAlD5ubn9AmDJ9viGyKOgGcgBAaoEH0_QztTE1X4QxDOwsENGMTgucyQTiNtjD1exPCmpWJCABZBOgAfixKEo&sig=AOD64_3a3ZQA_PgqHwtpfnhgI0-pFikCPg&ved=0CCwQ0Qw&adurl=http://wholesale-health-insurance.com/gsearch_C2C/ueid/glsr_051ec73374d383_G_EXed%3Danthanthem%2520com%2520register%26t%3Danthem%2520com%2520register&rct=j&q=anthem.com%2Fregister",
				 "leadtype":"healthins",
				 "keyword":"gsearch_C2C",
				 "variant":"freehealthinsquote",
				 "sureHitsFeedId":"",
				 "cookie":"f8daf99e00ae6d0e389ac921ae7e0fa0",
				 "keywords":"search|google|anthem.com/register||gsearch_C2C|freehealthinsquote",
				 "vendoremail":"google",
				 "vendorpassword":"ueint",
				 "keyword_id":"3346",
				 "variant_id":"26535",
				 "site_id":"414",
				 "hid":"nvt-node3",
				 "dynotrax_id":"521533174fcc48784f0000be",
				 "existingconditionstoggle":"no"
				}';
	
	$lmsDataJsonDecoded = json_decode($lmsData, true);
	
	$testUrl = 'https://nations.insidesales.com/do=noauth/add_lead/86';
	$productionUrl = 'https://nations.insidesales.com/do=noauth/add_lead/88';
	$response = getCurlRequest($testUrl, array( 'name_prefix' => '', // we do not have a mapping for this with the UE supplied Health Insurance Post Script
												'first_name' => '', // we do not have a mapping for this with the UE supplied Health Insurance Post Script
												'middle_name' => '', // we do not have a mapping for this with the UE supplied Health Insurance Post Script
												'last_name' => '', // we do not have a mapping for this with the UE supplied Health Insurance Post Script
												'birthdate' => '', // we do not have a mapping for this with the UE supplied Health Insurance Post Script
												'title' => '', // we do not have a mapping for this with the UE supplied Health Insurance Post Script
												'phone' => '', // we do not have a mapping for this with the UE supplied Health Insurance Post Script
												'mobile_phone' => '', // we do not have a mapping for this with the UE supplied Health Insurance Post Script
												'fax' => '', // we do not have a mapping for this with the UE supplied Health Insurance Post Script
												'home_phone' => '', // we do not have a mapping for this with the UE supplied Health Insurance Post Script
												'other_phone' => '', // we do not have a mapping for this with the UE supplied Health Insurance Post Script
												'email' => '', // we do not have a mapping for this with the UE supplied Health Insurance Post Script
												'email_opt_out' => '', // we do not have a mapping for this with the UE supplied Health Insurance Post Script
												'website' => $lmsDataJsonDecoded['site_id'],
												'addr1' => '', // we do not have a mapping for this with the UE supplied Health Insurance Post Script
												'addr2' => '', // we do not have a mapping for this with the UE supplied Health Insurance Post Script
												'city' => $lmsDataJsonDecoded['city'],
												'state' => $lmsDataJsonDecoded['state'],
												'state_abbrev' => $lmsDataJsonDecoded['state'],
												'zip' => $lmsDataJsonDecoded['zip'],
												'country' => $lmsDataJsonDecoded['country_code'],
												'country_abbrev' => 'US',
												'assistant_first_name' => '',  // we do not have a mapping for this with the UE supplied Health Insurance Post Script
												'assistant_last_name' => '',  // we do not have a mapping for this with the UE supplied Health Insurance Post Script
												'assistant_phone' => '',  // we do not have a mapping for this with the UE supplied Health Insurance Post Script
												'company_name' => '',  // we do not have a mapping for this with the UE supplied Health Insurance Post Script
												'industry' => '',  // we do not have a mapping for this with the UE supplied Health Insurance Post Script
												'annual_revenue' => '',  // we do not have a mapping for this with the UE supplied Health Insurance Post Script
												'ticker_symbol' => '',  // we do not have a mapping for this with the UE supplied Health Insurance Post Script
												'number_of_employees' => '',  // we do not have a mapping for this with the UE supplied Health Insurance Post Script
												'company_website' => '',  // we do not have a mapping for this with the UE supplied Health Insurance Post Script
												'account_ownership' => '',  // we do not have a mapping for this with the UE supplied Health Insurance Post Script
												'status' => '',  // we do not have a mapping for this with the UE supplied Health Insurance Post Script
												'web_offer_type' => $lmsDataJsonDecoded['leadtype'],
												'source' => $lmsDataJsonDecoded['referer'],
												'rating' => '', // we do not have a mapping for this with the UE supplied Health Insurance Post Script
												'description' => '', // we do not have a mapping for this with the UE supplied Health Insurance Post Script
												'do_not_call' => '', // we do not have a mapping for this with the UE supplied Health Insurance Post Script
												'fed_do_not_call' => '', // we do not have a mapping for this with the UE supplied Health Insurance Post Script
												'status_changed_date' => '', // we do not have a mapping for this with the UE supplied Health Insurance Post Script
												'last_purchase' => '', // we do not have a mapping for this with the UE supplied Health Insurance Post Script
												'last_inquiry' => '', // we do not have a mapping for this with the UE supplied Health Insurance Post Script
												'appointment_set' => '', // we do not have a mapping for this with the UE supplied Health Insurance Post Script
											  )
							  );
	var_dump($response);
