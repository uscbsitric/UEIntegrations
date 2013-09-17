<?php
	//require_once '../classes/pingpostCommon.php';

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

var_dump($response);
exit();
		return $response;
	}


/*
	if (isset($argv[1]) && !empty($argv[1])) {
		$leadid = $argv[1];
	} else {
		$leadid = isset($_POST['leadid']) ? $_POST['leadid'] : (isset($_GET['leadid']) ? $_GET['leadid'] : null);
	}
	
	if (empty($leadid)) {
		echo "Error: You must pass a leadid parameter";
		exit;
	}
	
	$pingPost = new PingPostCommon();
	$lmsData = $pingPost->fetchLead($leadid, 'healthins_lead');
	if (empty($lmsData)) {
		echo "Result=NotSold - Empty result found for lead id: " . $leadid;
		exit;
	}
	
	$postStringVals = json_decode($lmsData['poststring'], true);
	$vals = array_merge($lmsData,$postStringVals, $_POST);
*/
	$vals['name'] = 'sample';
	$vals['lastname'] = 'sample';
	$vals['address'] = 'kansas city';
	$vals['city'] = 'kansas city';
	$vals['state'] = 'AR';
	$vals['zip'] = '63101';
	$vals['homephone'] = '6262012360';
	$vals['gender'] = 'Male';
	$vals['dob_day'] = 15;
	$vals['dob_month'] = 10;
	$vals['dob_year'] = 1910;
	$vals['emailaddress'] = 'someone@somewhere.com';
	
	
	
	$testUrl 	   = 'https://qa.leads.intergies.com/SubmitLead';
	$productionUrl = 'https://leads.intergies.com/SubmitLead';
	$response = getCurlRequest($testUrl,  array('pid' => 1046,
											    'cid' => 10105,
											    'afid' => 220996,
											    'tzt.person.FirstName' => $vals['name'],
											    'tzt.person.LastName' => $vals['lastname'],
											    'tzt.person.Address.AddressLine1' => $vals['address'],
											    'tzt.person.Address.City' => $vals['city'],
											    'tzt.person.Address.State' => $vals['state'],
											    'tzt.person.Address.ZipCode' => $vals['zip'],
											    'tzt.person.PhoneNo' => $vals['homephone'],
											    'tzt.person.Gender' => ( strtolower($vals['gender']) == 'male' ) ? 'M':'F',
											    'tzt.person.DateOfBirth.Day' => $vals['dob_day'],
											    'tzt.person.DateOfBirth.Month' => $vals['dob_month'],
											    'tzt.person.DateOfBirth.Year' => $vals['dob_year'],
											    'tzt.person.EmailAddress' => $vals['emailaddress'],
											   )
							  );
	
	var_dump($response);