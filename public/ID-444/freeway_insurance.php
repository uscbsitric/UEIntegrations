<?php
require_once __DIR__.'/../classes/pingpostCommon.php';

if ($argv[1] != "") {
    $leadid = $argv[1];
} else {
    $leadid = $_POST["leadid"];
}

$pingPost = new PingPostCommon();
$lmsData = $pingPost->fetchLead($leadid);

$postStringVals = json_decode($lmsData['poststring'], true);
$leadData = array_merge($lmsData, $postStringVals, $_POST);

// set date
$lms_date = date("Y-m-d", strtotime($leadData['date']));

$lms_drivingexpyears = $leadData['driver2age'] - 16;
if ($lms_drivingexpyears == 0) {
        $lms_drivingexpyears = 3;
}

// Multiple Vehicles
$vehicle_xml = '';
$vehicles = PingPostCommon::getVehicles($leadData);
foreach ($vehicles as $vehicle) {
        $vehicle_xml .= '
        <vehicle>
                <lms_year>'.$vehicle['year'].'</lms_year>
                <lms_make>'.strtoupper($vehicle['make']).'</lms_make>
                <lms_model>'.strtoupper($vehicle['model']).'</lms_model>
                <lms_details>'.strtoupper($vehicle['trim']).'</lms_details>
                <lms_usagetype>'.self::$primaryUseMap[$vehicle['primaryUse']].'</lms_usagetype>
                <lms_milesoneway>'.$vehicle['commuteAvgMileage'].'</lms_milesoneway>
                <lms_mileage>'.$vehicle["annualMileage"].'</lms_mileage>
                <lms_comprehensivecoverage>'.$leadData['desiredcomprehensivedeductible'].'</lms_comprehensivecoverage>
                <lms_collisioncoverage>'.$leadData['desiredcollisiondeductible'].'</lms_collisioncoverage>
        </vehicle>';
}
// Full xml
$xml = '<?xml version="1.0" encoding="utf-8"?>
        <lead>
                <lms_idpartner>10080</lms_idpartner>
                <lms_mediacode>FWYCA-A-UE-XM-E-00275</lms_mediacode>
                <lms_gender>'.strtolower($leadData['driver1gender']).'</lms_gender>
                <lms_state>'.strtoupper($leadData['st']).'</lms_state>
                <lms_campaignid>'.$leadData['vendorid'].'</lms_campaignid>
                <lms_phone>'.$leadData['homephone'].'</lms_phone>
                <lms_email>'.$leadData['emailaddress'].'</lms_email>
                <lms_zipcode>'.$leadData['zip'].'</lms_zipcode>
                <lms_firstname>'.$leadData['firstname'].'</lms_firstname>
                <lms_lastname>'.$leadData['lastname'].'</lms_lastname>
                <lms_address>'.$leadData['address'].'</lms_address>
                <lms_aptsuite></lms_aptsuite>
                <lms_contactmecity></lms_contactmecity>
                <lms_apt></lms_apt>
                <lms_date>'.$lms_date.'</lms_date>
                <lms_ip></lms_ip>
                <lms_datepartner>'.$lms_date.'</lms_datepartner>
                <lms_maritalstatus>'.ucfirst(self::$maritalStatusMap[$leadData['driver1maritalstatus']]).'</lms_maritalstatus>
                <lms_birthdate>'.PingPostCommon::formatBirthdate($leadData['driver1dob_year'], $leadData['driver1dob_month'], $leadData['driver1dob_day'], "YYYY-MM-DD").'</lms_birthdate>
                <lms_cellphone></lms_cellphone>
                <lms_comment></lms_comment>
                <lms_ncar></lms_ncar>
                <lms_accidents></lms_accidents>
                <lms_cov>Full coverage</lms_cov>';
$xml .= $vehicle_xml;
if (!empty($leadData['driver2relationshipToApplicant'])) {
        $xml .= '
        <driver>
                <lms_relapplicant>'.self::$relationshipToApplicantMap[$leadData['driver2relationshipToApplicant']].'</lms_relapplicant>
                <lms_firstname>'.$leadData['firstname'].'</lms_firstname>
                <lms_lastname>'.$leadData['lastname'].'</lms_lastname>
                <lms_birthdate>'.PingPostCommon::formatBirthdate($leadData['driver2dob_year'], $leadData['driver2dob_month'], $leadData['driver2dob_day'], "YYYY-MM-DD").'</lms_birthdate>
                <lms_maritalstatus>'.ucfirst(self::$maritalStatusMap[$leadData['driver2maritalstatus']]).'</lms_maritalstatus>
                <lms_gender>'.strtolower($leadData['driver2gender']).'</lms_gender>
                <lms_employement>'.$leadData['driver1occupation'].'</lms_employement>
                <lms_statelicensed>'.strtoupper($leadData['st']).'</lms_statelicensed>
                <lms_drivingexpyears>'.$lms_drivingexpyears.'</lms_drivingexpyears>
                <lms_drivingexpmonths>6</lms_drivingexpmonths>
                <lms_milesoneway>'.$leadData['vehicle1commuteAvgMileage'].'</lms_milesoneway>
                <lms_mileage>'.$leadData["vehicle1annualMileage"].'</lms_mileage>
        </driver>';
}
$xml .= '</lead>';


    $tmpFname = tempnam("/tmp", "FOO");
    $handle = fopen($tmpFname, "w");
    fwrite($handle, $xml);
    fclose($handle);

    $url = 'http://www.freewaylms.com/controller_dev/ProcessXML.php';
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, array('filexml' => '@'.$tmpFname));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false );

    $result = curl_exec($ch);
    $info = curl_getinfo($ch);
    
    echo "<pre>";
    var_dump($result);

    unlink($tmpFname);
    
?>