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
    private static $url = 'https://prod1.leadamplms.com/prospect/post';

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

        $xml = '<ACORD xsi:noNamespaceSchemaLocation="acord-pcs-v1_23_0-nodoc-nocodes.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
				  <SignonRq>
					<ClientDt></ClientDt>
					<CustLangPref>en-US</CustLangPref>
					<ClientApp>
					  <Org>The Lead Company, Inc.</Org>
					  <Name>Auto Insurance Leads</Name>
					  <Version>1.0</Version>
					</ClientApp>
				  </SignonRq>
				  <InsuranceSvcRq>
					<RqUID>fcb40335-27b4-4856-96f3-3c85b2b5f3ea</RqUID>
					<PersAutoPolicyQuoteInqRq>
					  <RqUID>fe6f32f8-86fa-490f-8259-585e49a111d5</RqUID>
					  <TransactionRequestDt>'.date('c').'</TransactionRequestDt>
					  <CurCd>USD</CurCd>
					  <InsuredOrPrincipal>
						<GeneralPartyInfo>
						  <NameInfo>
							<PersonName>
							  <Surname>'.$leadData['lastname'].'</Surname>
							  <GivenName>'.$leadData['name'].'</GivenName>
							</PersonName>
						  </NameInfo>
						  <Addr>
							<AddrTypeCd>MailingAddress</AddrTypeCd>
							<Addr1>'.$leadData['address'].'</Addr1>
							<City>'.$leadData['city'].'</City>
							<StateProvCd>'.$leadData['state'].'</StateProvCd>
							<PostalCode>'.$leadData['zip'].'-0000</PostalCode>
						  </Addr>
						  <Communications>
							<PhoneInfo>
							  <PhoneTypeCd>Phone</PhoneTypeCd>
							  <CommunicationUseCd>Home</CommunicationUseCd>
							  <PhoneNumber>'.$homephone.'</PhoneNumber>
							</PhoneInfo>
							<EmailInfo>
							  <EmailAddr>'.$leadData['emailaddress'].'</EmailAddr>
							</EmailInfo>
						  </Communications>
						</GeneralPartyInfo>
						<InsuredOrPrincipalInfo>
						  <InsuredOrPrincipalRoleCd>Insured</InsuredOrPrincipalRoleCd>
						  <PersonInfo>
							<GenderCd>'.strtoupper(substr($leadData['driver1gender'], 0,1)).'</GenderCd>
							<BirthDt>'.$leadData['driver1dob_year'] . '-' . $leadData['driver1dob_month'] . '-' . $leadData['driver1dob_day'].'</BirthDt>
							<MaritalStatusCd>'.PostRequest_AIS::$maritalStatusMap[$leadData['driver1maritalstatus']].'</MaritalStatusCd>
							<OccupationClassCd>'.PostRequest_AIS::$occupationMap[$leadData['driver1occupation']].'</OccupationClassCd>
						  </PersonInfo>
						</InsuredOrPrincipalInfo>
					  </InsuredOrPrincipal>
					  <PersPolicy id="PersPolicy1">
						<BroadLOBCd>P</BroadLOBCd>
						<LOBCd>AUTOP</LOBCd>
						<OtherOrPriorPolicy id="OtherOrPriorPolicy1">
						  <PolicyCd>Prior</PolicyCd>
						  <LOBCd>AUTOP</LOBCd>
						  <InsurerName>Progressive</InsurerName>
						  <ContractTerm>
							<EffectiveDt>2012-08-28</EffectiveDt>
							<ExpirationDt>2013-02-28</ExpirationDt>
						  </ContractTerm>
						  <OriginalInceptionDt>2008-01-25</OriginalInceptionDt>
						  <Coverage>
							<CoverageCd>BI</CoverageCd>
							<Limit>
							  <FormatCurrencyAmt>
								<Amt>100000</Amt>
							  </FormatCurrencyAmt>
							  <LimitBasisCd>TotalLim</LimitBasisCd>
							  <LimitAppliesToCd>BIEachPers</LimitAppliesToCd>
							</Limit>
							<Limit>
							  <FormatCurrencyAmt>
								<Amt>300000</Amt>
							  </FormatCurrencyAmt>
							  <LimitBasisCd>TotalLim</LimitBasisCd>
							  <LimitAppliesToCd>BIEachOcc</LimitAppliesToCd>
							</Limit>
						  </Coverage>
						</OtherOrPriorPolicy>
						<PersApplicationInfo>
						  <InsuredOrPrincipal>
							<GeneralPartyInfo>
							  <NameInfo>
								<PersonName>
								  <Surname>'.$leadData['lastname'].'</Surname>
								  <GivenName>'.$leadData['name'].'</GivenName>
								</PersonName>
							  </NameInfo>
							</GeneralPartyInfo>
						  </InsuredOrPrincipal>
						  <ResidenceOwnedRentedCd>OWNED</ResidenceOwnedRentedCd>
						  <LengthTimeCurrentAddr>
							<DurationPeriod>
							  <NumUnits>2</NumUnits>
							  <UnitMeasurementCd>ANN</UnitMeasurementCd>
							</DurationPeriod>
						  </LengthTimeCurrentAddr>
						</PersApplicationInfo>
						<DriverVeh DriverRef="PersDriver1" VehRef="PersVeh1">
						  <UsePct>100</UsePct>
						  <DriverUseCd>Primary</DriverUseCd>
						</DriverVeh>
					  </PersPolicy>
					  <PersAutoLineBusiness>
						<LOBCd>AUTOP</LOBCd>
						<PersDriver id="PersDriver1">
						  <GeneralPartyInfo>
							<NameInfo>
							  <PersonName>
								<Surname>'.$leadData['lastname'].'</Surname>
								<GivenName>'.$leadData['name'].'</GivenName>
							  </PersonName>
							</NameInfo>
						  </GeneralPartyInfo>
						  <DriverInfo>
							<PersonInfo>
							  <GenderCd>'.strtoupper(substr($leadData['driver1gender'], 0,1)).'</GenderCd>
							  <BirthDt>'.$leadData['driver1dob_year'] . '-' . $leadData['driver1dob_month'] . '-' . $leadData['driver1dob_day'].'</BirthDt>
							  <MaritalStatusCd>'.PostRequest_AIS::$maritalStatusMap[$leadData['driver1maritalstatus']].'</MaritalStatusCd>
							  <OccupationClassCd>'.PostRequest_AIS::$occupationMap[$leadData['driver1occupation']].'</OccupationClassCd>
							  <EducationLevelCd>SomeCollegeNoDegree</EducationLevelCd>
							</PersonInfo>
							<License>
							  <LicenseTypeCd>Driver</LicenseTypeCd>
							  <LicenseStatusCd>Active</LicenseStatusCd>
							  <LicensedDt>1987-03-06</LicensedDt>
							  <StateProvCd>'.$leadData['state'].'</StateProvCd>
							</License>
						  </DriverInfo>
						  <PersDriverInfo VehPrincipallyDrivenRef="PersVeh1">
							<DriverRelationshipToApplicantCd>IN</DriverRelationshipToApplicantCd>
						  </PersDriverInfo>
						</PersDriver>
						<PersVeh id="PersVeh1" LocationRef="Location2" RatedDriverRef="PersDriver1">
						  <Manufacturer>'.$leadData['vehicle1make'].'</Manufacturer>
						  <Model>'.$leadData['vehicle1model'].'</Model>
						  <ModelYear>'.$leadData['vehicle1year'].'</ModelYear>
						  <VehBodyTypeCd>SEDAN</VehBodyTypeCd>
						  <VehTypeCd>PP</VehTypeCd>
						  <NumDaysDrivenPerWeek>5</NumDaysDrivenPerWeek>
						  <EstimatedAnnualDistance>
							<NumUnits>'.$leadData['vehicle1annualMileage'].'</NumUnits>
							<UnitMeasurementCd>SMI</UnitMeasurementCd>
						  </EstimatedAnnualDistance>
						  <LeasedVehInd>0</LeasedVehInd>
						  <VehIdentificationNumber>'.str_pad(PingPostCommon::getVINStub($leadData['vehicle1year'], $leadData['vehicle1make'], $leadData['vehicle1model'], $leadData['vehicle1trim']), 17, 0).'</VehIdentificationNumber>
						  <GaragingCd>D</GaragingCd>
						  <VehUseCd>DW</VehUseCd>
						  <Coverage>
							<CoverageCd>BI</CoverageCd>
							<CoverageDesc>Bodily Injury Liability</CoverageDesc>
							<Limit>
							  <FormatCurrencyAmt>
								<Amt>300000</Amt>
							  </FormatCurrencyAmt>
							  <LimitBasisCd>TotalLim</LimitBasisCd>
							  <LimitAppliesToCd>BIEachOcc</LimitAppliesToCd>
							</Limit>
						  </Coverage>
						  <Coverage>
							<CoverageCd>PD</CoverageCd>
							<CoverageDesc>Property Damage-Single Limit</CoverageDesc>
							<Limit>
							  <FormatCurrencyAmt>
								<Amt>50000</Amt>
							  </FormatCurrencyAmt>
							  <LimitBasisCd>TotalLim</LimitBasisCd>
							  <LimitAppliesToCd>PDEachOcc</LimitAppliesToCd>
							</Limit>
						  </Coverage>
						  <Coverage>
							<CoverageCd>UM</CoverageCd>
							<CoverageDesc>Uninsured Motorist Liability Coverage</CoverageDesc>
							<Limit>
							  <FormatCurrencyAmt>
								<Amt>100000</Amt>
							  </FormatCurrencyAmt>
							  <LimitBasisCd>TotalLim</LimitBasisCd>
							  <LimitAppliesToCd>BIEachPers</LimitAppliesToCd>
							</Limit>
						  </Coverage>
						  <Coverage>
							<CoverageCd>COMP</CoverageCd>
							<CoverageDesc>Comprehensive Coverage</CoverageDesc>
							<Deductible>
							  <FormatCurrencyAmt>
								<Amt>500</Amt>
							  </FormatCurrencyAmt>
							  <DeductibleBasisCd>P</DeductibleBasisCd>
							  <DeductibleAppliesToCd>Coverage</DeductibleAppliesToCd>
							</Deductible>
						  </Coverage>
						  <Coverage>
							<CoverageCd>COLL</CoverageCd>
							<CoverageDesc>Collision Coverage</CoverageDesc>
							<Deductible>
							  <FormatCurrencyAmt>
								<Amt>500</Amt>
							  </FormatCurrencyAmt>
							  <DeductibleBasisCd>P</DeductibleBasisCd>
							  <DeductibleAppliesToCd>Coverage</DeductibleAppliesToCd>
							</Deductible>
							<Option>
							  <OptionTypeCd>Opt1</OptionTypeCd>
							  <OptionCd>B</OptionCd>
							</Option>
						  </Coverage>
						</PersVeh>
					  </PersAutoLineBusiness>
					  <Location id="Location1">
						<ItemIdInfo>
						  <SystemId>09e1d7b6-afbf-4417-b255-baafc17c0c01</SystemId>
						</ItemIdInfo>
						<Addr>
						  <AddrTypeCd>MailingAddress</AddrTypeCd>
						  <Addr1>'.$leadData['address'].'</Addr1>
						  <City>'.$leadData['city'].'</City>
						  <StateProvCd>'.$leadData['state'].'</StateProvCd>
						  <PostalCode>'.$leadData['zip'].'-0000</PostalCode>
						</Addr>
					  </Location>
					  <Location id="Location2">
						<ItemIdInfo>
						  <SystemId>f9d2dd7e-6cf3-4273-a995-d01dfe578003</SystemId>
						</ItemIdInfo>
						<Addr>
						  <AddrTypeCd>GaragingAddress</AddrTypeCd>
						  <Addr1>'.$leadData['address'].'</Addr1>
						  <City>'.$leadData['city'].'</City>
						  <StateProvCd>'.$leadData['state'].'</StateProvCd>
						  <PostalCode>'.$leadData['zip'].'-0000</PostalCode>
						</Addr>
					  </Location>
					</PersAutoPolicyQuoteInqRq>
				  </InsuranceSvcRq>
				</ACORD>';

        return $xml;
    }

    protected function _getTestRequest() {
        // for now our test uses same request
        return $this->getRequest();
    }

    protected function _executeRequest() {
        try
        {
            $client = new SoapClient(PostRequest_AIS::$url.'?wsdl', array('location' => PostRequest_AIS::$url));
            $header = new SoapHeader("http://www.aispskleads.com", 'AuthHeader', array('Username' => $this->config['username'], 'Password' => $this->config['password']));
            $client->__setSoapHeaders($header);
            $response = $client->UploadWebLead(array("RefID" => $this->postObject->getLeadId(), "LeadData" => $this->getRequest()));
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

