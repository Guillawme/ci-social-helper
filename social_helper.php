<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// ------------------------------------------------------------------------

/**
 * CodeIgniter Social Networks Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Guillaume LATORRE (lats@free.fr) http://twitter.com/Guillaum
 */

// ------------------------------------------------------------------------

/**
 * returns number of fans for a given facebook page
 *
 * @access	public
 * @param	page id 
 * @param	numbers that will be displayed, completed by zero // example: $numbers_displayed=5 will display : 00012 / $numbers_displayed=FALSE will display : 12
 * @return	int
 */	
if ( ! function_exists('facebook_count'))
{
	function facebook_count($fid, $numbers_displayed = FALSE)
	{
		get_instance()->load->helper('file');
		
		// Check if the number is in session
		$count = get_instance()->session->userdata('fb_count');
		if ($count !== false)
		{
			return $count;
		}
		$count = false;
		
		// Check if a file exist with the number
		if(is_file(dirname(__FILE__).'/../config/social_fb.json'))
		{
			$data = read_file(dirname(__FILE__).'/../config/social_fb.json');
			$count = json_decode($data);
		
			// Every 30 minutes, we update the number
			if(strtotime($count->date) > mktime(date('H'), date('i')-30, date('s'), date('m'), date('d'), date('Y')))
				return $count->count;
			else
				$count = false;
		}
	  	
	  	// Else create the file and store in session
	  	if($count === false)
	  	{
	    	$count = 0;
			ini_set('user_agent', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; fr; rv:1.9.0.1) Gecko/2008070208 Firefox/3.0.1');  // Browser Simulation
			$ch = curl_init();
			$timeout = 0; // Timeout cURL
			curl_setopt ($ch, CURLOPT_URL, 'https://graph.facebook.com/'.$fid); // URL
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			$data = curl_exec($ch);
			if ($data)
	    	{
				$value = json_decode($data,true);
				$count = $value['likes'];
				if($numbers_displayed && strlen($count) < $numbers_displayed)
				{
					$zero = '';
					for($i=0; $i < $numbers_displayed - strlen($count);$i++)
					{
						$zero = '0'.$zero;
					}
					$count = $zero.$count;
				}
			}
			$result['date'] = date("Y-m-d H:i:s");
			$result['count'] = $count;
			write_file(dirname(__FILE__).'/../config/social_fb.json', json_encode($result));
			get_instance()->session->set_userdata('fb_count', $count);
			return $count;
		}
	}
}

/**
 * returns number of followers for a given twitter account
 *
 * @access	public
 * @param	page id // example: twitter_count('Guillaum');
 * @param	numbers that will be displayed, completed by zero // example: $numbers_displayed=5 will display : 00012 / $numbers_displayed=FALSE will display : 12 
 * @return	int
 */	
if ( ! function_exists('twitter_count'))
{
	function twitter_count($tid, $numbers_displayed = FALSE)
	{
		get_instance()->load->helper('file');

		// Check if the number is in session
		$count = get_instance()->session->userdata('tw_count');
		if ($count !== false)
		{
			return $count;
		}		
		$count = false;

		// Check if a file exist with the number
		if(is_file(dirname(__FILE__).'/../config/social_tw.json'))
		{
			$data = read_file(dirname(__FILE__).'/../config/social_tw.json');
			$count = json_decode($data);
		
			// Every 30 minutes, we update the number
			if(strtotime($count->date) > mktime(date('H'), date('i')-30, date('s'), date('m'), date('d'), date('Y')))
				return $count->count;
			else
				$count = false;
		}

	  	// Else create the file and store in session
	  	if($count === false)
	  	{
	    	$count = 0;
			ini_set('user_agent', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; fr; rv:1.9.0.1) Gecko/2008070208 Firefox/3.0.1'); // Browser Simulation
			$ch = curl_init();
			$timeout = 0; // Timeout cURL
			curl_setopt ($ch, CURLOPT_URL, 'http://twitter.com/users/show.json?screen_name='.$tid); // URL
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			$data = curl_exec($ch);
			if ($data)
	    	{
				$value = json_decode($data,true);
				if(array_key_exists('followers_count',$value))
					$count = $value['followers_count'];
				else
					$count = 0;
				if($numbers_displayed && strlen($count) < $numbers_displayed)
				{
					$zero = '';
					for($i=0; $i < $numbers_displayed - strlen($count);$i++)
					{
						$zero = '0'.$zero;
					}
					$count = $zero.$count;
				}
			}
			$result['date'] = date("Y-m-d H:i:s");
			$result['count'] = $count;
			write_file(dirname(__FILE__).'/../config/social_tw.json', json_encode($result));
			get_instance()->session->set_userdata('tw_count', $count);
			return $count;
		}
	}
}