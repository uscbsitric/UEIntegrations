<?php
	function getCurlRequest($url, $params)
	{
		$postVars = http_build_query($params);

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_URL, $url . '?' . $postVars);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
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


   $lmsData = '{"vertical": "lins",
			    "name": "Elliott",
			    "lastname": "pyles",
			    "emailaddress": "Elliottpyles@aol.com",
			    "address": "1509 5th Ave",
			    "city": "Ybor City",
			    "_City": "Ybor City",
			    "state": "FL",
			    "st": "FL",
			    "_State": "FL",
			    "zip": "33605",
			    "_PostalCode": "33605",
			    "homephone": "8132486365",
			    "ueid": "pchd_050e75a2322b6d_life_1",
			    "country_code": "1",
			    "cam": "life_1",
			    "querystring": "apd=05302e6dd8ffef5af208f4887d1ec325&mem=2",
			    "useragent": "Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; WOW64; Trident/6.0; MAGWJS)",
			    "ipaddress": "97.96.236.19",
			    "sid": "wholesaleinsurance.info",
			    "AFID": "43074",
			    "referer": "",
			    "leadtype": "lifeins",
			    "keyword": "pch",
			    "variant": "default_disclaim",
			    "sureHitsFeedId": "",
			    "dob_day": "21",
			    "dob_month": "12",
			    "dob_year": "1947",
			    "height": "5-8",
			    "weight": "190",
			    "gender": "MALE",
			    "tobacco": "no",
			    "existingconditionstoggle": "no",
			    "termlength": "20",
			    "coverageamount": "50000",
			    "homephone_area": "813",
			    "homephone_prefix": "248",
			    "homephone_suffix": "6365",
			    "cookie": "9105805d09102915f42af8064fc48892",
			    "keywords": "display|pch||apd=05302e6dd8ffef5af208f4887d1ec325&mem=2|pch|default_disclaim",
			    "vendoremail": "other",
			    "vendorpassword": "ueint",
			    "keyword_id": "2716",
			    "variant_id": "19969",
			    "site_id": "330",
			    "hid": "autoclone3",
			    "dynotrax_id": "51792b572e81cd1b62000044"
	 			 }';

  $lmsDataJsonDecoded = json_decode($lmsData, true);
  
  $testUrl = 'http://sqs-scqa.condadogroup.com/postredirect.aspx';
  $productionUrl = 'https://crm.selectquotesenior.com/postredirect.aspx';
  $response = getCurlRequest($testUrl, array('id' => 4,  // notice that there is no 'atomic' field name for the field 'id', it always comes as a suffix to, example: Campaign_Id, Status_Id etc.
  											 'Primary_FirstName' => $lmsDataJsonDecoded['name'],
  											 'Primary_LastName'  => $lmsDataJsonDecoded['lastname'],
  											 'State'	  => $lmsDataJsonDecoded['state'],
  											 'Zip'		  => $lmsDataJsonDecoded['zip'],
  											 'DOB'		  => $lmsDataJsonDecoded['dob_year'] . '-' . $lmsDataJsonDecoded['dob_day'] . '-' . $lmsDataJsonDecoded['dob_month'],
  											 'Primary_Email' => $lmsDataJsonDecoded['emailaddress'],
  											 'Phone_Number'  => $lmsDataJsonDecoded['homephone'],
  											 'CampaignId' => '455', // hardcoded for now, I cant find a suitable key and value pair in the supplied Life Insurance post string
  											 'StatusId'	  => '1' 	// hardcoded for now, I cant find a suitable key and value pair in the supplied Life Insurance post string
  											)
  							);
  var_dump( htmlentities( htmlspecialchars_decode($response) ) );
