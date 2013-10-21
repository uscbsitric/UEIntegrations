<?php

function calculateBMI($height, $weight)
{
    $height = explode('-', $height);
    $heightInches = (int)$height[0] * 12 + (int)$height[1];
    $BMI = (int)$weight / ($heightInches * $heightInches) * 703;
        return $BMI;
}

if ($argv[1] != "")
        $leadid = $argv[1];
else
        $leadid = $_POST["leadid"];



$pingPost = new PingPostCommon();
$lmsData = $pingPost->fetchLead($leadid, 'lifeins_lead');
if (empty($lmsData)) {
    echo "Result=NotSold - Empty result found for lead id: " . $leadid;
    exit;
}

$postStringVals = json_decode($lmsData['poststring'], true);
$vals = array_merge($lmsData,$postStringVals, $_POST);
extract($vals);


$stringCoverage = '93206,93215,93216,93220,93222,93224,93249,93251,93252,93276,93301,93302,93303,93304,93305,93306,
				   93309,93311,93312,93313,93380,93381,93382,93383,93384,93385,93386,93387,93388,93389,93390,93524,
				   93556,93581,93596';
$coverage = explode(',', $stringCoverage);

if(!(in_array($zip, $coverage)))
{
        echo "Zip code didn't match";
        exit;
}


$BMI = calculateBMI($height, $weight);

$message = 'The following person has inquired about a life insurance policy.  Please contact this person.  Do not respond to the email address from which this email was sent.
'."\n"."\n";


$message .= "First Name: " . $name . "\n";
$message .= "Last Name: " . $lastname . "\n";
$message .= "Address: " . $address . "\n";
$message .= "City: " . $city . "\=n";
$message .= "State: " . $st . "\n";
$message .= "Zip Code: " . $zip . "\n";
$message .= "Email Address: " . $emailaddress . "\n";
$message .= "Phone number: " . $homephone . "\n";
$message .= "Date of Birth: " . $dob_month . "-".$dob_day."-".$dob_year."\n";
$message .= "Height: " . $height . "\n";
$message .= "Weight: " . $weight . "\n";
$message .= "Tobacco Usage: " . $tobacco . "\n";
$message .= "Term Length: " . $termlength . "\n";
$message .= "Coverage Amount: " . $coverageamount . "\n";
$message .= "BMI: " . substr((string)$BMI, 0, 5) . "\n";

//REAL LEADS GO HERE
$to = 'delivery-jburton1@farmersagent.com, lucy@burtoninsurance.net';

$headers = "From: leads@undergroundelephant.com" . "\r\n";

if(@mail($to, 'Lead from Underground Elephant', $message, $headers)){
        echo 'Success';
}else{
        echo 'Failure';
}
?>