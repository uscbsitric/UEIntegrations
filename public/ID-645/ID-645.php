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
$stringCoverage = '';
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
$to = 'dean@yourinsuredpa.com,Jaime@yourinsured.com, robyn@yourinsured.com';

$headers = "From: leads@undergroundelephant.com" . "\r\n";

if(@mail($to, 'Lead from Underground Elephant', $message, $headers)){
        echo 'Success';
}else{
        echo 'Failure';
}
?>