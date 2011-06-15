<?php
/**
 * Pachube Application Datastream API class
 * Version 0.4 (June 2011)
 * Requirements: PHP5, cURL, API v.2.0
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 */
class PachubeDatastreamAPI
{
	private $Pachube;
	private $Key;
	private $Feed;
	private $Datastream;
	
	/**
	 * Constructor
	 */
	function __construct($key, $feed, $datastream) 
	{
		$this->Pachube = "api.pachube.com/v2";
		$this->Key  = $key;
		$this->Feed  = $feed;
		$this->Datastream  = $datastream;
	}
	
	/**
	 * Get datastream
	 * @param string format of output ("json", "xml", "csv")
	 * @return string
	 */
	public function getDatastream($format=false)
	{
		$url = "http://$this->Pachube/feeds/$this->Feed/datastreams/$this->Datastream";
		if($format && ($format == "json" || $format == "csv" || $format == "xml")) $url .= ".". $format;
		return $this->_getRequest($url);
	}
	
	/**
	 * Update datastream
	 * @param string format of output ("json", "xml", "csv")
     * @param string data
	 * @return http response headers
	 */
	public function updateDatastream($format=false, $data)
	{
		$url = "http://$this->Pachube/feeds/$this->Feed/datastreams/$this->Datastream";
		if($format && ($format == "json" || $format == "csv" || $format == "xml")) $url .= ".". $format;
		return $this->_putRequest($url, $data);
	}
	
	/**
	 * Get datastream history
	 * @param string format of output ("json", "xml", "csv")
	 * @param string start point
	 * @param string end point
	 * @param string duration
	 * @param int page
	 * @param int per_page
	 * @param string time
	 * @param bool find_previous
	 * @param string interval_type ("discrete", false)
	 * @param int interval (in seconds: 0, 30, 60, 300, 900, 3600, 10800, 21600, 43200, 86400)
	 * @return string
	 */
	public function getDatastreamHistory($format, $start=false, $end=false, $duration=false, $page=false, $per_page=false, $time=false, $find_previous=false, $interval_type=false, $interval=false)
	{
		$url = "http://$this->Pachube/feeds/$this->Feed/datastreams/$this->Datastream?";
		if($format && ($format == "json" || $format == "csv" || $format == "xml")) $url .= ".". $format;
		if($start) $url .= "start=" . $start . "&";
		if($end) $url .= "content=" . $content . "&";
		if($duration) $url .= "duration=" . $duration . "&";
		if($page) $url .= "page=" . $page . "&";
		if($per_page) $url .= "end=" . $end . "&";
		if($time) $url .= "time=" . $time . "&";
		if($find_previous) $url .= "find_previous=" . $find_previous . "&";
		if($interval_type) $url .= "interval_type=" . $interval_type . "&";
		if($interval) $url .= "interval=" . $interval;
		
		return $this->_getRequest($url);
	}
	
	/**
	 * Create GET request to Pachube (wrapper)
	 * @param string url
	 * @return http code response
	 */
	private function _getRequest($url)
	{
		if (strlen(strstr($url,'?'))>0)
		{
			$url .= "&key=" . $this->Key;
		}
		else
		{
			$url .= "?key=" . $this->Key;
		}
		if(function_exists('curl_init'))
		{
			return $this->_curl($url, true);
		}
		elseif(function_exists('file_get_contents') && ini_get('allow_url_fopen'))
		{
			return $this->_get($url);		
		}
		else
		{
			return 500;
		}
	}
	
	/**
	 * Create POST request to Pachube (wrapper)
	 * @param string url
	 * @param array data
	 * @return http code response
	 */
	private function _postRequest($url, $data)
	{
		if (strlen(strstr($url,'?'))>0)
		{
			$url .= "&key=" . $this->Key;
		}
		else
		{
			$url .= "?key=" . $this->Key;
		}
		if(function_exists('curl_init'))
		{
			return $this->_curl($url, true, true, $data);
		}
		elseif(function_exists('file_post_contents') && ini_get('allow_url_fopen'))
		{
			return $this->_post($url, $data);		
		}
		else
		{
			return 500;
		}
	}

	/**
	 * Create PUT request to Pachube (wrapper)
	 * @param string url
	 * @param string data
	 * @return http code response
	 */
	private function _putRequest($url, $data)
	{	
		if (strlen(strstr($url,'?'))>0)
		{
			$url .= "&key=" . $this->Key;
		}
		else
		{
			$url .= "?key=" . $this->Key;
		}
		if(function_exists('curl_init'))
		{
			$putData = tmpfile();
			fwrite($putData, $data);
			fseek($putData, 0);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_INFILE, $putData);
			curl_setopt($ch, CURLOPT_INFILESIZE, strlen($data));
			curl_setopt($ch, CURLOPT_PUT, true);
			curl_exec($ch);
			$headers = curl_getinfo($ch);
			fclose($putData);
			curl_close($ch);

			return $headers['http_code'];
		}
		elseif(function_exists('file_put_contents') && ini_get('allow_url_fopen'))
		{
			return $this->_put($url,$data);
		}
		else
		{
			return 500;
		}
	}
	
	/**
	 * Create DELETE request to Pachube
	 * @param string url
	 * @return http code response
	 */
	private function _deleteRequest($url)
	{
		if (strlen(strstr($url,'?'))>0)
		{
			$url .= "&key=" . $this->Key;
		}
		else
		{
			$url .= "?key=" . $this->Key;
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
		curl_exec($ch);
		$headers = curl_getinfo($ch);
		curl_close($ch);
		return $headers['http_code'];
	}
	
	/**
	 * GET requests to Pachube
	 * @param string url
	 * @return response
	 */
	private function _get($url)
	{
		// Create a stream
		$opts['http']['method'] = "GET";
		$context = stream_context_create($opts);
		// Open the file using the HTTP headers set above
		return file_get_contents($url, false, $context);
	}
	
	/**
	 * POST requests to Pachube
	 * @param string url
	 * @param array data
	 * @return response
	 */
	private function _post($url, $data)
	{
		$postfields = http_build_query($data);  
		$opts = array('http' =>  
		   array(  
		      'method'  => 'POST',  
		      'header'  => 'Content-type: application/x-www-form-urlencoded',
		      'content' => $postfields,  
		   )  
		);  
		$context  = stream_context_create($opts);  
		return file_get_contents($url, false, $context);
	}


	/**
	 * PUT requests to Pachube
	 * @param string url
	 * @param string data
	 * @return response
	 */
	private function _put($url,$data)
	{	
		// Create a stream
		$opts['http']['method'] = "PUT";
		$opts['http']['header'] .= "Content-Length: " . strlen($data) . "\r\n";
		$opts['http']['content'] = $data;
		$context = stream_context_create($opts);
		// Open the file using the HTTP headers set above
		return file_get_contents($url, false, $context);
	}

	/**
	 * cURL main function
	 * @param string url
	 * @param bool authentication
	 * @return response
	 */
	private function _curl($url, $auth=false, $post=false, $post_data=false)
	{
		if(function_exists('curl_init'))
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			if($post)
			{
				curl_setopt($ch, CURLOPT_POST, 1);
				if($post_data) curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
			}
			
			$data = curl_exec($ch);
			curl_close($ch);
			return $data;
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * Print debug status of error
	 * @param int status code
	 */
	public function _debugStatus($status_code)
	{
		switch ($status_code)
		{			
			case 200:
				$msg = "Pachube feed successfully updated";	
				break;
			case 401:
				$msg = "Pachube API key was incorrect";
				break;
			case 403:
				$msg = "Access forbidden!";
				break;
			case 404:
				$msg = "Feed ID or some other parameter does not exist";
				break;
			case 422:
				$msg = "Unprocessable Entity, semantic errors (CSV instead of XML?)";
				break;
			case 418:
				$msg = "Error in feed ID, data type or some other data";
				break;
			case 500:
				$msg = "cURL library not installed or some other internal error occured";
				break;	
			default:
				$msg = "Status code not recognised: ".$status_code;
				break;
		}
		echo $msg;		
	}
}
?>