<?php
    function executeCurlRequest($url, $post) 
    {
		$postVars = http_build_query($post);
	
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postVars);
		//curl_setopt($ch, CURLOPT_USERPWD, 'APItest:123TEST');
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/x-www-form-urlencoded")
			  	   );
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	
		$response = curl_exec($ch);
		if (curl_errno($ch)) {
		    $curlError = 'Curl error: ' . curl_error($ch);
		    return $curlError;
		}
	
		curl_close($ch);
	
		return $response;
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

    $lmsData =  '{"universal_leadid":"E9CF0E79-D62E-F38D-C360-C5A77AC7DEE8",
				  "sourcedeliveryid":"3",
				  "sid":"autoinsquote.us",
				  "AFID":"43074",
				  "homephone_area":"626",
				  "homephone_prefix":"201",
				  "homephone_suffix":"2360",
				  "name":"Rebeca",
				  "firstname":"Rebeca",
				  "lastname":"Pasillas",
				  "emailaddress":"beckypasillas@yahoo.com",
				  "email":"beckypasillas@yahoo.com",
				  "address":"16904 New Pine Drive",
				  "city":"Hacienda Heights",
				  "_City":"Hacienda Heights",
				  "state":"CA",
				  "st":"CA",
				  "_State":"CA",
				  "zip":"91745",
				  "_PostalCode":"91745",
				  "homephone":"626-201-2360",
				  "ueid":"fbso_0517af506af937_ad1_pp_6",
				  "country_code":"1",
				  "driver1edulevel":"Bachelors Degree",
				  "driver1firstname":"Rebeca",
				  "driver1lastname":"Pasillas",
				  "driver1dob_day":"07",
				  "driver1dob_month":"02",
				  "driver1dob_year":"1987",
				  "driver1gender":"Female",
				  "driver1maritalstatus":"Single",
				  "driver1occupation":"Advertising/Public Relations",
				  "driver1licenseage":"18",
				  "driver1yearsatresidence":"10",
				  "driver1sr22":"false",
				  "driver1credit":"Good",
				  "driver1yearsemployed":"4",
				  "driver1age":"26",
				  "currentpolicyexpiration":"2013-08-01",
				  "policystart":"2012-08-06",
				  "insuredsince":"2011-02-12",
				  "CURRENTINSURANCECOMPANY":"Infinity",
				  "desiredcoveragetype":"Basic Protection",
				  "desiredcollisiondeductible":"500",
				  "desiredcomprehensivedeductible":"500",
				  "propertydamage":"300",
				  "contact":"Morning",
				  "yearsatresidence":"10",
				  "bodilyinjury":"50",
				  "vehicle1year":"2010",
				  "vehicle1make":"Hyundai",
				  "vehicle1model":"ACCENT",
				  "vehicle1commuteAvgMileage":"8",
				  "vehicle1annualMileage":"25000",
				  "vehicle1primaryUse":"Commute",
				  "vehicle1leased":"Owned",
				  "vehicle1trim":"Blue",
				  "vehicle1garageType":"Full Garage",
				  "vehicle1alarm":"Alarm",
				  "vehicle1ownership":"Leased",
				  "vehicle1distance":"9",
				  "vehicle1commutedays":"4",
				  "vertical":"ains",
				  "cam":"ad1_pp_6",
				  "useragent":"Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.94 Safari/537.36",
				  "ipaddress":"252.252.211.211",
				  "referer":"https://www.facebook.com/",
				  "leadtype":"Auto",
				  "keyword":"social",
				  "variant":"gadget_copy",
				  "currentlyinsured":"1",
				  "currentresidence":"Own",
				  "driver2edulevel":"AA",
				  "cookie":"2f42075a6151eec7cb8424be36d5cf4a",
				  "keywords":"social|facebook|||social|gadget_copy",
				  "vendoremail":"facebook",
				  "vendorpassword":"ueint",
				  "vendorid":"underground",
				  "keyword_id":"2712",
				  "variant_id":"25214",
				  "site_id":"233",
				  "hid":"nvt-node12",
				  "dynotrax_id":"51ae795b91e1e77b45000005"
				  }';

    $lmsDataJsonDecoded = json_decode($lmsData, true);

    $pingTestUrl   = 'http://preciseleads.com/inbound/push_test.asp';
    $pingStaginUrl = 'http://preciseleads.com/inbound/push.asp';

    preg_match('/([0-9]{4})/', $lmsDataJsonDecoded['homephone'], $last4DigitsMatches);
    
    $possibleCoverageTypes = array('State Minimum', 'Basic Protection', ' Standard Protection', 'Superior Protection');
    $lmsDataJsonDecoded['desiredcoveragetype'] = getClosestWord($possibleCoverageTypes, $lmsDataJsonDecoded['desiredcoveragetype']);
    
    $possibleGarageTypes = array('Garage', 'Carport', 'Street', 'Driveway');
    $lmsDataJsonDecoded['vehicle1garageType'] = getClosestWord($possibleGarageTypes, $lmsDataJsonDecoded['vehicle1garageType']);
    
    $possiblePrimaryUses = array('Commute Work', 'Commute School', 'Commute Varies', 'Business', 'Pleasure');
    $lmsDataJsonDecoded['vehicle1primaryUse'] = getClosestWord($possiblePrimaryUses, $lmsDataJsonDecoded['vehicle1primaryUse']);
    
    $possibleDriverEducation = array('Some Or No High School', 'High School Diploma', 'GED', 'Some College',
    								 'Associate Degree', 'Bachelors Degree', 'Masters Degree', 'Doctorate Degree',
    								 'Other Professional Degree', 'Other Non-Professional Degree', 'Trade/Vocational School');
    $lmsDataJsonDecoded['driver1edulevel'] = getClosestWord($possibleDriverEducation, $lmsDataJsonDecoded['driver1edulevel']);

    $response = executeCurlRequest($pingTestUrl, array('provider_id' => $lmsDataJsonDecoded['sourcedeliveryid'],
												       'aff_subref' => $lmsDataJsonDecoded['AFID'],
												       'lead_type' => $lmsDataJsonDecoded['leadtype'],
												       'lead_id' => '1234560',
												       'lead_sent' => 0,
												       'agent_distribution' => '',
												       'company_distribution' => '',
												       'agent_avoid' => 'NY5445665454~FLLL5555',
												       'company_avoid' => '1',
												       'UniversalLeadiD' => $lmsDataJsonDecoded['universal_leadid'],
												       'ip' => $lmsDataJsonDecoded['ipaddress'],
											    	   'firstName' => $lmsDataJsonDecoded['firstname'],
											    	   'lastName' => $lmsDataJsonDecoded['lastname'],
												       'email' => $lmsDataJsonDecoded['email'],
    												   'phone1' => str_replace('-', '', $lmsDataJsonDecoded['homephone']),
    												   'address1' => $lmsDataJsonDecoded['address'],
												       'zip' => $lmsDataJsonDecoded['zip'],
												       'city' => $lmsDataJsonDecoded['city'],
												       'state' => $lmsDataJsonDecoded['state'],
    												   'numberOfCars' => 1,
    												   'insured' => ( isset($lmsDataJsonDecoded['CURRENTINSURANCECOMPANY']) ) ? 1:0,
    												   'insComp' => $lmsDataJsonDecoded['CURRENTINSURANCECOMPANY'],
    												   'expires' => str_replace('-', '/', $lmsDataJsonDecoded['currentpolicyexpiration']),
    												   'insLengthYr' => 1,
    												   'insLengthMn' => 0,
    												   'insTotalLengthMn' => 1,
    												   'comprehensive' => $lmsDataJsonDecoded['desiredcomprehensivedeductible'],
    												   'collision' => $lmsDataJsonDecoded['desiredcollisiondeductible'],
    												   'coverage' => $lmsDataJsonDecoded['desiredcoveragetype'],
    												   'car_1_vin' => 'JACDH58V0N',
    												   'car_1_autoYear' => $lmsDataJsonDecoded['vehicle1year'],
    												   'car_1_autoMake' => $lmsDataJsonDecoded['vehicle1make'],
    												   'car_1_autoModel' => $lmsDataJsonDecoded['vehicle1model'],
    												   'car_1_autoSubmodel' => $lmsDataJsonDecoded['vehicle1trim'],
    												   'car_1_carZip' => $lmsDataJsonDecoded['zip'],
    												   'car_1_garage' => $lmsDataJsonDecoded['vehicle1garageType'],
    												   'car_1_primaryUse' => $lmsDataJsonDecoded['vehicle1primaryUse'],
    												   'car_1_commuteDays' => $lmsDataJsonDecoded['vehicle1commutedays'],
    												   'car_1_commuteLength' => $lmsDataJsonDecoded['vehicle1commuteAvgMileage'],
    												   'car_1_mileage' => $lmsDataJsonDecoded['vehicle1annualMileage'],
    												   'car_1_leased' => ( strtolower($lmsDataJsonDecoded['vehicle1ownership']) == 'leased' ) ? 1:0,
    												   'car_1_alarm' => ( isset($lmsDataJsonDecoded['vehicle1alarm']) ) ? 1:0,
    												   'car_1_lojack' => 0,
    												   'driver_1_firstName' => $lmsDataJsonDecoded['driver1firstname'],
    												   'driver_1_lastName' => $lmsDataJsonDecoded['driver1lastname'],
    												   'driver_1_relationship' => 'Other',
    												   'driver_1_birth' => $lmsDataJsonDecoded['driver1dob_month'].'/'.$lmsDataJsonDecoded['driver1dob_day'].'/'.$lmsDataJsonDecoded['driver1dob_year'],
    												   'driver_1_gender' => ( strtolower($lmsDataJsonDecoded['driver1gender']) == 'female' ) ? 'F':'M',
    												   'driver_1_marital' => ( strtolower($lmsDataJsonDecoded['driver1gender']) == 'single' ) ? 'S':'M',
    												   'driver_1_education' => $lmsDataJsonDecoded['driver1edulevel'],
    												   'driver_1_workStatus' => ( isset($lmsDataJsonDecoded['driver1occupation']) ) ? 'Employed':'Unemployed',
    												   'driver_1_gpa' => 1,
    												   'driver_1_credit' => $lmsDataJsonDecoded['driver1credit'],
    												   'driver_1_homeOwner' => ( isset($lmsDataJsonDecoded['driver1yearsatresidence']) ) ? 1:0,
    												   'driver_1_licenseStatus' => 'Current',
    												   'driver_1_licenseState' => $lmsDataJsonDecoded['state'],
    												   'driver_1_ageLicensed' => $lmsDataJsonDecoded['driver1licenseage'],
    												   'driver_1_driverEducation' => ( isset($lmsDataJsonDecoded['driver1edulevel']) ) ? 1:0,
    												   'driver_1_matureDriver' => ( $lmsDataJsonDecoded['driver1licenseage'] > 55 ) ? 1:0,
    												   'driver_1_incident_1_date' => '01/01/2000',
    												   'driver_1_incident_1_type' => 'Ticket-Other',
    												   'driver_1_incident_1_atFault' => 0,
    												   'driver_1_incident_1_claimAmount' => 0,
    												   'driver_1_incident_1_speedType' => 'other',
    												   'driver_1_incident_1_duiType' => 'Open Container',
    												   'driver_1_incident_1_suspensionReason' => 'Driving without insurance',
    												   'driver_1_incident_1_sr22' => 0,
												      )
								  );
    var_dump($response);
    