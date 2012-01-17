<?php

/**
 * @author Dan Kraus
 * @link http://github.com/dankraus/Star-Wars-The-Old-Republic-Server-Status-Widget
   @email dskraus@gmail.com
 * @copyright Creative Common Attribution-NonCommercial 3.0: http://creativecommons.org/licenses/by-nc/3.0/
   Use as you wish non-commercially, but link back to the github repo please at: http://github.com/dankraus/Star-Wars-The-Old-Republic-Server-Status-Widget for credit.
   If you are interested in using it commercially, contact me
 */


//SERVER-SET-UP-CONFIG
$serverName = "terentatek"; //server name in lower case (spaces are ok)
$url = "http://www.swtor.com/server-status"; //we hit the server status page located here to get our server info
$cache_file_name = "server-status-cache.html"; //cache the server status page to this file name. It is saved in the same folder as this file
$cache_time_life = '300';//seconds to cache file



include_once('simple_html_dom.php');

$data = new simple_html_dom();

//Check to see if cache file exists. if it doesn't exist or we've exceed the cache life length, get fresh data from the source
//else, load it from the cached file
if( !(file_exists($cache_file_name)) || time() - filemtime($cache_file_name) >= $cache_time_life ) {
    $data->load_file($url);
    $data->save($cache_file_name);
}
else{
    $data->load_file($cache_file_name);
}
    

//Grab the div for this server on server status page.
$serverElm = $data->find("div[data-name=$serverName]", 0);

$server["name"] = $serverElm->find("div.name",0)->innertext; //name of server
$server["status"] = $serverElm->getAttribute('data-status'); //status - UP/DOWN
$server["type"] = $serverElm->getAttribute('data-type');  //type - PVP, PVE etc
$server["timezone"] = $serverElm->getAttribute('data-timezone'); //timezone - east, west
switch($serverElm->getAttribute('data-population')){ //1,2,3,4,5
    case '1':
        $server["population"] = 'Light';
        break;
    case '2':
        $server["population"] = 'Standard';
        break;
    case '3':
        $server["population"] = 'Heavy';
        break;
    case '4':
        $server["population"] = 'Very Heavy';
        break;
    case '5':
        $server["population"] = 'Full';
        break;
    
}

?>

<div id="swtor-serverStatus-widget">
    <div class="container">
        <div class="name"><?php echo $server["name"] .' ('.$server["type"].', '.$server["timezone"].')';?></div>
        <div class="status <?php echo $server["status"];?>"><strong>Status:</strong> <?php echo $server["status"];?></div>
	<div class="population"><strong>Population:</strong> <?php echo $server["population"];?></div>
    </div>
</div>