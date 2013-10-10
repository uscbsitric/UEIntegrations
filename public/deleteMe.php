<?php


$variable = explode('-', '5-8');
echo "<pre>";
var_dump($variable); exit();

$obj1 = new stdClass();
var_dump($obj1);
exit();


/*
var_dump(urldecode('id=3720541&lms_lead_id=3720541&poststring=%7B%22vertical%22%3A%22lins%22%2C%22name%22%3A%22Henry%22%2C%22lastname%22%3A%22Bryant%22%2C%22emailaddress%22%3A%22deshelle.bryant%40acs.k12.sc.us%22%2C%22address%22%3A%22PO+Box+664%22%2C%22city%22%3A%22Allendale%22%2C%22_City%22%3A%22Allendale%22%2C%22state%22%3A%22SC%22%2C%22st%22%3A%22SC%22%2C%22_State%22%3A%22SC%22%2C%22zip%22%3A%2229810%22%2C%22_PostalCode%22%3A%2229810%22%2C%22homephone%22%3A%228033000389%22%2C%22ueid%22%3A%22glsr_1165213f3e2ce260_BND_PH_AFLC%22%2C%22country_code%22%3A%221%22%2C%22cam%22%3A%22BND_PH_AFLC%22%2C%22querystring%22%3A%22seed%3DAflac%2520Life%2520Insurance%2520Cost%26t%3DAflac%22%2C%22useragent%22%3A%22Mozilla%2F4.0+%28compatible%3B+MSIE+8.0%3B+Windows+NT+5.1%3B+Trident%2F4.0%3B+GTB7.5%3B+.NET+CLR+1.1.4322%3B+.NET+CLR+2.0.50727%3B+.NET+CLR+3.0.04506.30%3B+.NET+CLR+3.0.4506.2152%3B+.NET+CLR+3.5.30729%3B+MDDR%3B+InfoPath.2%3B+AskTbMP3R7%2F5.17.7.45269%29%22%2C%22ipaddress%22%3A%2272.159.147.3%22%2C%22sid%22%3A%22wholesaleinsurance.info%22%2C%22AFID%22%3A%2243074%22%2C%22referer%22%3A%22http%3A%2F%2Fwww.google.com%2Faclk%3Fsa%3Dl%26ai%3DCH6Uv0IAoUralCob76QH584GoBqSZmKME5LiPh2O89fTxvgEIABACILGK3wYoA1Cew8nEAWDJhoCAgICkEKABvKXs2QPIAQGqBCVP0PhWOXluO1mbawYbwjR2QNJQiiTIe8j5-ez6kBLptr3u8NRCgAWQToAHrNqTJg%26sig%3DAOD64_1sqM4-VgeAziQxya3lS4NDnIJvKg%26rct%3Dj%26frm%3D1%26q%3Dhow%2Bmuch%2Bdoes%2Baflac%2Blife%2Binsurance%2Bcost%26ved%3D0CDMQ0Qw%26adurl%3Dhttp%3A%2F%2FAflac.wholesaleinsurance.info%2FGSEARCH%2Fueid%2Fglsr_1165213f3e2ce260_BND_PH_AFLC%2F%253Fseed%253DAflac%252520Life%252520Insurance%252520Cost%2526t%253DAflac%26surl%3D1%26safe%3Dactive%22%2C%22leadtype%22%3A%22lifeins%22%2C%22keyword%22%3A%22GSEARCH%22%2C%22variant%22%3A%22mom_daughter_one_new_longnumb_slidein_P3TCPA%22%2C%22sureHitsFeedId%22%3A%22%22%2C%22dob_day%22%3A%2225%22%2C%22dob_month%22%3A%2210%22%2C%22dob_year%22%3A%221946%22%2C%22height%22%3A%225-4%22%2C%22weight%22%3A%22182%22%2C%22gender%22%3A%22MALE%22%2C%22tobacco%22%3A%22no%22%2C%22existingconditionstoggle%22%3A%22yes%22%2C%22existingconditions%22%3A%22%22%2C%22termlength%22%3A%22whole%22%2C%22coverageamount%22%3A%2225000%22%2C%22homephone_area%22%3A%22803%22%2C%22homephone_prefix%22%3A%22300%22%2C%22homephone_suffix%22%3A%220389%22%2C%22cookie%22%3A%2221ba528b0a5be811f425521b69f7632c%22%2C%22keywords%22%3A%22search%7Cgoogle%7Chow+much+does+aflac+life+insurance+cost%7Cseed%3DAflac%2520Life%2520Insurance%2520Cost%26t%3DAflac%7CGSEARCH%7Cmom_daughter_one_new_longnumb_slidein_P3TCPA%22%2C%22vendoremail%22%3A%22google%22%2C%22vendorpassword%22%3A%22ueint%22%2C%22keyword_id%22%3A%222682%22%2C%22variant_id%22%3A%2227506%22%2C%22site_id%22%3A%22330%22%2C%22hid%22%3A%22nvt-node6%22%2C%22dynotrax_id%22%3A%22522880d3e3af649b2c000007%22%7D&insert_timestamp=2013-09-05+06%3A10%3A05&lead_id=&dynotrax_id=522880d3e3af649b2c000007&name=Henry&lastname=Bryant&city=Allendale&st=SC&address=PO+Box+664&zip=29810&workphone=&homephone=8033000389&propertytype=&currentvalue=0.00&desired_loan_amount=0.00&loan_amount=0.00&first_balance=0.00&rate=0.0000&loantype=&payment=0.00&mtg_payment=0.00&behind=30&credit=&place_of_employment=&years_of_employment=&income=0.00&best_time_to_call=&loanpurpose=&emailaddress=deshelle.bryant%40acs.k12.sc.us&date=2013-09-05+06%3A10%3A05&vendorid=101&sid=wholesaleinsurance.info&capturetime=2013-09-05+06%3A10%3A04&ipaddress=72.159.147.3&rejected=&name_of_college=&edu_dynamics_urls=&cunet_urls=&debt_amount=&dob=0000-00-00&ssn=&leadtype=lifeins&contact_email=&contact_phone=&contact_snail_mail=&response=&wrong_fields=&tax_lien=&tax_lien_amount=0&request_mastercard=&assets=&debt_type=&secured_debt_amount=0&tax_debt_amount=0&coverage_type=&rate_class=&coverage_amount=0&gender=MALE&employed=&status=transmitted&notice_default=&comments=&bankruptcy=&hardship=&lender=&secondrate=0.00&second_balance=0.00&property_use=&loan_program=&employment_status=&pastdue=&employer_name=&employment_length=&referer=http%3A%2F%2Fwww.google.com%2Faclk%3Fsa%3Dl%26ai%3DCH6Uv0IAoUralCob76QH584GoBqSZmKME5LiPh2O89fTxvgEIABACILGK3wYoA1Cew8nEAWDJhoCAgICkEKABvKXs2QPIAQGqBCVP0PhWOXluO1mbawYbwjR2QNJQiiTIe8j5-ez6kBLptr3u8NRCgAWQToAHrNqTJg%26sig%3DAOD64_1sqM4-VgeAziQxya3lS4NDnIJvKg%26rct%3Dj%26frm%3D1%26q%3Dhow%2Bmuch%2Bdoes%2Baflac%2Blife%2Binsurance%2Bcost%26ved%3D0CDMQ0Qw%26adurl%3Dhttp%3A%2F%2FAflac.wholesaleinsurance.info%2FGSEARCH%2Fueid%2Fglsr_1165213f3e2ce260_BND_PH_AFLC%2F%253Fseed%253DAflac%252520Life%252520Insurance%252520Cost%2526t%253DAflac%26surl%3D1%26safe%3Dactive&useragent=Mozilla%2F4.0+%28compatible%3B+MSIE+8.0%3B+Windows+NT+5.1%3B+Trident%2F4.0%3B+GTB7.5%3B+.NET+CLR+1.1.4322%3B+.NET+CLR+2.0.50727%3B+.NET+CLR+3.0.04506.30%3B+.NET+CLR+3.0.4506.2152%3B+.NET+CLR+3.5.30729%3B+MDDR%3B+InfoPath.2%3B+AskTbMP3R7%2F5.17.7.45269%29&keywords=search%7Cgoogle%7Chow+much+does+aflac+life+insurance+cost%7Cseed%3DAflac%2520Life%2520Insurance%2520Cost%26t%3DAflac%7CGSEARCH%7Cmom_daughter_one_new_longnumb_slidein_P3TCPA&cookie=21ba528b0a5be811f425521b69f7632c&pubid=&targus_score=&targus_detail=0&targus_append=&ipstate=SC&ipcountry=US&isp=South+Carolina+Budget+%26+Control+Board&hid=nvt-node6&middlename=&monthsatresidence=0&ismilitary=0&dlnumber=&dlstate=&employmenttype=&jobtitle=&payperiod=&monthsatwork=0&employeraddress=&mothersmaiden=&bankname=&accounttype=&directdeposit=0&routingnumber=&accountnumber=&accountactivelength=0&paydate1=&paydate2=&loancount=0&referencefirstname1=&referencelastname1=&referencephone1=&referencerelation1=&referencefirstname2=&referencelastname2=&referencephone2=&referencerelation2=&supervisor=&supervisorphone=&edulevel=&RN=0&TC=0&desirededulevel=&degree=&age=&whenstart=&transfercredits=&reverse_mortgage=0&degreeval=0&computeraccess=&careerval=0&career=&credits=0&gradyear=0&job=&job_seeking=&taxaction=0&taxstatus=&taxagency=&taxtype=&propst=&consumerobjective=&fhaloan=0&healthplan=&height=5-4&weight=182&selfemployed=0&currentinsured=0&beentreated=0&enrolled=0&DirectProgram=&BrandedProgram=&MatchedProgram=&MilitaryStatus=&optin=&home_equity_type=&cashborrow=0&PurchaseYear=0&propertyAddress=&propertyCity=&propertyState=&propertyZip=&rateType=&downpayment=0.00&purchasePrice=0.00&proofIncome=&realEstate=&lendingtree_radio=&keyword_id=2682&variant_id=27506&modality=&ueid=glsr_1165213f3e2ce260_BND_PH_AFLC&citizen=&lead_attribute_id=2258511&primary_language=&country=1&universal_leadid=&ebureau_transaction_id=1105425&ebureau_accept=1&ebureau_updated=0&marketing_id=0&modified_date=2013-09-05+06%3A10%3A28&vertical=lins&_City=Allendale&state=SC&_State=SC&_PostalCode=29810&country_code=1&cam=BND_PH_AFLC&querystring=seed%3DAflac%2520Life%2520Insurance%2520Cost%26t%3DAflac&AFID=43074&keyword=GSEARCH&variant=mom_daughter_one_new_longnumb_slidein_P3TCPA&sureHitsFeedId=&dob_day=25&dob_month=10&dob_year=1946&tobacco=no&existingconditionstoggle=yes&existingconditions=&termlength=whole&coverageamount=25000&homephone_area=803&homephone_prefix=300&homephone_suffix=0389&vendoremail=google&vendorpassword=ueint&site_id=330'
						   )
		);
*/
$data['vehicle[1][year]'] = 'sample';
var_dump($data);
exit();


$formdata = array('FirstName' => 'frederick',
				  'LastName' => 'lastname',
		          'EmailAddress' => 'emailaddres@somewhere.com',
				  'Address' => 'somewhere',
				  'City' => 'city',
				  'State' => 'ar',
				  'Zip' => 63101,
				  'Phone' => 7203088584,
				  'Ipaddress' => '67.176.102.216',
				  'DateofBirthDay' => 30,
				  'DateofBirthMonth' => 12,
				  'DateofBirthYear' => 1967,
				  'dob' => '1946-10-25',
				  'Height' => '5-7',
				  'Weight' => 185,
				  'Gender' => 'male',
				  'Tobacco' => 'no',
				  'ExistingConditions' => 'no',
				  'TermLength' => 10,
				  'CoverageAmount' => 25000
				 );
$postvars = http_build_query($formdata);

$curlHandle = curl_init('https://secure.velocify.com/Import.aspx?Provider=UndergroundElephant&Client=EquifirstInsuranceAgency&CampaignId=36');
curl_setopt($curlHandle, CURLOPT_POST, 1);
curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $postvars);
curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curlHandle, CURLOPT_TIMEOUT, 120);
curl_setopt($curlHandle, CURLOPT_FOLLOWLOCATION, 1);
$postResult = curl_exec($curlHandle);

var_dump($postResult);