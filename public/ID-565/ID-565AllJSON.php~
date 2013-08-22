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



    function sendCurlRequest($collectionUrls, $method = 'POST', $params = array())
    {
	$ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, true);
	//curl_setopt($ch, CURLOPT_USERPWD, 'APItest' .':' . '123TEST');
	curl_setopt($ch, CURLOPT_VERBOSE, true);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLINFO_HEADER_OUT, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: text/xml'));
	curl_setopt($ch, CURLOPT_HTTPHEADER, $params['httpHeader']);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false );


	foreach($collectionUrls as $collectionUrl => $url)
	{
 	  curl_setopt($ch, CURLOPT_URL, $url);
 	  curl_setopt($ch, CURLOPT_POSTFIELDS, $params['payload'][$collectionUrl]);
 	  $response = curl_exec($ch);


	  $responsesArray[$collectionUrl] = $response;
	}

echo "<pre>";
var_dump($responsesArray);
exit('debugging here	');


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
		  "driver1occupation":"AdministrativeClerical",
		  "driver1licenseage":"18",
		  "driver1yearsatresidence":"10",
		  "driver1sr22":"No",
		  "driver1credit":"Good",
		  "driver1yearsemployed":"4",
		  "driver1age":"26",
		  "currentpolicyexpiration":"2013-08-01",
		  "CURRENTINSURANCECOMPANY":"Infinity Insurance",
		  "desiredcoveragetype":"State_Min",
		  "desiredcollisiondeductible":"500",
		  "desiredcomprehensivedeductible":"500",
		  "propertydamage":"30000",
		  "contact":"Morning",
		  "yearsatresidence":"10",
		  "bodilyinjury":"50/100",
		  "policystart":"2012-08-06",
		  "insuredsince":"2011-02-12",
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
		  "ipaddress":"207.168.5.122",
		  "referer":"https://www.facebook.com/",
		  "leadtype":"ShortForm","keyword":"social",
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

    $username = 'APItest';	// <--- the username and password they(BPMOnline) provided, note the big difference in the response if you purposely make the username or password wrong
    $password = '123TEST';


    $accountCollectionJsonPayload = array('Account' => array('LeadFileId' 	     => $lmsDataJsonDecoded['universal_leadid'],
							     'LeadVendorSourceID'    => $lmsDataJsonDecoded['sourcedeliveryid'],
							     'Type' 		     => 'New Lead',
							     'SourceTypeLookup'      => 'Lead Vendor',
							     'SourceName' 	     => 'Underground Elephant',
							     'Name' 		     => $lmsDataJsonDecoded['firstname'].$lmsDataJsonDecoded['lastname'],
							     'NamedInsuredFirstName' => $lmsDataJsonDecoded['firstname'],
							     'NamedInsuredLastName'  => $lmsDataJsonDecoded['lastname'],
							     'Phone' 		     => $lmsDataJsonDecoded['homephone'],
							     'EmailAddressAt' 	     => $lmsDataJsonDecoded['emailaddress'],
							     'Address' 		     => $lmsDataJsonDecoded['address'],
							     'CityNonLookup' 	     => $lmsDataJsonDecoded['city'],
							     'Region' 		     => $lmsDataJsonDecoded['state'],
							     'Zip' 		     => $lmsDataJsonDecoded['zip']
							   )
					 );
    $accountCollectionJsonPayload = json_encode($accountCollectionJsonPayload);

    $contactCollectionJsonPayload = array('Contact' => array('ContactFirstName' => $lmsDataJsonDecoded['firstname'],
							     'ContactLastName'  => $lmsDataJsonDecoded['lastname'],
							     'Phone'		=> $lmsDataJsonDecoded['homephone'],
							     'EmailAddressAt'	=> $lmsDataJsonDecoded['emailaddress'],
							     'Address'		=> $lmsDataJsonDecoded['address'],
							     'CityNonLookup'	=> $lmsDataJsonDecoded['city'],
							     'State/Province'	=> $lmsDataJsonDecoded['state'],
							     'Zip'		=> $lmsDataJsonDecoded['zip'],
							     'PersonID'		=> 1,
							     'Gender'		=> $lmsDataJsonDecoded['driver1gender'],
							     'IncomingRelationshipType' => '',
							     'BirthDate'	=> $lmsDataJsonDecoded['driver1dob_month'].','.$lmsDataJsonDecoded['driver1dob_day'].','.$lmsDataJsonDecoded['driver1dob_year'],
							     'AgeValue'		=> $lmsDataJsonDecoded['driver1licenseage'],
							     'IncomingMarritalStatus'	=> $lmsDataJsonDecoded['driver1maritalstatus'],
							     'IncomingEducationLvl'	=> $lmsDataJsonDecoded['driver1edulevel'],
							     'Occupation'	=> $lmsDataJsonDecoded['driver1occupation'],
							     'CurrentResStatLookupColumn' => $lmsDataJsonDecoded['address'],
							     'DriverYearsAtRes' => $lmsDataJsonDecoded['driver1yearsatresidence'],
							     'CreditSelfRatingLookupColumn' => $lmsDataJsonDecoded['driver1credit'],
							     'SR22'		=> $lmsDataJsonDecoded['driver1sr22'],
							     'Incident'		=> '',
							     'Account'		=> $lmsDataJsonDecoded['firstname'].$lmsDataJsonDecoded['lastname']
							    )
					 );
    $contactCollectionJsonPayload = json_encode($contactCollectionJsonPayload);

    $propertiesCollectionJsonPayload = array('Property' => array('PropertyNameTitle' 	  => $lmsDataJsonDecoded['firstname'].$lmsDataJsonDecoded['lastname'],
								 'PropertyCategoryLookup' => 'Inquiry',
								 'PropertyTypeLookup' 	  => 'Auto',
								 'CustomerHousholdLookup' => $lmsDataJsonDecoded['firstname'].$lmsDataJsonDecoded['lastname'],
								 'NamedInsuredLookup'	  => $lmsDataJsonDecoded['firstname'].$lmsDataJsonDecoded['lastname'],
								 'VehYearColumn1'	  => $lmsDataJsonDecoded['vehicle1year'],
								 'VehMakeLookup1'	  => $lmsDataJsonDecoded['vehicle1make'],
								 'VehModelColumn1'	  => $lmsDataJsonDecoded['vehicle1model'],
								 'VehTrimColumn1'	  => $lmsDataJsonDecoded['vehicle1trim'],
								 'LeasedBoolean1'	  => $lmsDataJsonDecoded['vehicle1leased'],
								 'OwnershipLookup1'	  => $lmsDataJsonDecoded['vehicle1ownership'],
								 'TimeAtResColumn1'	  => $lmsDataJsonDecoded['yearsatresidence'],
								 'PrimaryUseLookup1'	  => $lmsDataJsonDecoded['vehicle1primaryUse'],
								 'MilesDrivenColumn1'	  => $lmsDataJsonDecoded['vehicle1commuteAvgMileage'],
								 'AnnualMilesColumn1'	  => $lmsDataJsonDecoded['vehicle1annualMileage'],
								 'CompDedLookup1'	  => $lmsDataJsonDecoded['desiredcomprehensivedeductible'],
								 'CollDedLookup1'	  => $lmsDataJsonDecoded['desiredcollisiondeductible']
								)
					    );
    $propertiesCollectionJsonPayload = json_encode($propertiesCollectionJsonPayload);

    $opportunitiesCollectionJsonPayload = array('Opportunity' => array('Type'	 => 'Auto',
								       'Stage'	 => 'Inquired',
								       'Account' => $lmsDataJsonDecoded['firstname'].$lmsDataJsonDecoded['lastname'],
								       'Contact' => $lmsDataJsonDecoded['firstname'].$lmsDataJsonDecoded['lastname'],
								       'PriorCarrierLookup' 	=> $lmsDataJsonDecoded['CURRENTINSURANCECOMPANY'],
								       'DesiredCovgLvlLUColumn' => $lmsDataJsonDecoded['desiredcoveragetype'],
								       'PriorBILimitPerPerson' 	=> $lmsDataJsonDecoded['bodilyinjury'],
								       'PriorBILimitPerIncident'=> 3000,
								       'PriorPDlimitsLookup' 	=> $lmsDataJsonDecoded['propertydamage'],
								       'PolicyExDate'		=> $lmsDataJsonDecoded['currentpolicyexpiration'],
								      )
					       );
    $opportunitiesCollectionJsonPayload = json_encode($opportunitiesCollectionJsonPayload);

    $jsonPayloads = array('accountCollection' 	  => $accountCollectionJsonPayload,
			  'contactCollection' 	  => $contactCollectionJsonPayload,
			  'propertyCollection' 	  => $propertiesCollectionJsonPayload,
			  'opportunityCollection' => $opportunitiesCollectionJsonPayload
			 );


    $config = array('collectionType'  => 'AccountCollection',
		    'url'             => 'https://connect-myinsurance.bpmonline.com/2/ServiceModel/EntityDataService.svc/', // <--- the URL BPMOnline provided
		    'username'        => $username,
		    'password'	      => $password,
		    'httpHeader'      => array("Authorization: Basic " . base64_encode($username . ":" . $password),
					       //"Accept: application/atom+xml;type=entry",
					       //"Content-Type: application/atom+xml;type=entry"
					       //"Content-type: text/xml"
					       "Content-Type: application/json"
					      ),
		    'payload'      => $jsonPayloads
		   );


    $collectionUrls = array('accountCollection' => 'https://connect-myinsurance.bpmonline.com/2/ServiceModel/EntityDataService.svc/AccountCollection/',
			    'contactCollection' => 'https://connect-myinsurance.bpmonline.com/2/ServiceModel/EntityDataService.svc/ContactCollection/',
			    'propertyCollection' => 'https://connect-myinsurance.bpmonline.com/2/ServiceModel/EntityDataService.svc/ItemsOfPropertyCollection/',
			    'opportunityCollection' => 'https://connect-myinsurance.bpmonline.com/2/ServiceModel/EntityDataService.svc/OpportunityCollection/'
			     //https://connect-myinsurance.bpmonline.com/2/ServiceModel/EntityDataService.svc/ClaimsCollection
			     //https://connect-myinsurance.bpmonline.com/0/ServiceModel/EntityDataService.svc/ClaimsCollection
			   );

    $postResponse = sendCurlRequest($collectionUrls,   // <--- see this http://www.bpmonline.com/bpmonlinesdken/WorkWithBpmByOdataHttp.html, and look for this string "Getting an object with specified characteristics"
				    'POST',
				    $config
				   );
    var_dump( htmlentities($postResponse) );
?>