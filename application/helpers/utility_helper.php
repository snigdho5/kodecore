<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');



// --------------------------------------------------------------------

/**
 * Returns the global CI object
 *
 * @return 	object
 */
if (!function_exists('CI'))
{
	function CI() {
	    if (!function_exists('get_instance')) return FALSE;

	    $CI =& get_instance();
	    return $CI;
	}
}


	

// --------------------------------------------------------------------

/**
 * Capture content via an output buffer
 *
 * @param	boolean	turn on output buffering
 * @param	string	if set to 'all', will clear end the buffer and clean it
 * @return 	string	return buffered content
 */
if (!function_exists('capture'))
{
	function capture($on = TRUE, $clean = 'all')
	{
		$str = '';
		if ($on)
		{
			ob_start();
		}
		else
		{
			$str = ob_get_contents();
			if (!empty($str))
			{
				if ($clean == 'all')
				{
					ob_end_clean();
				}
				else if ($clean)
				{
					ob_clean();
				}
			}
			return $str;
		}
	}
}

// --------------------------------------------------------------------

/**
 * Format true value
 *
 * @param	mixed	possible true value
 * @return 	string	formatted true value
 */
if (!function_exists('is_true_val'))
{
	function is_true_val($val)
	{
		$val = strtolower($val);
		return ($val == 'y' || $val == 'yes' || $val === 1  || $val == '1' || $val== 'true' || $val == 't');
	}
}

// --------------------------------------------------------------------

/**
 * Boolean check to determine string content is serialized
 *
 * @param	mixed	possible serialized string
 * @return 	boolean
 */
if (!function_exists('is_serialized_str'))
{
	function is_serialized_str($data)
	{
		if ( !is_string($data))
			return false;
		$data = trim($data);
		if ( 'N;' == $data )
			return true;
		if ( !preg_match('/^([adObis]):/', $data, $badions))
			return false;
		switch ( $badions[1] ) :
		case 'a' :
		case 'O' :
		case 's' :
			if ( preg_match("/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data))
				return true;
			break;
		case 'b' :
		case 'i' :
		case 'd' :
			if ( preg_match("/^{$badions[1]}:[0-9.E-]+;\$/", $data))
				return true;
			break;
		endswitch;
		return false;
	}
}

// --------------------------------------------------------------------

/**
 * Boolean check to determine string content is a JSON object string
 *
 * @param	mixed	possible serialized string
 * @return 	boolean
 */
if (!function_exists('is_json_str'))
{
	function is_json_str($data)
	{
		if (is_string($data))
		{
			$json = json_decode($data, TRUE);
			return ($json !== NULL AND $data != $json);
		}
		return NULL;
	}
}

// --------------------------------------------------------------------

/**
 * Print object in human-readible format
 * 
 * @param	mixed	The variable to dump
 * @param	boolean	Return string
 * @return 	string
 */
if (!function_exists('print_obj'))
{
	function print_obj($obj, $return = FALSE)
	{
		$str = "<pre>";
		if (is_array($obj))
		{
			// to prevent circular references
			if (is_a(current($obj), 'Data_record'))
			{
				foreach($obj as $key => $val)
				{
					$str .= '['.$key.']';
					$str .= $val;
				}
			}
			else
			{
				$str .= print_r($obj, TRUE);
			}
		}
		else
		{
			if (is_a($obj, 'Data_record'))
			{
				$str .= $obj;
			}
			else
			{
				$str .= print_r($obj, TRUE);
			}
		}
		$str .= "</pre>";
		if ($return) return $str;
		echo $str;
	}
}

// --------------------------------------------------------------------

/**
 * Logs an error message to logs file
 *
 * @param	string	Error message
 * @return 	void
 */
if (!function_exists('log_error'))
{
	function log_error($error) 
	{
		log_message('error', $error);
	}
}

// --------------------------------------------------------------------

/**
 * Returns whether the current environment is set for development
 *
 * @return 	boolean
 */
if (!function_exists('is_dev_mode'))
{
	function is_dev_mode()
	{
		return (ENVIRONMENT != 'production');
	}
}

// --------------------------------------------------------------------

/**
 * Returns whether the current environment is equal to the passed environment
 *
 * @return 	boolean
 */
if (!function_exists('is_environment'))
{
	function is_environment($environment)
	{
		return (strtolower(ENVIRONMENT) == strtolower($environment));
	}
}

if (!function_exists('json_headers'))
{
	function json_headers($no_cache = TRUE)
	{
		if ($no_cache)
		{
			header('Cache-Control: no-cache, must-revalidate');
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		}
		header('Content-type: application/json');
	}
}

if(!function_exists('char_separated')){

	function char_separated($array,$char=','){
		$char_separated = implode($char, $array);
		return $char_separated;
	}
}


if(!function_exists('char_separated_to_array')){

	function char_separated_to_array($string,$char=','){
		$char_separated_to_array = explode($char, $string);
		return $char_separated_to_array;
	}
}

if(!function_exists('ordinal')){
	function ordinal($number) {
	    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
	    if ((($number % 100) >= 11) && (($number%100) <= 13))
	        return $number. 'th';
	    else
	        return $number. $ends[$number % 10];
	}
}
	


function uniqidReal($lenght = 13) {
    // uniqid gives 13 chars, but you could adjust it to your needs.
    if (function_exists("random_bytes")) {
        $bytes = random_bytes(ceil($lenght / 2));
    } elseif (function_exists("openssl_random_pseudo_bytes")) {
        $bytes = openssl_random_pseudo_bytes(ceil($lenght / 2));
    } else {
        throw new Exception("no cryptographically secure random function available");
    }
    return substr(bin2hex($bytes), 0, $lenght);
}



//------------------------------------------------------------------

/*=================================================================
=            			EMAIL HELPER           	 				=
=================================================================*/

if( ! function_exists('sendmail')){


	function sendmail($mail_data=array(),$mail_type='html'){

		CI()->load->library('email');

		CI()->email->from($mail_data['from'], $mail_data['from_name']);
		CI()->email->to($mail_data['to']);

		if(isset($mail_data['cc'])){
			CI()->email->cc($mail_data['cc']);
		}

		if(isset($mail_data['bcc'])){
			CI()->email->bcc($mail_data['bcc']);
		}

		CI()->email->subject($mail_data['subject']);

		if(isset($mail_data['has_attachment']) && $mail_data['has_attachment']==FALSE){
			CI()->email->attach($mail_data['attachment']);
		}

		if($mail_type=='text'){	
			$message=$mail_data['data'];
		}else if($mail_type=='html'){
			$message=CI()->load->view($mail_data['view'],$mail_data['data']);
		}

		CI()->email->message($mail_data['data']);

		if(!CI()->email->send()){
			CI()->email->print_debugger(array('headers'));
		}
	}
}



/*=================================================================
=            			ASSETS COMMON           	 				=
=================================================================*/


if( ! function_exists('assets_url')){

	function assets_url(){
		return base_url().'common/assets/';
	}
}

if(!function_exists('delete_files')){

	function delete_files($target) {
	    if(is_dir($target)){
	        $files = glob( $target . '*', GLOB_MARK ); //GLOB_MARK adds a slash to directories returned

	        foreach( $files as $file ){
	            delete_files( $file );      
	        }
	        if(isset($target) && is_dir($target)){
	        	rmdir( $target );
	        }
	       
	    } elseif(is_file($target)) {
	        unlink( $target );  
	    }
	}	
}

if(!function_exists('isHomogenous')){

	function isHomogenous($arr) {
	    $firstValue = current($arr);
	    foreach ($arr as $val) {
	        if ($firstValue !== $val) {
	            return false;
	        }
	    }
	    return true;
	}

}


if(!function_exists('post_data')){
	function post_data($post_var){
		return xss_clean(strip_javascript(strip_whitespace(encode_php_tags(CI()->input->post($post_var)))));
	}
}

if(!function_exists('get_data')){
	function get_data($get_var){
		return xss_clean(strip_javascript(strip_whitespace(encode_php_tags(CI()->input->get($get_var)))));
	}
}



if(!function_exists('get_percentage')){

	function get_percentage($m,$v){
		return (($m*$v)/100);
	}
}

function encode_url($string, $key="", $url_safe=TRUE)
{
    if($key==null || $key=="")
    {
        $key="1158065099ec139e84a532f651c1f1d1";
    }

    $CI =& get_instance();
    
    // if (version_compare(PHP_VERSION, '5.7', '>=')){
    	$ret = $CI->encryption->encrypt($string);
    // }else{
    // 	$ret = $CI->encrypt->encode($string, $key);
    // }
    

    if ($url_safe)
    {
        $ret = strtr(
                $ret,
                array(
                    '+' => '.',
                    '=' => '-',
                    '/' => '~'
                )
            );
    }

    return $ret;
}
function decode_url($string, $key="")
{
     if($key==null || $key=="")
    {
        $key="1158065099ec139e84a532f651c1f1d1";
    }
        $CI =& get_instance();
    	$string = strtr(
            $string,
            array(
                '.' => '+',
                '-' => '=',
                '~' => '/'
            )
        );

        // if (version_compare(PHP_VERSION, '5.7', '>=')){
	    	return $CI->encryption->decrypt($string);
	    // }else{
	    // 	return $CI->encrypt->decode($string, $key);
	    // }

    
}

// my functions

function encrypt_it($str_to_enc){
	$enc_string = $str_to_enc;

	// Store the cipher method
	$ciphering = "AES-256-CBC";
	// Use OpenSSl Encryption method
	$iv_length = openssl_cipher_iv_length($ciphering);
	$options = 0;
	// Non-NULL Initialization Vector for encryption
	$encryption_iv = '5104459805871797';
	//$encryption_iv = openssl_random_pseudo_bytes($iv_length);
	// Store the encryption key
	$encryption_key = "KaPdSgVkYp3s5v8y/B?E(H+MbQeThWmZ";

	$encrypted_str = openssl_encrypt($enc_string, $ciphering,
		$encryption_key, $options, $encryption_iv);
	//echo "Encrypted String: " . $encrypted_str . "\n";
	return($encrypted_str);

}

function decrypt_it($str_to_dec){
	// Store the cipher method
	$ciphering = "AES-256-CBC";

	// Use OpenSSl Encryption method
	$iv_length = openssl_cipher_iv_length($ciphering);
	$options = 0;
	// Non-NULL Initialization Vector for decryption
	$decryption_iv = '5104459805871797';
	//$decryption_iv = openssl_random_pseudo_bytes($iv_length);

	// Store the decryption key
	$decryption_key = "KaPdSgVkYp3s5v8y/B?E(H+MbQeThWmZ";
	$decrypted_str=openssl_decrypt ($str_to_dec, $ciphering,
		$decryption_key, $options, $decryption_iv);
	return($decrypted_str);
	//echo "Decrypted String: " . $decryption;

}

function random_strings($length_of_string) {

	// String of all alphanumeric character
	$str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

	// Shufle the $str_result and returns substring
	// of specified length
	return substr( str_shuffle( $str_result ),
		0, $length_of_string );
}

function random_numbers($length_of_string) {

	// String of all alphanumeric character
	$str_result = '0123456789';

	// Shufle the $str_result and returns substring
	// of specified length
	return substr( str_shuffle( $str_result ),
		0, $length_of_string );
}

if(!function_exists('isUrlExists')){
    function isUrlExists($tblName, $col_name, $urlSlug){
        if(!empty($tblName) && !empty($urlSlug)){
            $ci = & get_instance();
            $ci->db->from($tblName);
            $ci->db->where($col_name,$urlSlug);
            $rowNum = $ci->db->count_all_results();
            return ($rowNum>0)?true:false;
        }else{
            return true;
        }
    }
}

if(!function_exists('addViewCount')){
    function addViewCount($table,$id_col,$id_val){
        if(!empty($id_val)){
            $ci = & get_instance();
            $ci->db->set('total_hits', '`total_hits`+ 1', FALSE);
			$ci->db->where($id_col, $id_val);
			$ci->db->update($table);
            //return ($rowNum>0)?true:false;
        }else{
           // return true;
        }
    }
}

if(!function_exists('getiplocation')){
    function getiplocation($ip){
        if(!empty($ip)){
        	//get location
			//$ip = '185.220.101.136';
		    //$location = file_get_contents('http://freegeoip.net/json/'.$_SERVER['REMOTE_ADDR']);
		    $location = file_get_contents('http://ip-api.com/json/'.$ip);
		    //you can also use ipinfo.io or any other ip location provider API
		    $data_loc = json_decode($location);
		    return $data_loc;
        }else{
            //return true;
        }
    }
}



/* End of file utility_helper.php */
/* Location: ./helpers/utility_helper.php */
