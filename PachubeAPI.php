<?php
require('PachubeUserAPI.php');
require('PachubeFeedAPI.php');
require('PachubeDatastreamAPI.php');

function getConfig($token)
{
	$url = "http://beta.apps.pachube.com/conf/$token";
	$config = file_get_contents($url,false);
	return json_decode($config);
}

?>