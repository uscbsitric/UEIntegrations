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
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/x-www-form-urlencoded; charset=UTF-8"));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: text/plain"));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fieldsString);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false );

		$result = curl_exec($ch);
		$info	  = curl_getinfo($ch);

		return $result;
	}
	
	//$prePingUrl = "https://test1.leadamplms.com/prospect/prospect/preping";   NOTE: this is not working, always returns FALSE
	//$prePingUrl = "https://prod1.leadamplms.com/prospect/prospect/preping";


	// Pre Ping
	$curlParams = array('url' 			   => 'https://prod1.leadamplms.com/prospect/prospect/preping',
							  'clientCode'    => urlencode('AMFAM'),
						 	  'sourceCode'    => urlencode('UGRELNT'),
						 	  'campaignCode'  => urlencode('AUTO'),
						 	  'zip' 			   => urlencode('64101'),
						 	  'affiliateCode' => urlencode('underground')
							 );

	/////var_dump(MakeRequest($curlParams));
	/////exit();


	// Ping


	// POST
	function AccordXMLBuilder($parameters = array())
	{
		$signonRqNode = '<SignonRq>
								 <ClientDt>'.$parameters['ClientDt'].'</ClientDt>
								 <CustLangPref>'.$parameters['CustLangPref'].'</CustLangPref>
								 <ClientApp>
								   <Org>'.$parameters['Org'].'</Org>
								   <Name>'.$parameters['Name'].'</Name>
								   <Version>'.$parameters['Version'].'</Version>
								 </ClientApp>
							  </SignonRq>
							 ';

		$accordStandardXML = '<ACORD xsi:noNamespaceSchemaLocation="acord-pcs-v1_23_0-nodoc-nocodes.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
										'.$signonRqNode.'
										<InsuranceSvcRq>
											<RqUID>'.$parameters['InsuranceSvcRqRqUID'].'</RqUID>
											'.PersAutoPolicyQuoteInqRqNodeBuilder($parameters).'
										</InsuranceSvcRq>
									 </ACORD>
									';
echo "<pre>";
//echo var_dump($parameters);
echo htmlentities($accordStandardXML);
exit('debugging here');
		return $accordStandardXML;
	}
	
	function PersAutoPolicyQuoteInqRqNodeBuilder(&$nodeParameters = array())
	{
		$persAutoPolicyQuoteInqRqNode = '<PersAutoPolicyQuoteInqRq>
														<RqUID>'.$nodeParameters['PersAutoPolicyQuoteInqRqRqUID'].'</RqUID>
														<TransactionRequestDt>'.$nodeParameters['TransactionRequestDt'].'</TransactionRequestDt>
														<CurCd>'.$nodeParameters['CurCd'].'</CurCd>
														'.InsuredOrPrincipalNodeBuilder($nodeParameters).'
														'.PersPolicyNodeBuilder($nodeParameters).'
														'.PersAutoLineBusinessNodeBuilder($nodeParameters).'
													</PersAutoPolicyQuoteInqRq>
												  ';
		return $persAutoPolicyQuoteInqRqNode;
	}

	function InsuredOrPrincipalNodeBuilder(&$nodeParameters = array())
	{
		$insuredOrPrincipalNode = '<InsuredOrPrincipal>
												'.GeneralPartyInfoNodeBuilder($nodeParameters).'
												'.InsuredOrPrincipalInfoNodeBuilder($nodeParameters).'
											</InsuredOrPrincipal>
										  ';

		return $insuredOrPrincipalNode;
	}

	function PersPolicyNodeBuilder(&$nodeParameters = array())
	{
		$persPolicyNode = '<PersPolicy ';

		foreach($nodeParameters['PersPolicy'] as $key => $value)
		{
			$persPolicyNode .= $key . '="'.$value.'" ';
		}

		$persPolicyNode = rtrim($persPolicyNode, ' ');
		$lobCdCurrentPosition = $nodeParameters['LOBCd']['currentPosition'];
		$persPolicyNode .= '>
							<BroadLOBCd>'.$nodeParameters['BroadLOBCd'].'</BroadLOBCd>
							<LOBCd>'.$nodeParameters['LOBCd'][$lobCdCurrentPosition].'</LOBCd>
							'.OtherOrPriorPolicyNodeBuilder($nodeParameters).'
							'.PersApplicationInfoNodeBuilder($nodeParameters).'
							<DriverVeh DriverRef="'.$nodeParameters['DriverVeh']['DriverRef'].'" VehRef="'.$nodeParameters['DriverVeh']['VehRef'].'">
							  <UsePct>'.$nodeParameters['UsePct'].'</UsePct>
							  <DriverUseCd>'.$nodeParameters['DriverUseCd'].'</DriverUseCd>
							</DriverVeh>
							</PersPolicy>
						   ';
		$nodeParameters['LOBCd']['currentPosition']++;

		return $persPolicyNode;
	}

	function OtherOrPriorPolicyNodeBuilder(&$nodeParameters = array())
	{
		$otherOrPriorPolicy = '<OtherOrPriorPolicy ';
		
		foreach($nodeParameters['OtherOrPriorPolicy'] as $key => $value)
		{
			$otherOrPriorPolicy .= $key . '="'.$value.'" ';
		}
		
		$otherOrPriorPolicy = rtrim($otherOrPriorPolicy, ' ');
		$lobCdCurrentPosition = $nodeParameters['LOBCd']['currentPosition'];
		$coverageCdPosition = $nodeParameters['CoverageCd']['currentPosition'];
		$otherOrPriorPolicy .= '>
									<PolicyCd>'.$nodeParameters['PolicyCd'].'</PolicyCd>
									<LOBCd>'.$nodeParameters['LOBCd'][$lobCdCurrentPosition].'</LOBCd>
									<InsurerName>'.$nodeParameters['InsurerName'].'</InsurerName>
									<ContractTerm>
										<EffectiveDt>'.$nodeParameters['EffectiveDt'].'</EffectiveDt>
										<ExpirationDt>'.$nodeParameters['ExpirationDt'].'</ExpirationDt>
									</ContractTerm>
									<OriginalInceptionDt>'.$nodeParameters['OriginalInceptionDt'].'</OriginalInceptionDt>
									'.CoverageNodeBuilder($nodeParameters, array('childNodes' => array('CoverageCd' => $nodeParameters['CoverageCd'][$coverageCdPosition],
																									   'Limit'		=> 2, // meaning, create two Limit nodes
																									  )
																				)
														  ).'
								</OtherOrPriorPolicy>
							   ';
		$nodeParameters['LOBCd']['currentPosition']++;
		$nodeParameters['CoverageCd']['currentPosition']++;
		
		return $otherOrPriorPolicy;
	}
	
	function PersApplicationInfoNodeBuilder(&$nodeParameters = array())
	{
		$numUnitsPosition = $nodeParameters['NumUnits']['currentPosition'];
		$UnitMeasurementCd = $nodeParameters['UnitMeasurementCd']['currentPosition'];
		$persApplicationInfoNodeBuilder = '<PersApplicationInfo>
											  <InsuredOrPrincipal>
												<GeneralPartyInfo>
												  <NameInfo>
													<PersonName>
													  <Surname>'.$nodeParameters['Surname'].'</Surname>
													  <GivenName>'.$nodeParameters['GivenName'].'</GivenName>
													</PersonName>
												  </NameInfo>
												</GeneralPartyInfo>
											  </InsuredOrPrincipal>
											  <ResidenceOwnedRentedCd>'.$nodeParameters['ResidenceOwnedRentedCd'].'</ResidenceOwnedRentedCd>
											  <LengthTimeCurrentAddr>
												<DurationPeriod>
												  <NumUnits>'.$nodeParameters['NumUnits'][$numUnitsPosition].'</NumUnits>
												  <UnitMeasurementCd>'.$nodeParameters['UnitMeasurementCd'][$UnitMeasurementCd].'</UnitMeasurementCd>
												</DurationPeriod>
											  </LengthTimeCurrentAddr>
										   </PersApplicationInfo>
										  ';
		$nodeParameters['NumUnits'][$numUnitsPosition]++;
		$nodeParameters['UnitMeasurementCd'][$UnitMeasurementCd]++;		
		
		return $persApplicationInfoNodeBuilder;
	}

	function CoverageNodeBuilder(&$nodeParameters = array(), $childrenNodeConfig = array())
	{
		$childrenNodes = '';
		
		foreach($childrenNodeConfig['childNodes'] as $key => $value)
		{
			$childrenNodes .= '<'.$key.'>'.$value.'</'.$key.'>';
			
			if($key === 'Limit')
			{
				for($counter = 0; $counter < $value; $counter++)
				{
					$childrenNodes .= LimitNodeBuilder($nodeParameters);
				}
			}
			
			if($key === 'Deductible')
			{
				for($counter = 0; $counter < $value; $counter++)
				{
					$childrenNodes .= DeductibleNodeBuilder($nodeParameters);
				}
			}
			
			if($key === 'Option')
			{
				for($counter = 0; $counter < $value; $counter++)
				{
					$childrenNodes .= OptionNodeBuilder($nodeParameters);
				}
			}
		}
		
		$coverageNode = '<Coverage>
							'.$childrenNodes.'
						 </Coverage>
						';

		return $coverageNode;
	}
	
	function LimitNodeBuilder(&$nodeParameters = array())
	{
		$amtPosition 	  = $nodeParameters['Amt']['currentPosition'];
		$limitBasisCd 	  = $nodeParameters['LimitBasisCd']['currentPosition'];
		$limitAppliesToCd = $nodeParameters['LimitAppliesToCd']['currentPosition'];
		$limitNode = '<Limit>
						<FormatCurrencyAmt>
							<Amt>'.$nodeParameters['Amt'][$amtPosition].'</Amt>
						</FormatCurrencyAmt>
						<LimitBasisCd>'.$nodeParameters['Amt'][$limitBasisCd].'</LimitBasisCd>
						<LimitAppliesToCd>'.$nodeParameters['LimitAppliesToCd'][$limitAppliesToCd].'</LimitAppliesToCd>
					  </Limit>
					 ';
		$nodeParameters['Amt']['currentPosition']++;
		$nodeParameters['LimitBasisCd']['currentPosition']++;
		$nodeParameters['LimitAppliesToCd']['currentPosition']++;
		
		return $limitNode;
	}

	function DeductibleNodeBuilder(&$nodeParameters = array())
	{
		$amtPosition = $nodeParameters['Amt']['currentPosition'];
		$deductibleBasisCd = $nodeParameters['DeductibleBasisCd']['currentPosition'];
		$deductibleAppliesToCd = $nodeParameters['DeductibleAppliesToCd']['currentPosition'];
		$deductibleNode = '<Deductible>
							  <FormatCurrencyAmt>
								<Amt>'.$nodeParameters['Amt'][$amtPosition].'</Amt>
							  </FormatCurrencyAmt>
							  <DeductibleBasisCd>'.$nodeParameters['DeductibleBasisCd'][$deductibleBasisCd].'</DeductibleBasisCd>
							  <DeductibleAppliesToCd>'.$nodeParameters['DeductibleAppliesToCd'][$deductibleAppliesToCd].'</DeductibleAppliesToCd>
						   </Deductible>
						  ';
		$nodeParameters['Amt']['currentPosition']++;
		$nodeParameters['DeductibleBasisCd']['currentPosition']++;
		$nodeParameters['DeductibleAppliesToCd']['currentPosition']++;
		
		return $deductibleNode;
	}
	
	function OptionNodeBuilder(&$nodeParameters = array())
	{
		$optionNode = '<Option>
						  <OptionTypeCd>'.$nodeParameters['OptionTypeCd'].'</OptionTypeCd>
						  <OptionCd>'.$nodeParameters['OptionCd'].'</OptionCd>
					   </Option>
					  ';
		
		return $optionNode;
	}
	
	function GeneralPartyInfoNodeBuilder(&$nodeParameters = array(), $childrenNodeConfig = array())
	{
		$addrTypeCdPosition  = $nodeParameters['AddrTypeCd']['currentPosition'];
		$addr1Position		   = $nodeParameters['Addr1']['currentPosition'];
		$cityPosition		   = $nodeParameters['City']['currentPosition'];
		$stateProvCdPosition = $nodeParameters['StateProvCd']['currentPosition'];
		$postalCode				= $nodeParameters['PostalCode']['currentPosition'];

		$generalPartyInfoNode = '<GeneralPartyInfo>
											<NameInfo>
												<PersonName>
												  <Surname>'.$nodeParameters['Surname'].'</Surname>
												  <GivenName>'.$nodeParameters['GivenName'].'</GivenName>
												</PersonName>
											</NameInfo>
										   <Addr>
											  <AddrTypeCd>'.$nodeParameters['AddrTypeCd'][$addrTypeCdPosition].'</AddrTypeCd>
											  <Addr1>'.$nodeParameters['Addr1'].'</Addr1>
											  <City>'.$nodeParameters['City'].'</City>
											  <StateProvCd>'.$nodeParameters['StateProvCd'].'</StateProvCd>
											  <PostalCode>'.$nodeParameters['PostalCode'].'</PostalCode>
										   </Addr>
										  <Communications>
											 <PhoneInfo>
											   <PhoneTypeCd>'.$nodeParameters['PhoneTypeCd'].'</PhoneTypeCd>
											   <CommunicationUseCd>'.$nodeParameters['CommunicationUseCd'].'</CommunicationUseCd>
											   <PhoneNumber>'.$nodeParameters['PhoneNumber'].'</PhoneNumber>
											 </PhoneInfo>
											 <EmailInfo>
											   <EmailAddr>'.$nodeParameters['EmailAddr'].'</EmailAddr>
											 </EmailInfo>
										  </Communications>
										 </GeneralPartyInfo>
										';

		$nodeParameters['AddrTypeCd']['currentPosition']++;
		$nodeParameters['Addr1']['currentPosition']++;
		$nodeParameters['City']['currentPosition']++;
		$nodeParameters['StateProvCd']['currentPosition']++;
		$nodeParameters['PostalCode']['currentPosition']++;
		return $generalPartyInfoNode;
	}

	function InsuredOrPrincipalInfoNodeBuilder(&$nodeParameters = array())
	{
		$insuredOrPrincipalInfo = '<InsuredOrPrincipalInfo>
												<InsuredOrPrincipalRoleCd>'.$nodeParameters['InsuredOrPrincipalRoleCd'].'</InsuredOrPrincipalRoleCd>
											  '.PersonInfoNodeBuilder($nodeParameters, array('childNodes' => array('GenderCd',
																																		  'BirthDt',
																																		  'MaritalStatusCd',
																																		  'OccupationClassCd')))
												.'
											</InsuredOrPrincipalInfo>
										  ';

		return $insuredOrPrincipalInfo;
	}

	function PersonInfoNodeBuilder(&$nodeParameters = array(), $childrenNodeConfig = array())
	{
		$chilrenNodes = '';

		foreach($childrenNodeConfig['childNodes'] as $key => $value)
		{
			$chilrenNodes .= '<'.$value.'>'.$nodeParameters['PersonInfo'.$value].'</'.$value.'>';
		}

		$personalInfoNode = '<PersonInfo>
										'.$chilrenNodes.'
									</PersonInfo>
								  ';

		return $personalInfoNode;
	}
	
	
	function PersAutoLineBusinessNodeBuilder(&$nodeParameters = array())
	{	
		$lobcdPosition = $nodeParameters['LOBCd']['currentPosition'];
		$stateProvCdPosition = $nodeParameters['StateProvCd']['currentPosition'];
		$numUnitsPosition = $nodeParameters['NumUnits']['currentPosition'];
		$unitMeasurementCd = $nodeParameters['UnitMeasurementCd']['currentPosition'];
		$coverageCdPosition = $nodeParameters['CoverageCd']['currentPosition'];
		$coverageDescPosition = $nodeParameters['CoverageDesc']['currentPosition'];
		$persAutoLineBusinessNode = ' <PersAutoLineBusiness>
										<LOBCd>'.$nodeParameters['LOBCd'][$lobcdPosition].'</LOBCd>
										<PersDriver id="'.$nodeParameters['PersDriver']['id'].'">
										  <GeneralPartyInfo>
											<NameInfo>
											  <PersonName>
												<Surname>'.$nodeParameters['Surname'].'</Surname>
												<GivenName>'.$nodeParameters['GivenName'].'</GivenName>
											  </PersonName>
											</NameInfo>
										  </GeneralPartyInfo>
										  <DriverInfo>
											<PersonInfo>
											  <GenderCd>'.$nodeParameters['PersonInfoGenderCd'].'</GenderCd>
											  <BirthDt>'.$nodeParameters['PersonInfoBirthDt'].'</BirthDt>
											  <MaritalStatusCd>'.$nodeParameters['PersonInfoMaritalStatusCd'].'</MaritalStatusCd>
											  <OccupationClassCd>'.$nodeParameters['PersonInfoOccupationClassCd'].'</OccupationClassCd>
											  <EducationLevelCd>'.$nodeParameters['PersonInfoEducationLevelCd'].'</EducationLevelCd>
											</PersonInfo>
											<License>
											  <LicenseTypeCd>'.$nodeParameters['LicenseTypeCd'].'</LicenseTypeCd>
											  <LicenseStatusCd>'.$nodeParameters['LicenseStatusCd'].'</LicenseStatusCd>
											  <LicensedDt>'.$nodeParameters['LicensedDt'].'</LicensedDt>
											  <StateProvCd>'.$nodeParameters['StateProvCd'][$stateProvCdPosition].'</StateProvCd>
											</License>
										  </DriverInfo>
										  <PersDriverInfo VehPrincipallyDrivenRef="'.$nodeParameters['PersDriverInfo']['VehPrincipallyDrivenRef'].'">
											<DriverRelationshipToApplicantCd>'.$nodeParameters['DriverRelationshipToApplicantCd'].'</DriverRelationshipToApplicantCd>
										  </PersDriverInfo>
										</PersDriver>
										<PersVeh id="'.$nodeParameters['PersVeh']['id'].'" LocationRef="'.$nodeParameters['PersVeh']['LocationRef'].'" RatedDriverRef="'.$nodeParameters['PersVeh']['RatedDriverRef'].'">
										  <Manufacturer>'.$nodeParameters['Manufacturer'].'</Manufacturer>
										  <Model>'.$nodeParameters['Model'].'</Model>
										  <ModelYear>'.$nodeParameters['ModelYear'].'</ModelYear>
										  <VehBodyTypeCd>'.$nodeParameters['VehBodyTypeCd'].'</VehBodyTypeCd>
										  <VehTypeCd>'.$nodeParameters['VehTypeCd'].'</VehTypeCd>
										  <NumDaysDrivenPerWeek>'.$nodeParameters['NumDaysDrivenPerWeek'].'</NumDaysDrivenPerWeek>
										  <EstimatedAnnualDistance>
											<NumUnits>'.$nodeParameters['NumUnits'][$numUnitsPosition].'</NumUnits>
											<UnitMeasurementCd>'.$nodeParameters['UnitMeasurementCd'][$unitMeasurementCd].'</UnitMeasurementCd>
										  </EstimatedAnnualDistance>
										  <LeasedVehInd>'.$nodeParameters['LeasedVehInd'].'</LeasedVehInd>
										  <VehIdentificationNumber>'.$nodeParameters['VehIdentificationNumber'].'</VehIdentificationNumber>
										  <GaragingCd>'.$nodeParameters['GaragingCd'].'</GaragingCd>
										  <VehUseCd>'.$nodeParameters['VehUseCd'].'</VehUseCd>
										  '.CoverageNodeBuilder($nodeParameters, array('childNodes' => array('CoverageCd' => $nodeParameters['CoverageCd'][$coverageCdPosition],
																											 'CoverageDesc' => $nodeParameters['CoverageDesc'][$coverageDescPosition],
																											 'Limit' => 1
																											)
																					  )
																).'
										 '.CoverageNodeBuilder($nodeParameters, array('childNodes' => array('CoverageCd' => ++$nodeParameters['CoverageCd'][$coverageCdPosition],
																											'CoverageDesc' => ++$nodeParameters['CoverageDesc'][$coverageDescPosition],
																											'Limit' => 1
																											)
																					  )
																).'
										 '.CoverageNodeBuilder($nodeParameters, array('childNodes' => array('CoverageCd' => ++$nodeParameters['CoverageCd'][$coverageCdPosition],
																											'CoverageDesc' => ++$nodeParameters['CoverageDesc'][$coverageDescPosition],
																											'Limit' => 1
																											)
																					  )
																).'
										 '.CoverageNodeBuilder($nodeParameters, array('childNodes' => array('CoverageCd' => ++$nodeParameters['CoverageCd'][$coverageCdPosition],
																											'CoverageDesc' => ++$nodeParameters['CoverageDesc'][$coverageDescPosition],
																											'Deductible' => 1
																											)
																					  )
																).'
										 '.CoverageNodeBuilder($nodeParameters, array('childNodes' => array('CoverageCd' => ++$nodeParameters['CoverageCd'][$coverageCdPosition],
																											'CoverageDesc' => ++$nodeParameters['CoverageDesc'][$coverageDescPosition],
																											'Deductible' => 1,
																											'Option'	 => 1
																											)
																					  )
																).'
										  
										</PersVeh>
									  </PersAutoLineBusiness>
									';
		$nodeParameters['LOBCd']['currentPosition']++;
		$nodeParameters['StateProvCd']['currentPosition']++;
		$nodeParameters['NumUnits']['currentPosition']++;
		$nodeParameters['CoverageCd']['currentPosition']++;
		return $persAutoLineBusinessNode;
	}
	
	function LocationNodeBuilder(&$nodeParameters = array())
	{
		$locationNodeBuilder = '';
		return $locationNodeBuilder;
	}
	
	function AddrNodeBuilder(&$nodeParameters = array())
	{
		$addrNodeBuilder = '';
		return $addrNodeBuilder;
	}
	
	
	$parameters = array('ClientDt' 							 => '',
							  'CustLangPref' 						 => 'en-US',
							  'Org'									 => 'The Lead Company, Inc.',
							  'Name' 								 => 'Auto Insurance Leads',
							  'Version' 							 => '1.0',
							  'InsuranceSvcRqRqUID' 			 => 'fcb40335-27b4-4856-96f3-3c85b2b5f3ea',
							  'PersAutoPolicyQuoteInqRqRqUID' => 'fe6f32f8-86fa-490f-8259-585e49a111d5',
							  'TransactionRequestDt'			 => '2013-01-25T12:42:04-05:00',
							  'CurCd'								 => 'USD',
							  'Surname'								 => 'Mcswain',
							  'GivenName'							 => 'Amanda',
							  'AddrTypeCd'							 => array('MailingAddress', 'MailingAddress', 'GaragingAddress', 'currentPosition' => 0),
							  'Addr1'								 => array('1500 State Route 125', '1500 State Route 125', '1500 State Route 125', 'currentPosition' => 0),
							  'City'									 => array('West Union', 'West Union', 'West Union', 'currentPosition' => 0),
							  'StateProvCd'						 => array('OH', 'OH', 'OH', 'currentPosition' => 0),
							  'PostalCode'							 => array('45693', '45693', '45693', 'currentPosition' => 0),
							  'PhoneTypeCd'						 => 'Phone',
							  'CommunicationUseCd'				 => 'Home',
							  'PhoneNumber'						 => '+1-937-3733137',
							  'EmailAddr'							 => 'mcswainamanda@aim.com',
							  'InsuredOrPrincipalRoleCd'		 => 'Insured',
							  'PersonInfoGenderCd'				 => 'F',
							  'PersonInfoBirthDt'				 => '1971-03-06',
							  'PersonInfoMaritalStatusCd'		 => 'D',
							  'PersonInfoOccupationClassCd'	 => 'UNEM',
							  'PersonInfoEducationLevelCd'	 => 'SomeCollegeNoDegree',
							  'PersPolicy'						    => array('id' => 'PersPolicy1'),
							  'BroadLOBCd'							 => 'P',
							  'LOBCd'								 => array('AUTOP', 'AUTOP', 'AUTOP', 'currentPosition' => 0),
							  'OtherOrPriorPolicy'				 => array('id' => 'OtherOrPriorPolicy1'),
							  'PolicyCd'							 => 'Prior',
							  'InsurerName'						 => 'Progressive',
							  'EffectiveDt'						 => '2012-08-28',
							  'ExpirationDt'						 => '2013-02-28',
							  'OriginalInceptionDt'				 => '2008-01-25',
							  'CoverageCd'							 => array('BI', 'BI', 'PD', 'UM', 'COMP', 'COLL', 'currentPosition' => 0),
							  'Amt'									 => array('100000', '300000', '300000', '50000', '100000', '500', '500', 'currentPosition' => 0),
							  'LimitBasisCd'						 => array('TotalLim', 'TotalLim', 'TotalLim', 'TotalLim', 'TotalLim', 'currentPosition' => 0),
							  'LimitAppliesToCd'					 => array('BIEachPers', 'BIEachOcc', 'BIEachOcc', 'PDEachOcc', 'BIEachPers', 'currentPosition' => 0),
							  'ResidenceOwnedRentedCd'			 => 'OWNED',
							  'NumUnits'							 => array('2', '12500', 'currentPosition' => 0),
							  'UnitMeasurementCd'				 => array('ANN', 'SMI', 'currentPosition' => 0),
							  'DriverVeh'							 => array('DriverRef' => 'PersDriver1', 'VehRef' => 'PersVeh1'),
							  'UsePct'								 => '100',
							  'DriverUseCd'						 => 'Primary',
							  'PersDriver'							 => array('id' => 'PersDriver1'),
							  'OccupationClassCd'				 => 'UNEM',
							  'EducationLevelCd'					 => array('SomeCollegeNoDegree', 'currentPosition' => 0),
							  'LicenseTypeCd'						 => 'Driver',
							  'LicenseStatusCd'					 => 'Active',
							  'LicensedDt'							 => '1987-03-06',
							  'PersDriverInfo'					 => array('VehPrincipallyDrivenRef' => 'PersVeh1'),
							  'DriverRelationshipToApplicantCd' => 'IN',
							  'PersVeh'								 => array('id' => 'PersVeh1', 'LocationRef' => 'Location2', 'RatedDriverRef' => 'PersDriver1'),
							  'Manufacturer'						 => 'CHEVROLET',
							  'Model'								 => 'CAVALIER LS',
							  'ModelYear'							 => '2001',
							  'VehBodyTypeCd'						 => 'SEDAN',
							  'VehTypeCd'							 => 'PP',
							  'NumDaysDrivenPerWeek'			 => '5',
							  'NumUnits'							 => array('2', '12500', 'currentPosition' => 0),
							  'UnitMeasurementCd'				 => array('ANN', 'SMI', 'currentPosition' => 0),
							  'LeasedVehInd'						 => '0',
							  'VehIdentificationNumber'		 => '1G1JF52401',
							  'GaragingCd'							 => 'D',
							  'VehUseCd'							 => 'DW',
							  'CoverageDesc'						 => array('Bodily Injury Liability', 
																					 'Procurl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: text/xml"));perty Damage-Single Limit',
																					 'Uninsured Motorist Liability Coverage',
																					 'Comprehensive Coverage',
																					 'Collision Coverage',
																					 'currentPosition' => 0),
							  'DeductibleBasisCd'				 => array('P', 'P', 'P', 'P', 'currentPosition' => 0),
							  'DeductibleAppliesToCd'			 => array('Coverage', 'Coverage', 'currentPosition' => 0),
							  'OptionTypeCd'						 => 'Opt1',
							  'OptionCd'							 => 'B',
							  'Location'							 => array(0 => array('id' => 'Location1'),
																					 1 => array('id' => 'Location2')
																					),
							  'SystemId'							 => array('09e1d7b6-afbf-4417-b255-baafc17c0c01', 'f9d2dd7e-6cf3-4273-a995-d01dfe578003', 'currentPosition' => 0),
							  'AddrTypeCd'							 => array('MailingAddress', 'MailingAddress', 'MailingAddress', 'currentPosition' => 0),
							  'Addr1'								 => array('1500 State Route 125', '1500 State Route 125', '1500 State Route 125', 'currentPosition' => 0),
							  'City'									 => array('West Union', 'West Union', 'West Union', 'currentPosition' => 0),
							  'PostalCode'							 => array('45693', '45693', '45693', 'currentPosition' => 0),
							 );
	$accordStandardXML = AccordXMLBuilder(&$parameters);
	//$url = 'https://test1.leadamplms.com/prospect/prospect/post';
	$url = 'https://prod1.leadamplms.com/prospect/prospect/post';
	$postfields = array('sourceCode' => 'UGRELNT',
							  'campaignCode' => 'AUTO',
							  'payload' => $accordStandardXML
							 );
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: text/plain"));
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false );
	$response = curl_exec($ch);
	
	echo "<pre>";
	var_dump($response);
?>
