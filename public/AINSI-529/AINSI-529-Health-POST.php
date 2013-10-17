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
	
	$xmlPayload = '<?xml version="1.0"?>
					<Request>
						<Key>c65KclcVojZ_odK5ojoW9x-J8sfJ-.GjvxUEcjo.cg-JoW5_9d3uFePP</Key>
						<API_Action>pingPostLead</API_Action>
						<Mode>post</Mode>
						<Lead_ID>'.$leadid.'</Lead_ID>
						<Allowed_Times_Sold>2</Allowed_Times_Sold>
						<TYPE>6</TYPE>
						<Test_Lead>1</Test_Lead>
						<Data>
							<Landing_Page>landing</Landing_Page>
							<IP_Address>'.$vals['ipaddress'].'</IP_Address>
							<First_Name>'.$vals['name'].'</First_Name>
							<Last_Name>'.$vals['lastname'].'</Last_Name>
							<Address>'.$vals['address'].'</Address>
							<City>'.$vals['city'].'</City>
							<State>'.$vals['state'].'</State>
							<Home_Phone>'.$vals['homephone'].'</Home_Phone>
							<Email>'.$vals['emailaddress'].'</Email>
							<Birth_Month>'.$vals['dob_month'].'</Birth_Month>
							<Birth_Day>'.$vals['dob_day'].'</Birth_Day>
							<Birth_Year>'.$vals['dob_year'].'</Birth_Year>
							<Age>21</Age>
							<Gender>'.ucfirst( strtolower($vals['gender']) ).'</Gender>
						</Data>
					</Request>';
	$url = 'https://leads.usacoverage.com/apiXML.php';
	$result = sendCurlRequest($url, $xmlPayload);
	
	var_dump($result); 