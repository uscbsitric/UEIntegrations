<?php

function getCurlRequest($url, $params)
{
	$postVars = http_build_query($params);

	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postVars);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/x-www-form-urlencoded"));
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false );

	$response = curl_exec($ch);
	if(curl_errno($ch))
	{
		$curlError = 'Curl error: ' . curl_error($ch);
		return $curlError;
	}

	curl_close($ch);

	return $response;
}


$lmsData =  '{"universal_leadid":"GHCF0E79-RT2E-F3HJ-C760-C5A77AC7DEDF",
				  "sourcedeliveryid":"3",
				  "sid":"autoinsquote.us",
				  "AFID":"43074",
				  "homephone_area":"626",
				  "homephone_prefix":"201",
				  "homephone_suffix":"2360",
				  "name":"Rebeca",
				  "firstname":"Rebeca",
				  "lastname":"Pasillas",
				  "emailaddress":"beckypasillas@yahoo.com",
				  "email":"beckypasillas@yahoo.com",
				  "address":"16904 New Pine Drive",
				  "city":"Hacienda Heights",
				  "_City":"Hacienda Heights",
				  "state":"CA",
				  "st":"CA",
				  "_State":"CA",
				  "zip":"91745",
				  "_PostalCode":"91745",
				  "homephone":"626-201-2360",
				  "ueid":"fbso_0517af506af937_ad1_pp_6",
				  "country_code":"1",
				  "driver1edulevel":"Bachelors",
				  "driver1firstname":"Rebeca",
				  "driver1lastname":"Pasillas",
				  "driver1dob_day":"07",
				  "driver1dob_month":"02",
				  "driver1dob_year":"1987",
				  "driver1gender":"Female",
				  "driver1maritalstatus":"Single",
				  "driver1occupation":"Advertising/Public Relations",
				  "driver1licenseage":"18",
				  "driver1yearsatresidence":"10",
				  "driver1sr22":"false",
				  "driver1credit":"Good",
				  "driver1yearsemployed":"4",
				  "driver1age":"26",
				  "currentpolicyexpiration":"2013-08-01",
				  "policystart":"2012-08-06",
				  "insuredsince":"2011-02-12",
				  "CURRENTINSURANCECOMPANY":"Infinity Insurance",
				  "desiredcoveragetype":"Basic",
				  "desiredcollisiondeductible":"500",
				  "desiredcomprehensivedeductible":"500",
				  "propertydamage":"300",
				  "contact":"Morning",
				  "yearsatresidence":"10",
				  "bodilyinjury":"100",
				  "vehicle1year":"2010",
				  "vehicle1make":"Hyundai",
				  "vehicle1model":"ACCENT",
				  "vehicle1commuteAvgMileage":"8",
				  "vehicle1annualMileage":"25000",
				  "vehicle1primaryUse":"Commute",
				  "vehicle1leased":"Owned",
				  "vehicle1trim":"Blue",
				  "vehicle1garageType":"Full Garage",
				  "vehicle1alarm":"Alarm",
				  "vehicle1ownership":"Leased",
				  "vehicle1distance":"9",
				  "vehicle1commutedays":"4",
				  "vertical":"ains",
				  "cam":"ad1_pp_6",
				  "useragent":"Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.94 Safari/537.36",
				  "ipaddress":"252.252.211.211",
				  "referer":"https://www.facebook.com/",
				  "leadtype":"ShortForm",
				  "keyword":"social",
				  "variant":"gadget_copy",
				  "currentlyinsured":"1",
				  "currentresidence":"Own",
				  "driver2edulevel":"AA",
				  "cookie":"2f42075a6151eec7cb8424be36d5cf4a",
				  "keywords":"social|facebook|||social|gadget_copy",
				  "vendoremail":"facebook",
				  "vendorpassword":"ueint",
				  "vendorid":"underground",
				  "keyword_id":"2712",
				  "variant_id":"25214",
				  "site_id":"233",
				  "hid":"nvt-node12",
				  "dynotrax_id":"51ae795b91e1e77b45000005"
				  }';

$lmsDataJsonDecoded = json_decode($lmsData, true);

$testUrl = 'https://test1.leadamplms.com/prospect/preping';		// it used to be https://www.leadamplms.com/preping
$productionUrl = 'https://prod1.leadamplms.com/prospect/preping';
$params = array('clientCode' => 'AMFAM',
				'sourceCode' => 'UGRELNT',
				'campaignCode' => 'AUTO',
				'zip' => $lmsDataJsonDecoded['zip'],
				'affiliateCode' => $lmsDataJsonDecoded['vendorid']
			   );

$response = getCurlRequest($testUrl, $params);
var_dump($response);
