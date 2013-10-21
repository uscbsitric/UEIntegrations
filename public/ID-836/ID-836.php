<?php

require_once '../classes/pingpostCommon.php';

function curlposting($url , $params)
{
		$postVars = http_build_query($params);

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_URL, $url.'?'.$postVars);
//		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
//		curl_setopt($ch, CURLOPT_POSTFIELDS, $postVars);
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


if (empty($_POST)) {
    echo "Result=NotSold - Empty post";
    exit;
}

$pingPost = new PingPostCommon();
$lmsData = $pingPost->fetchLead($leadid, 'autoins_lead');
if (empty($lmsData))
{
	echo "Result=NotSold - Empty result found for lead id: " . $leadid;
	exit;
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



if ($vals['state'] == 'GA' && $vals['currentlyinsured'] == 'no')
{
    echo "Filter for state of GA - Not currently Insured";
    exit;
}

$current_resident_status = strtolower($vals['current_resident_status']);

if($current_resident_status == "dormitory")
        $current_resident_status = 'other';

$coverage['STATEMINIMUM'] = "Minimum Coverage";
$coverage['BASIC'] = "15000/30000/5000";
$coverage['STANDARD'] = "50000/100000/25000";
$coverage['SUPERIOR'] = "500000/500000";

$education['HIGHSCHOOL'] = "High School Diploma";
$education['AA'] = "Associates";
$education['BA'] = "Bachelors";
$education['POST'] = "Masters";

$driver1gender = ($vals['driver1gender'] == 'female') ? 'F':'M';

$use["pleasure"] = "pleasure";
$use["commutework"] = "commuting";
$use["business"] = "business";
$use["selfemployed"] = "business";
$use["gov"] = "commuting";
$use["farm"] = "business";

$driver1marital_status = ucfirst($vals['driver1marital_status']);

$sr22 = ($vals['sr22'] == '1') ? 'suspended':'active';

if($vals['currentlyinsured'] == "0")
{
	$current_insurance_carrier = "None";
}

if($vals['SubID'] == '2094' || $vals['SubID'] == '1726')
        $SubID = '1279';

// Per Kevin: to remap the source that is cap for this month 07/13
if ($vals['SubID'] == '2768') {
    $SubID = '1462';
}

// Traffic source Filter per Consumer United on 04-02-13
if ($vals['SubID'] == '1899' || $vals['SubID'] == '2431' || $vals['SubID'] == '2918')
{
    echo "Traffic Source Filter on keyword 1899, 2431 and 2918";
    exit;
}

$data = array('ref_id' => $leadid,
			  'PublisherID' => 62,
			  'OfferID' => $vals['OfferID'],
			  'FirstName' => $vals['name'],
			  'LastName' => $vals['lastname'],
			  'Address'	=> $vals['address'],
			  'City' => $vals['city'],
			  'State' => $vals['state'],
			  'Zip' => $vals['zip'],
			  'Email' => $vals['email'],
			  'TimeStamp' => $vals['TimeStamp'],
			  'current_insurance_carrier' => $vals['current_insurance_carrier']
			 );

if($vals['currentlyinsured'] != "no")
	$data['current_insurance_expiration_date'] = $vals['current_insurance_expiration_date'];

$data['vehicle[1][year]'] = $vals['vehicle1year'];
$data['vehicle[1][make]'] = $vals['vehicle1make'];
$data['vehicle[1][model]'] = $vals['vehicle1model'];
$data['vehicle[1][submodel]'] = $vals['vehicle1trim'];
$data['vehicle[1][est_annual_mileage]'] = $vals['vehicle1annualMileage'];
$data['vehicle[1][desired_comprehensive_deductible]'] = $vals['desiredcomprehensivedeductible'];
$data['vehicle[1][desired_collision_deductible]'] = $vals['desiredcollisiondeductible'];
$data['driver[1][dob]'] = $vals['driver1dob_year'].'-'.$vals['driver1dob_month'].'-'.$vals['driver1dob_day'];
$data['driver[1][credit_self_rating]'] = 'Good';
$data['driver[1][relation_to_applicant]'] = 'Self';
$data['HomePhone'] = str_replace('-', '', $vals['homephone']);
$data['vehicle[1][commute_miles]'] = $vals['vehicle1commute_miles'];
$data['vehicle[1][main_use]'] = $use[$vals['vehicle1main_use']];
$data['current_resident_status'] = $current_resident_status;
$data['level_of_coverage_requested'] = $coverage[$vals['level_of_coverage_requested']];
$data['driver[1][highest_degree]'] = $education[$vals['driver1highest_degree']];
$data['driver[1][gender]'] = $driver1gender;
$data['driver[1][marital_status]'] = $driver1marital_status;
$data['driver[1][occupation]'] = $vals['driver1occupation'];
$data['driver[1][license_first_received_age]'] = $vals['driver1license_first_received_age'];
$data['driver[1][first_name]'] = $vals['name'];
$data['driver[1][last_name]'] = $vals['lastname'];
$data['driver[1][license_status]'] = $sr22;
$data['SubID'] = $SubID;
$data['test_post'] = 0;

if($vals['vehicle2model'] != '')
{
	$data['vehicle[2][year]'] = $vehicle2year;
	$data['vehicle[2][make]'] = $vehicle2make;
	$data['vehicle[2][model]'] = $vehicle2model;
	$data['vehicle[2][submodel]'] = $vehicle2trim;
	$data['vehicle[2][est_annual_mileage]'] = $vehicle2annualMileage;
	$data['vehicle[2][desired_comprehensive_deductible]'] = $vals['desiredcomprehensivedeductible'];
	$data['vehicle[2][desired_collision_deductible]'] = $vals['desiredcollisiondeductible'];
	$data['vehicle[2][commute_miles]'] = $vehicle2commute_miles;
	$data['vehicle[2][main_use]'] = $use[$vals['vehicle2main_use']];
}

if($vals['driver2occupation'] != '')
{
    $relation['spouse'] = "Spouse";
    $relation['child'] = "Child";
    $relation['self'] = "Other";
    $relation['sibling'] = "Other";
    $relation['parent'] = "Parent";
    $relation['grandparent'] = "Parent";
    $relation['grandchild'] = "Parent";
    $relation['other'] = "Other";

    $driver2gender = (($driver2gender == "male")) ? 'M':'F';
    $driver2marital_status = ucfirst($driver2marital_status);

    $data['driver[2][highest_degree]'] = $vals['driver2highest_degree'];
    $data['driver[2][gender]'] = $vals['$driver2gender'];
    $data['driver[2][license_status]'] = $sr22;
    $data['driver[2][marital_status]'] = $driver2marital_status;
    $data['driver[2][occupation]'] = $vals['driver2occupation'];
    $data['driver[2][license_first_received_age]'] = $vals['driver2license_first_received_age'];
    $data['driver[2][credit_self_rating]'] = 'Good';
    $data['driver[2][relation_to_applicant]'] = $relation[$vals['driver2relation_to_applicant']];
    $data['driver[2][dob]'] = $vals['dob2Year'].'-'.$vals['dob2Month'].'-'.$vals['dob2Day'];
    $data['driver[2][first_name]'] = 'Driver2';
    $data['driver[2][last_name]'] = 'Driver2';
}


$url = 'http://leads.consumerunited.com:7684/import';
$res = curlposting($url, $data);

echo $res;


?>

