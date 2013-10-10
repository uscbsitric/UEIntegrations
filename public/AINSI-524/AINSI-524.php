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
	
	$url = "https://crm.sqah.com/PostRedirect.aspx?id=4";
	
	$postData = array('CampaignId'=>'443',
					  'StatusId'=>'40',
					  'Lead_Pub_ID'=>'1234',
					  'Lead_IP_Address'=>$vals['ipaddress'],
					  'Primary_FirstName'=>$vals['name'],
					  'Primary_LastName'=>$vals['lastname'],
					  'Primary_Gender'=>$vals['gender'],
					  'Primary_DayPhone'=>$vals['homephone'],
					  'Primary_EveningPhone'=>$vals['homephone'],
					  'Primary_MobilePhone'=>'',
					  'Primary_Email'=>$vals['emailaddress'],
					  'Primary_Fax'=>'',
					  'Primary_Address1'=>$vals['address'],
					  'Primary_Address2'=>$vals['address'],
					  'Primary_City'=>$vals['city'],
					  'PrimaryState'=>$vals['state'],
					  'Primary_Zip'=>$vals['zip'],
					  'Primary_BirthDate'=>$vals['dob_month'].'-'.$vals['dob_day'].'-'.$vals['dob_year'],
					  'Primary_Tobacco'=>$vals['tobacco'],
					  'Primary_Notes'=>'',
					  'Driver1_DlState'=>$vals['state'],
					  'Driver1_MaritalStatus'=>$vals['driver1maritalstatus'],
					  'Driver1_LicenseStatus'=>'',
					  'Driver1_AgeLicensed'=>$vals['driver1licenseage'],
					  'Driver1_YearsAtResidence'=>$vals['driver1yearsatresidence'],
					  'Driver1_Occupation'=>$vals['driver1occupation'],
					  'Driver1_YearsWithCompany'=>$vals['driver1yearsemployed'],
					  'Driver1_YrsInField'=>$vals['driver1yearsemployed'],
					  'Driver1_Education'=>$vals['driver1edulevel'],
					  'Driver1_NmbrIncidents'=>'0',
					  'Driver1_Sr22'=>$vals['driver1sr22'],
					  'Driver1_PolicyYears'=>(int)($vals['currentpolicyexpiration'] - $vals['policystart']),
					  'Driver1_LicenseNumber'=>'',
					  'Driver1_CurrentCarrier'=>$vals['CURRENTINSURANCECOMPANY'],
					  'Driver1_LiabilityLimit'=>'',
					  'Driver1_CurrentAutoXDate'=>'',
					  'Driver1_MedicalPayment'=>'',
					  'Driver1_TicketsAccidentsClaims'=>'',
					  'Driver1_IncidentType'=>'',
					  'Driver1_IncidentDescription'=>'',
					  'Driver1_IncidentDate'=>'',
					  'Driver1_ClaimPaidAmount'=>''
					 );
	
	$result = executeCurlRequest($url, $postData);
	var_dump($result);