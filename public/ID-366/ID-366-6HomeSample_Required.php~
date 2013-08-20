<?php
error_reporting(E_ALL);
require_once __DIR__.'/../postRequest.php';
require_once __DIR__.'/../pingpostCommon.php';

/**
 * A post request to AIS
 *
 * @package PingPost
 * @copyright (c) 2013-2014 Underground Elephant
 * 
 */
class PostRequest_AIS extends PostRequest {
    private static $url = 'http://stage.quotewizard.com/LEADAPI/Services/InboundVendorServices.asmx';

    private static $maritalStatusMap = array(
        'single' => 'S',
        'married' => 'M',
        'divorced' => 'D',
        'separated' => 'P',
        'widowed' => 'W',
    );

    private static $ticketTypeMap = array(
        'speeding' => 'SPEED',
        'alcohol' => 'DUI',
        'other' => 'ADMOV',
    );

    private static $incidentDescriptionIndexMap = array(
        'speeding' => 'ticketSpeeding',
        'alcohol' => 'ticketAlcohol',
        'other' => 'ticketOther',
    );

    private static $incidentAccidentFaultMap = array(
        'yes' => 'ACCAF',
        'no' => 'ACCFN',
    );

    private static $relationshipTypeMap = array(
        'self' => 'SP',
        'spouse' => 'SP',
        'child' => 'CH',
        'sibling' => 'RE',
        'parent' => 'PA',
        'grandparent' => 'RE',
        'grandchild' => 'RE',
        'other' => 'RE',
    );

    public function _getRequest() {
        $id = $this->postObject->getLeadId();
        $leadData = $this->postObject->getLeadPostData();

        if(!isset($leadData['state']))
        {
            $leadData['state'] = (isset($leadData['st']) ? $leadData['st'] : '' );
        }

        if ($leadData['currentresidence'] == 'Own'){
            $currentresidence = 'OWNED';
        } else {
            $currentresidence = 'RENTD';
        }

        if($leadData['vehicle1leased'] == 'yes'){
            $vehicle1Ownership = 'LEASED';
        } else {
            $vehicle1Ownership = 'OWNED';
        }

        if(isset($leadData['vehicle2leased']))
            {
            if($leadData['vehicle2leased'] == 'yes'){
                $vehicle2Ownership = 'LEASED';
            } else {
                $vehicle2Ownership = 'OWNED';
            }
        }
        switch ($leadData['desiredcoveragetype']) {
            case 'SUPERIOR':
                $coverage = array(
                    array(
                        'CoverageCd' => 'BI',
                        'limits' => array(
                            array(
                                'FormatCurrencyAmt' => '250000',
                                'LimitAppliesToCd' => 'PerPerson'
                            ),
                            array(
                                'FormatCurrencyAmt' => '500000',
                                'LimitAppliesToCd' => 'PerAcc'
                            )
                        )
                    ),
                    array(
                        'CoverageCd' => 'PD',
                        'limits' => array(
                            array(
                                'FormatCurrencyAmt' => '50000',
                                'LimitAppliesToCd' => 'PropDam'
                            )
                        )
                    )
                );
                break;
            case 'STANDARD':
                $coverage = array(
                    array(
                        'CoverageCd' => 'BI',
                        'limits' => array(
                            array(
                                'FormatCurrencyAmt' => '100000',
                                'LimitAppliesToCd' => 'PerPerson'
                            ),
                            array(
                                'FormatCurrencyAmt' => '300000',
                                'LimitAppliesToCd' => 'PerAcc'
                            )
                        )
                    ),
                    array(
                        'CoverageCd' => 'PD',
                        'limits' => array(
                            array(
                                'FormatCurrencyAmt' => '50000',
                                'LimitAppliesToCd' => 'PropDam'
                            )
                        )
                    )
                );
                break;
            case 'BASIC':
                $coverage = array(
                    array(
                        'CoverageCd' => 'BI',
                        'limits' => array(
                            array(
                                'FormatCurrencyAmt' => '50000',
                                'LimitAppliesToCd' => 'PerPerson'
                            ),
                            array(
                                'FormatCurrencyAmt' => '100000',
                                'LimitAppliesToCd' => 'PerAcc'
                            )
                        )
                    ),
                    array(
                        'CoverageCd' => 'PD',
                        'limits' => array(
                            array(
                                'FormatCurrencyAmt' => '25000',
                                'LimitAppliesToCd' => 'PropDam'
                            )
                        )
                    )
                );
                break;
            case 'STATEMINIMUM':
                $coverage = array(
                    array(
                        'CoverageCd' => 'BI',
                        'limits' => array(
                            array(
                                'FormatCurrencyAmt' => '15000',
                                'LimitAppliesToCd' => 'PerPerson'
                            ),
                            array(
                                'FormatCurrencyAmt' => '30000',
                                'LimitAppliesToCd' => 'PerAcc'
                            )
                        )
                    ),
                    array(
                        'CoverageCd' => 'PD',
                        'limits' => array(
                            array(
                                'FormatCurrencyAmt' => '5000',
                                'LimitAppliesToCd' => 'PropDam'
                            )
                        )
                    )
                );
                break;
        }

        if(isset($leadData['driver1incident']) && $leadData['driver1incident'] == 'yes'){
            $driver1incident = array(
                'AccidentViolationCd' => '',
                'AccidentViolationDesc' => '',
                'AccidentViolationDt' => $leadData['driver1incident1incidentDateYear'].'-'.$leadData['driver1incident1incidentDateMonth'].'-'.'01',
            );

            switch($leadData['driver1incident1incidentType'])
            {
                case 'ticket':
                        $driver1incident['AccidentViolationCd'] = PostRequest_AIS::$ticketTypeMap['driver1incident1'.$leadData['driver1incident1ticketType']];
                        $driver1incident['AccidentViolationDesc'] = $leadData[PostRequest_AIS::$incidentDescriptionIndexMap[$leadData['driver1incident1ticketType']]];
                    break;

                case 'accident':
                    $driver1incident['AccidentViolationCd'] = PostRequest_AIS::$incidentAccidentFaultMap[$leadData['driver1incident1driverAtFault']];
                    $driver1incident['AccidentViolationDesc'] = $leadData['driver1incident1accident'];
                    break;

                default:
                    break;
            }
        }

        if(isset($leadData['driver2incident']) && $leadData['driver2incident'] == 'yes'){
            $driver2incident = array(
                'AccidentViolationCd' => '',
                'AccidentViolationDesc' => '',
                'AccidentViolationDt' => $leadData['driver2incident1incidentDateYear'].'-'.$leadData['driver2incident1incidentDateMonth'].'-'.'01',
            );

            switch($leadData['driver2incident1incidentType'])
            {
                case 'ticket':
                        $driver2incident['AccidentViolationCd'] = PostRequest_AIS::$ticketTypeMap[$leadData['driver2incident1ticketType']];
                        $driver2incident['AccidentViolationDesc'] = $leadData['driver2incident1'.PostRequest_AIS::$incidentDescriptionIndexMap[$leadData['driver2incident1ticketType']]];
                    break;

                case 'accident':
                    $driver2incident['AccidentViolationCd'] = PostRequest_AIS::$incidentAccidentFaultMap[$leadData['driver2incident1driverAtFault']];
                    $driver2incident['AccidentViolationDesc'] = $leadData['driver2incident1accident'];
                    break;

                default:
                    break;
            }
        }

        $homephone = $leadData['homephone_area'].$leadData['homephone_prefix'].$leadData['homephone_suffix'];

	$xmlPayload = '<?xml version="1.0" encoding="UTF-8"?>
		       <!--Sample XML file generated by XMLSpy v2009 sp1 (http://www.altova.com)-->
			<QuoteWizardData xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="C:\Development\Clients\QuoteWizard\XSD\QW.xsd">
			  <QuoteRequest DateTime="2001-12-17T09:30:47Z">
			    <HomeInsurance>
			      <Contact MaritalStatus="Single" Gender="Male">
				<FirstName>'.$leadData['firstname'].'</FirstName>
				<LastName>'.$leadData['lastname'].'</LastName>
				<Address1>'.$leadtype['address'].'</Address1>
				<City>'.$leadData['city'].'</City>
				<State>'.$leadData['state'].'</State>
				<ZIPCode>'.$leadData['zip'].'</ZIPCode>
				<BirthDate>'.$leadData['driver1dob_year'] . '-' . $leadData['driver1dob_month'] . '-' . $leadData['driver1dob_day'].'</BirthDate>
				<EmailAddress>'.$leadData['emailaddress'].'</EmailAddress>
				<PhoneNumbers>
				  <PrimaryPhone>
				    <PhoneNumberValue>'.$leadData['homephone'].'</PhoneNumberValue>
				  </PrimaryPhone>
				  <SecondaryPhone>
				    <PhoneNumberValue>970-498-5555</PhoneNumberValue>
				  </SecondaryPhone>
				</PhoneNumbers>
				<Occupation>'.PostRequest_AIS::$occupationMap[$leadData['driver1occupation']].'</Occupation>
				<HighestLevelOfEducation>SomeOrNoHighSchool</HighestLevelOfEducation>
				<CreditRating SelfRating="Excellent"/>
				<CurrentResidence ResidenceStatus="Own">
				  <OccupancyDate>1987-08-13</OccupancyDate>
				</CurrentResidence>
			      </Contact>
			      <HomeInsuranceQuoteRequest>
				<ResidenceProfile DogBreed="None" UsedForBusinessOrFarming="Yes" DwellingType="Single_Family" OwnershipStatus="Own" SmokerInHousehold="Yes" OccupiedByApplicant="Yes" ResidenceType="Primary">
				  <ResidenceLocation>
				    <ResidenceAddress1>'.$leadtype['address'].'</ResidenceAddress1>
				    <ResidenceCity>'.$leadData['city'].'</ResidenceCity>
				    <ResidenceState>'.$leadData['state'].'</ResidenceState>
				    <ResidenceZIPCode>'.$leadData['zip'].'</ResidenceZIPCode>
				  </ResidenceLocation>
				  <ConstructionProfile HeatingType="GasForcedAir" PropertyInFloodplain="Yes" ServicePanelType="CircuitBreaker" RoofType="CompositionShingle" RoofAge="OneTo5Years" FoundationType="No basement at all" GarageType="NoGarage" DesignType="OneStory" WiringType="Copper" ExteriorWallType="MostlyBrick">
				    <YearBuilt>1928</YearBuilt>
				    <ResidenceSquareFootage>900</ResidenceSquareFootage>
				    <NumberBedrooms>2</NumberBedrooms>
				    <NumberBathrooms>1 Bath</NumberBathrooms>
				  </ConstructionProfile>
				  <ResidenceAccessories/>
				  <ResidenceSecurityFeatures/>
				</ResidenceProfile>
				<InsuranceProfile>
				  <RequestedPolicy NewHome="Yes">
				    <StartDate>2009-08-13</StartDate>
				    <RequestedCoverages>
				      <RequestedCoverage>
					<CoverageAmount PersonalLiability="100000" Deductible="100">250000</CoverageAmount>
				      </RequestedCoverage>
				    </RequestedCoverages>
				  </RequestedPolicy>
				</InsuranceProfile>
			      </HomeInsuranceQuoteRequest>
			    </HomeInsurance>
			  </QuoteRequest>
			</QuoteWizardData>
			';

        return $xml;
    }

    protected function _getTestRequest() {
        // for now our test uses same request
        return $this->getRequest();
    }

    protected function _executeRequest() {
        try
        {
	    $xmlParams = array('vendor'     => 'UndergroundElephant',
			       'contractID' => '9690133E-2BA5-434B-A50F-A907DADE3CC5',
			       'quoteData'  => $this->_getRequest(),
			       'initialID'  => '',
			       'format'	    => 'xml',
			       'pass'	    => 0
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

?>