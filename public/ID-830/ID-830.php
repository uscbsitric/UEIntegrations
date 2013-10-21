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
        public function _getRequest($leadType = 'autoins')
        {
        	$postvars = $this->buildParams($leadType);
        	
            return $postvars;
        }

        protected function _getTestRequest() {
                // for now our test uses same request
                return $this->getRequest();
        }

        protected function _executeRequest() {
                $options = array(
                        CURLOPT_POST => true,
                        CURLOPT_HTTPHEADER, array("Content-type: application/x-www-form-urlencoded"),
                        CURLOPT_SSL_VERIFYPEER => false,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_TIMEOUT => 120,
                        CURLOPT_FOLLOWLOCATION => true
                );
                $response = PingPostCommon::sendCurlRequest(
                        'https://secure.leads360.com/Import.aspx?Provider=UndergroundElephant&Client='.$this->config['Client'].'&CampaignId='.$this->config['CampaignId'],
                        'POST',
                        $this->getRequest(),
                        $options);

                return html_entity_decode($response);
        }

        protected function _executeTestRequest() {
                // TODO - no tests at the moment
                return '';
        }

        protected function _wasRequestSuccessful() {
                if (!$this->request) {
                        throw new Exception('No request sent yet');
                }

                if(trim($this->response) == 'Success') {
                        return true;
                }

                return false;
        }
        
        protected function buildParams($leadType)
        {
        	$id = $this->postObject->getLeadId();
        	$leadData = $this->postObject->getLeadPostData();
        	$postvars = '';

        	switch($leadType)
        	{
        		case 'health':
        			break;
        		case 'life':
        			$params = array('FirstName' => $leadData['name'],
		        					'LastName' => $leadData['lastname'],
		        					'EmailAddress' => $leadData['emailaddress'],
		        					'Address' => $leadData['address'],
		        					'City' => $leadData['city'],
		        					'State' => $leadData['state'],
		        					'Zip' => $leadData['zip'],
		        					'Phone' => $leadData['homephone'],
		        					'Ipaddress' => $leadData['ipaddress'],
		        					'DOB' => $leadData['dob_month'] . '' . $leadData['dob_day'] . '' . $leadData['dob_year'],
		        					'Height' => $leadData['height'],
		        					'Weight' => $leadData['weight'],
		        					'Gender' => ucfirst($leadData['gender']),
		        					'Tobacco' => $leadData['tobacco'],
		        					'ExistingConditions' => $leadData['existingconditionstoggle'],
		        					'TermLength' => $leadData['termlength'],
		        					'CoverageAmount' => $leadData['coverageamount']
		        					);
        				$postvars = http_build_query($params);
        			break;
        		default: //auto
	        			if ($leadData['desiredcollisiondeductible'] == 0)
	        			{
	        				$leadData['desiredcollisiondeductible'] = 'No Coverage';
	        			}
	        			
	        			if ($leadData['desiredcomprehensivedeductible'] == 0)
	        			{
	        				$leadData['desiredcomprehensivedeductible'] = 'No Coverage';
	        			}
	        			
	        			if (empty($leadData['homephone']))
	        			{
	        				$leadData['homephone'] = $vals['homephone_area'] . $vals['homephone_prefix'] . $vals['homephone_suffix'];
	        			}
	        			
	        			$postvars = http_build_query($leadData);
        			break;
        	}
        	
        	return $postvars;
        }
}

?>
