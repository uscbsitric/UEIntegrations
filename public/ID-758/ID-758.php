<?php
	function getCurlRequest($url, $params)
	{
		$postVars = http_build_query($params);

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_URL, $url . '?' . $postVars);
		//curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		//curl_setopt($ch, CURLOPT_POSTFIELDS, $postVars);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/x-www-form-urlencoded"));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false );
	
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
				 "state":"AZ",
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
				 "AFID":"53075",
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
	
	//website linking instructions
	//$testUrl = 'http://208.49.45.190/planlistmedicare.aspx';	// not functioning, it returns the markup of a webpage
	//$productionUrl = 'http://www.medicaresolutions.com/planlistmedicare.aspx';  // not functioning, it returns the markup of a webpage
	
	//lead delivery
	$ldTestUrl = 'http://208.49.45.190/IncomingMedicareLead/leadValidator.aspx';
	$ldProductionUrl = 'http://www.medicaresolutions.com/IncomingMedicareLead/leadValidator.aspx';
	
	$response = getCurlRequest($ldTestUrl, array('temp1'   => $lmsDataJsonDecoded['AFID'],
											   'zip' 	   => $lmsDataJsonDecoded['zip'],
											   'firstname' => 'Aac', //change this when confronted with Lead Rejected because of duplicates;
											   'lastname'  => 'Aac', //change this when confronted with Lead Rejected because of duplicates;
											   'email' 	   => 'aac@gmail.com', //change this when confronted with Lead Rejected because of duplicates;
											   'phone' 	   => '626-201-2362',
											   'address1'  => '1509 5th Ave',
											   'mm' 	   => '08',
											   'dd' 	   => '04',
											   'yyyy' 	   => '1944',
											   'gender'    => 'M',
											   'smoker'    => 'N',
											   'IP' => $lmsDataJsonDecoded['ipaddress'],
											   'city' => $lmsDataJsonDecoded['city'],
											   'state' => $lmsDataJsonDecoded['state'],
											   'effectiveDate' => '09/05/2013',
											  )
							  );
	
	var_dump($response);
	
	
	
	
	
	
	