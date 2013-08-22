<?php

	function getCurlRequest($url, $params)
	{
		$postVars = http_build_query($params);

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postVars);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded"));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		
		$response = curl_exec($ch);
		
		if( curl_errno($ch) )
		{
			$curlError = 'Curl error: ' . curl_error($ch);

			return $curlError;
		}
		
		curl_close($ch);
		
		return $response;
	}
	
    function getClosestWord($words = array(), $input)
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
	
	function getXmlPayload( $lmsDataJsonDecoded, $affiliateLogin, $affiliatePassword, $companyID, $vehUse, $garageType, $occupation, $education )
	{
		$xmlPayload = '<?xml version="1.0" encoding="UTF-8"?>
					<MSALead>
					   <LeadSourceData>
					      <AffiliateLogin>'.$affiliateLogin.'</AffiliateLogin>
					      <AffiliatePassword>'.$affiliatePassword.'</AffiliatePassword>
					   </LeadSourceData>
					   <DistributionDirectives DistributionCap="3">
					   <Directive Action="Include">21st Century</Directive>
					   <Directive Action="Include">Allstate</Directive>
					   <Directive Action="Include" Detail="1234567">Independent</Directive>
					   </DistributionDirectives>
					   <LeadData>
					      <ContactDetails>
					        <FirstName>'.$lmsDataJsonDecoded['firstname'].'</FirstName>
							<LastName>'.$lmsDataJsonDecoded['lastname'].'</LastName>
							<StreetAddress>'.$lmsDataJsonDecoded['address'].'</StreetAddress>
							<City>'.$lmsDataJsonDecoded['city'].'</City>
							<State>'.$lmsDataJsonDecoded['state'].'</State>
							<ZIPCode>'.$lmsDataJsonDecoded['zip'].'</ZIPCode>
							<Email>'.$lmsDataJsonDecoded['emailaddress'].'</Email>
							<PhoneNumbers>
								<PhoneNumber Type="Work">
									<Number>'.str_replace('-', '', $lmsDataJsonDecoded['homephone']).'</Number>
								</PhoneNumber>
								<PhoneNumber Type="Home">
									<Number>'.str_replace('-', '', $lmsDataJsonDecoded['homephone']).'</Number>
								</PhoneNumber>
							</PhoneNumbers>
							<ResidenceStatus YearsAt="'.$lmsDataJsonDecoded['driver1yearsatresidence'].'" MonthsAt="1">'.$lmsDataJsonDecoded['currentresidence'].'</ResidenceStatus>
					      </ContactDetails>
					      <InsurancePolicy>
					         <NewPolicy>
					            <RequestedCoverage>'.$lmsDataJsonDecoded['desiredcoveragetype'].'</RequestedCoverage>
					         </NewPolicy>
					         <PriorPolicy CurrentlyInsured="Yes">
					            <InsuranceCompany YearsWith="1" MonthsWith="1">'.$companyID.'</InsuranceCompany>
					            <PolicyExpirationDate>'.$lmsDataJsonDecoded['currentpolicyexpiration'].'</PolicyExpirationDate>
					            <YearsContinuous>1</YearsContinuous>
					            <MonthsContinuous>1</MonthsContinuous>
					         </PriorPolicy>
					      </InsurancePolicy>
					      <AutoLead>
					         <Vehicles>
					            <Vehicle VehicleID="1" Ownership="'.( strtolower($lmsDataJsonDecoded['vehicle1ownership']) == 'leased' ? 'No':'Yes').'">
					               <VIN>JT2JA81L0S0000000</VIN>
					               <VehUse AnnualMiles="'.$lmsDataJsonDecoded['vehicle1annualMileage'].'" WeeklyCommuteDays="'.$lmsDataJsonDecoded['vehicle1commutedays'].'" DailyCommuteMiles="'.$lmsDataJsonDecoded['vehicle1commuteAvgMileage'].'">'.$vehUse.'</VehUse>
					               <ComphrensiveDeductible>'.$lmsDataJsonDecoded['desiredcomprehensivedeductible'].'</ComphrensiveDeductible>
					               <CollisionDeductible>'.$lmsDataJsonDecoded['desiredcollisiondeductible'].'</CollisionDeductible>
					               <GarageType>'.$garageType.'</GarageType>
					            </Vehicle>
					         </Vehicles>
					         <Drivers>
					            <Driver DriverID="1">
					               <PersonalInfo Gender="'.$lmsDataJsonDecoded['driver1gender'].'" MaritalStatus="'.$lmsDataJsonDecoded['driver1maritalstatus'].'" RelationshipToApplicant="Other">
					               	  <FirstName>'.$lmsDataJsonDecoded['firstname'].'</FirstName>
									  <LastName>'.$lmsDataJsonDecoded['lastname'].'</LastName>
					                  <BirthDate>'.$lmsDataJsonDecoded['driver1dob_year'].'-'.$lmsDataJsonDecoded['driver1dob_day'].'-'.$lmsDataJsonDecoded['driver1dob_month'].'</BirthDate>
					                  <Occupation>'.$occupation.'</Occupation>
					                  <MilitaryExperience>No Military Experience</MilitaryExperience>
					                  <Education GoodStudentDiscount="Yes">'.$education.'</Education>
					                  <CreditRating Bankruptcy="No">Unsure</CreditRating>
					               </PersonalInfo>
					               <PrimaryVehicle>1</PrimaryVehicle>
					               <DriversLicense LicenseEverSuspendedRevoked="No">
					                  <State>'.$lmsDataJsonDecoded['state'].'</State>
					                  <LicensedAge>'.$lmsDataJsonDecoded['driver1licenseage'].'</LicensedAge>
					               </DriversLicense>
					               <DrivingRecord SR22Required="'.( strtolower($lmsDataJsonDecoded['driver1sr22'])  == 'false'  ? 'No':'Yes').'" DriverTraining="No" />
					            </Driver>
					         </Drivers>
					      </AutoLead>
					   </LeadData>
					</MSALead>
						';
		
		return $xmlPayload;
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
				  "driver1credit":"Unsure",
				  "driver1yearsemployed":"4",
				  "driver1age":"26",
				  "currentpolicyexpiration":"2013-08-01",
				  "policystart":"2012-08-06",
				  "insuredsince":"2011-02-12",
				  "CURRENTINSURANCECOMPANY":"Infinity Insurance",
				  "desiredcoveragetype":"Basic Protection",
				  "desiredcollisiondeductible":"500",
				  "desiredcomprehensivedeductible":"500",
				  "propertydamage":"300",
				  "contact":"Morning",
				  "yearsatresidence":"10",
				  "bodilyinjury":"50",
				  "vehicle1year":"2010",
				  "vehicle1make":"Hyundai",
				  "vehicle1model":"ACCENT",
				  "vehicle1commuteAvgMileage":"8",
				  "vehicle1annualMileage":"25000",
				  "vehicle1primaryUse":"Pleasure",
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
	$pingUrl = 'https://services.mossexchange.com/leadhandler/lead.aspx';
	$affiliateLogin = 'CD13019';
	$affiliatePassword = 'buQuhA8';
	$pingResponseID = '4bdfee278adf4970ab93b5631b20ba24';
	

	$insuranceCompanies = array(1=>"21st Century",
								2=>"AAA",
								3=>"AABCO",
								4=>"AARP",
								5=>"Access Insurance",
								6=>"Acordia",
								7=>"Aegis Security",
								8=>"AETNA",
								9=>"Affirmative",
								10=>"AFLAC",
								11=>"AHCP",
								12=>"AIG",
								13=>"AIU",
								417=>"Alfa Insurance",
								14=>"All Nation",
								15=>"All Risk",
								16=>"Allianz",
								17=>"Allied",
								18=>"Allstate",
								19=>"American Alliance",
								20=>"American Automobile Insurance",
								21=>"American Banks",
								22=>"American Casualty",
								23=>"American Deposit Insurance",
								24=>"American Direct Business Insurance",
								25=>"American Economy",
								26=>"American Empire Insurance",
								27=>"American Family",
								28=>"American Financial",
								29=>"American Health Underwriters",
								30=>"American Home Assurance",
								31=>"American Insurance",
								32=>"American International",
								33=>"American International Pacific",
								34=>"American International South",
								35=>"American Manufacturers",
								36=>"American Mayflower Insurance",
								37=>"American Medical Securities",
								38=>"American Motorists Insurance",
								39=>"American National",
								40=>"American National Property and Casualty",
								41=>"American Premier",
								42=>"American Protection Insurance",
								43=>"American Reliable",
								44=>"American Republic",
								45=>"American Savers Plan",
								46=>"American Service Insurance",
								47=>"American Skyline Insurance Company",
								48=>"American Spirit Insurance",
								49=>"American Standard",
								50=>"American States",
								51=>"America's Ins. Consultants",
								52=>"AmeriPlan",
								53=>"Amerisure",
								54=>"Amica",
								55=>"Answer Financial",
								56=>"Anthem",
								57=>"API",
								58=>"Arbella",
								59=>"Arizona General",
								60=>"Armed Forces Insurance",
								61=>"Assigned Risk",
								62=>"Associated Indemnity",
								63=>"Associated Insurance Managers",
								64=>"Assurant",
								65=>"Atlanta Casualty",
								66=>"Atlanta Specialty",
								67=>"Atlantic Indemnity",
								68=>"Atlantis",
								69=>"Austin Mutual",
								70=>"Auto Club Insurance Company",
								71=>"Auto Owners",
								72=>"Avomark",
								73=>"AXA Advisors",
								74=>"Badger Mutual",
								75=>"Bankers & Shippers",
								76=>"Bankers Life and Casualty",
								77=>"Banner Life",
								78=>"Best Agency USA",
								79=>"Blue Cross",
								80=>"Blue Cross / Blue Shield",
								81=>"Blue Shield of California",
								82=>"Bonneville",
								83=>"Boston Old Colony",
								84=>"Brooke Insurance",
								85=>"Builders",
								86=>"Cal Farm Insurance",
								87=>"California Casualty",
								88=>"California State Automobile Association",
								89=>"Camden",
								90=>"Capital Choice",
								91=>"Care Entr?e",
								92=>"Cascade National Ins",
								93=>"Casualty Assurance",
								423=>"Celtic Insurance",
								94=>"Centennial",
								95=>"Charter Oak",
								96=>"Chase Insurance Group",
								97=>"Chicago Insurance",
								98=>"Chubb",
								99=>"Church Mutual",
								100=>"Cigna",
								101=>"Citizens",
								102=>"Clarendon",
								103=>"Clarendon National Insurance",
								104=>"Cloverleaf",
								105=>"CNA",
								106=>"Colonial",
								107=>"Combined",
								108=>"Commercial Union",
								109=>"Commonwealth",
								110=>"Company Not Listed",
								111=>"Comparison Market",
								112=>"Continental",
								113=>"Continental Casualty",
								114=>"Continental Divide Insurance",
								115=>"Cotton States",
								116=>"Cottonwood",
								117=>"Country Insurance and Financial Services",
								118=>"Countrywide",
								119=>"Credit Union",
								120=>"Criterion",
								121=>"CSE Insurance Group",
								122=>"CUNA Mutual",
								123=>"Dairyland",
								124=>"Dakota Fire",
								125=>"Deerbrook",
								126=>"Depositors Emcasc",
								127=>"Dixie",
								128=>"Ebco General",
								129=>"Economy",
								130=>"Economy Fire & Casualty",
								131=>"Economy Preferred",
								435=>"eFinancial",
								132=>"eHealth",
								133=>"Electric Insurance",
								418=>"Elephant",
								134=>"EMC",
								135=>"Empire",
								136=>"Employers Fire",
								137=>"Ensure",
								138=>"Equitable Life",
								139=>"Erie",
								140=>"Esurance",
								141=>"Explorer",
								142=>"Facility",
								143=>"Farm and Ranch",
								144=>"Farm Bureau",
								145=>"Farm Bureau St. Pau",
								146=>"Farmers",
								147=>"Farmers Union",
								148=>"Farmland",
								149=>"Federal",
								150=>"Federated",
								151=>"Fidelity Insurance Company",
								152=>"FinanceBox.com",
								153=>"Financial Indemnity",
								154=>"Fire and Casualty Insurance Co of CT",
								155=>"Firemans Fund",
								156=>"First Acceptance Insurance",
								157=>"First American",
								158=>"First Financial",
								159=>"First General",
								160=>"First National",
								161=>"Ford Motor Credit",
								162=>"Foremost",
								163=>"Foresters",
								164=>"Fortis",
								165=>"Franklin",
								166=>"Geico",
								167=>"General Accident",
								168=>"General Insurance",
								169=>"Genworth Financial",
								170=>"Globe",
								171=>"GMAC",
								172=>"Golden Rule (map to United Healthcare)",
								173=>"Golden Rule Insurance",
								174=>"Government Employees",
								175=>"Grange",
								176=>"GRE Harleysville H",
								177=>"Great American",
								178=>"Great Way",
								179=>"Great West",
								180=>"Grinnell Mutual",
								181=>"Guaranty National",
								182=>"Guardian",
								183=>"Guide One",
								184=>"Halcyon",
								185=>"Hanover Lloyd's Insurance Company",
								186=>"Happy Days",
								187=>"Hartford",
								188=>"Hartford AARP",
								424=>"Harvard Pilgrim",
								189=>"Hawkeye Security",
								190=>"Health Benefits Direct",
								425=>"Health Care Solutions",
								191=>"Health Choice One",
								426=>"Healthmarkets",
								192=>"Health Net",
								193=>"Health Plus of America",
								194=>"HealthShare American",
								195=>"Heritage",
								440=>"Homeinsurance",
								427=>"Homeland Health",
								196=>"Horace Mann",
								197=>"Humana",
								198=>"IAB",
								199=>"IDS",
								200=>"IFA Auto Insurance",
								201=>"IGF",
								419=>"IIS Insurance",
								202=>"Independent",
								203=>"Infinity",
								428=>"InSphere",
								204=>"Insur. of Evanston",
								205=>"Insurance Insight",
								206=>"Insurance.com",
								207=>"Integon",
								208=>"Interstate",
								429=>"Investors Life",
								209=>"John Deere",
								210=>"John Hancock",
								211=>"Kaiser Permanente",
								212=>"Kemper",
								213=>"Kentucky Central",
								214=>"Landmark American Insurance",
								215=>"Leader Insurance",
								216=>"League General",
								217=>"Liberty Insurance Corp",
								218=>"Liberty Mutual",
								219=>"Liberty National",
								220=>"Liberty Northwest",
								430=>"LifeWise Health Plan",
								221=>"Lincoln Benefit Life",
								222=>"LTC Financial Partners",
								223=>"Lumbermens Mutual",
								224=>"Marathon",
								225=>"Markel American",
								226=>"Maryland Casualty",
								227=>"Mass Mutual",
								436=>"Matrix Direct",
								228=>"MEGA Life and Health",
								229=>"Mega/Midwest",
								230=>"Mendota",
								231=>"Merastar",
								232=>"Mercury",
								233=>"MetLife",
								431=>"Metropolitan Insurance Co.",
								234=>"Mid Century Insurance",
								235=>"Mid-Continent",
								236=>"Middlesex Insurance",
								237=>"Midland National Life",
								238=>"Midwest Mutual",
								239=>"Millbank",
								240=>"Millers Mutual",
								241=>"Milwaukee",
								242=>"Milwaukee General",
								243=>"Milwaukee Guardian",
								244=>"Milwaukee Mutual",
								245=>"Minnehoma",
								246=>"Missouri General",
								247=>"Modern Woodmen of America",
								248=>"Mony Group",
								249=>"Mortgage Protection Bureau",
								250=>"Motors",
								251=>"Mountain Laurel",
								252=>"Mutual Insurance",
								253=>"Mutual Of Enumclaw",
								437=>"Mutual of New York",
								254=>"Mutual Of Omaha",
								255=>"National Alliance",
								256=>"National Ben Franklin Insurance",
								257=>"National Casualty",
								258=>"National Colonial",
								259=>"National Continental",
								260=>"National Fire Insurance Company of Hartford",
								261=>"National Indemnity",
								262=>"National Insurance",
								263=>"National Merit",
								264=>"National Surety Corp",
								265=>"National Union Fire Insurance",
								266=>"National Union Fire Insurance of LA",
								267=>"National Union Fire Insurance of PA",
								268=>"Nationwide",
								269=>"Nat'l Farmers Union",
								270=>"New England Financial",
								271=>"New York Life",
								272=>"North American",
								273=>"North Pacific",
								274=>"North Pointe",
								275=>"North Shore",
								276=>"Northern Capital",
								277=>"Northern States",
								278=>"Northland",
								279=>"Northwest Pacific",
								280=>"Northwestern",
								281=>"Northwestern Mutual",
								282=>"Northwestern Mutual Life",
								283=>"Northwestern Pacific Indemnity",
								420=>"NuStar Insurance",
								284=>"Ohio Casualty",
								285=>"Ohio Security",
								286=>"Olympia",
								287=>"Omaha",
								288=>"Omni Ins.",
								289=>"Omni Insurance",
								290=>"Oregon Mutual",
								291=>"Orion Ins.",
								292=>"Orion Insurance",
								432=>"Oxford Health Plans",
								433=>"PacifiCare",
								293=>"Pacific Indemnity",
								294=>"Pacific Insurance",
								295=>"Pafco",
								296=>"Paloverde",
								297=>"Patriot General",
								298=>"Peak Property and Casualty Insurance",
								299=>"Pemco",
								300=>"Penn America",
								301=>"Penn Mutual",
								302=>"Pennsylvania Natl",
								303=>"Phoenix",
								304=>"Physicians",
								305=>"Pinnacle",
								306=>"Pioneer Life",
								422=>"Plymouth Rock",
								307=>"Preferred Abstainers",
								308=>"Preferred Mutual",
								309=>"Premier",
								310=>"Prestige",
								311=>"Primerica",
								312=>"Principal Financial",
								313=>"Progressive",
								314=>"Protective Life",
								315=>"Provident",
								316=>"Prudential",
								317=>"Quality",
								318=>"Ramsey",
								319=>"Ranger",
								320=>"RBC Liberty",
								321=>"RBC Liberty Insurance",
								322=>"Regal",
								323=>"Reliance",
								324=>"Reliant",
								325=>"Republic Indemnity",
								326=>"Response Insurance",
								327=>"Rockford Mutual",
								328=>"Rodney D. Young",
								330=>"Safeco",
								421=>"Safeguard",
								331=>"Safeway",
								332=>"Sea West Insurance",
								333=>"Secura",
								334=>"Security Insurance",
								335=>"Security National",
								336=>"Sedgwick James",
								337=>"Sentinel Insurance",
								338=>"Sentry",
								339=>"Shelter",
								340=>"Skandia TIG Tita",
								341=>"Spectrum",
								342=>"St. Paul",
								343=>"Standard Fire Insurance Company",
								344=>"Standard Guaranty",
								345=>"State and County Mutual Fire Insurance",
								346=>"State Auto",
								347=>"State Farm",
								348=>"State Mutual",
								349=>"State National",
								350=>"Sun Coast",
								351=>"Superior",
								352=>"Superior Guaranty Insurance",
								353=>"Sure Health Plans",
								354=>"Sutter",
								355=>"The Ahbe Group",
								356=>"The Credo Group",
								357=>"The General",
								358=>"TICO Insurance",
								359=>"TIG Countrywide Insurance",
								360=>"Titan",
								361=>"Total",
								362=>"Tower",
								363=>"TransAmerica",
								364=>"Travelers",
								365=>"Trinity Universal",
								366=>"Tri-State Consumer",
								367=>"Trust Hall",
								434=>"Tufts Health Plan",
								368=>"Twentieth Century",
								369=>"Twin City Fire Insurance",
								370=>"Unicare",
								371=>"Uniguard",
								372=>"Union",
								373=>"United American",
								374=>"United Financial",
								375=>"United Fire & Casual",
								376=>"United Health Care (also Golden Rule)",
								377=>"United Insurance",
								438=>"United Pacific Insurance",
								378=>"United Security",
								379=>"United Services Auto",
								380=>"United States Fideli",
								381=>"Unitrin",
								382=>"Universal Underwriters Insurance",
								383=>"US Financial",
								384=>"US Health Advisors",
								439=>"US Health Group",
								385=>"USA Benefits/Continental General",
								386=>"USAA",
								387=>"USF and G",
								388=>"USF&G",
								389=>"Utah Home and Fire",
								390=>"Utica",
								391=>"Vasa North Atlantic",
								392=>"Vigilant",
								393=>"Viking",
								394=>"Wawaunesa",
								395=>"Wellington",
								396=>"Wellpoint",
								397=>"West American",
								398=>"West Bend Mutual",
								399=>"West Field",
								400=>"West Plains",
								401=>"Western and Southern Life",
								402=>"Western Mutual",
								403=>"Western National",
								404=>"Western Southern Life",
								405=>"Westfield",
								406=>"William Penn",
								407=>"Windsor",
								408=>"Windstar",
								409=>"Wisconsin Mutual",
								410=>"Woodlands Financial Group",
								411=>"Workmans Auto",
								412=>"World Insurance",
								413=>"Worldwide",
								414=>"Yellow Key",
								415=>"Yosemite",
								416=>"Zurich North America");
	$companyID = array_search($lmsDataJsonDecoded['CURRENTINSURANCECOMPANY'], $insuranceCompanies);
	
	if(FALSE == $companyID)
	{
		$companyID = 1;
	}
	
	$possibleVehUse = array('CommuteWork', 'CommuteSchool', 'CommuteVaries', 'Farm', ' Government', 'Pleasure', 'Business', 'Other');
	$vehUse = getClosestWord($possibleVehUse, $lmsDataJsonDecoded['vehicle1primaryUse']);
	$possibleGarageType = array('No Cover', 'Carport', 'Garage', 'Locked', 'Other', 'Parking Lot', 'Private', 'Street');
	$garageType = getClosestWord($possibleGarageType, $lmsDataJsonDecoded['vehicle1garageType']);
	$possibleOccupations = array("Administrative Clerical","Advertising","Appraiser","Architect","Artist","Assembler","Baker","Banker","Bank Teller","Bartender","Broker","Business Owner","Cashier","Casino Worker","CEO","Certified Public Accountant","Chemist","ChildCare","CityWorker","Clergy","Construction Trades","Consultant","Contractor","Decorator","Delivery Driver","Dentist","Disabled","Electrician","Engineer","Entertainer","Farmer","FireFighter","Government","Health Care","Homemaker","Hospitality Travel","Human Relations","Human Resources","Journalist","Lab Technician","Lawyer","Marketing","Manager Supervisor","Military Officer","Military Enlisted","Military E1E4","Military E5E9","Military Other","Minor Not Applicable","Model","Other Non Technical","Other Technical","Paralegal","Paramedic","Photographer","Physician","Police Officer","Postal Worker","Professional Salaried","Professor","Real Estate","Receptionist","Retail","Retired","Sales","Sales Inside","Sales Outside","School Teacher","Scientist","Self Employed","Skilled Semi Skilled","Student","Teacher","Technology","Telecommunications","Transportation or Logistics","Unemployed","WaiterWaitress","Not Listed");
	$occupation = getClosestWord($possibleOccupations, $lmsDataJsonDecoded['driver1occupation']);
	$possibleEducationLvl = array('Some Or No HighSchool', 'HighSchool Diploma', 'GED', 'Associate Degree', 'Bachelors Degree', 'Masters Degree', 'Doctorate Degree', 'Other Professional Degree', 'Other NonProfessional Degree', 'Some College', 'Trade Vocational School');
	$education = getClosestWord($possibleEducationLvl, $lmsDataJsonDecoded['driver1edulevel']);


	$xmlPayload = getXmlPayload(  $lmsDataJsonDecoded, $affiliateLogin, $affiliatePassword, $companyID, $vehUse, $garageType, $occupation, $education );
	
	$response = getCurlRequest($pingUrl, array('ProductType' => 'auto',
											   'RequestType' => 'post',
											   'LeadData'	 => $xmlPayload,
											   'PingResponseID' => $pingResponseID
											  )
							  );
	var_dump($response);
	
	
	
	
	
	
	
	
	
	

	