<?php
	require_once '../classes/pingpostCommon.php';
	
	function sendCurlRequest($url, $params, $marketID)
	{
		$params = http_build_query($params);
		$params = 'MID'.$marketID . '&leads0=' . $params;
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/x-www-form-urlencoded"));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false );
		//curl_setopt($ch, CURLOPT_USERPWD, "UndergroundElephantHostPost:Tj57QnWr");
	
		$result = curl_exec($ch);
		$info   = curl_getinfo($ch);
	
		return $result;
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
	
	$url = 'https://diana.matrixdirect.com/AutoLeads/autoleadsctrl';
	$optInDate = new DateTime();
	$marketId = '101';

	$params = array('optIn' => 'Y',
					'optInDate'=>$optInDate->format('Y-m-d H:i:s'),
					'Client_First_Name'=>$vals['name'],
					'Client_Last_Name'=>$vals['lastname'],
					'Client_Sex'=>($vals['gender'] == 'MALE') ? 'M':'F',
					'Client_DOB'=>$vals['dob_month'].'/'.$vals['dob_day'].'/'.$vals['dob_year'],
					'Client_State'=>$vals['state'],
					'Client_Phone1'=>$vals['homephone'],
					'Client_DNIS'=>'1234',
				   );
	
	$result = sendCurlRequest($url, $params, $marketId);
	var_dump($result);