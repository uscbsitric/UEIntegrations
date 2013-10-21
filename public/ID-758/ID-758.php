<?php
	require_once '../classes/pingpostCommon.php';

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


	$ldTestUrl = 'http://208.49.45.190/IncomingMedicareLead/leadValidator.aspx';
	$ldProductionUrl = 'http://www.medicaresolutions.com/IncomingMedicareLead/leadValidator.aspx';
	
	$response = getCurlRequest($ldTestUrl, array('temp1'   => $vals['AFID'],
												 'zip' 	   => $vals['zip'],
												 'firstname' => $vals['name'], //change this when confronted with Lead Rejected because of duplicates;
												 'lastname'  => $vals['lastname'], //change this when confronted with Lead Rejected because of duplicates;
												 'email' 	 => $vals['emailaddress'], //change this when confronted with Lead Rejected because of duplicates;
												 'phone' 	 => $vals['homephone_area'].'-'.$vals['homephone_prefix'].'-'.$vals['homephone_suffix'],//change this when confronted with Lead Rejected because of duplicates '626-201-2362',
												 'address1'  => $vals['address'],
												 'mm' 	   	 => $vals['dob_month'],
												 'dd' 	   	 => $vals['dob_day'],
												 'yyyy' 	 => $vals['dob_year'],
												 'gender'    => ( strtolower($vals['gender']) == 'male' ? 'M':'N' ),
												 'smoker'    => ( $vals['tobacco'] == 'yes' ? 'Y':'N' ),
												 'IP' 		 => $vals['ipaddress'],
												 'city' 	 => $vals['city'],
												 'state' 	 => $vals['state'],
												 'effectiveDate' => '09/05/2013',
												)
							);
	
	var_dump($response);

