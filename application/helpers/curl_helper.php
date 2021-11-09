<?php

if(!function_exists('curl_get')){

	function curl_get($param=array()){

		if(is_array($param) && isset($param['url']) && $param['url']!=''){

			if(isset($param['port'])){
				$curl_array=array(
				  CURLOPT_PORT => $param['port'],
				  CURLOPT_URL => $param['url'],
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => "",
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 30,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => "GET",
				  CURLOPT_HTTPHEADER => array("Cache-Control: no-cache")
				);
			}else{
				$curl_array=array(
				  CURLOPT_URL => $param['url'],
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => "",
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 30,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => "GET",
				  CURLOPT_HTTPHEADER => array("Cache-Control: no-cache")
				);
			}

			// return $curl_array;

			$curl = curl_init();
			curl_setopt_array($curl,$curl_array);
			$response = curl_exec($curl);
			$err = curl_error($curl);
			curl_close($curl);

			if ($err) {
			  $return['errors']=$err;
			} else {
			  $return['success']=$response;
			}
		}else{
			$return['errors']='Endpoint Url is not valid';
		}

		return $return;
	}

}


if(!function_exists('curl_post')){

	function curl_post($param=array()){

		if(is_array($param) && isset($param['url']) && $param['url']!=''){
			$curl = curl_init();

			if(isset($param['data']) && !empty($param['data'])){
				curl_setopt_array($curl, array(
				  CURLOPT_URL => $param['url'],
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => "",
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 30,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => "POST",
				  CURLOPT_POSTFIELDS => json_encode($param['data']),
				  CURLOPT_HTTPHEADER => array(
				    "Cache-Control: no-cache",
				    "Content-Type: application/json"
				  ),
				));

				$response = curl_exec($curl);
				$err = curl_error($curl);

				curl_close($curl);

				if ($err) {
				  $return['errors']=$err;
				} else {
				  $return['success']=$response;
				}
			}else{
				$return['errors']='Endpoint Data not valid';
			}	
		}else{
			$return['errors']='Endpoint Url is not valid';
		}

		return $return;
	}

}