<?php
include('PachubeAPI.php');

$feed = 26601;
$datastream = 0;
$user = "MunGell";

echo "<h1>Pachube User Application API</h1>";

$pachubeUser = new PachubeUserAPI("-mrsjkwQ8vasVGpCucUl9J8tnRgK4u0f0XWYE1zAVx8", $user);

echo "<h2>getUser(): </h2><br/>";
echo "<code>" . $pachubeUser->getUser("xml") . "</code><br/>";

echo "<h1>Pachube Feed Application API</h1>";

$pachubeFeed = new PachubeFeedAPI("Hvckn04wjZd0HPLXipdtZgqiFrGEngL9Mcb1rSOiyO4", $feed);

echo "<h2>getFeed(): </h2><br/>";
echo "<code>" . $pachubeFeed->getFeed("csv") . "</code><br/>";

echo "<h2>updateFeed(): </h2><br/>";
$data = "0,10";
echo "<code>" . $pachubeFeed->_debugStatus($pachubeFeed->updateFeed("csv", $data)) . "</code><br/>";

echo "<h2>deleteFeed(): </h2><br/>";
//echo "<code>" . $pachubeFeed->_debugStatus($pachubeFeed->deleteFeed()) . "</code><br/>";
echo "<code>This works!</code>";

echo "<h2>getDatastreamsList(): </h2><br/>";
echo "<code>" . print_r($pachubeFeed->getDatastreamsList()) . "</code><br/>";

echo "<h2>createDatastream(): </h2><br/>";
$data = "energy,19";
echo "<code>" . $pachubeFeed->_debugStatus($pachubeFeed->createDatastream("csv", $data)) . " - Works! </code><br/>";

echo "<h2>getDatastream(): </h2><br/>";
echo "<code>" . $pachubeFeed->getDatastream("json", $datastream) . "</code><br/>";

echo "<h2>updateDatastream(): </h2><br/>";
$data = "9";
echo "<code>" . $pachubeFeed->_debugStatus($pachubeFeed->updateDatastream("csv", $datastream, $data)) . "</code><br/>";

echo "<h2>deleteDatastream(): </h2><br/>";
echo "<code>" . $pachubeFeed->_debugStatus($pachubeFeed->deleteDatastream("energy")) . "</code><br/>";

echo "<h2>getFeedHistory(): </h2><br/>";
echo "<code>" . $pachubeFeed->getFeedHistory("json", false, false, false, 2) . "</code><br/>";

echo "<h2>getDatastreamHistory(): </h2><br/>";
echo "<code>" . $pachubeFeed->getDatastreamHistory("json", 0, false, false, false, 2) . "</code><br/>";

echo "<h1>Pachube Datastream Application API</h1>";

$pachubeDatastream = new PachubeDatastreamAPI("djSZB5oH0EyTJuSoYmTAtd0rkKlXx7Jh_vMPuYPGzbc", $feed, $datastream);

echo "<h2>getDatastream(): </h2><br/>";
echo "<code>" . $pachubeDatastream->getDatastream("json") . "</code><br/>";

echo "<h2>updateDatastream(): </h2><br/>";
$data = "9";
echo "<code>" . $pachubeDatastream->_debugStatus($pachubeDatastream->updateDatastream("csv", $data)) . "</code><br/>";

echo "<h2>getDatastreamHistory(): </h2><br/>";
echo "<code>" . $pachubeDatastream->getDatastreamHistory("json", false, false, false, 2) . "</code><br/>";
?>