<?php

// Life Insurance

	function getCurlRequest($url, $params)
	{
		$postVars = http_build_query($params);
		
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postVars);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/x-www-form-urlencoded"));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		
		$response = curl_exec($ch);
		
		if( curl_errno($ch) )
		{
			$curlError = 'Curl error: ' . curl_error($ch);

			return $curlError;
		}
		
		curl_close($ch);
		
		return $response;
	}
	
	function convertHeightToInches( $heightString )
	{
		$pieces = explode('-', $heightString);
		$height = $pieces[0] * 12;
		$height += $pieces[1];
		
		return $height;
	}
	
	function getClosestWord($words = array(), $input)
	{
		// no shortest distance found, yet
		$shortest = -1;
	
		// loop through words to find the closest
		foreach ($words as $word)
		{
			// calculate the distance between the input word,
			// and the current word
			$lev = levenshtein($input, $word);
	
			// check for an exact match
			if ($lev == 0)
			{
				// closest word is this one (exact match)
				$closest = $word;
				$shortest = 0;
	
				// break out of the loop; we've found an exact match
				break;
			}
	
			// if this distance is less than the next found shortest
			// distance, OR if a next shortest word has not yet been found
			if ($lev <= $shortest || $shortest < 0)
			{
				// set the closest match, and shortest distance
				$closest  = $word;
				$shortest = $lev;
			}
		}
	
		return ($shortest >= 0) ? ($closest) : (-1);
	}

	// Life Insurance Post script
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
	
	$height = convertHeightToInches($lmsDataJsonDecoded['height']);
	
	$possibleTobaccoValues = array("None", "Cigarette", "Cigar", "Pipe", "Chewing Tobacco", "Nicotine Patch", "Gum");
	$tobacco = ( strtolower($lmsDataJsonDecoded['tobacco']) == 'no') ? 'None' : getClosestWord($possibleTobaccoValues, $lmsDataJsonDecoded['tobacco']);
	
	

	//$authorizationCode = 'TestTest12345';
	$authorizationCode = 'Underg130805111332';
	$url = 'https://www.efinancial.net/addleadservice/addleadservice.asmx';
	$response = getCurlRequest($url, array('AuthorizationCode' => $authorizationCode,
										   //'Campaign'		   => '',
										   'FirstName' 		   => $lmsDataJsonDecoded['name'],
										   'LastName' 		   => $lmsDataJsonDecoded['lastname'],
										   'Address1' 		   => $lmsDataJsonDecoded['address'],
										   'City' 			   => $lmsDataJsonDecoded['city'],
										   'State' 			   => $lmsDataJsonDecoded['state'],
										   'Zipcode' 		   => $lmsDataJsonDecoded['zip'],
										   'EmailAddress' 	   => $lmsDataJsonDecoded['emailaddress'],
										   'DOB' 			   => $lmsDataJsonDecoded['dob_month'] . '/' . $lmsDataJsonDecoded['dob_day'] . '/' . $lmsDataJsonDecoded['dob_year'],
										   'Gender' 		   => ( (strtolower($lmsDataJsonDecoded['gender']) == 'male') ? 'M':'F' ),
										   'HomePhone' 		   => $lmsDataJsonDecoded['homephone'],
										   'IPAddress' 		   => $lmsDataJsonDecoded['ipaddress'],
										   'TermLength' 	   => $lmsDataJsonDecoded['termlength'],
										   'CoverageAmount'    => $lmsDataJsonDecoded['coverageamount'],
										   'Tobacco' 		   => $tobacco,
										   'Height' 		   => $height,
										   'Weight' 		   => $lmsDataJsonDecoded['weight'],
										   'FamilyHealth' 	   => false,
										   'FamilyDeath' 	   => false,
										   'DUI' 			   => -99,
										   'LicenseSuspRev'    => -99
										  )
							  );
	
	var_dump($response);