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
	
	$height = explode('-', $vals['height']);
	
	$xmlPayload = '<?xml version="1.0"?>
					<Request>
						<Key>c65KclcVojZ_odK5ojoW9x-J8sfJ-.GjvxUEcjo.cg-JoW5_9d3uFePP</Key>
						<API_Action>pingPostLead</API_Action>
						<Mode>post</Mode>
						<Allowed_Times_Sold>2</Allowed_Times_Sold>
						<Lead_ID>'.$leadid.'</Lead_ID>
						<TYPE>14</TYPE>
						<Test_Lead>1</Test_Lead>
						<Data>
							<IP_Address>'.$vals['ipaddress'].'</IP_Address>
							<SRC>test</SRC>
							<Landing_Page>landing</Landing_Page>
							<First_Name>'.$vals['name'].'</First_Name>
							<Last_Name>'.$vals['lastname'].'</Last_Name>
							<State>'.$vals['state'].'</State>
							<Phone>'.$vals['homephone'].'</Phone>
							<Email>'.$vals['email'].'</Email>
							<Birth_Month>'.$vals['dob_month'].'</Birth_Month>
							<Birth_Day>'.$vals['dob_day'].'</Birth_Day>
							<Birth_Year>'.$vals['dob_year'].'</Birth_Year>
							<Age>21</Age>
							<Height_Feet>'.$height[0].'</Height_Feet>
							<Height_Inches>'.$height[1].'</Height_Inches>
							<Sex>'.ucfirst( strtolower($vals['gender']) ).'</Sex>
							<Face_Amount>1.00</Face_Amount>
						</Data>
					</Request>';
	$url = 'https://leads.usacoverage.com/apiXML.php';
	$result = sendCurlRequest($url, $xmlPayload);
	
	var_dump($result); 