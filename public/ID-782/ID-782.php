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
		
		$url = 'https://www.net-lead-apps.net/web_post_lead2.php';
		$params = array('source'    => 'underground',
						'source_id' => $lmsDataJsonDecoded['sourcedeliveryid'],
						'agency_id' => '02479.5180',
						'leadtype'  => 'auto',
						'auto_driver_count'  => 1,
						'auto_vehicle_count' => 1
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