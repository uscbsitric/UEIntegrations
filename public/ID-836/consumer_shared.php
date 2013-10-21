<?php


function curlposting($url1 , $result , $header){
                $Curl_Session = curl_init($url1);
                curl_setopt ($Curl_Session, CURLOPT_POST, 1);
                curl_setopt ($Curl_Session, CURLOPT_POSTFIELDS, $result);
                curl_setopt($Curl_Session, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($Curl_Session, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($Curl_Session, CURLOPT_TIMEOUT, 120);
                curl_setopt ($Curl_Session, CURLOPT_FOLLOWLOCATION, 1);
                $res = curl_exec ($Curl_Session);
                curl_close ($Curl_Session);
                return $res;
}


if (empty($_POST)) {
    echo "Result=NotSold - Empty post";
    exit;
}

$vals = $_POST;

extract($vals);



if ($State == 'GA' && $currentlyinsured == '0') {
    echo "Filter for state of GA - Not currently Insured";
    exit;
}

$current_resident_status = strtolower($current_resident_status);

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

if($driver1gender == "female")
        $driver1gender = 'F';
else $driver1gender = 'M';


$use["pleasure"] = "pleasure";
$use["commutework"] = "commuting";
$use["business"] = "business";
$use["selfemployed"] = "business";
$use["gov"] = "commuting";
$use["farm"] = "business";

$driver1marital_status = ucfirst($driver1marital_status);

if($sr22 == '1')
        $sr22 = "suspended";
else
        $sr22 = "active";

if($currentlyinsured == "0")
{
        $current_insurance_carrier = "None";
}

if($SubID == '2094' || $SubID == '1726')
        $SubID = '1279';

// Per Kevin: to remap the source that is cap for this month 07/13
if ($SubID == '2768') {
    $SubID = '1462';
}

// Traffic source Filter per Consumer United on 04-02-13
if ($SubID == '1899' || $SubID == '2431' || $SubID == '2918') {
    echo "Traffic Source Filter on keyword 1899, 2431 and 2918";
    exit;
}

$data = "ref_id=".$leadid."&PublisherID=62&OfferID=" . $OfferID. "&FirstName=" . $FirstName. "&LastName=" . $LastName. "&Address=" . $Address. "&City=" . $City. "&State=" . $State. "&Zip=" . $Zip. "&Email=" . 
		$Email. "&TimeStamp=" . $TimeStamp. "&current_insurance_carrier=" . $current_insurance_carrier;

if($currentlyinsured != "0")
        $data .= "&current_insurance_expiration_date=" . $current_insurance_expiration_date;

$data .= "&vehicle[1][year]=" . $vehicle1year. "&vehicle[1][make]=" . $vehicle1make. "&vehicle[1][model]=" . $vehicle1model. "&vehicle[1][submodel]=" . $vehicle1trim. "&vehicle[1][est_annual_mileage]=" . 
		$vehicle1annualMileage. "&vehicle[1][desired_comprehensive_deductible]=" . $desiredcomprehensivedeductible. "&vehicle[1][desired_collision_deductible]=" . 
		$desiredcollisiondeductible. "&driver[1][dob]=".$dobYear."-".$dobMonth."-".$dobDay."&driver[1][credit_self_rating]=Good&driver[1][relation_to_applicant]=Self&HomePhone=".
		$HomePhone."&vehicle[1][commute_miles]=".$vehicle1commute_miles."&vehicle[1][main_use]=".$use[$vehicle1main_use]."&current_resident_status=".$current_resident_status."&level_of_coverage_requested=".
		$coverage[$level_of_coverage_requested]."&driver[1][highest_degree]=".$education[$driver1highest_degree]."&driver[1][gender]=".$driver1gender."&driver[1][marital_status]=".
		$driver1marital_status."&driver[1][occupation]=".$driver1occupation."&driver[1][license_first_received_age]=".$driver1license_first_received_age."&driver[1][first_name]=".$FirstName."&driver[1][last_name]=".
		$LastName."&driver[1][license_status]=".$sr22."&SubID=".$SubID."&test_post=0";

if($vehicle2model != '')
        $data .= "&vehicle[2][year]=" . $vehicle2year. "&vehicle[2][make]=" . $vehicle2make. "&vehicle[2][model]=" . $vehicle2model. "&vehicle[2][submodel]=" . $vehicle2trim. "&vehicle[2][est_annual_mileage]=" . $vehicle2annualMileage. "&vehicle[2][desired_comprehensive_deductible]=" . $desiredcomprehensivedeductible. "&vehicle[2][desired_collision_deductible]=" . $desiredcollisiondeductible ."&vehicle[2][commute_miles]=".$vehicle2commute_miles."&vehicle[2][main_use]=".$use[$vehicle2main_use];

if($driver2occupation != '')
{
        $relation['spouse'] = "Spouse";
        $relation['child'] = "Child";
        $relation['self'] = "Other";
        $relation['sibling'] = "Other";
        $relation['parent'] = "Parent";
        $relation['grandparent'] = "Parent";
        $relation['grandchild'] = "Parent";
        $relation['other'] = "Other";

        if($driver2gender == "male")
                $driver2gender = 'M';
        else $driver2gender = 'F';

        $driver2marital_status = ucfirst($driver2marital_status);

        $data .= "&driver[2][highest_degree]=".$driver2highest_degree."&driver[2][gender]=".$driver2gender."&driver[2][license_status]=".$sr22."&driver[2][marital_status]=".$driver2marital_status."&driver[2][occupation]=".$driver2occupation."&driver[2][license_first_received_age]=".$driver2license_first_received_age."&driver[2][credit_self_rating]=Good&driver[2][relation_to_applicant]=".$relation[$driver2relation_to_applicant]."&driver[2][dob]=".$dob2Year."-".$dob2Month."-".$dob2Day."&driver[2][first_name]=Driver2&driver[2][last_name]=Driver2";
}


echo $data;

$url = 'http://leads.consumerunited.com:7684/import';
$res = curlposting($url, $data, $header);

echo $res;


?>

