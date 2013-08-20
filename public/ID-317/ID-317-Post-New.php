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
	
	
	function buildPostData( $postData = array() )
	{
	
		// license date
		$licenseYear = $postData['driver1dob_year'] + $postData['driver1licenseage'];
		$postData['licensedate'] = $licenseYear . "-" . $postData['driver1dob_month'] . "-" . $postData['driver1dob_day'];
	
		date_default_timezone_set('America/Los_Angeles');
		$date = date('c');
	
		// Policy Expiration Date
		if (empty($postData['currentpolicyexpiration'])) {
			$postData['currentpolicyexpiration'] = date("Y-01-01");
		}
		$policyExpirationDate = date('c', strtotime($postData['currentpolicyexpiration']));
	
		$xmlData = '
                      <?xml version="1.0" encoding="UTF-8"?>
                      <ACORD xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="acord-pcs-v1_23_0-nodoc-nocodes.xsd">
                        <SignonRq>
                            <ClientDt>'.$date.'</ClientDt>
                            <CustLangPref>en-US</CustLangPref>
                            <ClientApp>
                              <Org>undergroundelephant</Org>
                              <Name>Auto Insurance Leads</Name>
                              <Version>1.0</Version>
                            </ClientApp>
                        </SignonRq>
                        <InsuranceSvcRq>
                            <RqUID>'.$postData['universal_leadid'].'</RqUID>
                            <PersAutoPolicyQuoteInqRq>
                              <RqUID>'.$postData['universal_leadid'].'</RqUID>
                              <TransactionRequestDt>'.$date.'</TransactionRequestDt>
                              <CurCd>USD</CurCd>
                              <InsuredOrPrincipal>
                                  <GeneralPartyInfo>
                                    <NameInfo>
                                            <PersonName>
                                                <Surname>'.$postData['lastname'].'</Surname>
                                                <GivenName>'.$postData['name'].'</GivenName>
                                            </PersonName>
                                    </NameInfo>
                                    <Addr>
                                            <AddrTypeCd>MailingAddress</AddrTypeCd>
                                            <Addr1>'.$postData['address'].'</Addr1>
                                            <City>'.$postData['city'].'</City>
                                            <StateProvCd>'.$postData['st'].'</StateProvCd>
                                            <PostalCode>'.$postData['zip'].'</PostalCode>
                                    </Addr>
                                    <Communications>
                                            <PhoneInfo>
                                                <PhoneTypeCd>Phone</PhoneTypeCd>
                                                <CommunicationUseCd>Home</CommunicationUseCd>
                                                <PhoneNumber>'.$postData['homephone'].'</PhoneNumber>
                                            </PhoneInfo>
                                            <EmailInfo>
                                                <EmailAddr>'.$postData['emailaddress'].'</EmailAddr>
                                            </EmailInfo>
                                    </Communications>
                                  </GeneralPartyInfo>
                                  <InsuredOrPrincipalInfo>
                                    <InsuredOrPrincipalRoleCd>Insured</InsuredOrPrincipalRoleCd>
                                    <PersonInfo>
                                        <GenderCd>'.$postData['driver1gender'].'</GenderCd>
                                        <BirthDt>'.$postData['driver1dob_year'].'-'.$postData['driver1dob_day'].'-'.$postData['driver1dob_month'].'</BirthDt>
                                        <MaritalStatusCd>'.$postData['driver1maritalstatus'].'</MaritalStatusCd>
                                        <OccupationClassCd>'.$postData['driver1occupation'].'</OccupationClassCd>
                                    </PersonInfo>
                                  </InsuredOrPrincipalInfo>
                              </InsuredOrPrincipal>
                              <PersPolicy id="PersPolicy1">
                                  <BroadLOBCd>P</BroadLOBCd>
                                  <LOBCd>AUTOP</LOBCd>
                                  <OtherOrPriorPolicy id="OtherOrPriorPolicy1">
                                    <PolicyCd>Prior</PolicyCd>
                                    <LOBCd>AUTOP</LOBCd>
                                    <InsurerName>'.$postData['CURRENTINSURANCECOMPANY'].'</InsurerName>
                                    <ContractTerm>
                                        <EffectiveDt></EffectiveDt>
                                        <ExpirationDt>'.$policyExpirationDate.'</ExpirationDt>
                                    </ContractTerm>
                                    <OriginalInceptionDt></OriginalInceptionDt>
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
                                                    <Surname>'.$postData['lastname'].'</Surname>
                                                    <GivenName>'.$postData['name'].'</GivenName>
                                            </PersonName>
                                          </NameInfo>
                                        </GeneralPartyInfo>
                                    </InsuredOrPrincipal>
                                    <ResidenceOwnedRentedCd>'.$postData['currentresidence'].'</ResidenceOwnedRentedCd>
                                    <LengthTimeCurrentAddr>
                                        <DurationPeriod>
                                          <NumUnits>'.$postData['driver1yearsatresidence'].'</NumUnits>
                                          <UnitMeasurementCd>ANN</UnitMeasurementCd>
                                        </DurationPeriod>
                                    </LengthTimeCurrentAddr>
                                  </PersApplicationInfo>
                                  <DriverVeh DriverRef="PersDriver1" VehRef="PersVeh1">
                                    <UsePct>'.$postData['vehicle1commuteAvgMileage'].'</UsePct>
                                    <DriverUseCd>'.$postData['vehicle1primaryUse'].'</DriverUseCd>
                                  </DriverVeh>
                              </PersPolicy>
                              <PersAutoLineBusiness>
                                  <LOBCd>AUTOP</LOBCd>
                                  <PersDriver id="PersDriver1">
                                    <GeneralPartyInfo>
                                        <NameInfo>
                                          <PersonName>
                                              <Surname>'.$postData['lastname'].'</Surname>
                                              <GivenName>'.$postData['name'].'</GivenName>
                                          </PersonName>
                                        </NameInfo>
                                    </GeneralPartyInfo>
                                    <DriverInfo>
                                        <PersonInfo>
                                          <GenderCd>'.$postData['driver1gender'].'</GenderCd>
                                          <BirthDt>'.$postData['driver1dob_year'].'-'.$postData['driver1dob_day'].'-'.$postData['driver1dob_month'].'</BirthDt>
                                          <MaritalStatusCd>'.$postData['driver1maritalstatus'].'</MaritalStatusCd>
                                          <OccupationClassCd>'.$postData['driver1occupation'].'</OccupationClassCd>
                                          <EducationLevelCd>'.$postData['driver1edulevel'].'</EducationLevelCd>
                                        </PersonInfo>
                                        <License>
                                          <LicenseTypeCd>Driver</LicenseTypeCd>
                                          <LicenseStatusCd>Active</LicenseStatusCd>
                                          <LicensedDt>'.$postData['licensedate'].'</LicensedDt>
                                          <StateProvCd>'.$postData['st'].'</StateProvCd>
                                        </License>
                                    </DriverInfo>
                                    <PersDriverInfo VehPrincipallyDrivenRef="PersVeh1">
                                        <DriverRelationshipToApplicantCd>IN</DriverRelationshipToApplicantCd>
                                    </PersDriverInfo>
                                  </PersDriver>';
		if (!empty($postData['driver2gender'])) {
			$xmlData .= '<PersDriver id="PersDriver2">
                                    <GeneralPartyInfo>
                                        <NameInfo>
                                          <PersonName>
                                              <Surname>'.$postData['lastname'].'</Surname>
                                              <GivenName>'.$postData['name'].'</GivenName>
                                          </PersonName>
                                        </NameInfo>
                                    </GeneralPartyInfo>
                                    <DriverInfo>
                                        <PersonInfo>
                                          <GenderCd>'.$postData['driver2gender'].'</GenderCd>
                                          <BirthDt>'.$postData['driver2dob_year'].'-'.$postData['driver2dob_day'].'-'.$postData['driver2dob_month'].'</BirthDt>
                                          <MaritalStatusCd>'.$postData['driver2maritalstatus'].'</MaritalStatusCd>
                                          <OccupationClassCd>'.$postData['driver2occupation'].'</OccupationClassCd>
                                          <EducationLevelCd>'.$postData['driver2edulevel'].'</EducationLevelCd>
                                        </PersonInfo>
                                        <License>
                                          <LicenseTypeCd>Driver</LicenseTypeCd>
                                          <LicenseStatusCd>Active</LicenseStatusCd>
                                          <LicensedDt>'.$postData['licensedate'].'</LicensedDt>
                                          <StateProvCd>'.$postData['st'].'</StateProvCd>
                                        </License>
                                    </DriverInfo>
                                    <PersDriverInfo VehPrincipallyDrivenRef="PersVeh1">
                                        <DriverRelationshipToApplicantCd>IN</DriverRelationshipToApplicantCd>
                                    </PersDriverInfo>
                                  </PersDriver>';
		}
		$xmlData .= '<PersVeh id="PersVeh1" LocationRef="Location2" RatedDriverRef="PersDriver1">
                                    <Manufacturer>'.$postData['vehicle1make'].'</Manufacturer>
                                    <Model>'.$postData['vehicle1model'].'</Model>
                                    <ModelYear>'.$postData['vehicle1year'].'</ModelYear>
                                    <VehBodyTypeCd>'.$postData['vehicle1trim'].'</VehBodyTypeCd>
                                    <VehTypeCd>PP</VehTypeCd>
                                    <NumDaysDrivenPerWeek>'.$postData['vehicle1commutedays'].'</NumDaysDrivenPerWeek>
                                    <EstimatedAnnualDistance>
                                        <NumUnits>'.$postData['vehicle1annualMileage'].'</NumUnits>
                                        <UnitMeasurementCd>SMI</UnitMeasurementCd>
                                    </EstimatedAnnualDistance>
                                    <LeasedVehInd>'.$postData['vehicle1leased'].'</LeasedVehInd>
                                    <VehIdentificationNumber></VehIdentificationNumber>
                                    <GaragingCd>D</GaragingCd>
                                    <VehUseCd>DW</VehUseCd>
                                    <Coverage>
                                        <CoverageCd>COMP</CoverageCd>
                                        <CoverageDesc>Comprehensive Coverage</CoverageDesc>
                                        <Deductible>
                                          <FormatCurrencyAmt>
                                              <Amt>'.$postData['desiredcomprehensivedeductible'].'</Amt>
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
                                              <Amt>'.$postData['desiredcollisiondeductible'].'</Amt>
                                          </FormatCurrencyAmt>
                                          <DeductibleBasisCd>P</DeductibleBasisCd>
                                          <DeductibleAppliesToCd>Coverage</DeductibleAppliesToCd>
                                        </Deductible>
                                        <Option>
                                          <OptionTypeCd>Opt1</OptionTypeCd>
                                          <OptionCd>B</OptionCd>
                                        </Option>
                                    </Coverage>
                                  </PersVeh>';
		if (!empty($postData['vehicle2make'])) {
			$xmlData .= '<PersVeh id="PersVeh2" LocationRef="Location2" RatedDriverRef="PersDriver1">
                                    <Manufacturer>'.$postData['vehicle2make'].'</Manufacturer>
                                    <Model>'.$postData['vehicle2model'].'</Model>
                                    <ModelYear>'.$postData['vehicle2year'].'</ModelYear>
                                    <VehBodyTypeCd>'.$postData['vehicle2trim'].'</VehBodyTypeCd>
                                    <VehTypeCd>PP</VehTypeCd>
                                    <NumDaysDrivenPerWeek>'.$postData['vehicle2commutedays'].'</NumDaysDrivenPerWeek>
                                    <EstimatedAnnualDistance>
                                        <NumUnits>'.$postData['vehicle2annualMileage'].'</NumUnits>
                                        <UnitMeasurementCd>SMI</UnitMeasurementCd>
                                    </EstimatedAnnualDistance>
                                    <LeasedVehInd>'.$postData['vehicle2leased'].'</LeasedVehInd>
                                    <VehIdentificationNumber></VehIdentificationNumber>
                                    <GaragingCd>D</GaragingCd>
                                    <VehUseCd>DW</VehUseCd>
                                    <Coverage>
                                        <CoverageCd>COMP</CoverageCd>
                                        <CoverageDesc>Comprehensive Coverage</CoverageDesc>
                                        <Deductible>
                                          <FormatCurrencyAmt>
                                              <Amt>'.$postData['desiredcomprehensivedeductible'].'</Amt>
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
                                              <Amt>'.$postData['desiredcollisiondeductible'].'</Amt>
                                          </FormatCurrencyAmt>
                                          <DeductibleBasisCd>P</DeductibleBasisCd>
                                          <DeductibleAppliesToCd>Coverage</DeductibleAppliesToCd>
                                        </Deductible>
                                        <Option>
                                          <OptionTypeCd>Opt1</OptionTypeCd>
                                          <OptionCd>B</OptionCd>
                                        </Option>
                                    </Coverage>
                                  </PersVeh>';
		}
		$xmlData .= '</PersAutoLineBusiness>
                              <Location id="Location1">
                                  <ItemIdInfo>
                                    <SystemId>09e1d7b6-afbf-4417-b255-baafc17c0c01</SystemId>
                                  </ItemIdInfo>
                                  <Addr>
                                    <AddrTypeCd>MailingAddress</AddrTypeCd>
                                    <Addr1>'.$postData['address'].'</Addr1>
                                    <City>'.$postData['city'].'</City>
                                    <StateProvCd>'.$postData['st'].'</StateProvCd>
                                    <PostalCode>'.$postData['zip'].'</PostalCode>
                                  </Addr>
                              </Location>
                              <Location id="Location2">
                                  <ItemIdInfo>
                                    <SystemId>f9d2dd7e-6cf3-4273-a995-d01dfe578003</SystemId>
                                  </ItemIdInfo>
                                  <Addr>
                                    <AddrTypeCd>GaragingAddress</AddrTypeCd>
                                    <Addr1>'.$postData['address'].'</Addr1>
                                    <City>'.$postData['city'].'</City>
                                    <StateProvCd>'.$postData['st'].'</StateProvCd>
                                    <PostalCode>'.$postData['zip'].'</PostalCode>
                                  </Addr>
                              </Location>
                            </PersAutoPolicyQuoteInqRq>
                        </InsuranceSvcRq>
                      </ACORD>';
	
		// Clean up xml data
		// LeadAmp prefers we remove line breaks from the xml
		$xmlData = str_replace(array("\r\n", "\r", "\n", "\t"), "", $xmlData);
		return $xmlData;
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
					  "zip":"64101",
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
	
	$testUrl = 'http://test1.leadamplms.com/prospect/prospect/post';
	$productionUrl = 'https://prod1.leadamplms.com/prospect/post';
	$params	= array('clientCode' => 'AMFAM',
					'sourceCode' => 'UGRELNT',
					'campaignCode' => 'AUTO',
					'pingID' => '8102281',	// hardcoded for now
					'productCode' => 'A',
					'vendorLeadCode' => $lmsDataJsonDecoded['vendorid'],
					'affiliateCode' => $lmsDataJsonDecoded['universal_leadid'], // i am unsure what field we collect to use here, this is of GUID type according to the documentation
					'firstName' => $lmsDataJsonDecoded['firstname'],
					'lastName' => $lmsDataJsonDecoded['lastname'],
					'email' => $lmsDataJsonDecoded['email'],
					'primaryPhone' => $lmsDataJsonDecoded['homephone'],
					'alternatePhone' => $lmsDataJsonDecoded['homephone'],
					'address' => $lmsDataJsonDecoded['address'],
					'address2' => $lmsDataJsonDecoded['address'],
					'city' => $lmsDataJsonDecoded['city'],
					'state' => $lmsDataJsonDecoded['state'],
					'zip' => $lmsDataJsonDecoded['zip'],
					'country' => 'US',
					'payload' => buildPostData($lmsDataJsonDecoded)
				   );
	
	$response = getCurlRequest($testUrl, $params);
	var_dump($response);