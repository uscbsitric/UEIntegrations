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
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);

	if( $method === 'POST' )
	{
	  curl_setopt($ch, CURLOPT_POST, true);
	}

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $params['httpHeader'] );
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




    function sendCurlRequest2($url, $method = 'POST', $params = array())
    {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_VERBOSE, true);
	    curl_setopt($ch, CURLOPT_HEADER, 1);
	    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $params['httpHeader'] );
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false );

	$response = curl_exec($ch);

echo "<pre>";
//var_dump( curl_getinfo($ch) );
var_dump( curl_getinfo($ch) );
exit('debugging here	');

	if(curl_errno($ch))
	{
	    $curlError = 'Curl error: ' . curl_error($ch);
	    return $curlError;
	}

	curl_close($ch);

	return $response;
    }




    $lmsData =  '{"name":"Rebeca",
		  "lastname":"Pasillas",
		  "driver1edulevel":"HighSchoolDiploma",
		  "email":"beckypasillas@yahoo.com",
		  "currentpolicyexpiration":"2013-08-01",
		  "CURRENTINSURANCECOMPANY":"Infinity Insurance",
		  "desiredcoveragetype":"State_Min",
		  "desiredcollisiondeductible":"500",
		  "desiredcomprehensivedeductible":"500",
		  "driver1firstname":"Rebeca",
		  "driver1lastname":"Pasillas",
		  "driver1dob_day":"07",
		  "driver1dob_month":"02",
		  "driver1dob_year":"1987",
		  "driver1gender":"Female",
		  "driver1maritalstatus":"Single",
		  "driver1occupation":"AdministrativeClerical",
		  "vehicle1year":"2010",
		  "vehicle1make":"Hyundai",
		  "vehicle1model":"ACCENT",
		  "vehicle1commuteAvgMileage":"8",
		  "vehicle1annualMileage":"25000",
		  "vehicle1primaryUse":"Commute_Work",
		  "vehicle1leased":"Owned",
		  "vertical":"ains",
		  "emailaddress":"beckypasillas@yahoo.com",
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
		  "universal_leadid":"33704ac4-0507-480c-9de8-02937a9f6bf4",
		  "cam":"ad1_pp_6",
		  "useragent":"Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.94 Safari/537.36",
		  "ipaddress":"207.168.5.122",
		  "sid":"autoinsquote.us",
		  "AFID":"43074",
		  "referer":"https://www.facebook.com/",
		  "leadtype":"ShortForm","keyword":"social",
		  "variant":"gadget_copy",
		  "currentlyinsured":"1",
		  "vehicle1trim":"Blue",
		  "vehicle1garageType":"Full Garage",
		  "vehicle1alarm":"Alarm",
		  "driver1licenseage":"18",
		  "currentresidence":"Own",
		  "driver1yearsatresidence":"10",
		  "driver2edulevel":"AA",
		  "homephone_area":"626",
		  "homephone_prefix":"201",
		  "homephone_suffix":"2360",
		  "firstname":"Rebeca",
		  "sourcedeliveryid":"3",
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
		  "contact":"Morning",
		  "propertydamage":"30000",
		  "yearsatresidence":"10",
		  "bodilyinjury":"50/100",
		  "policystart":"2012-08-06",
		  "insuredsince":"2011-02-12",
		  "driver1sr22":"No",
		  "driver1credit":"Good",
		  "driver1yearsemployed":"4",
		  "driver1age":"26",
		  "vehicle1ownership":"Leased",
		  "vehicle1distance":"9",
		  "vehicle1commutedays":"4"
		  }';

    $lmsDataJsonDecoded = json_decode($lmsData, true);

    $username = 'APItest';	// <--- the username and password they(BPMOnline) provided, note the big difference in the response if you purposely make the username or password wrong
    $password = '123TEST';


/*****  THIS ONE IS WORKING
    $config = array('collectionType'  => 'ContactCollection',
 		    'url'             => 'https://connect-myinsurance.bpmonline.com/0/ServiceModel/EntityDataService.svc/', // <--- the URL BPMOnline provided
		    'username'        => $username,
		    'password'	      => $password,
		    'httpHeader'      => array("Authorization: Basic " . base64_encode($username . ":" . $password), // <--- please see this http://www.bpmonline.com/bpmonlinesdken/WorkWithBpmByOdata.html, 
														     // and look for this string "Request Authentication" and below it you will see "Basic-authentication" 
														     //and I adjusted my codes according to this article: http://stackoverflow.com/questions/13654892/php-curl-basic-authentication-alternatives-for-curlopt-httpheader-curlopt-user
					       //"Content–Type: application/json"   // <--- not sure if this is still required, see this http://www.bpmonline.com/bpmonlinesdken/WorkWithBpmByOdata.html
					      )
 		   );


    $postResponse = sendCurlRequest(  $config['url'] . "ContactCollection(guid'".$lmsDataJsonDecoded['universal_leadid']."')",   // <--- see this http://www.bpmonline.com/bpmonlinesdken/WorkWithBpmByOdataHttp.html, and look for this string "Getting an object with specified characteristics"
				    //"https://connect-myinsurance.bpmonline.com/0/ServiceModel/EntityDataService.svc/ContactCollection(guid'33704ac4-0507-480c-9de8-02937a9f6bf4')",
				    'GET',
				    $config
				   );
*****/









    $xmlPayload = "<?xml version='1.0' encoding='UTF-8'?>
		    <atom:entry>
		      <atom:content type='application/xml'>
			<dsdm:properties>
			  <ds:LeadFileId>33704ac4-0507-480c-9de8-02937a9f6bf4</ds:LeadFileId>
			  <ds:LeadVendorSourceID>3</ds:LeadVendorSourceID>
			  <ds:Type>New Lead</ds:Type>
			  <ds:SourceTypeLookup>Lead Vendor</ds:SourceTypeLookup>
			  <ds:SourceName>Underground Elephant</ds:SourceName>
  			  <ds:Name>RebecaPasillas</ds:Name>
			  <ds:NamedInsuredFirstName>Rebeca</ds:NamedInsuredFirstName>
			  <ds:NamedInsuredLastName>Pasillas</ds:NamedInsuredLastName>
			  <ds:Phone>626-201-2360</ds:Phone>
			  <ds:EmailAddressAt>beckypasillas@yahoo.com</ds:EmailAddressAt>
			  <ds:Address>16904 New Pine Drive</ds:Address>
  			  <ds:CityNonLookup>Hacienda Heights</ds:CityNonLookup>
			  <ds:Region>CA</ds:Region>
  			  <ds:Zip>91745</ds:Zip>
			</dsdm:properties>
		      </atom:content>
		    </atom:entry>
		  ";

    $xmlPayload2 = '
		    <entry xmlns="http://www.w3.org/2005/Atom">
		      <content type="application/xml">
			<properties xmlns="http://schemas.microsoft.com/ado/2007/08/dataservices/metadata">
			  <LeadFileId xmlns="http://schemas.microsoft.com/ado/2007/08/dataservices">33704ac4-0507-480c-9de8-02937a9f6bf4</LeadFileId>
			  <LeadVendorSourceID xmlns="http://schemas.microsoft.com/ado/2007/08/dataservices">3</LeadVendorSourceID>
			  <Type xmlns="http://schemas.microsoft.com/ado/2007/08/dataservices">New Lead</Type>
			  <SourceTypeLookup xmlns="http://schemas.microsoft.com/ado/2007/08/dataservices">Lead Vendor</SourceTypeLookup>
			  <SourceName xmlns="http://schemas.microsoft.com/ado/2007/08/dataservices">Underground Elephant</SourceName>
			  <Name xmlns="http://schemas.microsoft.com/ado/2007/08/dataservices">RebecaPasillas</Name>
			  <NamedInsuredFirstName xmlns="http://schemas.microsoft.com/ado/2007/08/dataservices">Rebeca</NamedInsuredFirstName>
			  <NamedInsuredLastName xmlns="http://schemas.microsoft.com/ado/2007/08/dataservices">Pasillas</NamedInsuredLastName>
			  <Phone xmlns="http://schemas.microsoft.com/ado/2007/08/dataservices">626-201-2360</Phone>
			  <EmailAddressAt xmlns="http://schemas.microsoft.com/ado/2007/08/dataservices">beckypasillas@yahoo.com</EmailAddressAt>
			  <Address xmlns="http://schemas.microsoft.com/ado/2007/08/dataservices">16904 New Pine Drive</Address>
			  <CityNonLookup xmlns="http://schemas.microsoft.com/ado/2007/08/dataservices">Hacienda Heights</CityNonLookup>
			  <Region xmlns="http://schemas.microsoft.com/ado/2007/08/dataservices">CA</Region>
			  <Zip xmlns="http://schemas.microsoft.com/ado/2007/08/dataservices">91745</Zip>
			</properties>
		      </content>
		    </entry>
		   ';

    $config = array('collectionType'  => 'AccountCollection',
		    'url'             => 'https://connect-myinsurance.bpmonline.com/0/ServiceModel/EntityDataService.svc/', // <--- the URL BPMOnline provided
		    'username'        => $username,
		    'password'	      => $password,
		    'httpHeader'      => array("Authorization: Basic " . base64_encode($username . ":" . $password),
					       "Accept: application/atom+xml;type=entry",
					       "Content-Type: application/atom+xml;type=entry"
					      ),
		    'xmlPayload'      => $xmlPayload2
		   );


    $postResponse = sendCurlRequest2( $config['url'] . $config['collectionType'] . "/",   // <--- see this http://www.bpmonline.com/bpmonlinesdken/WorkWithBpmByOdataHttp.html, and look for this string "Getting an object with specified characteristics"
				     'POST',
				     $config
				   );
    var_dump( htmlentities($postResponse) );
?>