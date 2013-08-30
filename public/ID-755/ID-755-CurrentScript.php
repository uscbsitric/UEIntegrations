<?php
require_once '../classes/pingpostCommon.php';

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
//print_r($vals);
extract($vals);

if($deniedcoverage == 'no')
        $deniedcoverage = 'N';
else $deniedcoverage = 'Y';

if((int)$dob_year > 1949)
{
        echo "ERROR: age filter";
        exit;
}
$ping_data = 'pid=1046&cid=10105&afid=220996&tzt.person.FirstName='.$name.'&tzt.person.LastName='.$lastname.'&tzt.person.Address.AddressLine1='.$address.'&tzt.person.Address.City='.$city.'&tzt.person.Address.State='.$st.'&tzt.person.PhoneNo='.$homephone.'&tzt.person.Address.ZipCode='.$zip.'&Gender=M&tzt.person.DateOfBirth.Day='.$dob_day.'&tzt.person.DateOfBirth.Month='.$dob_month.'&tzt.person.DateOfBirth.Year='.$dob_year.'&tzt.person.EmailAddress='.$emailaddress.'&tzt.personData.DeniedHealthCoverage='.$deniedcoverage;

echo $ping_data;

//$url = 'https://qa.leads.intergies.com/SubmitLead'; //Test url
$url = 'https://leads.intergies.com/SubmitLead';

$ch = curl_init();
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch,CURLOPT_POSTFIELDS, $ping_data);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/x-www-form-urlencoded"));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false );
$response = curl_exec($ch);
curl_close($ch);

if (stripos($response, '<Success>true</Success>') !== false) {
    echo "\n\n" . "Success";
} else {
    echo "\n\n" . "Error";
}

echo "\n---response---\n";
echo $response;

?>
