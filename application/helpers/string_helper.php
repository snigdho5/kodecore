<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');


// --------------------------------------------------------------------

/**
 * Evaluates a strings PHP code. Used especially for outputing FUEL page data
 *
 * @param 	string 	string to evaluate
 * @param 	mixed 	variables to pass to the string
 * @return	string
 */
if (!function_exists('eval_string'))
{
	function eval_string($str, $vars = array())
	{
		$CI =& get_instance();
		extract($CI->load->get_vars()); // extract cached variables
		extract($vars);

		// fix XML
		$str = str_replace('<?xml', '<@xml', $str);

		ob_start();
		if ((bool) @ini_get('short_open_tag') === FALSE AND $CI->config->item('rewrite_short_tags') == TRUE)
		{
			$str = eval('?>'.preg_replace("/;*\s*\?>/", "; ?>", str_replace('<?=', '<?php echo ', $str)).'<?php ');
		}
		else
		{
			$str = eval('?>'.$str.'<?php ');
		}
		$str = ob_get_clean();
		
		// change XML back
		$str = str_replace('<@xml', '<?xml', $str);
		return $str;
	}
}

// --------------------------------------------------------------------

/**
 * Add an s to the end of s string based on the number 
 *
 * @param 	int 	number to compare against to determine if it needs to be plural
 * @param 	string 	string to evaluate
 * @param 	string 	plural value to add
 * @return	string
 */
//
if (!function_exists('pluralize'))
{ 
	function pluralize($num, $str = '', $plural = 's')
	{
		if (is_array($num))
		{
			$num = count($num);
		}
		
		if ($num != 1)
		{
			$str .= $plural;
		}
		return $str;
	}
}

// --------------------------------------------------------------------

/**
 * Strips extra whitespace from a string
 *
 * @param 	string
 * @return	string
 */
if (!function_exists('strip_whitespace'))
{
	function strip_whitespace($str)
	{
		return trim(preg_replace('/\s\s+|\n/m', '', $str));
	}
}

// --------------------------------------------------------------------

/**
 * Trims extra whitespace from the end and beginning of a string on multiple lines
 *
 * @param 	string
 * @return	string
 */
if (!function_exists('trim_multiline'))
{
	function trim_multiline($str)
	{
		return trim(implode("\n", array_map('trim', explode("\n", $str))));
	}
}

// --------------------------------------------------------------------

/**
 * Converts words to title case and allows for exceptions
 *
 * @param 	string 	string to evaluate
 * @param 	mixed 	variables to pass to the string
 * @return	string
 */
if (!function_exists('smart_ucwords'))
{
	function smart_ucwords($str, $exceptions = array('of', 'the'))
	{
		$out = "";
		$i = 0;
		foreach (explode(" ", $str) as $word)
		{
			$out .= (!in_array($word, $exceptions) OR $i == 0) ? strtoupper($word[0]) . substr($word, 1) . " " : $word . " ";
			$i++;
		}
		return rtrim($out);
	}
}

// --------------------------------------------------------------------

/**
 * Removes "Gremlin" characters 
 *
 * (hidden control characters that the remove_invisible_characters function misses)
 *
 * @param 	string 	string to evaluate
 * @param 	string 	the value used to replace a gremlin
 * @return	string
 */
if (!function_exists('zap_gremlins'))
{
	function zap_gremlins($str, $replace = '')
	{
		// there is a hidden bullet looking thingy that photoshop likes to include in it's text'
		// the remove_invisible_characters doesn't seem to remove this
		$str = preg_replace('/[^\x0A\x0D\x20-\x7E]/', $replace, $str);
		return $str;
	}
}

// --------------------------------------------------------------------

/**
 * Removes javascript from a string
 *
 * @param 	string 	string to remove javascript
 * @return	string
 */
if (!function_exists('strip_javascript'))
{
	function strip_javascript($str)
	{
		$str = preg_replace('#<script[^>]*>.*?</script>#is', '', $str);
		return $str;
	}
}

// --------------------------------------------------------------------

/**
 * Safely converts a string's entities without encoding HTML tags and quotes
 *
 * @param 	string 	string to evaluate
 * @param 	boolean determines whether to encode the ampersand or not
 * @return	string
 */
if (!function_exists('safe_htmlentities'))
{
	function safe_htmlentities($str, $protect_amp = TRUE)
	{
		// convert all hex single quotes to numeric ... 
		// this was due to an issue we saw with htmlentities still encoding it's ampersand again'... 
		// but was inconsistent across different environments and versions... not sure the issue
		// may need to look into other hex characters
		$str = str_replace('&#x27;', '&#39;', $str);
		
		// setup temp markers for existing encoded tag brackets 
		$find = array('&lt;','&gt;');
		$replace = array('__TEMP_LT__','__TEMP_GT__');
		$str = str_replace($find,$replace, $str);
		
		// encode just &
		if ($protect_amp)
		{
			$str = preg_replace('/&(?![a-z#]+;)/i', '__TEMP_AMP__', $str);
		}

		// safely translate now
		if (version_compare(PHP_VERSION, '5.2.3', '>='))
		{
			//$str = htmlspecialchars($str, ENT_NOQUOTES, 'UTF-8', FALSE);
			$str = htmlentities($str, ENT_NOQUOTES, config_item('charset'), FALSE);
		}
		else
		{
			$str = preg_replace('/&(?!(?:#\d++|[a-z]++);)/ui', '&amp;', $str);
			$str = str_replace(array('<', '>'), array('&lt;', '&gt;'), $str);
		}
		
		// translate everything back
		$str = str_replace($find, array('<','>'), $str);
		$str = str_replace($replace, $find, $str);
		if ($protect_amp)
		{
			$str = str_replace('__TEMP_AMP__', '&', $str);
		}
		return $str;
	}
}


// --------------------------------------------------------------------

/**
 * Convert PHP syntax to templating syntax
 *
 * @param 	string 	string to evaluate
 * @return	string
 */
function php_to_template_syntax($str, $engine = NULL)
{
	$CI =& get_instance();
	if (empty($engine))
	{
		$engine = $CI->fuel->config('parser_engine');
	}
	return $CI->fuel->parser->set_engine($engine)->php_to_syntax($str);
}

// --------------------------------------------------------------------
/**
 * Convert string to  templating syntax
 *
 * @param 	string 	string to evaluate
 * @param 	array 	variables to parse with string
 * @param 	string	the templating engine to use
 * @param 	array 	an array of configuration variables like compile_dir, delimiters, allowed_functions, refs and data
 * @return	string
 */
function parse_template_syntax($str, $vars = array())
{
	CI()->load->library('parser');
	CI()->parser->set_delimiters('{','}');
	return CI()->parser->parse_string($str, $vars, TRUE);	
}


function url_slug($str, $options = array()) {
	// Make sure string is in UTF-8 and strip invalid UTF-8 characters
	$str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());
	
	$defaults = array(
		'delimiter' => '-',
		'limit' => null,
		'lowercase' => true,
		'replacements' => array(),
		'transliterate' => false,
	);
	
	// Merge options
	$options = array_merge($defaults, $options);
	
	$char_map = array(
		// Latin
		'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C', 
		'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 
		'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O', 
		'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH', 
		'ß' => 'ss', 
		'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c', 
		'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 
		'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o', 
		'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th', 
		'ÿ' => 'y',

		// Latin symbols
		'©' => '(c)',

		// Greek
		'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
		'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
		'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
		'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
		'Ϋ' => 'Y',
		'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
		'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
		'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
		'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
		'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',

		// Turkish
		'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
		'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g', 

		// Russian
		'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
		'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
		'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
		'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
		'Я' => 'Ya',
		'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
		'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
		'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
		'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
		'я' => 'ya',

		// Ukrainian
		'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G',
		'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',

		// Czech
		'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U', 
		'Ž' => 'Z', 
		'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
		'ž' => 'z', 

		// Polish
		'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z', 
		'Ż' => 'Z', 
		'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
		'ż' => 'z',

		// Latvian
		'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N', 
		'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z',
		'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
		'š' => 's', 'ū' => 'u', 'ž' => 'z'
	);
	
	// Make custom replacements
	$str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);
	
	// Transliterate characters to ASCII
	if ($options['transliterate']) {
		$str = str_replace(array_keys($char_map), $char_map, $str);
	}
	
	// Replace non-alphanumeric characters with our delimiter
	$str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);
	
	// Remove duplicate delimiters
	$str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);
	
	// Truncate slug to max. characters
	$str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');
	
	// Remove delimiter from ends
	$str = trim($str, $options['delimiter']);
	
	return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
}

function check_date($string){

	if (preg_match("/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/", $string, $matches)) {
	    if (!checkdate($matches[2], $matches[1], $matches[3])) {
	        $error = true;
	    }else{
	    	$error=false;
	    }
	} else {
	    $error = true;
	}

	return $error;
}

function check_format($string,$format){
	if (!preg_match($format, $string, $matches)){
		return false;
	}else{
		return true;
	}
}

function check_time_format($date, $format = 'h:i:s'){

    $dateObj = DateTime::createFromFormat($format, $date);
    return $dateObj && $dateObj->format($format) == $date;
}

 function isTime($time)
{
return preg_match("#([0-1]{1}[0-9]{1}|[2]{1}[0-3]{1}):[0-5]{1}[0-9]{1}#", $time);
}

function columnLetter($c){

    $c = intval($c);
    //if ($c<= 0) return '';

    $letter = '';
             
    while($c != 0){
       $p = ($c - 1) % 26;
       $c = intval(($c - $p) / 26);
       $letter = chr(65 + $p) . $letter;
    }
    
    return $letter;
        
}


function odd_even($number){
	if ($number % 2 == 0){
		return true;//even
	}else{
		return false;//odd
	}
}

function check_your_datetime($x) {
    return (date('d-M-Y', strtotime($x)) == $x);
}

/* End of file MY_string_helper.php */