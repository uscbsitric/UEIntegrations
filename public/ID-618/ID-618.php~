<?php

    /*****
    require_once __DIR__.'/../classes/pingpostCommon.php';

    if ($argv[1] != "")
	$leadid = $argv[1];
    else
	$leadid = $_POST["leadid"];

    $pingPost = new PingPostCommon();
    $lmsData = $pingPost->fetchLead($leadid);
    *****/


    function sendCurlRequest($url, $method = 'POST', $params = array())
    {
	$postVars = http_build_query($params);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postVars);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/x-www-form-urlencoded"));
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false );

	$response = curl_exec($ch);
var_dump($response);
exit();
	if(curl_errno($ch))
	{
	    $curlError = 'Curl error: ' . curl_error($ch);
	    return $curlError;
	}

	curl_close($ch);

	return $response;
    }


    $lmsData =  '{"universal_leadid":"E9CF0E79-D62E-F38D-C360-C5A77AC7DEE8",
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
		  "driver1edulevel":"HighSchoolDiploma",
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
		  "driver1sr22":"No",
		  "driver1credit":"Good",
		  "driver1yearsemployed":"4",
		  "driver1age":"26",
		  "currentpolicyexpiration":"2013-08-01",
		  "policystart":"2012-08-06",
		  "insuredsince":"2011-02-12",
		  "CURRENTINSURANCECOMPANY":"Infinity Insurance",
		  "desiredcoveragetype":"Term 1 Year",
		  "desiredcollisiondeductible":"500",
		  "desiredcomprehensivedeductible":"500",
		  "propertydamage":"30000",
		  "contact":"Morning",
		  "yearsatresidence":"10",
		  "bodilyinjury":"50/100",
		  "vehicle1year":"2010",
		  "vehicle1make":"Hyundai",
		  "vehicle1model":"ACCENT",
		  "vehicle1commuteAvgMileage":"8",
		  "vehicle1annualMileage":"25000",
		  "vehicle1primaryUse":"Commute_Work",
		  "vehicle1leased":"Owned",
		  "vehicle1trim":"Blue",
		  "vehicle1garageType":"Full Garage",
		  "vehicle1alarm":"Alarm",
		  "vehicle1ownership":"Leased",
		  "vehicle1distance":"9",
		  "vehicle1commutedays":"4"
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
		  "dynotrax_id":"51ae795b91e1e77b45000005",
		  }';

    $lmsDataJsonDecoded = json_decode($lmsData, true);

    $userName = '13753';
    $password = 'Dl5O8OkV92q';

    $xmlPayload = '<?xml version="1.0" encoding="utf-8"?>
		   <InsuranceRequest>
		      <AffiliateInfo>
			<Username>'.$userName.'</Username>
			<Password>'.$password.'</Password>
			<TrackingCampaign>str1234</TrackingCampaign>
			<TrackingKeyword>str1234</TrackingKeyword>
			<LeadSourceID>'.$lmsDataJsonDecoded['universal_leadid'].'</LeadSourceID>
			<LeadIdToken>str1234</LeadIdToken>
			<TrafficLogID>1234</TrafficLogID>
			<ProductionEnvironment>false</ProductionEnvironment>
		      </AffiliateInfo>
		      <DistributionDirectives>
			<Cap>8</Cap>
			<DistributionCount>8</DistributionCount>
			<Excludes />
		      </DistributionDirectives>
		   </InsuranceRequest>
		   <LegAcceptOffers>
		    <Leg>
		      <LegID>12345</LegID>
		      <LegGUID>str1234</LegGUID>
		    </Leg>
		   </LegAcceptOffers>
		   <Legs>
		    <Leg>
		      <LegID>12345</LegID>
		      <Payout>123.45</Payout>
		      <CarrierID>1234</CarrierID>
		      <CarrierName>'.$lmsDataJsonDecoded['CURRENTINSURANCECOMPANY'].'</CarrierName>
		      <LicenseNumber>1234</LicenseNumber>
		    </Leg>
		   </Legs>
		   <IsMobile>true</IsMobile>
		   <LeadMetaData>
		      <LeadBornOnDateTimeUtc>2012-12-13T12:12:12</LeadBornOnDateTimeUtc>
		      <IpAddress>'.$lmsDataJsonDecoded['ipaddress'].'</IpAddress>
		      <UserAgent>234</UserAgent>
		   </LeadMetaData>
		   <ZipCode>61334</ZipCode>
		   <ContactInfo>
		      <FirstName>'.$lmsDataJsonDecoded['name'].'</FirstName>
		      <LastName>'.$lmsDataJsonDecoded['lastname'].'</LastName>
		      <Address>'.$lmsDataJsonDecoded['address'].'</Address>
		      <ZipCode>'.$lmsDataJsonDecoded['zip'].'</ZipCode>
		      <City>'.$lmsDataJsonDecoded['city'].'</City>
		      <County>Brazoria County</County>
		      <State>'.$lmsDataJsonDecoded['state'].'</State>
		      <PhoneDay>'.$lmsDataJsonDecoded['homephone'].'</PhoneDay>
		      <PhoneEve>'.$lmsDataJsonDecoded['homephone'].'</PhoneEve>
		      <PhoneCell>'.$lmsDataJsonDecoded['homephone'].'</PhoneCell>
		      <Email>'.$lmsDataJsonDecoded['emailaddress'].'</Email>
		      <Comment></Comment>
		      <PreferredLanguage>EN</PreferredLanguage>
		   </ContactInfo>
		   <LifeInsurance>
		      <PersonInfo>
			<DOB>'.$lmsDataJsonDecoded['driver1dob_year'].'-'.$lmsDataJsonDecoded['driver1dob_month'].'-'.$lmsDataJsonDecoded['driver1dob_day'].'</DOB>
			<Gender>'.$lmsDataJsonDecoded['driver1gender'].'</Gender>
			<Height_FT>5</Height_FT>
			<Height_IN>7</Height_IN>
			<Weight>80</Weight>
			<Tobacco>false</Tobacco>
		      </PersonInfo>
		      <IsMilitary>false</IsMilitary>
		      <MedicalHistory>
			<Relative_Heart>false</Relative_Heart>
			<Relative_Cancer>false</Relative_Cancer>
			<Medication>false</Medication>
			<Medical_Treatment>false</Medical_Treatment>
			<Hospital>false</Hospital>
			<Comments></Comments>
		      </MedicalHistory>
		      <MajorMedical>
			<AIDS_HIV>false</AIDS_HIV>
			<Alcohol_Drug_Abuse>false</Alcohol_Drug_Abuse>
			<Alzheimers_Disease>false</Alzheimers_Disease>
			<Asthma>false</Asthma>
			<Cancer>false</Cancer>
			<Cholesterol>false</Cholesterol>
			<Depression>false</Depression>
			<Diabetes>false</Diabetes>
			<Heart_Disease>false</Heart_Disease>
			<High_Blood_Pressure>false</High_Blood_Pressure>
			<Kidney_Disease>false</Kidney_Disease>
			<Liver_Disease>false</Liver_Disease>
			<Mental_Illness>false</Mental_Illness>
			<Pulmonary_Disease>false</Pulmonary_Disease>
			<Stroke>false</Stroke>
			<Ulcer>false</Ulcer>
			<Vascular_Disease>false</Vascular_Disease>
			<Other_Major_Disease>false</Other_Major_Disease>
		      </MajorMedical>
		      <Occupation>'.$lmsDataJsonDecoded['driver1occupation'].'</Occupation>
		      <DUI>false</DUI>
		      <Hazards>
			<Pilot>false</Pilot>
			<Felony>false</Felony>
			<OtherHazards>false</OtherHazards>
			<Comments></Comments>
		      </Hazards>
		      <CurrentInsurance>
			<CurrentlyInsured>true</CurrentlyInsured>
			<CurrentPolicy>
			  <CarrierID>1234</CarrierID>
			  <Carrier>'.$lmsDataJsonDecoded['CURRENTINSURANCECOMPANY'].'</Carrier>
			  <Expiration>'.$lmsDataJsonDecoded['currentpolicyexpiration'].'</Expiration>
			  <InsuredSince>'.$lmsDataJsonDecoded['insuredsince'].'</InsuredSince>
			</CurrentPolicy>
		      </CurrentInsurance>
		      <RequestedCoverage>
			<CoverageType>'.$lmsDataJsonDecoded['desiredcoveragetype'].'</CoverageType>
			<CoverageAmount>1234</CoverageAmount>
		      </RequestedCoverage>
		    </LifeInsurance>
		  </InsuranceRequest>
		  ';


    // staging urls
    $stagingPingUrl = "https://dastaging-lm.dev.allwebleads.com/leads/4.0/LeadServiceHttpPost.svc/PricePresentationPingLead";
    $stagingPostUrl = "https://dastaging-lm.dev.allwebleads.com/leads/4.0/LeadServiceHttpPost.svc/PricePresentationPostLead";

    // production urls
    $pingUrl = "https://ws.allwebleads.com/leads/4.0/LeadServiceHttpPost.svc/PricePresentationPingLead";
    $postUrl = "https://ws.allwebleads.com/leads/4.0/LeadServiceHttpPost.svc/PricePresentationPostLead";

    // PING
//     $pingResponse = sendCurlRequest($stagingPingUrl, 'POST', array('XmlString' => $xmlPayload));
//     var_dump($pingResponse);
//     exit();


    // POST
    $postResponse = sendCurlRequest($stagingPostUrl, 'POST', array('LeadID' => '123456789',
								   'XmlString' => $xmlPayload
								  )
				   );
    //var_dump($postResponse);