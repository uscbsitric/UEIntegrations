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
		
		$response = curl_exec($ch);
		if(curl_errno($ch))
		{
			$curlError = 'Curl error: ' . curl_error($ch);
			return $curlError;
		}
		
		curl_close($ch);


		return $response;
	}

/* 	if (isset($argv[1]) && !empty($argv[1])) {
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
	$vals = array_merge($lmsData,$postStringVals, $_POST); */

	/*
	$vals['FirstName'] = 'frederick';
	$vals['LastName'] = 'frederick';
	$vals['Address'] = 'frederick';
	$vals['City'] = 'frederick';
	$vals['ZipCode'] = 'frederick';
	$vals['name'] = 'frederick';
	$vals['name'] = 'frederick';
	$vals['name'] = 'frederick';
	$vals['name'] = 'frederick';
	$vals['name'] = 'frederick';
	$vals['name'] = 'frederick';
	$vals['name'] = 'frederick';
	$vals['name'] = 'frederick';
	*/
	
	$url = 'http://www.smartautowarranty.com/HotDirect01.asp';
	$params = array('paid' => 12, // value of 12 means this is for testing purposes only
			
				    'subpaid' => 'campaign123',
				    'formname' => 'HotTransfer',
				    'FirstName' => $vals['name'],
				    'LastName' => $vals['lastname'],
				    'Address' => $vals['address'],
				    'City' => $vals['city'],
				    'State' => $vals['state'],
				    'ZipCode' => $vals['zip'],
					'Email' => $vals['emailaddress'],
				    'WorkPhone' => $vals['homephone'],
					'HomePhone' => $vals['homephone'],
					'Make'	=> ucfirst( strtolower($vals['vehicle1make']) ),
					'Model' => $vals['vehicle1model'],
					'Year'  => $vals['vehicle1year'],
					'CurrentMileage' => $vals['vehicle1annualMileage'],
					'IPAddress' => $vals['ipaddress']
				   );
	$response = getCurlRequest($url, $params);
	
	var_dump($response);