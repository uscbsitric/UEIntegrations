<?php

require_once __DIR__.'/../postRequest.php';
require_once __DIR__.'/../pingpostCommon.php';

/**
 * A post request to Leads360
 *
 * @package PingPost
 * @copyright (c) 2013-2014 Underground Elephant
 */
class PostRequest_Leads360 extends PostRequest
{
	// @name _getRequest
	// @purpose this is where necessary adjustments for the equivalent of $lmsData(auto/life/health insurance post string) from previous scripts
	public function _getRequest()
	{
		$id = $this->postObject->getLeadId();
		$leadData = $this->postObject->getLeadPostData();
		
		$postvars = http_build_query($leadData);
		return $postvars;
	}
	
	protected function _getTestRequest()
	{
		// for now our test uses same request
		return $this->getRequest();
	}
	
	// this is more or less where the equivalent of getCurlRequest() is placed/located
	protected function _executeRequest($url = '', $params = array())
	{
		$postVars = $this->_getRequest();
		$testUrl 	   = 'https://qa.leads.intergies.com/SubmitLead';
		$productionUrl = 'https://leads.intergies.com/SubmitLead';
		$params = array('pid' => 1046,
					    'cid' => 10105,
					    'afid' => 220996,
					    'tzt.person.FirstName' => 'firstname', // we dont have a mapping for health insurance post script
					    'tzt.person.LastName' => 'lastname', // we dont have a mapping for health insurance post script
					    'tzt.person.Address.AddressLine1' => 'address', // we dont have a mapping for health insurance post script
					    'tzt.person.Address.City' => $postVars['city'],
					    'tzt.person.Address.State' => $postVars['state'],
					    'tzt.person.Address.ZipCode' => $postVars['zip'],
					    'tzt.person.PhoneNo' => '6262012360', // we dont have a mapping for health insurance post script
					    'tzt.person.Gender' => 'M', // we dont have a mapping for health insurance post script
					    'tzt.person.DateOfBirth.Day' => 15,
					    'tzt.person.DateOfBirth.Month' => 10,
					    'tzt.person.DateOfBirth.Year' => 1910,
					    'tzt.person.EmailAddress' => 'someone@somewhere.com', // we dont have a mapping for health insurance post script
					   );
		$postVars = http_build_query($params);
		
		$options = array(CURLOPT_URL => $url,
						 CURLOPT_POST => true,
						 CURLOPT_RETURNTRANSFER => true,
						 CURLOPT_POSTFIELDS => $postVars,
						 CURLOPT_HTTPHEADER, array("Content-type: application/x-www-form-urlencoded"),
						 CURLOPT_SSL_VERIFYPEER => false,
						 //CURLOPT_TIMEOUT => 120,
						 //CURLOPT_FOLLOWLOCATION => true
				  		);

		$response = PingPostCommon::sendCurlRequest($url,
													'POST',
													$this->getRequest(),
													$options
												   );
	
		return html_entity_decode($response);
	}
	
	// I am not sure if function _executeRequest() will be called here, if so, function _executeRequest() can/should be refactored
	protected function _executeTestRequest()
	{
		// TODO - no tests at the moment
		return '';
	}
	
	protected function _wasRequestSuccessful()
	{
		if (!$this->request)
		{
			throw new Exception('No request sent yet');
		}
	
		if(trim($this->response) == 'Success')
		{
			return true;
		}
	
		return false;
	}
}