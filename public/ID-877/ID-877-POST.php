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


	$policyExpirationDate = explode('-', $vals['currentpolicyexpiration']);
	$policyExpirationDate = $policyExpirationDate[1].'/'.$policyExpirationDate[2].'/'.$policyExpirationDate[0];


	$xmlPayload = '<?xml version="1.0"?>
					<Request>
						<Key>c65KclcVojZ_odK5ojoW9x-J8sfJ-.GjvxUEcjo.cg-</Key>
						<API_Action>pingPostLead</API_Action>
						<Mode>post</Mode>
						<Allowed_Times_Sold>2</Allowed_Times_Sold>
						<Lead_ID>'.$leadid.'</Lead_ID>
						<TYPE>11</TYPE>
						<Test_Lead>1</Test_Lead>
						<Data>
							<Landing_Page>autoins</Landing_Page>
							<IP_Address>'.$vals['ipaddress'].'</IP_Address>
							<Pub_ID>'.$leadid.'</Pub_ID>
							<First_Name>'.$vals['name'].'</First_Name>
							<Last_Name>'.$vals['lastname'].'</Last_Name>
							<Email>'.$vals['emailaddress'].'</Email>
							<Home_Phone>'.$vals['homephone'].'</Home_Phone>
							<Work_Phone>'.$vals['homephone'].'</Work_Phone>
							<Street_Address>'.$valsp['address'].'Street_Address>
							<City>'.$vals['city'].'</City>
							<Driver1_Policy_Expiration_Date>'.$policyExpirationDate.'</Driver1_Policy_Expiration_Date>
							<Driver1_Marital_Status>'.$vals['driver1maritalstatus'].'</Driver1_Marital_Status>
							<Driver1_Coverage_Type>'.$vals['desiredcoveragetype'].'</Driver1_Coverage_Type>
							<Driver1_Current_Coverage_Level>'.$vals['desiredcoveragetype'].'</Driver1_Current_Coverage_Level>
							<Driver1_Average_One_Way_Mileage>'.$vals['vehicle1commuteAvgMileage'].'</Driver1_Average_One_Way_Mileage>
							<Driver1_Education>'.$vals['driver1edulevel'].'</Driver1_Education>
							<Driver1_Occupation>'.$vals['driver1occupation'].'</Driver1_Occupation>
							<Veh1_Primary_Driver>'.$vals['name'].' '.$vals['lastname'].'</Veh1_Primary_Driver>
							<Veh1_Year>'.$vals['vehicle1year'].'</Veh1_Year>
							<Veh1_Make>'.$vals['vehicle1make'].'</Veh1_Make>
							<Veh1_Model>'.$vals['vehicle1model'].'</Veh1_Model>
							<Veh1_Primary_Use>'.$vals['vehicle1primaryUse'].'</Veh1_Primary_Use>
							<Veh1_Annual_Mileage>'.$vals['vehicle1annualMileage'].'</Veh1_Annual_Mileage>
							<Veh1_Ownership>'.$vals['vehicle1ownership'].'</Veh1_Ownership>
							<Veh1_Car_Coverage>'.$vals['desiredcoveragetype'].'</Veh1_Car_Coverage>
							<Veh1_Desired_Collision_Coverage_Ded>'.$vals['desiredcollisiondeductible'].'</Veh1_Desired_Collision_Coverage_Ded>
							<Veh1_Desired_Comprehensive_Coverage_Ded>'.$vals['desiredcomprehensivedeductible'].'</Veh1_Desired_Comprehensive_Coverage_Ded>
							<Veh1_Trim>'.$vals['vehicle1trim'].'</Veh1_Trim>
							<Driver1_Age_When_First_Licensed>'.$vals['driver1licenseage'].'</Driver1_Age_When_First_Licensed>
							<Medical_Payments>0</Medical_Payments>
							<Driver1_Current_Residence>'.$vals['address'].'</Driver1_Current_Residence>
							<Driver1_Salvaged_Vehicle>No</Driver1_Salvaged_Vehicle>
						</Data>
					</Request>';
	

	$url = 'https://www.parasolleadslogin.com/api.php';
	$result = sendCurlRequest($url, $xmlPayload);
	
	var_dump($result);