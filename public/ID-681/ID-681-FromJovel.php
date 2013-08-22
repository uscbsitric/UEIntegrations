<?php

require_once '../classes/pingpostCommon.php';

if (isset($argv[1]) && !empty($argv[1])) {
    $leadid = $argv[1];
} else {
    $leadid = $_POST["leadid"];
}

if (empty($leadid)) {
    echo "Error: no lead";
    exit;
}

// Fetch the lead
$pingPost = new PingPostCommon();
$lmsData = $pingPost->fetchLead($leadid, 'lifeins_lead');
if (empty($lmsData)) {
    echo "Result=NotSold - Empty result found for lead id: " . $leadid;
    exit;
}

$postStringVals = json_decode($lmsData['poststring'], true);
$leadData = array_merge($lmsData,$postStringVals, $_POST);


if ($leadData['termlength'] == 'whole') {
    $leadData['termlength'] = 99;
}

$leadData['tobacco'] = 'None';
if (strtolower($leadData['tobacco']) == 'yes') {
    $leadData['tobacco'] = 'Cigarette';
}

$postData = array(
        'AuthorizationCode'     => 'Underg130805111332',
        'FirstName'             => $leadData['name'],
        'LastName'              => $leadData['lastname'],
        'Address1'              => $leadData['address'],
        'City'                  => $leadData['city'],
        'State'                 => $leadData['state'],
        'Zipcode'               => $leadData['zip'],
        'EmailAddress'          => $leadData['emailaddress'],
        'DOB'                   => PingPostCommon::formatBirthdate($leadData['dob_year'], $leadData['dob_month'], $leadData['dob_day'], "MM/DD/YYYY"),
        'Gender'                => ucfirst($leadData['gender'][0]),
        'HomePhone'             => $leadData['homephone'],
        'IPAddress'             => $leadData['ipaddress'],
        'TermLength'            => $leadData['termlength'],
        'CoverageAmount'        => $leadData['coverageamount'],
        'Tobacco'               => $leadData['tobacco'],
        'Height'                => convertHeightToInches($leadData['height']),
        'Weight'                => $leadData['weight'],
        'FamilyHealth'          => 'false',
        'FamilyDeath'           => 'false',
        'DUI'                   => '-1', // This could be set to unknown "-99"
        'LicenseSuspRev'        => '-1'  // This could be set to unknown "-99"
        );

try {
    // CONFIG - client settings below
    $url = 'https://www.efinancial.net/addleadservice/addleadservice.asmx';

    $client = new SoapClient($url.'?WSDL');
    $response = $client->AddLeadRetail($postData);
} catch (Exception $e) {
    throw new Exception("Submit curl request failed",0,$e);
}

print_r($response);
//echo $response . "\n\n";


function convertHeightToInches($height) {
    $height = explode('-', $height);
    $heightInches = (int)$height[0] * 12 + (int)$height[1];

    return $heightInches;
}
