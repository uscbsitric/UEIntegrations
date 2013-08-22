<?php

require_once '../classes/pingpostCommon.php';

function calculateBMI($height, $weight)
{
    $height = explode('-', $height);
        $heightInches = (int)$height[0] * 12 + (int)$height[1];
        $BMI = (int)$weight / ($heightInches * $heightInches) * 703;
        return $BMI;
}

$cov['whole']='whole';
$cov['10']='term';
$cov['15']='term';
$cov['20']='term';
$cov['25']='term';
$cov['30']='term';

$covAmount['25000'] = '25k';
$covAmount['50000'] = '50k';
$covAmount['100000'] = '100k';
$covAmount['150000'] = '150k';
$covAmount['200000'] = '200k';
$covAmount['250000'] = '250k';
$covAmount['300000'] = '300k';
$covAmount['350000'] = '350k';
$covAmount['400000'] = '400k';
$covAmount['450000'] = '400k';
$covAmount['500000'] = '500k';
$covAmount['550000'] = '500k';
$covAmount['600000'] = '600k';
$covAmount['650000'] = '600k';
$covAmount['700000'] = '700k';
$covAmount['750000'] = '700k';
$covAmount['800000'] = '800k';
$covAmount['850000'] = '800k';
$covAmount['900000'] = '900k';
$covAmount['950000'] = '1mil';
$covAmount['1000000'] = '1mil';
$covAmount['1500000'] = '2mil';
$covAmount['2000000'] = '2mil';

if ($argv[1] != "")
        $leadid = $argv[1];
else
        $leadid = $_POST["leadid"];

if ($leadid == "")
        die("no lead");


$pingPost = new PingPostCommon();
$lmsData = $pingPost->fetchLead($leadid, 'lifeins_lead');
if (empty($lmsData)) {
    echo "Result=NotSold - Empty result found for lead id: " . $leadid;
    exit;
}

$postStringVals = json_decode($lmsData['poststring'], true);
$vals = array_merge($lmsData,$postStringVals, $_POST);
extract($vals);

$BMI = calculateBMI($height, $weight);

$height = explode('-', $height);
$age = date('Y') - (int)$dob_year;

$a = array();
$a["fname"] = $name;
$a["lname"] = $lastname;
$a["h_address"] = $address;
$a["h_city"] = $city;
$a["h_state"] = $st;
$a["h_zip"] = $zip;
$a["email"] = $emailaddress;
$a["gender"] = $gender;
$a["h_areacode"] = substr($homephone, 0, 3);
$a["h_prefix"] = substr($homephone, 3, 3);
$a["h_suffix"] = substr($homephone, 6, 4);
$a["ip_address"] = $ipaddress;
$a["birth_month"] = $dob_month;
$a["birth_year"] = $dob_year;
$a["birth_day"] = $dob_day;
$a["weight"] = $weight;
$a["smoker"] = $tobacco;
$a["height_feet"] = $height[0];
$a["height_inches"] = $height[1];
$a["insurance_type"] = $cov[$termlength];
$a["insurance_amount"] = $covAmount[$coverageamount];

$postvars = http_build_query($a);

echo $postvars;

$url = 'https://post.leadn.net/?CID=109&LSID=1927';
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POST,true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $postvars);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/x-www-form-urlencoded"));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false );
        $response = curl_exec($ch);
        curl_close($ch);


echo $response . "\n\n";

?>
