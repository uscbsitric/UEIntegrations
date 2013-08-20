<?php

require_once __DIR__.'/../classes/pingpostCommon.php';

if ($argv[1] != "")
        $leadid = $argv[1];
else
        $leadid = $_POST["leadid"];

$pingPost = new PingPostCommon();
$lmsData = $pingPost->fetchLead($leadid);

$postStringVals = json_decode($lmsData['poststring'], true);
$vals = array_merge($lmsData, $postStringVals, $_POST);


$config = array(
            'clientCode'    => 'AMFAM',
                    'productCode'   => 'A',
                    'sourceCode'    => 'UGRELNT',
                    'campaignCode'  => 'AUTO',
                    'postUrl'       => 'https://www.leadamplms.com/submitLead'
);

// Home phone format
$vals['homephone'] = $vals['homephone_area']. '-' . $vals['homephone_prefix'] . $vals['homephone_suffix'];

$payloadXML = buildPostData($vals);
// echo $payloadXML;

$data = array(
          'ClientCode'          => $config['clientCode'],
                  'ProductCode'         => $config['productCode'],
                  'SourceCode'          => $config['sourceCode'],
                  'AffiliateCode'       => $vals['vendorid'],
                  'SourceLeadCode'      => $leadid,
                  'CampaignCode'        => $config['campaignCode'],
                  'Email'                   => $vals['emailaddress'],
                  'FirstName'           => $vals['firstname'],
                  'LastName'            => $vals['lastname'],
                  'HomePhone'           => $vals['homephone'],
                  'AltPhone'            => '',
                  'AltPhoneTxt'         => 'N',
                  'Address'                 => $vals['address'],
                  'Address2'            => '',
                  'City'                    => $vals['city'],
                  'State'                   => $vals['st'],
                  'Country'                 => 'USA',
                  'PostalCode'          => $vals['zip'],
                  'Payload'                 => $payloadXML
);

$postResponse = PingPostCommon::sendCurlRequest($config['postUrl'], 'POST', $data);
//$responseObject = json_decode($postResponse);
//var_dump( $postResponse );

$success = stripos($postResponse, '[success] => true');
$qualified = stripos($postResponse, '[qualified] => true');
if ($success !== false && $qualified !== false) {
    echo "Success";
} else {
    echo "Failure";
}

echo "\n---response---\n";
echo $postResponse;




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
        if(curl_errno($ch))
        {
            $curlError = 'Curl error: ' . curl_error($ch);
            return $curlError;
        }

        curl_close($ch);

        return $response;
    }
