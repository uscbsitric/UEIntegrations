<?php
	require_once '../classes/pingpostCommon.php';

	function sendCurlRequest($url, $xmlPayload)
	{
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlPayload);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: text/xml"));
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


	
	$driverBirthdate = $vals['driver1dob_month'].'/'.$vals['driver1dob_day'].'/'.$vals['driver1dob_year'];
	$xmlPayload = '<?xml version="1.0"?>
					<Request>
						<Key>c65KclcVojZ_odK5ojoW9x-J8sfJ-.GjvxUEcjo.cg-</Key>
						<API_Action>pingPostLead</API_Action>
						<Mode>ping</Mode>
						<Allowed_Times_Sold>2</Allowed_Times_Sold>
						<TYPE>11</TYPE>
						<Test_Lead>1</Test_Lead>
						<Data>
							<SRC>test</SRC>
							<State>'.$vals['state'].'</State>
							<Zip_Code>'.$vals['zip'].'</Zip_Code>
							<Driver1_Age>'.$vals['driver1licenseage'].'</Driver1_Age>
							<Driver1_Birthdate>'.$driverBirthdate.'</Driver1_Birthdate>
							<Driver1_Gender>'.$vals['driver1gender'].'</Driver1_Gender>
							<Driver1_Filing_Required>Yes</Driver1_Filing_Required>
							<Driver1_Multiple_Policy_Discount>Yes</Driver1_Multiple_Policy_Discount>
							<Driver1_Suspended_Or_Revoked>No</Driver1_Suspended_Or_Revoked>
							<Driver1_DUI_DWI>No</Driver1_DUI_DWI>
							<Currently_Insured>'.$vals['currentlyinsured'].'</Currently_Insured>
							<Home_Owner>'.(isset($vals['yearsatresidence']) ? 'Yes':'No').'</Home_Owner>
							<Driver1_Violations>0</Driver1_Violations>
							<Multi_Car>'.( isset($vals['vehicle2make']) ? 'Yes':'No' ).'</Multi_Car>
							<Driver1_Credit_Rating>'.$vals['driver1credit'].'</Driver1_Credit_Rating>
							<Driver1_License_Status>Active</Driver1_License_Status>
							<Driver1_Licensed_State>'.$vals['state'].'</Driver1_Licensed_State>
							<Driver1_Insurance_Company>'.$vals['CURRENTINSURANCECOMPANY'].'</Driver1_Insurance_Company>
						</Data>
					</Request>';
	
	$url = 'https://www.parasolleadslogin.com/api.php';
	$result = sendCurlRequest($url, $xmlPayload);
	
	var_dump($result);