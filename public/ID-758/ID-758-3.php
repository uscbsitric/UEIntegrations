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
				 "zip":"94132",
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
	$testUrl = 'http://208.49.45.190/IncomingMedicareLead/medicareleadservice.asmx';


	// Ping
	$response = getCurlRequest($testUrl, array('op' => 'Ping',
											   'AffiliateId' => $lmsDataJsonDecoded['AFID'],
											   'IncomingId' => $lmsDataJsonDecoded['ueid'],
											   'FirstName' => 'dfrederick',
											   'Address1' => '1509 5th Ave',
											   'City' => $lmsDataJsonDecoded['city'],
											   'State' => $lmsDataJsonDecoded['state'],
											   'Zip' => $lmsDataJsonDecoded['zip'],
											   'Birthday' => '1964-08-08',
											   'Gender' => 'Male',
											   'IsSmoker' => false,
											   'EffectiveDate' => '2013-09-03',
											   'IpAddress' => $lmsDataJsonDecoded['ipaddress']
											  )
							  );

	var_dump($response);
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	