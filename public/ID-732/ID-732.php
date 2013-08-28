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
		curl_setopt($ch, CURLOPT_USERPWD, "UndergroundElephantHostPost:Tj57QnWr");
	
		$response = curl_exec($ch);
		if(curl_errno($ch))
		{
			$curlError = 'Curl error: ' . curl_error($ch);
			return $curlError;
		}
	
		curl_close($ch);
	
		return $response;
	}
	
	// Life Insurance Post Script
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
	
	$stateIDs = array(1=>'AL',
					2=>'AK',
					3=>'AZ',
					4=>'AR',
					5=>'CA',
					6=>'CO',
					7=>'CT',
					8=>'DE',
					9=>'DC',
					10=>'FL',
					11=>'GA',
					12=>'HI',
					13=>'ID',
					14=>'IL',
					15=>'IN',
					16=>'IA',
					17=>'KS',
					18=>'KY',
					19=>'LA',
					20=>'ME',
					21=>'MD',
					22=>'MA',
					23=>'MI',
					24=>'MN',
					25=>'MS',
					26=>'MO',
					27=>'MT',
					28=>'NE',
					29=>'NV',
					30=>'NH',
					31=>'NJ',
					32=>'NM',
					33=>'NY',
					34=>'NC',
					35=>'ND',
					36=>'OH',
					37=>'OK',
					38=>'OR',
					39=>'PA',
					40=>'RI',
					41=>'SC',
					42=>'SD',
					43=>'TN',
					44=>'TX',
					45=>'UT',
					46=>'VT',
					47=>'VA',
					48=>'WA',
					49=>'WV',
					50=>'WI',
					51=>'WY');
	
	$testUrl = 'http://50.57.17.72/webservices/leadpost.cfm';
	$productionUrl = 'http://www.accuquote.com/webservices/leadpost.cfm';
	$feetAndInches = explode('-', $lmsDataJsonDecoded['height']);
	
	$response = getCurlRequest($testUrl, array('FirstName' => $lmsDataJsonDecoded['name'],
											   'LastName'  => $lmsDataJsonDecoded['lastname'],
											   'StateID'   => array_search($lmsDataJsonDecoded['state'], $stateIDs),
											   'Zip'	   => $lmsDataJsonDecoded['zip'],
											   'EmailAddress' => $lmsDataJsonDecoded['emailaddress'],
											   'DateOfBirth' => $lmsDataJsonDecoded['dob_year'] . '-' . $lmsDataJsonDecoded['dob_month'] . '-' . $lmsDataJsonDecoded['dob_day'],
											   'Gender'    => ( (strtolower($lmsDataJsonDecoded['gender']) == 'male') ?1:2 ),
											   'HomePhone' => $lmsDataJsonDecoded['homephone'],
											   'WorkPhone' => $lmsDataJsonDecoded['homephone'],
											   'CellPhone' => '',
											   'IPAddress' => $lmsDataJsonDecoded['ipaddress'],
											   'Category'  => $lmsDataJsonDecoded['termlength'],
											   'TobaccoUseTypeID' => ( ( strtolower($lmsDataJsonDecoded['tobacco']) == 'no' ) ? 2:3 ),
											   'Health'	   => 478, //Standard class, we dont have a mapping for this
											   'HeightFeet'=> $feetAndInches[0],
											   'HeightInches' => $feetAndInches[1],
											   'Weight'	   => $lmsDataJsonDecoded['weight'],
											   'FaceAmountID' => 47, //$50000 its a required field so I assigned the minimum, I dont think we have a mapping for this and I am not certain to use the "coverageamount" key of the life insurance POST string.
											   'Username' => 'UndergroundElephantHostPost',
											   'Password' => 'Tj57QnWr',
											   'PartnerID' => 364 // used the LeadTypeID found in Jira, not certain on what key in the Life Insurance post string to use.
											  )
							  );
	var_dump($response);