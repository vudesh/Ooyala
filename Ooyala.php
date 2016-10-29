<?php
class OoyalaAPI{
	public function generateURL($HTTP_method, $api_key, $secret_key, $expires, $request_path, $request_body = "", $parameters=array()) //Generating URL for PATCH Http Method
	{

		$parameters["api_key"] = $api_key;
		$parameters["expires"] = $expires;
		$signature = $this->generateSignature($HTTP_method, $secret_key, $request_path, $parameters, $request_body);
		$base = "https://api.ooyala.com";
		$url = $base.$request_path."?";
		foreach ($parameters as $key => $value) {
			$url .=  $key . "=" . urlencode($value) . "&";
		}
		$url .= "signature=" . $signature;
		return $url;
	}
	
	private function generateSignature($HTTP_method, $secret_key, $request_path, $parameters, $request_body = "") //Signature which needs to be added in the URL
	{
		$to_sign = $secret_key . $HTTP_method . $request_path;
		$keys = $this->sortKeys($parameters);
		foreach ($keys as $key) {
			$to_sign .= $key . "=" . $parameters[$key];
		}
		$to_sign .= $request_body;
		$hash = hash("sha256", $to_sign,true);
		$base = base64_encode($hash);
		$base = substr($base,0,43);
		$base = urlencode($base);
		return $base;
	}
	
	private function sortKeys($array)
	{
		$keys = array();$ind=0;
		foreach ($array as $key => $val) {
			$keys[$ind++]=$key;
		}
		sort($keys);
		return $keys;
	}
}

		$HTTP_method = "PATCH";
		$secret_key = "4RMGiqDWtXFuz5DuOVIXfGb7dq6K68yP6inDsglU";
		$request_path = "/v2/assets/V1MzhwNjE6JCOUobaA3TKh7HvGf4A9xb/";
		$request_body = $_POST["request_body"];   						 //New Title name 
		$request_body = '{'.'"name":"'.$request_body.'"'.'}'; 
		$api_key = "VsOGkyOqIcMcPLI9ebckMAUtem-4.U6hPS";
		$expires = time()+15; 
		$parameters["api_key"] = $api_key;
		$parameters["expires"] = $expires;

send_request($HTTP_method, $api_key, $secret_key, $expires, $request_path, $request_body, $parameters=array());

function send_request($HTTP_method, $api_key, $secret_key, $expires, $request_path, $request_body, $parameters=array())
		{
			$OoyalaObj = new OoyalaAPI;
			$url = $OoyalaObj->generateURL($HTTP_method, $api_key, $secret_key, $expires, $request_path, $request_body, $parameters=array());
			$headers = array('Content-Type: application/json');
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
			curl_setopt($curl, CURLOPT_POSTFIELDS, $request_body);
			curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
			$response = curl_exec($curl);
			curl_close($curl);
		}

		
	echo "You have successfully changed your Title";
?>