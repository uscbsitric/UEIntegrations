<?php
error_reporting(E_ALL);
//require_once __DIR__.'/../postRequest.php';
//require_once __DIR__.'/../pingpostCommon.php';

/**
 * A post request to AIS
 *
 * @package PingPost
 * @copyright (c) 2013-2014 Underground Elephant
 * 
 */
class PostRequest_AIS{ // extends PostRequest {
    private static $url = 'http://stage.quotewizard.com/LEADAPI/Services/InboundVendorServices.asmx';

    public function getClosestWord($words = array(), $input)
    {
	// no shortest distance found, yet
	$shortest = -1;

	// loop through words to find the closest
	foreach ($words as $word) 
	{

	    // calculate the distance between the input word,
	    // and the current word
	    $lev = levenshtein($input, $word);

	    // check for an exact match
	    if ($lev == 0)
	    {

		// closest word is this one (exact match)
		$closest = $word;
		$shortest = 0;

		// break out of the loop; we've found an exact match
		break;
	    }

	    // if this distance is less than the next found shortest
	    // distance, OR if a next shortest word has not yet been found
	    if ($lev <= $shortest || $shortest < 0)
	    {
		// set the closest match, and shortest distance
		$closest  = $word;
		$shortest = $lev;
	    }
	}

	return ($shortest >= 0) ? ($closest) : (-1);
    }

    public function _getRequest() {
       // $id = $this->postObject->getLeadId();
        //$leadData = $this->postObject->getLeadPostData();
	
		$jsonStrng = '{"name":"Rebeca",
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
				      "lastname":"Pasillas",
				      "emailaddress":"beckypasillas@yahoo.com",
				      "address":"16904 New Pine Drive",
				      "city":"Hacienda Heights",
				      "_City":"Hacienda Heights",
				      "state":"CA","st":"CA",
				      "_State":"CA",
				      "zip":"91745",
				      "_PostalCode":"91745",
				      "homephone":"626-201-2360",
				      "ueid":"fbso_0517af506af937_ad1_pp_6",
				      "country_code":"1",
				      "universal_leadid":"4D0BD454-6B89-E940-EE4C-573CABF0D046",
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
				      "vehicle1commutedays":"4"}';
		$leadData = json_decode($jsonStrng, true);

        if(!isset($leadData['state']))
        {
            $leadData['state'] = (isset($leadData['st']) ? $leadData['st'] : '' );
        }

        if ($leadData['currentresidence'] == 'Own')
	{
            $currentresidence = 'OWNED';
        }
	else
	{
            $currentresidence = 'RENTD';
        }

	if( preg_match('/^(\d{3})(\d{3})(\d{4})$/', $leadData['homephone'], $matches) )
	{
	  $leadData['homephone'] = '';
	  $leadData['homephone'] = $matches[1] . '-' . $matches[2] . '-' . $matches[3];
	}

	$leadData['driver1maritalstatus'] = $this->getClosestWord($words = array('Married', 'Single'), $leadData['driver1maritalstatus']);

	$possibleOccupations = array('AdministrativeClerical', 'Architect', 'BusinessOwner', 'CertifiedPublicAccountant', 'Clergy', 'ConstructionTrades',
				     'Dentist', 'Disabled', 'Engineer', 'Homemaker', 'Lawyer', 'ManagerSupervisor', 'MilitaryE1', 'MilitaryE5', 'MilitaryOfficer',
				     'MilitaryOther', 'MinorNotApplicable', 'OtherNonTechnical', 'OtherTechnical', 'Physician', 'ProfessionalSalaried', 'Professor',
				     'Retail', 'Retired', 'SalesInside', 'SalesOutside', 'SchoolTeacher', 'Scientist', 'SelfEmployed', 'SkilledSemiSkilled', 'Student', 'Unemployed', 'Unknown'
				    );

	$leadData['driver1occupation'] = $this->getClosestWord($possibleOccupations, $leadData['driver1occupation']);

	if( $leadData['driver1occupation'] === -1 )
	{
	   $leadData['driver1occupation'] = 'Unknown';
	}
	
	$possibleEducations = array('SomeOrNoHighSchool', 'HighSchoolDiploma', 'GED', 'AssociateDegree', 'BachelorsDegree', 'MastersDegree', 'DoctorateDegree', 
				    'OtherProfessionalDegree', 'OtherNonProfessionalDegree', 'SomeCollege', 'TradeVocationalSchool', 'Unknown');
	
	$leadData['driver1edulevel'] = $this->getClosestWord($possibleEducations, $leadData['driver1edulevel']);

	if( $leadData['driver1edulevel'] === -1 )
	{
	  $leadData['driver1edulevel'] = 'Unknown';
	}

	$leadData['vehicle1leased'] = ('no' === $leadData['vehicle1leased']) ? 'Owned' : 'Leased';

	$possibleVehicleDescriptions = array('Commute_Work', 'Commute_School', 'Pleasure', 'Individual', 'Corporate', 'Government', 'Farm');
	
	$leadData['vehicle1primaryUse'] = $this->getClosestWord($possibleVehicleDescriptions, $leadData['vehicle1primaryUse']);

	if( $leadData['vehicle1primaryUse'] === -1 )
	{
	    $leadData['vehicle1primaryUse'] = 'Individual';
	}

	$possibleAnnualMiles = array('5000', '7500', '10000', '12500', '15000', '18000', '25000', '50000', '50001');
	
	$leadData['vehicle1annualMileage'] = $this->getClosestWord($possibleAnnualMiles, $leadData['vehicle1annualMileage']);

	if( $leadData['vehicle1annualMileage'] === -1 )
	{
	    $leadData['vehicle1annualMileage'] = '5000';
	}

	$possibleOneWayDistance = array('3', '5', '9', '19', '20', '51');

	$leadData['vehicle1distance'] = $this->getClosestWord($possibleOneWayDistance, $leadData['vehicle1distance'])

	if( $leadData['vehicle1distance'] === -1 )
	{
	    $leadData['vehicle1distance'] = '3';
	}
	
	$possibleCoverageType = array('Premium', 'Standard', 'Preferred', 'State_Min');

	$leadData['desiredcoveragetype'] = $this->getClosestWord($possibleCoverageType, $leadData['desiredcoveragetype']);

	if( $leadData['desiredcoveragetype'] === -1)
	{
	    $leadData['desiredcoveragetype'] = 'State_Min';
	}

	$xml	 = '<?xml version="1.0" encoding="UTF-8"?>
			<QuoteWizardData Version="1.0" xsi:noNamespaceSchemaLocation="QW.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
			  <QuoteRequest DateTime="2001-12-17T09:30:47Z">
			    <VendorData>
			      <LeadID>'.$leadData['universal_leadid'].'</LeadID>
			      <PubID>12345</PubID>
			      <SourceID>'.$leadData['sourcedeliveryid'].'</SourceID>
			      <SubID>12345</SubID>
			      <GroupID>12345</GroupID>
			      <LeadType>'.$leadData['leadtype'].'</LeadType>
			      <SourceIPAddress>'.$leadData['ipaddress'].'</SourceIPAddress>
			      <DateLeadReceived>1967-08-13</DateLeadReceived>
			    </VendorData>
			    <DistributionDirectives>
			      <DistributionDirective LeadDistributionCap="5">
				<Entity Type="Company" Action="Exclude">AllState</Entity>
				<Entity Type="Company" Action="Exclude">StateFarm</Entity>
				<Entity Type="Company" Action="Exclude">Prudent</Entity>
			      </DistributionDirective>
			    </DistributionDirectives>
			    <AutoInsurance>
			      <Contact>
				<FirstName>'.$leadData['firstname'].'</FirstName>
				<LastName>'.$leadData['lastname'].'</LastName>
				<Address1>'.$leadData['address'].'</Address1>
				<Address2>Apt. 2</Address2>
				<City>'.$leadData['city'].'</City>
				<County>Pierce</County>
				<State>'.$leadData['state'].'</State>
				<ZIPCode>'.$leadData['zip'].'</ZIPCode>
				<EmailAddress>'.$leadData['emailaddress'].'</EmailAddress>
				<PhoneNumbers>
				  <PrimaryPhone>
				    <PhoneNumberValue>'.$leadData['homephone'].'</PhoneNumberValue>
				  </PrimaryPhone>
				  <SecondaryPhone>
				    <PhoneNumberValue>206-912-5555</PhoneNumberValue>
				  </SecondaryPhone>
				</PhoneNumbers>
				<CurrentResidence ResidenceStatus="Own">
				  <OccupancyDate>1997-08-13</OccupancyDate>
				</CurrentResidence>
			      </Contact>
			      <Drivers>
				<Driver MaritalStatus="'.$leadData['driver1maritalstatus'].'" RelationshipToApplicant="Self" Gender="'.$leadData['driver1gender'].'">
				  <FirstName>'.$leadData['firstname'].'</FirstName>
				  <LastName>'.$leadData['lastname'].'</LastName>
				  <BirthDate>'.$leadData['driver1dob_year'] . '-' . $leadData['driver1dob_month'] . '-' . $leadData['driver1dob_day'].'</BirthDate>
				  <State>'.$leadData['state'].'</State>
				  <LicenseNumber>123456789</LicenseNumber>
				  <AgeLicensed>'.$leadData['driver1licenseage'].'</AgeLicensed>
				  <LicenseStatus>Valid</LicenseStatus>
				  <LicenseEverSuspendedRevoked>Yes</LicenseEverSuspendedRevoked>
				  <Occupation Name="'.$leadData['driver1occupation'].'">
				    <YearsInField>'.$leadData['driver1yearsemployed'].'</YearsInField>
				  </Occupation>
				  <HighestLevelOfEducation>
				    <Education AtHomeStudent="Yes" HighestDegree="'.$leadData['driver1edulevel'].'">
				      <GPA>4.0</GPA>
				    </Education>
				  </HighestLevelOfEducation>
				  <Incidents>
				    <Incident AtFault="Yes" Type="Intoxic">
				      <DUIState>AK</DUIState>
				      <IncidentDate>1987-08-13</IncidentDate>
				      <InsurancePaid>986.65</InsurancePaid>
				      <WhatDamaged>People</WhatDamaged>
				    </Incident>
				  </Incidents>
				  <RequiresSR22Filing>'.$leadData['driver1sr22'].'</RequiresSR22Filing>
				  <CreditRating Bankruptcy="Yes" SelfRating="'.$leadData['driver1credit'].'" Repossessions="Yes"/>
				</Driver>
			      </Drivers>
			      <AutoInsuranceQuoteRequest>
				<Vehicles>
				  <Vehicle>
				    <Year>'.$leadData['vehicle1year'].'</Year>
				    <Make>'.$leadData['vehicle1make'].'</Make>
				    <Model>'.$leadData['vehicle1model'].'</Model>
				    <Submodel>'.$leadData['vehicle1trim'].'</Submodel>
				    <LocationParked>'.$leadData['vehicle1garageType'].'</LocationParked>
				    <OwnedOrLeased>'.$leadData['vehicle1leased'].'</OwnedOrLeased>
				    <AntitheftFeatures>'.$leadData['vehicle1alarm'].'</AntitheftFeatures>
				    <VINPrefix>JACDH58V0N</VINPrefix>
				    <VehicleUse VehicleUseDescription="'.$leadData['vehicle1primaryUse'].'">
				      <AnnualMiles>'.$leadData['vehicle1annualMileage'].'</AnnualMiles>
				      <WeeklyCommuteDays>'.$leadData['vehicle1commutedays'].'</WeeklyCommuteDays>
				      <OneWayDistance>'.$leadData['vehicle1distance'].'</OneWayDistance>
				    </VehicleUse>
				  </Vehicle>
				</Vehicles>
				<InsuranceProfile>
				  <RequestedPolicy>
				    <CoverageType>'.$leadData['desiredcoveragetype'].'</CoverageType>
				  </RequestedPolicy>
				  <CurrentPolicy>
				    <InsuranceCompany>
				      <CompanyName>'.$leadData['CURRENTINSURANCECOMPANY'].'</CompanyName>
				    </InsuranceCompany>
				    <StartDate>2008-08-13</StartDate>
				    <ExpirationDate>2009-08-13</ExpirationDate>
				  </CurrentPolicy>
				  <ContinuouslyInsuredSinceDate>1987-08-13</ContinuouslyInsuredSinceDate>
				</InsuranceProfile>
				<Comment>Comments</Comment>
			      </AutoInsuranceQuoteRequest>
			    </AutoInsurance>
			  </QuoteRequest>
			</QuoteWizardData>
			';

        return $xml;
    }

    protected function _getTestRequest() {
        // for now our test uses same request
        return $this->getRequest();
    }

    protected function _executeRequest($abstractParams = array()) {
        try
        {
	    $xmlParams = array('vendor'     => 'UndergroundElephant',
			       'contractID' => '9690133E-2BA5-434B-A50F-A907DADE3CC5',
			       'quoteData'  => $this->_getRequest(),
			       'initialID'  => $abstractParams['initialID'],
			       'format'	    => 'xml',
			       'pass'	    => $abstractParams['pass']
			      );
            $client = new SoapClient(PostRequest_AIS::$url.'?wsdl');
            ////$header = new SoapHeader("http://www.aispskleads.com", 'AuthHeader', array('Username' => $this->config['username'], 'Password' => $this->config['password']));
            //$client->__setSoapHeaders($header);

            $response = $client->SubmitVendorLead($xmlParams);
        }
        catch (Exception $e)
        {
            $response = new stdClass();
        }

        return $response->UploadWebLeadResult;
    }

    protected function _executeTestRequest() {
        // TODO - no tests at the moment
        return '';
    }

    protected function _wasRequestSuccessful() {
        if (!$this->request) {
            throw new Exception('No request sent yet.');
        }

        if(strpos($this->response, '<Name>UploadWebLead</Name><StatusEnum>Succeeded</StatusEnum><StatusText>') !== false)
        {
            return true;
        }

        return false;
    }
}


  $PostRequest_AIS = new PostRequest_AIS();
  echo htmlentities( $PostRequest_AIS->_getRequest() );
?>