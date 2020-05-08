<?php 
namespace Textents;


class Textents{

    private $apiKey;

    private $token;

    public $apiBase = 'https://api.textents.com';

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
        if(empty($this->token)){
        	$this->token = $this->login($this->apiKey);
        }
    }

    private function curl_it($endPoint, $requestType = "GET", $param ="", $token = ""){
    	$curl = curl_init();
    	if(!is_array($param)){
			curl_setopt_array($curl, array(
			  CURLOPT_URL => $this->apiBase.$endPoint.$param,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 0,
			  CURLOPT_FOLLOWLOCATION => true,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => $requestType,
			  CURLOPT_HTTPHEADER => array(
			    "Authorization: Bearer ".$token,
			    "Content-Type: text/plain"
			  ),
			));

    	}else{
    		curl_setopt_array($curl, array(
			  CURLOPT_URL => $this->apiBase.$endPoint,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 0,
			  CURLOPT_FOLLOWLOCATION => true,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => $requestType,
			  CURLOPT_POSTFIELDS =>json_encode($param),
			  CURLOPT_HTTPHEADER => array(
			    "Content-Type: text/plain",
  			    "Authorization: Bearer ".$token,

			  ),
			  CURLOPT_VERBOSE => true,


			));
    	}

		$response = curl_exec($curl);

		curl_close($curl);
		return $response;
    }


    public function login(){

    	$endPoint = "/login";
    	$param = array("key"=>$this->apiKey);
    	$token = $this->curl_it($endPoint, "POST", $param);
    	$token = json_decode($token);
    	return $token->token;

    }

    public function getInsolvencyJudicalDecisions($CompanyRegistrationNumber){
    	$endPoint = "/insolvency/";
    	$decisions = $this->curl_it($endPoint, "GET", $CompanyRegistrationNumber, $this->token);

    	return json_decode($decisions);
    }
}