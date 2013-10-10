<?php
	require_once '../classes/pingpostCommon.php';

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

    $pingTestUrl   = 'http://preciseleads.com/inbound/push_test.asp';
    $pingStaginUrl = 'http://preciseleads.com/inbound/push.asp';

    preg_match('/([0-9]{4})/', $vals['homephone'], $last4DigitsMatches);

    $height = explode('-', $vals['height']);

    $response = executeCurlRequest($pingTestUrl, array('provider_id' => $vals['sourcedeliveryid'],
												       'aff_subref' => $vals['AFID'],
												       'lead_type' => $vals['leadtype'],
												       'lead_id' => '1234560',
												       'lead_sent' => 0,
												       'agent_distribution' => '',
												       'company_distribution' => '',
												       'agent_avoid' => 'NY5445665454~FLLL5555',
												       'company_avoid' => '1',
												       'UniversalLeadiD' => $vals['universal_leadid'],
												       'ip' => $vals['ipaddress'],
												       'email' => $vals['email'],
												       'zip' => $vals['zip'],
												       'city' => $vals['city'],
												       'state' => $vals['state'],
												       'income' => $vals['income'],
												       'self' => 0,
												       'gender' => ( strtolower($vals['driver1gender']) == 'female' ) ? 'F':'M',
												       'birth' => $vals['driver1dob_month'].'/'.$vals['driver1dob_day'].'/'.$vals['driver1dob_year'],
												       'marital' => ( strtolower($vals['driver1maritalstatus']) == 'single' ) ? 'S':'M',
												       'kids' => 0,
												       'insType' => 'NotSure',
												       'coverage' => 55000,
												       'insured' => ( isset($vals['CURRENTINSURANCECOMPANY']) ) ? 1:0,
												       'insComp' => $vals['CURRENTINSURANCECOMPANY'],
												       'education' => $vals['driver1edulevel'],
												       'height1' => $height[0],
												       'height2' => $height[1],
												       'weight' => $vals['weight'],
												       'smoke' => ($vals['tobacco'] == 'no') ? 0:1,
												       'preExCon' => 'None',
												       'cancer' => 0,
												       'drunk' => 0,
												       'ping' => 'deep',
												       'tele4' => $last4DigitsMatches[0]
												      )
								  );
    var_dump($response);