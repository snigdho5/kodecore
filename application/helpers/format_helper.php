<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Formats value into a currency string
 *
 * @access	public
 * @param	string
 * @param	bool	whether to include the cents or not
 * @return	string
 */
if (!function_exists('currency'))
{
	function currency($value, $symbol = '$',  $include_cents = TRUE, $decimal_sep = '.', $thousands_sep = ',')
	{
		if (!isset($value) OR $value === "")
		{
			return;
		}
		$value = (float) $value;
		$dec_num = (!$include_cents) ? 0 : 2;
		$is_negative = (strpos($value, '-') === 0) ? '-' : '';
		$value = abs($value);
		//return $is_negative.$symbol.number_format($value, $dec_num, $decimal_sep, $thousands_sep);

		return $is_negative.number_format($value, $dec_num, $decimal_sep, $thousands_sep);
	}
}

if (!function_exists('numberTowords'))
{
	function numberTowords(float $number)
	{
	    $decimal = round($number - ($no = floor($number)), 2) * 100;
	    $hundred = null;
	    $digits_length = strlen($no);
	    $i = 0;
	    $str = array();
	    $words = array(0 => '', 1 => 'one', 2 => 'two',
	        3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
	        7 => 'seven', 8 => 'eight', 9 => 'nine',
	        10 => 'ten', 11 => 'eleven', 12 => 'twelve',
	        13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
	        16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
	        19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
	        40 => 'forty', 50 => 'fifty', 60 => 'sixty',
	        70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
	    $digits = array('', 'hundred','thousand','lakh', 'crore');
	    while( $i < $digits_length ) {
	        $divider = ($i == 2) ? 10 : 100;
	        $number = floor($no % $divider);
	        $no = floor($no / $divider);
	        $i += $divider == 10 ? 1 : 2;
	        if ($number) {
	            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
	            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
	            $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
	        } else $str[] = null;
	    }
	    $Rupees = implode('', array_reverse($str));
	    $paise = ($decimal) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
	    //return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise;

	    return 'Rupees '.ucwords($Rupees).' Only';
	}
}

	

// if(!function_exists('validate_aadhhar')){

// 	function validate_aadhhar($aadhhar){
// 		$dihedral = array(
// 		    array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9),
// 		    array(1, 2, 3, 4, 0, 6, 7, 8, 9, 5),
// 		    array(2, 3, 4, 0, 1, 7, 8, 9, 5, 6),
// 		    array(3, 4, 0, 1, 2, 8, 9, 5, 6, 7),
// 		    array(4, 0, 1, 2, 3, 9, 5, 6, 7, 8),
// 		    array(5, 9, 8, 7, 6, 0, 4, 3, 2, 1),
// 		    array(6, 5, 9, 8, 7, 1, 0, 4, 3, 2),
// 		    array(7, 6, 5, 9, 8, 2, 1, 0, 4, 3),
// 		    array(8, 7, 6, 5, 9, 3, 2, 1, 0, 4),
// 		    array(9, 8, 7, 6, 5, 4, 3, 2, 1, 0)
// 		);

// 		$permutation = array(
// 		    array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9),
// 		    array(1, 5, 7, 6, 2, 8, 3, 0, 9, 4),
// 		    array(5, 8, 0, 3, 7, 9, 6, 1, 4, 2),
// 		    array(8, 9, 1, 6, 0, 4, 3, 5, 2, 7),
// 		    array(9, 4, 5, 3, 1, 2, 6, 8, 7, 0),
// 		    array(4, 2, 8, 6, 5, 7, 3, 9, 0, 1),
// 		    array(2, 7, 9, 3, 8, 0, 6, 4, 1, 5),
// 		    array(7, 0, 4, 6, 9, 1, 3, 2, 5, 8)
// 		);

// 		$inverse = array(0, 4, 3, 2, 1, 5, 6, 7, 8, 9);

		

// 		$valid = isAadharValid($AadharNo);
// 		$isValid = false;
// 		if ($valid == 1) {
// 		    $isValid = true;
// 		}

// 	}
// }

// function isAadharValid($num) {
//     settype($num, "string");
//     $expectedDigit = substr($num, -1);
//     $actualDigit = CheckSumAadharDigit(substr($num, 0, -1));
//     return ($expectedDigit == $actualDigit) ? $expectedDigit == $actualDigit : 0;
// }

// function CheckSumAadharDigit($partial) {
//     settype($partial, "string");
//     $partial = strrev($partial);
//     $digitIndex = 0;
//     for ($i = 0; $i < strlen($partial); $i++) {
//         $digitIndex = $dihedral[$digitIndex][$permutation[($i + 1) % 8][$partial[$i]]];
//     }
//     return $inverse[$digitIndex];
// }

function int_to_words($x)
{

  //
  $x=explode(".",$x);
  //var_export($x);
  $x=$x[0];
//

    $nwords = array( "Zero", "One", "Two", "Three", "Four", "Five", "Six", "Seven",
                     "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen",
                     "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen",
                     "Nineteen", "Twenty", 30 => "Thirty", 40 => "Forty",
                     50 => "Fifty", 60 => "Sixty", 70 => "Seventy", 80 => "Eighty",
                     90 => "Ninety" );
           
     if(!is_numeric($x))
     {
         $w = '#';
     }else if(fmod($x, 1) != 0)
     {
         $w = '#';
     }else{
         if($x < 0)
         {
             $w = 'minus ';
             $x = -$x;
         }else{
             $w = '';
         }
         if($x < 21)
         {
             $w .= $nwords[$x];
         }else if($x < 100)
         {
             $w .= $nwords[10 * floor($x/10)];
             $r = fmod($x, 10);
             if($r > 0)
             {
                 $w .= '-'. $nwords[$r];
             }
         } else if($x < 1000)
         {
             $w .= $nwords[floor($x/100)] .' Hundred';
             $r = fmod($x, 100);
             if($r > 0)
             {
                 $w .= ' and '. int_to_words($r);
             }
         } else if($x < 100000)
         {
             $w .= int_to_words(floor($x/1000)) .' Thousand';
             $r = fmod($x, 1000);
             if($r > 0)
             {
                 $w .= ' ';
                 if($r < 100)
                 {
                     $w .= 'and ';
                 }
                 $w .= int_to_words($r);
             }
         } else if($x < 10000000)
     {
             $w .= int_to_words(floor($x/100000)) .' Lakh';
             $r = fmod($x, 100000);
             if($r > 0)
             {
        $w .=' ';
                $w .= int_to_words($r);
             }
         }else if($x < 1000000000)
     {
             $w .= int_to_words(floor($x/10000000)) .' Crore';
             $r = fmod($x, 10000000);
             if($r > 0)
             {
        $w .=' ';
                $w .= int_to_words($r);
             }
         }
     }
     return $w;
}


/**
 * Function to convert a number to a the string literal for the number
 */
function getStringOfAmount($num) {
  $count = 0;
  global $ones, $tens, $triplets;
  $ones = array(
    '',
    ' One',
    ' Two',
    ' Three',
    ' Four',
    ' Five',
    ' Six',
    ' Seven',
    ' Eight',
    ' Nine',
    ' Ten',
    ' Eleven',
    ' Twelve',
    ' Thirteen',
    ' Fourteen',
    ' Fifteen',
    ' Sixteen',
    ' Seventeen',
    ' Eighteen',
    ' Nineteen'
  );
  $tens = array(
    '',
    '',
    ' Twenty',
    ' Thirty',
    ' Forty',
    ' Fifty',
    ' Sixty',
    ' Seventy',
    ' Eighty',
    ' Ninety'
  );

  $triplets = array(
    '',
    ' Thousand',
    ' Million',
    ' Billion',
    ' Trillion',
    ' Quadrillion',
    ' Quintillion',
    ' Sextillion',
    ' Septillion',
    ' Octillion',
    ' Nonillion'
  );
  
  return convertNum($num);
}

/**
 * Function to dislay tens and ones
 */
function commonloop($val, $str1 = '', $str2 = '') {
  global $ones, $tens;
  $string = '';
  if ($val == 0)
    $string .= $ones[$val];
  else if ($val < 20)
    $string .= $str1.$ones[$val] . $str2;  
  else
    $string .= $str1 . $tens[(int) ($val / 10)] . $ones[$val % 10] . $str2;
  return $string;
}

/**
 * returns the number as an anglicized string
 */
function convertNum($num) {
  $num = (int) $num;    // make sure it's an integer

  if ($num < 0)
    return 'negative' . convertTri(-$num, 0);

  if ($num == 0)
    return 'Zero';
  return convertTri($num, 0);
}

/**
 * recursive fn, converts numbers to words
 */
function convertTri($num, $tri) {
  global $ones, $tens, $triplets, $count;
  $test = $num;
  $count++;
  // chunk the number, ...rxyy
  // init the output string
  $str = '';
  // to display hundred & digits
  if ($count == 1) {
    $r = (int) ($num / 1000);
    $x = ($num / 100) % 10;
    $y = $num % 100;
    // do hundreds
    if ($x > 0) {
      $str = $ones[$x] . ' Hundred';
      // do ones and tens
      $str .= commonloop($y, ' and ', '');
    }
    else if ($r > 0) {
      // do ones and tens
      $str .= commonloop($y, ' and ', '');
    }
    else {
      // do ones and tens
      $str .= commonloop($y);
    }
  }
  // To display lakh and thousands
  else if($count == 2) {
    $r = (int) ($num / 10000);
    $x = ($num / 100) % 100;
    $y = $num % 100;
    $str .= commonloop($x, '', ' Lakh ');
    $str .= commonloop($y);
    if ($str != '')
      $str .= $triplets[$tri];
  }
  // to display till hundred crore
  else if($count == 3) {
    $r = (int) ($num / 1000);
    $x = ($num / 100) % 10;
    $y = $num % 100;
    // do hundreds
    if ($x > 0) {
      $str = $ones[$x] . ' Hundred';
      // do ones and tens
      $str .= commonloop($y,' and ',' Crore ');
    }
    else if ($r > 0) {
      // do ones and tens
      $str .= commonloop($y,' and ',' Crore ');
    }
    else {
      // do ones and tens
      $str .= commonloop($y);
    }
  }
  else {
    $r = (int) ($num / 1000);
  }
  // add triplet modifier only if there
  // is some output to be modified...
  // continue recursing?
  if ($r > 0)
    return convertTri($r, $tri+1) . $str;
  else
    return $str;
}


if(!function_exists('isValidPAN')){

	function isValidPAN($pan_number){

		$pattern = '/^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/';
		$result = preg_match($pattern, $pan_number);
		if ($result) {
		    $findme = ucfirst(substr($pan_number, 3, 1));
		    $mystring = 'CPHFATBLJG';
		    $pos = strpos($mystring, $findme);
		    if ($pos === false) {
		       return false;
		    } else {
		       return true;
		    }
		} else {
		    return false;
		}
	}		
}


function numberTowords($num)
{ 

  $ones = array(
    0 =>"ZERO", 
    1 => "ONE", 
    2 => "TWO", 
    3 => "THREE", 
    4 => "FOUR", 
    5 => "FIVE", 
    6 => "SIX", 
    7 => "SEVEN", 
    8 => "EIGHT", 
    9 => "NINE", 
    10 => "TEN", 
    11 => "ELEVEN", 
    12 => "TWELVE", 
    13 => "THIRTEEN", 
    14 => "FOURTEEN", 
    15 => "FIFTEEN", 
    16 => "SIXTEEN", 
    17 => "SEVENTEEN", 
    18 => "EIGHTEEN", 
    19 => "NINETEEN",
    "014" => "FOURTEEN" 
  ); 
  $tens = array( 
    0 => "ZERO",
    1 => "TEN",
    2 => "TWENTY",
    3 => "THIRTY", 
    4 => "FORTY", 
    5 => "FIFTY", 
    6 => "SIXTY", 
    7 => "SEVENTY", 
    8 => "EIGHTY", 
    9 => "NINETY" 
  ); 
  $hundreds = array( 
    "HUNDRED", 
    "THOUSAND", 
    "MILLION", 
    "BILLION", 
    "TRILLION", 
    "QUARDRILLION" 
  ); /*limit t quadrillion */
  $num = number_format($num,2,".",","); 
  $num_arr = explode(".",$num); 
  $wholenum = $num_arr[0]; 
  $decnum = $num_arr[1]; 
  $whole_arr = array_reverse(explode(",",$wholenum)); 
  krsort($whole_arr,1); 
  $rettxt = ""; 
  foreach($whole_arr as $key => $i){
    
    while(substr($i,0,1)=="0")
      $i=substr($i,1,5);
      if($i < 20){ 
        /* echo "getting:".$i; */
        $rettxt .= $ones[$i]; 
      }elseif($i < 100){ 
        if(substr($i,0,1)!="0")  $rettxt .= $tens[substr($i,0,1)]; 
        if(substr($i,1,1)!="0") $rettxt .= " ".$ones[substr($i,1,1)]; 
      }else{ 
        if(substr($i,0,1)!="0") $rettxt .= $ones[substr($i,0,1)]." ".$hundreds[0]; 
        if(substr($i,1,1)!="0")$rettxt .= " ".$tens[substr($i,1,1)]; 
        if(substr($i,2,1)!="0")$rettxt .= " ".$ones[substr($i,2,1)]; 
      }

      if($key > 0){ 
        $rettxt .= " ".$hundreds[$key]." "; 
      }
  }

  if($decnum > 0){ 
    $rettxt .= " and "; 
    if($decnum < 20){ 
      $rettxt .= $ones[$decnum]; 
    }elseif($decnum < 100){ 
      $rettxt .= $tens[substr($decnum,0,1)]; 
      $rettxt .= " ".$ones[substr($decnum,1,1)]; 
    } 
  }
  
  return $rettxt; 
}




	
/* End of file format_helper.php */
