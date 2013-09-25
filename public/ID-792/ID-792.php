<?php

require_once '../classes/pingpostCommon.php';

/*
 * Verify lead data
 */

if ($argv[1] != "") {
    $leadid = $argv[1];
} else {
    $leadid = isset($_POST['leadid']) ? $_POST['leadid'] : (isset($_GET['leadid']) ? $_GET['leadid'] : null);
}

if ($leadid == "") {
    echo "Error No lead";
    exit;
}

$pingPost = new PingPostCommon();
$lmsData = $pingPost->fetchLead($leadid, 'lifeins_lead');
$lmsData = array_merge($lmsData, json_decode($lmsData['poststring'], true), $_POST);

if (empty($lmsData)) {
    echo 'Error Not sold';
    exit;
}

// Per James: need real time filter for the next couple of days.
$today = date("Y-m-d 00:00:00");
if ($lmsData['insert_timestamp'] < $today) {
    echo 'Error: Old lead filter - ' . $lmsData['insert_timestamp'];
    exit;
}


/*
 * Calculate BMIs
 */

if(
    isset($lmsData['height']) &&
    isset($lmsData['weight'])
){
    $height = explode('-', $lmsData['height']);
    $heightInches = (int)$height[0] * 12 + (int)$height[1];
    $bmi = (int)$lmsData['weight'] / ($heightInches * $heightInches) * 703;
    $bmi = substr((string)$bmi, 0, 5);
}
else {
    $bmi = 'Unknown';
}


$postvars = array('FirstName' => $lmsData['name'],
				  'LastName' => $lmsData['lastname'],
		          'EmailAddress' => $lmsData['emailaddress'],
				  'Address' => $lmsData['address'],
				  'City' => $lmsData['city'],
				  'State' => $lmsData['state'],
				  'Zip' => $lmsData['zip'],
				  'Phone' => $lmsData['homephone'],
				  'Ipaddress' => $lmsData['ipaddress'],
				  'DateofBirthDay' => $lmsData['dob_day'],
				  'DateofBirthMonth' => $lmsData['dob_month'],
				  'DateofBirthYear' => $lmsData['dob_year'],
				  'dob' => $lmsData['dob_year'] . '-' . $lmsData['dob_month'] . '-' . $lmsData['dob_day'],//'1946-10-25',
				  'Height' => $lmsData['height'],
				  'Weight' => $lmsData['weight'],
				  'Gender' => strtolower($lmsData['gender']),
				  'Tobacco' => $lmsData['tobacco'],
				  'ExistingConditions' => '',
				  'TermLength' => $lmsData['termlength'],
				  'CoverageAmount' => $lmsData['coverageamount']
				 );
$postvars = http_build_query($postvars);

//echo $query_string;
/*
 * cURL post query
 */

$curlHandle = curl_init('https://secure.velocify.com/Import.aspx?Provider=UndergroundElephant&Client=EquifirstInsuranceAgency&CampaignId=36');
curl_setopt($curlHandle, CURLOPT_POST, 1);
curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $postvars);
curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curlHandle, CURLOPT_TIMEOUT, 120);
curl_setopt($curlHandle, CURLOPT_FOLLOWLOCATION, 1);
$postResult = curl_exec($curlHandle);

//print_r(curl_getinfo($curlHandle));

curl_close($curlHandle);

/*
 * Construct email
 */

$to = 'mlindsay@undergroundelephant.com';

$headers = "From: leads@undergroundelephant.com"."\r\n";

$message = 'The following person has inquired about a life insurance policy.  Please contact this person.  Do not respond to the email address from which this email was sent.
'."\n"."\n";
$message .= "First Name: ".$lmsData['name']."\n";
$message .= "Last Name: ".$lmsData['lastname']."\n";
$message .= "Address: ".$lmsData['address']."\n";
$message .= "City: ".$lmsData['city']."\n";
$message .= "State: ".$lmsData['st']."\n";
$message .= "Zip Code: ".$lmsData['zip']."\n";
$message .= "Email Address: ".$lmsData['emailaddress']."\n";
$message .= "Phone number: ".$lmsData['homephone']."\n";
$message .= "Date of Birth: ".$lmsData['dob_month']."-".$lmsData['dob_day']."-".$lmsData['dob_year']."\n";
$message .= "Height: ".$lmsData['height']."\n";
$message .= "Weight: ".$lmsData['weight']."\n";
$message .= "Tobacco Usage: ".$lmsData['tobacco']."\n";
$message .= "Term Length: ".$lmsData['termlength']."\n";
$message .= "Coverage Amount: ".$lmsData['coverageamount']."\n";
$message .= "BMI: ".$bmi."\n";

/*
 * Send email
 */

$emailResult = mail($to, 'Lead from Underground Elephant', $message, $headers);

/*
 * Test success from email and post request
 * Return "Success" on success of post request or email and "Failure" on failure
 */

if(
    $emailResult ||
    (trim($postResult) == 'Success')
){
        echo "Success\n\n";
}else{
        echo "Error\n\n";
}

echo "EMAIL SENT: ".($emailResult ? 'Yes' : 'No')."\n\n";
echo "REQUEST: ".http_build_query($lmsData)."\n\n";
echo "RESPONSE: ".$postResult."\n\n";

?>