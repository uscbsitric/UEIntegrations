<?php

function MakeRequest($curlParams = array())
{
  $fieldsString = '';
  $prePingUrl = '';

  foreach($curlParams as $key => $value)
  {
    if($key === 'url')
    {
      $prePingUrl = $value;
    }

    $fieldsString .= $key . '=' . $value . '&';
  }

  $fieldsString = rtrim($fieldsString, "&");

  $ch = curl_init($prePingUrl);
  curl_setopt($ch, CURLOPT_URL, $prePingUrl);
  curl_setopt($ch, CURLOPT_POST, TRUE);
  //curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  //curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/x-www-form-urlencoded; charset=UTF-8"));
  curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: text/plain",
					    )
	     );
  curl_setopt($ch, CURLOPT_POSTFIELDS, $fieldsString);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false );

  $result = curl_exec($ch);
  $info	 = curl_getinfo($ch);

  return $result;
}


$xmlPayload = '<?xml version="1.0" encoding="UTF-8"?>
		<QuoteWizardData Version="1.0" xsi:noNamespaceSchemaLocation="QW.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
		    <QuoteRequest DateTime="2001-12-17T09:30:47Z">
			<VendorData>
			  <LeadID>12345</LeadID>
			  <PubID>12345</PubID>
			  <SourceID>12345</SourceID>
			  <SubID>12345</SubID>
			  <GroupID>12345</GroupID>
			  <LeadType>ShortForm</LeadType>
			  <SourceIPAddress>255.255.255.255</SourceIPAddress>
			  <DateLeadReceived>1967-08-13</DateLeadReceived>
			</VendorData>
			<DistributionDirectives>
			  <DistributionDirective LeadDistributionCap="5">
				  <Entity Type="Company" Action="Exclude">AllState</Entity>
				  <Entity Type="Company" Action="Exclude">StateFarm</Entity>
				  <Entity Type="Company" Action="Exclude">Prudent</Entity>
				  <Entity Type="Company" Action="Include">Farmers</Entity>
				  <Entity Type="Agent" Action="Include">12345</Entity>
			  </DistributionDirective>
			</DistributionDirectives>
			<AutoInsurance>
			  <Contact>
			    <FirstName>Bob</FirstName>
			    <LastName>Jones</LastName>
			    <Address1>111 Main Street</Address1>
			    <Address2>Apt. 2</Address2>
			    <City>Tacoma</City>
			    <County>Pierce</County>
			    <State>WA</State>
			    <ZIPCode>98401</ZIPCode>
			    <EmailAddress>email@email.com</EmailAddress>
			    <PhoneNumbers>
			      <PrimaryPhone>
				<PhoneNumberValue>206-912-5555</PhoneNumberValue>
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
			    <Driver MaritalStatus="Single" RelationshipToApplicant="Self" Gender="Male">
			      <FirstName>Bob</FirstName>
			      <LastName>Jones</LastName>
			      <BirthDate>1967-08-13</BirthDate>
			      <State>WA</State>
			      <LicenseNumber>123456789</LicenseNumber>
			      <AgeLicensed>16</AgeLicensed>
			      <LicenseStatus>Valid</LicenseStatus>
			      <LicenseEverSuspendedRevoked>Yes</LicenseEverSuspendedRevoked>
			      <Occupation Name="AdministrativeClerical">
				<YearsInField>1</YearsInField>
			      </Occupation>
			      <HighestLevelOfEducation>
				<Education AtHomeStudent="Yes" HighestDegree="SomeOrNoHighSchool">
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
			      <RequiresSR22Filing>Yes</RequiresSR22Filing>
			      <CreditRating Bankruptcy="Yes" SelfRating="Excellent" Repossessions="Yes"/>
			    </Driver>
			  </Drivers>
			  <AutoInsuranceQuoteRequest>
			    <Vehicles>
			      <Vehicle>
				<Year>2002</Year>
				<Make>Toyota</Make>
				<Model>Prius</Model>
				<Submodel>Prius-Sedan 4 Door</Submodel>
				<LocationParked>No Cover</LocationParked>
				<OwnedOrLeased>Owned</OwnedOrLeased>
				<AntitheftFeatures>Alarm</AntitheftFeatures>
				<VINPrefix>JACDH58V0N</VINPrefix>
				<VehicleUse VehicleUseDescription="Commute_Work">
				  <AnnualMiles>5000</AnnualMiles>
				  <WeeklyCommuteDays>1</WeeklyCommuteDays>
				  <OneWayDistance>3</OneWayDistance>
				</VehicleUse>
			      </Vehicle>
			    </Vehicles>
			    <InsuranceProfile>
			      <RequestedPolicy>
				<CoverageType>Premium</CoverageType>
			      </RequestedPolicy>
			      <CurrentPolicy>
				<InsuranceCompany>
				  <CompanyName>American National Property and Casualty</CompanyName>
				</InsuranceCompany>
				<StartDate>2008-08-13</StartDate>
				<ExpirationDate>2013-08-13</ExpirationDate>
			      </CurrentPolicy>
			      <ContinuouslyInsuredSinceDate>1987-08-13</ContinuouslyInsuredSinceDate>
			    </InsuranceProfile>
			    <Comment>Comments</Comment>
			  </AutoInsuranceQuoteRequest>
			</AutoInsurance>
		    </QuoteRequest>
		</QuoteWizardData>';

//$prePingUrl = "https://test1.leadamplms.com/prospect/prospect/preping"; NOTE: this is not working, always returns FALSE
//$prePingUrl = "https://prod1.leadamplms.com/prospect/prospect/preping";
$prePingUrl = 'http://stage.quotewizard.com/LEADAPI/Services/InboundVendorServices.asmx';

// Pre Ping
$curlParams = array('url' => $prePingUrl,
		    'vendor' => 'UndergroundElephant',
		    'contractID' => '9690133E-2BA5-434B-A50F-A907DADE3CC5',
		    'quoteData' => htmlentities($xmlPayload),
		    'initialID' => '',
		    'format'	=> 'XML',
		    'pass'	=> 0
		    );

var_dump(MakeRequest($curlParams));
exit();


// Ping

?> 