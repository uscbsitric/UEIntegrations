<?php

// require_once __DIR__.'/../classes/post.php';
// require_once __DIR__.'/../classes/postRequest.php';
// require_once __DIR__.'/../classes/pingpostCommon.php';
// 
// if ($argv[1] != "") {
//     $leadid = $argv[1];
// } else {
//     $leadid = $_POST["leadid"];
// }
// 
// if (empty($leadid)) {
//     echo "Error: no lead";
//     exit;
// }
// 
// $pingPost = new PingPostCommon();
// $lmsData = $pingPost->fetchLead($leadid);
// $postStringVals = json_decode($lmsData['poststring'], true);
// $leadPostData = array_merge($lmsData, $postStringVals, $_POST);

$config = array('accountUrl'  	 => 'https://connect-myinsurance.bpmonline.com/0/ServiceModel/EntityDataService.svc/AccountCollection/',
		'contactUrl'  	 => 'https://connect-myinsurance.bpmonline.com/0/ServiceModel/EntityDataService.svc/ContactCollection/',
		'propertiesUrl'  => 'https://connect-myinsurance.bpmonline.com/0/ServiceModel/EntityDataService.svc/ItemsOfPropertyCollection/',
		'opportunityUrl' => 'https://connect-myinsurance.bpmonline.com/0/ServiceModel/EntityDataService.svc/OpportunityCollection/'
	       );

	// Auto Insurance Post script
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


try
{
    $accountData = generateAccountXml($lmsDataJsonDecoded);
    $response = executeCurlRequest($config['accountUrl'], $accountData);
    preg_match('/(([a-zA-Z0-9]){8})-(([a-zA-Z0-9]){4})-(([a-zA-Z0-9]){4})-(([a-zA-Z0-9]){4})-(([a-zA-Z0-9]){12})/', $response, $matches);
    $lmsDataJsonDecoded['accountDataGUID'] = $matches[0];
echo "<strong style='color:#990000'>GUID from Account Collection: </strong>";
echo var_dump($matches[0]);
echo "<br>";
echo "<strong style='color:#006600'>XML input for Account Collection: </strong>";
var_dump( htmlentities($accountData) );
echo "<br><br>";


    $lmsDataJsonDecoded['driver1gender']    = ( strtolower($lmsDataJsonDecoded['driver1gender']) == 'female' ) ? 'fc2483f8-65b6-df11-831a-001d60e938c6' : 'eeac42ee-65b6-df11-831a-001d60e938c6';
    $lmsDataJsonDecoded['currentresidence'] = ( strtolower($lmsDataJsonDecoded['currentresidence'] == 'own') ? '4fcdbaeb-cd73-481e-a02b-a904ea8bfd5e':'f7b77256-81b5-4db7-9cb8-4672068e23bf' );
    switch( strtolower($lmsDataJsonDecoded['driver1credit']) )
    {
      case 'average':
	$lmsDataJsonDecoded['driver1credit'] = '212176f7-76eb-4c68-9cab-3d2aace5346c';
      break;
      case 'excellent':
	$lmsDataJsonDecoded['driver1credit'] = 'c0142494-a23f-41a6-8166-fb80098fa44e';
      break;
      case 'good':
	$lmsDataJsonDecoded['driver1credit'] = 'ace4d9ee-8eb5-4db7-80af-7a9c5cbb4473';
      break;
      default:	// poor
	$lmsDataJsonDecoded['driver1credit'] = '53ac4bd0-56fd-4177-8c78-a8390bbac595';
      break;
    }

    $lmsDataJsonDecoded['driver1occupation'] = ( strtolower($lmsDataJsonDecoded['driver1occupation']) == 'unemployed'  ) ? '6c805ce7-db52-4cd2-a702-eb00b3d4ad21':'7e7c982c-a972-4d0f-859c-39d13acddbea';


    switch( strtolower($lmsDataJsonDecoded['driver1maritalstatus']) )
    {
      case 'divorced':
	$lmsDataJsonDecoded['driver1maritalstatus'] = 'e4e3e3b4-58a8-4fc2-993b-1a1447771a30';
      break;
      case 'married':
	$lmsDataJsonDecoded['driver1maritalstatus'] = '76ac1d57-1391-4686-a32d-d6bf7e394e5d';
      break;
      case 'separated':
	$lmsDataJsonDecoded['driver1maritalstatus'] = 'ef2ff4bf-0d45-47da-9742-cb4aaee5edaa';
      break;
      case 'single':
	$lmsDataJsonDecoded['driver1maritalstatus'] = 'f4159108-4fdd-4843-91bb-1e20a0e807ff';
      break;
      case 'widowed':
	$lmsDataJsonDecoded['driver1maritalstatus'] = '07f0c96a-d837-4809-8bbb-641c57ed1dca';
      break;
      default: // unknown
	 $lmsDataJsonDecoded['driver1maritalstatus'] = 'a43101d2-8878-4102-96a8-03747f427936';
      break;
    }

    switch( strtolower($lmsDataJsonDecoded['driver1edulevel']) )
    {
      case 'associates':
	$lmsDataJsonDecoded['driver1edulevel'] = 'f9224bec-dc80-4e75-ae8b-97da3a01ee72';
      break;
      case 'bachelors':
	$lmsDataJsonDecoded['driver1edulevel'] = 'f32e5638-2543-4c60-a4ed-e3a9a9943f60';
      break;
      case 'doctorate':
	$lmsDataJsonDecoded['driver1edulevel'] = 'cc350328-d648-4a72-b79a-bc24742e60e2';
      break;
      case 'ged':
	$lmsDataJsonDecoded['driver1edulevel'] = '417b16ae-15c7-405f-8b2e-05e4935b7852';
      break;
      case 'high school diploma':
	$lmsDataJsonDecoded['driver1edulevel'] = '1ae297ba-67fd-4d07-9edc-097fc53eeb9b';
      break;
      case 'masters':
	$lmsDataJsonDecoded['driver1edulevel'] = 'e0d1b1af-1cd1-4a63-9e43-9794aae63c14';
      break;
      case 'other non profess degree':
	$lmsDataJsonDecoded['driver1edulevel'] = 'bfbf3ac8-dc9d-4825-b5bf-91826dc0d3c0';
      break;
      case 'other professional degree':
	$lmsDataJsonDecoded['driver1edulevel'] = '878d978d-c489-4e95-95c7-75b21edd2a18';
      break;
      case 'some colleg':
	$lmsDataJsonDecoded['driver1edulevel'] = '21dc0c55-3c7e-410b-bc7f-28d2ed7eb6cb';
      break;
      case 'some or no high school':
	$lmsDataJsonDecoded['driver1edulevel'] = '2c9e041b-8156-4625-8baa-91b9d472e915';
      break;
      case 'trade vocational school':
	$lmsDataJsonDecoded['driver1edulevel'] = '1aea55a0-1cd2-4feb-9067-b4d19723259f';
      break;
      default:
	$lmsDataJsonDecoded['driver1edulevel'] = 'b38cc5ff-04c7-47af-98c9-363e6101bd8b';
      break;
    }




    $contactData = generateContactXml($lmsDataJsonDecoded);
    $response = executeCurlRequest($config['contactUrl'], $contactData);
    preg_match('/(([a-zA-Z0-9]){8})-(([a-zA-Z0-9]){4})-(([a-zA-Z0-9]){4})-(([a-zA-Z0-9]){4})-(([a-zA-Z0-9]){12})/', $response, $matches);
    $lmsDataJsonDecoded['contactDataGUID'] = $matches[0];
echo "<strong style='color:#990000'>GUID from Contact Collection: </strong>";
var_dump($matches[0]);
echo "<br>";
echo "<strong style='color:#006600'>XML input for Contact Collection: </strong>";
var_dump( htmlentities($contactData) );
echo "<br><br>";



    switch( strtoupper($lmsDataJsonDecoded['vehicle1make']) )
    {
      case 'ACURA':
	$lmsDataJsonDecoded['vehicle1make'] = '9188a5e0-a920-49b7-9a4e-c672a8f90dc3';
      break;
      case 'ASTON MARTIN':
	$lmsDataJsonDecoded['vehicle1make'] = '13889d2f-3091-4808-bc52-a3b655652609';
      break;
      case 'AUDI':
	$lmsDataJsonDecoded['vehicle1make'] = '9c6e88e0-8a6c-41ba-84be-d754e29cc92b';
      break;
      case 'AZURE DYNAMICS':
	$lmsDataJsonDecoded['vehicle1make'] = '293bbcf4-af28-41d1-8766-872dededcaad';
      break;
      case 'BMW':
	$lmsDataJsonDecoded['vehicle1make'] = '066f97d2-f615-4c14-a3a6-295959059b9d';
      break;
      case 'BUICK':
	$lmsDataJsonDecoded['vehicle1make'] = 'b6ff2363-2510-4e5c-b963-00084cee286e';
      break;
      case 'CADILLAC':
	$lmsDataJsonDecoded['vehicle1make'] = '5f055f76-38df-4128-9193-c5b9c4c0a1b4';
      break;
      case 'CHEVROLET':
	$lmsDataJsonDecoded['vehicle1make'] = '4580ba0f-8425-49a3-8ef8-ac117de1b059';
      break;
      case 'CHRYSLER':
	$lmsDataJsonDecoded['vehicle1make'] = '2fb530f2-b84e-4109-8361-5f76cbe1e3b3';
      break;
      case 'DODGE':
	$lmsDataJsonDecoded['vehicle1make'] = '49baf27e-12d4-442b-911a-03927c16d378';
      break;
      case 'FERRARI':
	$lmsDataJsonDecoded['vehicle1make'] = 'ad2909e4-3946-4628-af7a-ebbcad5f0769';
      break;
      case 'FIAT':
	$lmsDataJsonDecoded['vehicle1make'] = '3f0f9ca0-d2dd-495f-bfa4-e2048eaf01f7';
      break;
      case 'FORD':
	$lmsDataJsonDecoded['vehicle1make'] = 'a2cbd3e4-fa05-4e2a-a38c-7fc8fe1fbd78';
      break;
      case 'GMC':
	$lmsDataJsonDecoded['vehicle1make'] = 'ac23264c-a747-4166-b795-8df287667f94';
      break;
      case 'HONDA':
	$lmsDataJsonDecoded['vehicle1make'] = '7454989b-39e1-46e9-afbd-d9d5074d9ea2';
      break;
      case 'HYUNDAI':
	$lmsDataJsonDecoded['vehicle1make'] = 'c388ed57-ae55-4c6a-89e3-057f2fb899f9';
      break;
      case 'INFINITI':
	$lmsDataJsonDecoded['vehicle1make'] = '8bc09c5c-fbf8-4b64-b1ba-0d02c297b988';
      break;
      case 'JAGUAR':
	$lmsDataJsonDecoded['vehicle1make'] = '1f4098d1-fb05-44fe-bccd-bd308eeeca1c';
      break;
      case 'JEEP':
	$lmsDataJsonDecoded['vehicle1make'] = 'b4451646-1be5-499f-86f9-901a86e54ede';
      break;
      case 'KIA':
	$lmsDataJsonDecoded['vehicle1make'] = '9b955728-16dc-42ed-a147-ed163a7634ea';
      break;
      case 'LAND ROVER':
	$lmsDataJsonDecoded['vehicle1make'] = 'cb76e8c8-bba2-48b0-895a-04c25cef5dce';
      break;
      case 'LEXUS':
	$lmsDataJsonDecoded['vehicle1make'] = '8bc2fd6c-50e6-43eb-ad04-f8e253e154f5';
      break;
      case 'LINCOLN':
	$lmsDataJsonDecoded['vehicle1make'] = 'd45b8df2-1a09-406b-9e0f-e5feca33e4f0';
      break;
      case 'LOTUS':
	$lmsDataJsonDecoded['vehicle1make'] = 'a191c656-02bf-49ab-bda7-900789920bae';
      break;
      case 'MASERATI':
	$lmsDataJsonDecoded['vehicle1make'] = '38819946-5876-44e9-9152-5882ddb0ad24';
      break;
      case 'MAZDA':
	$lmsDataJsonDecoded['vehicle1make'] = '10f7a97d-1de5-40b5-8bbf-f4018f316301';
      break;
      case 'MCLAREN':
	$lmsDataJsonDecoded['vehicle1make'] = 'e05cf4ae-a171-4bb2-9c07-5a8823b758c7';
      break;
      case 'MERCEDES-BENZ':
	$lmsDataJsonDecoded['vehicle1make'] = '38a4d018-e398-4c41-a8a6-b7e0ae93b728';
      break;
      case 'MERCEDEZ BENZ':
	$lmsDataJsonDecoded['vehicle1make'] = '0d2c87ec-97a0-4278-830e-e0037b306781';
      break;
      case 'MERCURY':
	$lmsDataJsonDecoded['vehicle1make'] = '2a1ef2db-f2f3-4f6e-a54f-4e4976917269';
      break;
      case 'MINI':
	$lmsDataJsonDecoded['vehicle1make'] = 'bf834c18-af25-409e-8123-257eb03b435e';
      break;
      case 'MITSUBISHI':
	$lmsDataJsonDecoded['vehicle1make'] = '6d7f4fc8-479c-4879-84a1-8b82c17211d5';
      break;
      case 'NISSAN':
	$lmsDataJsonDecoded['vehicle1make'] = '63db129e-b0b6-42ea-b430-6e43311c8689';
      break;
      case 'OLDSMOBILE':
	$lmsDataJsonDecoded['vehicle1make'] = '7639c283-5241-4562-93f4-c3813984c6ef';
      break;
      case 'PLYMOUTH':
	$lmsDataJsonDecoded['vehicle1make'] = 'd29f59b9-71bf-4306-8848-9a096165e2e0';
      break;
      case 'PONTIAC':
	$lmsDataJsonDecoded['vehicle1make'] = 'e2289380-4afb-4794-9e0a-510179bdc21f';
      break;
      case 'PORSCHE':
	$lmsDataJsonDecoded['vehicle1make'] = '4f839a11-8823-4494-a791-623fdcf96fd4';
      break;
      case 'RAM':
	$lmsDataJsonDecoded['vehicle1make'] = 'b2574d9f-c2ac-44fa-864a-5b38879d4bb7';
      break;
      case 'SAAB':
	$lmsDataJsonDecoded['vehicle1make'] = '7f6ac617-af26-4e20-ae17-43f8accef257';
      break;
      case 'SATURN':
	$lmsDataJsonDecoded['vehicle1make'] = '31e7e352-e638-4ce8-8dfd-fbf5cfb76700';
      break;
      case 'SMART':
	$lmsDataJsonDecoded['vehicle1make'] = '5d6f384c-7bb1-4850-baca-4d1633d8e684';
      break;
      case 'SUBARU':
	$lmsDataJsonDecoded['vehicle1make'] = 'd6e0347b-a077-4db5-9103-cc125958b02e';
      break;
      case 'SUZUKI':
	$lmsDataJsonDecoded['vehicle1make'] = 'ff9abf46-b99c-4348-b9e3-3c12efa99ab7';
      break;
      case 'TESLA':
	$lmsDataJsonDecoded['vehicle1make'] = '3d3520e7-e46c-49e7-9537-b1e187f9df81';
      break;
      case 'TOYOTA':
	$lmsDataJsonDecoded['vehicle1make'] = 'd6c5582e-6198-4f10-839d-088f394fed1b';
      break;
      case 'VOLKSWAGEN':
	$lmsDataJsonDecoded['vehicle1make'] = '1835d04e-0849-4a2e-a4fe-26b177d38632';
      break;
      default: // 'VOLVO'
	$lmsDataJsonDecoded['vehicle1make'] = '352adab7-2644-4737-a41b-001cbf855dbf';
      break;
    }
    switch( $lmsDataJsonDecoded['vehicle1ownership'] )
    {
      case 'Leased':
	$lmsDataJsonDecoded['vehicle1ownership'] = 'd5f571ef-5bcc-416b-b97a-379bc45a7aff';
      break;
      case 'Owned';
	$lmsDataJsonDecoded['vehicle1ownership'] = '2d6ea226-93d8-4380-8c24-679da4d66862';
      break;
      default:
	$lmsDataJsonDecoded['vehicle1ownership'] = '8ededa22-2a32-417a-b3ee-35ec3855a518';
      break;
    }

    switch( $lmsDataJsonDecoded['vehicle1primaryUse'] )
    {
      case 'Commute':
	 $lmsDataJsonDecoded['vehicle1primaryUse'] = '62c9a8ce-56c5-4942-be03-433f6e6dda93';
      break;
      case 'Business':
	 $lmsDataJsonDecoded['vehicle1primaryUse'] = '5a53233c-1a87-464c-854d-8610009b9eaa';
      break;
      default:
	 $lmsDataJsonDecoded['vehicle1primaryUse'] = '7f82b1b4-6661-4201-b779-aabe9c61da57';
      break;
    }
 
    switch( $lmsDataJsonDecoded['desiredcomprehensivedeductible'] )
    {
      case 0:
	$lmsDataJsonDecoded['desiredcomprehensivedeductible'] = 'afccb8c1-fc34-407a-a994-4bc845d12a36';
      break;
      case 500:
	$lmsDataJsonDecoded['desiredcomprehensivedeductible'] = '7f4e8e90-b410-454c-a058-d22a6cc521ab';
      break;
      case 1000:
	$lmsDataJsonDecoded['desiredcomprehensivedeductible'] = '1fbb771a-f261-4866-b857-a5fb620b8bb6';
      break;
      default:
	$lmsDataJsonDecoded['desiredcomprehensivedeductible'] = 'cd8aaa81-6d8d-4535-aafa-ba803e57cc79';
      break;
    }

    switch( $lmsDataJsonDecoded['desiredcollisiondeductible'] )
    {
      case 0:
	$lmsDataJsonDecoded['desiredcollisiondeductible'] = 'c26c1128-f696-41f1-ad2f-ed9ea373db47';
      break;
      case 500:
	$lmsDataJsonDecoded['desiredcollisiondeductible'] = 'cad2c3d3-74a5-46cd-9807-102286773d2e';
      break;
      case 1000:
	$lmsDataJsonDecoded['desiredcollisiondeductible'] = 'c96732a7-b3ad-4eba-a8b9-6b9df8e4e09a';
      break;
      default:
	$lmsDataJsonDecoded['desiredcollisiondeductible'] = '2b14627b-c296-4f2a-8387-8f8e98c0f76f';
      break;
    }

    switch( strtolower($lmsDataJsonDecoded['vehicle1leased']) )
    {
      case 'owned':
	 $lmsDataJsonDecoded['vehicle1leased'] = 0;
      break;
      default:
	 $lmsDataJsonDecoded['vehicle1leased'] = 1;
      break;
    }


    $propertiesData = generatePropertiesXML($lmsDataJsonDecoded);
    $response = executeCurlRequest($config['propertiesUrl'], $propertiesData);
    preg_match('/(([a-zA-Z0-9]){8})-(([a-zA-Z0-9]){4})-(([a-zA-Z0-9]){4})-(([a-zA-Z0-9]){4})-(([a-zA-Z0-9]){12})/', $response, $matches);
    $lmsDataJsonDecoded['propertiesDataGUID'] = $matches[0];
    $propertiesGUID = $matches[0];
echo "<strong style='color:#990000'>GUID from Properties Collection: </strong>";
var_dump($matches[0]);
echo "<br>";
echo "<strong style='color:#006600'>XML input for Properties Collection: </strong>";
var_dump( htmlentities($propertiesData) );
echo "<br><br>";


    switch( $lmsDataJsonDecoded['bodilyinjury'] )
    {
      case '30':
	$lmsDataJsonDecoded['bodilyinjury'] 		   = '4f1b0978-04d4-4355-a3b5-074f01f2fc30';
	$lmsDataJsonDecoded['bodilyinjuryPerIncident'] = '03faf26d-4391-4e1d-87eb-c8f60e5a7a76';
	$lmsDataJsonDecoded['propertyDamageLimits']    = '5bf2a35a-b07e-4348-8247-54400ceecaaf';
      break;
      case '50':
	$lmsDataJsonDecoded['bodilyinjury'] 		   = '9d9979ea-e236-487a-bc60-f19774626c45';
	$lmsDataJsonDecoded['bodilyinjuryPerIncident'] = 'd248e18f-0eed-4bc5-a35c-3af536bad0b8';
	$lmsDataJsonDecoded['propertyDamageLimits']    = '93327617-7a93-4881-9020-be27cbdac32c';
      break;
      case '100':
	$lmsDataJsonDecoded['bodilyinjury'] 		   = 'd248e18f-0eed-4bc5-a35c-3af536bad0b8';
	$lmsDataJsonDecoded['bodilyinjuryPerIncident'] = '48ea207a-5452-4451-940c-4656b21c7bce';
	$lmsDataJsonDecoded['propertyDamageLimits']    = '07b23e09-eaa1-4b70-971b-c12666122158';
      break;
      case '250':
	$lmsDataJsonDecoded['bodilyinjury'] 		   = 'd3c0acb1-b88b-4b7b-9abd-889c8f3d0755';
	$lmsDataJsonDecoded['bodilyinjuryPerIncident'] = '0b21c048-465c-40a1-8c9f-6088b07f3a4c';
	$lmsDataJsonDecoded['propertyDamageLimits']    = '2c9e834a-222a-4ddd-85f4-b56f5173ed70';
      break;
      default: // 300
	$lmsDataJsonDecoded['bodilyinjury'] 		   = '48ea207a-5452-4451-940c-4656b21c7bce';
	$lmsDataJsonDecoded['bodilyinjuryPerIncident'] = '0b21c048-465c-40a1-8c9f-6088b07f3a4c';
	$lmsDataJsonDecoded['propertyDamageLimits']    = '1a158992-74b5-4e89-ab16-d4eade9cce08';
      break;
    }

    switch( strtolower($lmsDataJsonDecoded['desiredcoveragetype']) )
    {
      case 'basic':
	$lmsDataJsonDecoded['desiredcoveragetype'] = '5f9d176b-24ce-4006-9e7f-cf521a0f17b9';
      break;
      case 'standard':
	$lmsDataJsonDecoded['desiredcoveragetype'] = '1479f62f-890e-493e-9bf8-6bde38d23d67';
      break;
      case 'stateminimum':
	$lmsDataJsonDecoded['desiredcoveragetype'] = 'ca6c3c2d-1322-4674-9c2e-753005271bda';
      break;
      default: // Superior
	$lmsDataJsonDecoded['desiredcoveragetype'] = 'ab460f64-5f3a-4590-a701-9784c1b71fce';
      break;
    }

    $opportunitiesXML = generateOpportunitiesXML($lmsDataJsonDecoded, $propertiesGUID);
    $response = executeCurlRequest($config['opportunityUrl'], $opportunitiesXML);
echo "<strong style='color:#990000'>GUID from Opportunity Collection: </strong>";
    preg_match('/(([a-zA-Z0-9]){8})-(([a-zA-Z0-9]){4})-(([a-zA-Z0-9]){4})-(([a-zA-Z0-9]){4})-(([a-zA-Z0-9]){12})/', $response, $matches);
    $lmsDataJsonDecoded['opportunityDataGUID'] = $matches[0];
var_dump($matches[0]);
echo "<br>";
echo "<strong style='color:#006600'>XML input for Opportunities Collection: </strong>";
var_dump( htmlentities($opportunitiesXML) );
echo "<br><br>";
var_dump($response);

}
catch (Exception $e)
{
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}


function generateAccountXml($lead = array()) {
    $account_xml = '<?xml version="1.0" encoding="utf-8"?>
        <entry xmlns="http://www.w3.org/2005/Atom" xmlns:d="http://schemas.microsoft.com/ado/2007/08/dataservices" xmlns:m="http://schemas.microsoft.com/ado/2007/08/dataservices/metadata">
            <category scheme="http://schemas.microsoft.com/ado/2007/08/dataservices/scheme" term="Terrasoft.Configuration.Account"/>
            <content type="application/xml">
                <m:properties>
                    <d:LeadFileId>'.$lead['universal_leadid'].'</d:LeadFileId>
                    <d:LeadVendorSourceID>'.$lead['vendorid'].'</d:LeadVendorSourceID>
                    <d:TypeId m:type="Edm.Guid">dbe895ac-686d-41b2-857f-c01e7b594d3b</d:TypeId>
                    <d:SourceTypeLookupId m:type="Edm.Guid">bf69bfcd-ded8-4b3a-ad2b-ced8a9a37569</d:SourceTypeLookupId>
                    <d:SourceNameId m:type="Edm.Guid">8b72bf82-a85b-4d41-8b6e-bc57e9f4a663</d:SourceNameId>
                    <d:Name>'.$lead['name']. ' ' .$lead['lastname'].' Household</d:Name>
                    <d:NamedInsuredFirstName>'.$lead['name'].'</d:NamedInsuredFirstName>
                    <d:NamedInsuredLastName>'.$lead['lastname'].'</d:NamedInsuredLastName>
                    <d:Phone>'.$lead['homephone_area']. '-' . $lead['homephone_prefix'] . $lead['homephone_suffix']. '</d:Phone>
                    <d:EmailAddressAt>'.$lead['emailaddress'].'</d:EmailAddressAt>
                    <d:Address>'.$lead['address'].'</d:Address>
                    <d:CityNonLookup>'.$lead['city'].'</d:CityNonLookup>
                    <d:Zip>'.$lead['zip'].'</d:Zip>
                    <d:RegionId m:type="Edm.Guid">beae2282-f36b-1410-fd98-00155d043204</d:RegionId>
                </m:properties>
            </content>
        </entry>';
    
    return $account_xml;
}

function generateContactXml($lead = array()) {
    $contact_xml = '<?xml version="1.0" encoding="utf-8"?>
		    <entry xmlns="http://www.w3.org/2005/Atom" xmlns:d="http://schemas.microsoft.com/ado/2007/08/dataservices" xmlns:m="http://schemas.microsoft.com/ado/2007/08/dataservices/metadata">
			<category scheme="http://schemas.microsoft.com/ado/2007/08/dataservices/scheme" term="Terrasoft.Configuration.Contact"/>
			<content type="application/xml">
			    <m:properties>
    			<d:TypeId>5b753c6b-e2f9-4915-9084-84f013b7296d</d:TypeId>
				<d:Name>'.$lead['name'].' '.$lead['lastname'].'</d:Name>
				<d:ContactFirstName>'.$lead['name'].'</d:ContactFirstName>
				<d:ContactLastName>'.$lead['lastname'].'</d:ContactLastName>
				<d:Phone>'.$lead['homephone_area']. '-' . $lead['homephone_prefix'] . $lead['homephone_suffix']. '</d:Phone>
				<d:EmailAddressAt>'.$lead['emailaddress'].'</d:EmailAddressAt>
				<d:Address>'.$lead['address'].'</d:Address>
				<d:CityNonLookup>'.$lead['city'].'</d:CityNonLookup>
				<d:RegionId m:type="Edm.Guid">beae2282-f36b-1410-fd98-00155d043204</d:RegionId>
				<d:Zip>'.$lead['zip'].'</d:Zip>
				<d:PersonID>1</d:PersonID>
				<d:GenderId m:type="Edm.Guid">'.$lead['driver1gender'].'</d:GenderId>
				<d:BirthDate>'.$lead['driver1dob_year'].'-'.$lead['driver1dob_day'].'-'.$lead['driver1dob_month'].'</d:BirthDate>
				<d:AgeValue>'.$lead['driver1age'].'</d:AgeValue>
				<d:StatusId m:type="Edm.Guid">'.$lead['driver1maritalstatus'].'</d:StatusId>
				<d:EducationLevelId m:type="Edm.Guid">'.$lead['driver1edulevel'].'</d:EducationLevelId>
				<d:OccNameLookupColumnId m:type="Edm.Guid">'.$lead['driver1occupation'].'</d:OccNameLookupColumnId>
				<d:CurrentResStatLookupColumnId m:type="Edm.Guid">'.$lead['currentresidence'].'</d:CurrentResStatLookupColumnId>
				<d:DriverYearsAtRes>'.$lead['driver1yearsatresidence'].'</d:DriverYearsAtRes>
				<d:CreditSelfRatingLookupColumnId m:type="Edm.Guid">'.$lead['driver1credit'].'</d:CreditSelfRatingLookupColumnId>
				<d:SR22>'.$lead['driver1sr22'].'</d:SR22>
				<d:IncidentBoolean>0</d:IncidentBoolean>
				<d:AccountId m:type="Edm.Guid">'.$lead['accountDataGUID'].'</d:AccountId>
				<d:RelationToAccountId m:type="Edm.Guid">31a9c72e-529c-423f-aa0b-a41d91979592</d:RelationToAccountId>
				<d:RelationshipTypeToInsuredId m:type="Edm.Guid">59260570-5b69-460c-aaff-78de68635347</d:RelationshipTypeToInsuredId>
			    </m:properties>
			</content>
		    </entry>';

//var_dump($contact_xml);
    return $contact_xml;
}


function generatePropertiesXML($lead = array())
{
    $propertiesXML = '<?xml version="1.0" encoding="utf-8"?>
		      <entry xmlns="http://www.w3.org/2005/Atom" xmlns:d="http://schemas.microsoft.com/ado/2007/08/dataservices" xmlns:m="http://schemas.microsoft.com/ado/2007/08/dataservices/metadata">
		      <category scheme="http://schemas.microsoft.com/ado/2007/08/dataservices/scheme" term="Terrasoft.Configuration.ItemsOfProperty"/>
		      <content type="application/xml">
			      <m:properties>
				    <d:PropertyName>'.$lead['firstname'].' '.$lead['lastname'].' Household Auto</d:PropertyName>
				    <d:PropertyCategoryLookupId m:type="Edm.Guid">44904d7e-6395-411d-8847-03d5e67e5c44</d:PropertyCategoryLookupId>
				    <d:PropertyTypeLookupId m:type="Edm.Guid">efb45f30-b3b9-479a-a2f6-e688f5df3a97</d:PropertyTypeLookupId>
				    <d:CustomerHousholdLookupId m:type="Edm.Guid">'.$lead['accountDataGUID'].'</d:CustomerHousholdLookupId>
				    <d:NamedInsuredLookupId m:type="Edm.Guid">'.$lead['contactDataGUID'].'</d:NamedInsuredLookupId>
				    <d:VehYearColumn1>'.$lead['vehicle1year'].'</d:VehYearColumn1>
				    <d:VehMakeLookup1Id m:type="Edm.Guid">'.$lead['vehicle1make'].'</d:VehMakeLookup1Id>
				    <d:VehModelColumn1>'.$lead['vehicle1model'].'</d:VehModelColumn1>
				    <d:VehTrimColumn1>'.$lead['vehicle1trim'].'</d:VehTrimColumn1>
				    <d:LeasedBoolean1>'.$lead['vehicle1leased'].'</d:LeasedBoolean1>
				    <d:OwnershipLookup1Id m:type="Edm.Guid">'.$lead['vehicle1ownership'].'</d:OwnershipLookup1Id>
				    <d:TimeAtResColumn1>'.$lead['yearsatresidence'].'</d:TimeAtResColumn1>
				    <d:PrimaryUseLookup1Id m:type="Edm.Guid">'.$lead['vehicle1primaryUse'].'</d:PrimaryUseLookup1Id>
				    <d:MilesDrivenColumn1>'.$lead['vehicle1commuteAvgMileage'].'</d:MilesDrivenColumn1>
				    <d:AnnualMilesColumn1>'.$lead['vehicle1annualMileage'].'</d:AnnualMilesColumn1>
				    <d:CompDedLookup1Id m:type="Edm.Guid">'.$lead['desiredcomprehensivedeductible'].'</d:CompDedLookup1Id>
				    <d:CollDedLookup1Id m:type="Edm.Guid">'.$lead['desiredcollisiondeductible'].'</d:CollDedLookup1Id>
			      </m:properties>
		      </content>
		      </entry>';

    return $propertiesXML;
}

function generateOpportunitiesXML($lead = array(), $propertyGUID)
{
    $opportunitiesXML = '<?xml version="1.0" encoding="utf-8"?>
			    <entry xmlns="http://www.w3.org/2005/Atom" xmlns:d="http://schemas.microsoft.com/ado/2007/08/dataservices" xmlns:m="http://schemas.microsoft.com/ado/2007/08/dataservices/metadata">
				<category scheme="http://schemas.microsoft.com/ado/2007/08/dataservices/scheme" term="Terrasoft.Configuration.Opportunity"/>
				<content type="application/xml">
				    <m:properties>
				      <d:Title>'.$lead['firstname'].' '.$lead['lastname'].' Household Auto Quote</d:Title>
				      <d:TypeId m:type="Edm.Guid">a2532764-d14c-48a5-83a3-4123c9c04bae</d:TypeId>
				      <d:StageId m:type="Edm.Guid">cd4937b4-3553-4f14-8186-8751272fc748</d:StageId>
				      <d:AccountId m:type="Edm.Guid">'.$lead['accountDataGUID'].'</d:AccountId>
				      <d:ContactId m:type="Edm.Guid">'.$lead['contactDataGUID'].'</d:ContactId>
				      <d:DesiredCovgLvlLUColumnId m:type="Edm.Guid">'.$lead['desiredcoveragetype'].'</d:DesiredCovgLvlLUColumnId>
				      <d:PriorBILimitPerPersonId m:type="Edm.Guid">'.$lead['bodilyinjury'].'</d:PriorBILimitPerPersonId>
				      <d:PriorBILimitPerIncidentId m:type="Edm.Guid">'.$lead['bodilyinjuryPerIncident'].'</d:PriorBILimitPerIncidentId>
				      <d:PriorPDlimitsLookupId m:type="Edm.Guid">'.$lead['propertyDamageLimits'].'</d:PriorPDlimitsLookupId>
				      <d:PolicyExDate>'.$lead['currentpolicyexpiration'].'</d:PolicyExDate>
				    </m:properties>
				</content>
			    </entry>';

// <d:PriorBILimitPerIncidentId m:type="Edm.Guid">4f1b0978-04d4-4355-a3b5-074f01f2fc30</d:PriorBILimitPerIncidentId>
    
    return $opportunitiesXML;
}

function executeCurlRequest($url, $post) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_setopt($ch, CURLOPT_USERPWD, 'APItest:123TEST');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/atom+xml")
	       );
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        $curlError = 'Curl error: ' . curl_error($ch);
        return $curlError;
    }

    curl_close($ch);

    return $response;
}


