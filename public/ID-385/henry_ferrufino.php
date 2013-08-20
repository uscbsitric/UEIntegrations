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


//$coverage = array('06390','00544','00501','11757','11755','11754','11764','11763','11760','11752','11746','11743','11742','11751','11749','11747','11766','11779','11778','11777','11784','11782','11780','11776','11769','11768','11767','11775','11772','11770','11741','11716','11715','11713','11719','11718','11717','11707','11703','11702','11701','11706','11705','11704','11720','11733','11731','11730','11740','11739','11738','11729','11724','11722','11721','11727','11726','11725','11948','11947','11969','11949','11967','11968','11950','11971','11972','11973','11942','11946','11970','11944','11965','11955','11956','11957','11954','11953','11952','11951','11962','11963','11964','11961','11958','11959','11960','11975','11798','11796','11795','11901','11932','11931','11930','11788','11787','11786','11789','11794','11792','11790','11940','11939','11977','11980','11941','11978','11933','11976','11934','11937','11935');
$stringCoverage = '90022,90040,90201,90221,90240,90241,90242,90262,90270,90278,90280,90601,90602,90603,90604,90605,90606,90620,90621,90622,90623,90624,90630,90631,90632,90633,
		   90638,90639,90650,90660,90670,90680,90701,90703,90706,90712,90713,90714,90715,90716,90720,90721,90723,90740,90742,90743,90801,90802,90803,90804,90805,90806,
		   90807,90808,90813,90814,90815,90822,90831,90833,90834,90835,90840,90846,91745,91748,92602,92603,92604,92605,92606,92607,92610,92612,92614,92615,92616,92618,
		   92619,92620,92623,92625,92626,92627,92628,92630,92646,92647,92648,92649,92650,92651,92652,92653,92654,92655,92656,92657,92658,92659,92660,92661,92662,92663,
		   92672,92675,92676,92677,92678,92679,92683,92684,92685,92688,92690,92691,92692,92693,92694,92697,92698,92701,92702,92703,92704,92705,92706,92707,92708,92709,
		   92710,92711,92712,92728,92735,92780,92781,92782,92799,92801,92802,92803,92804,92805,92806,92807,92808,92811,92812,92814,92815,92816,92817,92821,92822,92823,
		   92825,92831,92832,92833,92834,92835,92836,92837,92838,92840,92841,92842,92843,92844,92845,92846,92850,92856,92857,92859,92861,92862,92863,92864,92865,92866,
		   92867,92868,92869,92870,92871,92885,92886,92887,92899';
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
$to = 'eferrufino@farmersagent.com, insurancebyjohana@yahoo.com';

$headers = "From: leads@undergroundelephant.com" . "\r\n";

if(@mail($to, 'Lead from Underground Elephant', $message, $headers)){
        echo 'Success';
}else{
        echo 'Failure';
}
?>