<?php

//require_once '../classes/pingpostCommon.php';

function getCurlRequest($url, $params)
{
	$postvars = http_build_query($params);

	$ch = curl_init($url);
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_POST,true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_POSTFIELDS, $postvars);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/x-www-form-urlencoded"));
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false );
	$response = curl_exec($ch);
	curl_close($ch);
	
	return $response;
}

	$lmsData = '{"vertical": "lins",
			    "name": "Elliott",
			    "lastname": "pyles",
			    "emailaddress": "Elliottpyles@aol.com",
			    "address": "1509 5th Ave",
			    "city": "Ybor City",
			    "_City": "Ybor City",
			    "state": "FL",
			    "st": "FL",
			    "_State": "FL",
			    "zip": "33605",
			    "_PostalCode": "33605",
			    "homephone": "8132486365",
			    "ueid": "pchd_050e75a2322b6d_life_1",
			    "country_code": "1",
			    "cam": "life_1",
			    "querystring": "apd=05302e6dd8ffef5af208f4887d1ec325&mem=2",
			    "useragent": "Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; WOW64; Trident/6.0; MAGWJS)",
			    "ipaddress": "97.96.236.19",
			    "sid": "wholesaleinsurance.info",
			    "AFID": "43074",
			    "referer": "",
			    "leadtype": "lifeins",
			    "keyword": "pch",
			    "variant": "default_disclaim",
			    "sureHitsFeedId": "",
			    "dob_day": "21",
			    "dob_month": "12",
			    "dob_year": "1947",
			    "height": "5-8",
			    "weight": "190",
			    "gender": "MALE",
			    "tobacco": "no",
			    "existingconditionstoggle": "no",
			    "termlength": "20",
			    "coverageamount": "50000",
			    "homephone_area": "813",
			    "homephone_prefix": "248",
			    "homephone_suffix": "6365",
			    "cookie": "9105805d09102915f42af8064fc48892",
			    "keywords": "display|pch||apd=05302e6dd8ffef5af208f4887d1ec325&mem=2|pch|default_disclaim",
			    "vendoremail": "other",
			    "vendorpassword": "ueint",
			    "keyword_id": "2716",
			    "variant_id": "19969",
			    "site_id": "330",
			    "hid": "autoclone3",
			    "dynotrax_id": "51792b572e81cd1b62000044"
	 			 }';

$lmsDataJsonDecoded = json_decode($lmsData, true);


$cov['whole']='whole';
$cov['10'] = $cov['15'] = $cov['20'] = $cov['25'] = $cov['30'] = 'term';

$covAmount = array( '25000' => '25k',
					'50000' => '50k',
					'100000' => '100k',
					'150000' => '150k',
					'200000' => '200k',
					'250000' => '250k',
					'300000' => '300k',
					'350000' => '350k',
					'400000' => '400k',
					'450000' => '400k',
					'500000' => '500k',
					'550000' => '500k',
					'600000' => '600k',
					'650000' => '600k',
					'700000' => '700k',
					'750000' => '700k',
					'800000' => '800k',
					'850000' => '800k',
					'900000' => '900k',
					'950000' => '1mil',
					'1000000' => '1mil',
					'1500000'=> '2mil',
					'2000000' => '2mil'
				 );

$height = explode('-', $lmsDataJsonDecoded['height']);
$totalHeight = explode('-', $lmsDataJsonDecoded['height']);

$a = array('fname' => $lmsDataJsonDecoded['name'],
		   'lname' => $lmsDataJsonDecoded['lastname'],
		   'email' => $lmsDataJsonDecoded['emailaddress'],
		   'gender' => strtolower($lmsDataJsonDecoded['gender']),
		   'birth_month' => $lmsDataJsonDecoded['dob_month'],
		   'birth_day' => $lmsDataJsonDecoded['dob_day'],
		   'birth_year' => $lmsDataJsonDecoded['dob_year'],
		   'smoker' => $lmsDataJsonDecoded['tobacco'],
		   'height_feet' => $totalHeight[0],
		   'height_inches' => $totalHeight[1],
		   'weight' => $lmsDataJsonDecoded['weight'],
		   'insurance_type' => $cov[$lmsDataJsonDecoded['termlength']],
		   'insurance_amount' => $covAmount[$lmsDataJsonDecoded['coverageamount']],
		   'ip_address' => $lmsDataJsonDecoded['ipaddress'],
		   'h_address' => $lmsDataJsonDecoded['address'],
		   'h_city' => $lmsDataJsonDecoded['city'],
		   'h_state' => $lmsDataJsonDecoded['state'],
		   'h_zip' => $lmsDataJsonDecoded['zip'],
		   'h_areacode' => substr($lmsDataJsonDecoded['homephone'], 0, 3),
		   'h_prefix' => substr($lmsDataJsonDecoded['homephone'], 3, 3),
		   'h_suffix' => substr($lmsDataJsonDecoded['homephone'], 6, 4)
		  );

$url = 'https://post.leadn.net/test?CID=109&LSID=1927';
$response = getCurlRequest($url, $a);

var_dump($response);

?>
