<?php
error_reporting(E_ALL & ~E_WARNING);
ini_set('display_errors', '0');
header('Content-Type: application/json');

define('ROOT_DIR', realpath(dirname(__FILE__)) .DIRECTORY_SEPARATOR);
define('CURL_UA', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.141 Safari/537.36');
define('CURL_TIMEOUT', 30);
// define('TMP_DIR', ROOT_DIR . 'temp'. DIRECTORY_SEPARATOR);

function randomPassword(){
    $alphabet = 'abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789';
    $pass = array();
    $alphaLength = strlen($alphabet) - 1;
    for ($i = 0; $i < 9; $i++){
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass);
}


function curlGET($url,$ref_url = "https://www.google.com/",$agent = CURL_UA){
    // $cookie = TMP_DIR.unqFile(TMP_DIR, randomPassword().'_curl.tdata');
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, $agent);
    // curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
    // curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 100);
    curl_setopt($ch, CURLOPT_HTTPHEADER, Array(
        "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
        "Accept-Language: en-US,en;q=0.5",
        "Accept-Encoding: gzip, deflate",
    ));
    curl_setopt($ch, CURLOPT_VERBOSE, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, CURL_TIMEOUT);
    curl_setopt($ch, CURLOPT_TIMEOUT, CURL_TIMEOUT);
    curl_setopt($ch, CURLOPT_REFERER, $ref_url);
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
    $html=curl_exec($ch);
    curl_close($ch);
    return $html;
}


function unqFile($path,$filename){
    if (file_exists($path.$filename)) {
        $filename = rand(1, 99999999) . "_" . $filename;
        return unqFile($path,$filename);
    }else{
        return $filename;
    }
}

function getMyMeta($url,$promo, $noTitle='No Title', $noDes='No Description', $noKey='No Keywords'){

    $title = $description = $keywords = $html = $author = $siteName = $image = '';
    $viewport = '-';
    $lenTitle = $lenDes = 0;
    $openG = false;

    //Get Data of the URL
    $html = curlGET($url);

    if($html == '')
        return false;

    //Fix Meta Uppercase Problem
    $html = str_ireplace(array("Title","TITLE"),"title",$html);
    $html = str_ireplace(array("Description","DESCRIPTION"),"description",$html);
    $html = str_ireplace(array("Keywords","KEYWORDS"),"keywords",$html);
    $html = str_ireplace(array("Content","CONTENT"),"content",$html);
    $html = str_ireplace(array("Meta","META"),"meta",$html);
    $html = str_ireplace(array("Name","NAME"),"name",$html);

    //Load the document and parse the meta
    $doc = new DOMDocument();
    @$doc->loadHTML($html);
    $nodes = $doc->getElementsByTagName('title');
    $title = $nodes->item(0)->nodeValue;
    $metas = $doc->getElementsByTagName('meta');

    for ($i = 0; $i < $metas->length; $i++){
        $meta = $metas->item($i);
        if($meta->getAttribute('name') == 'description')
            $description = $meta->getAttribute('content');
        if($meta->getAttribute('name') == 'keywords')
            $keywords = $meta->getAttribute('content');
        if($meta->getAttribute('name') == 'viewport')
            $viewport = $meta->getAttribute('content');
        if($meta->getAttribute('name') == 'author')
            $author = $meta->getAttribute('content');
        if($meta->getAttribute('property') == 'site_name')
            $siteName = $meta->getAttribute('content');
        if($meta->getAttribute('property') == 'og:title')
            $openG = true;
        if($meta->getAttribute('property') == 'og:image')
            $image = $meta->getAttribute('content');
    }

    $lenTitle = mb_strlen($title);
    $lenDes = strlen($description);

    //Check Empty Data
    $title = ($title == '' ? $noTitle : $title);
    $description = ($description == '' ? $noDes : $description);
    $keywords = ($keywords == '' ? $noKey : $keywords);

    if(!$image) {
        $image = "//image.thum.io/get/$url";
    }

    if(!$image) {
        //https://developers.google.com/speed/docs/insights/v5/get-started
        $API_KEY = "AIzaSyCVlqsFsbxciSKZEQQIz9YHy6IhxS0fCvE";
        try{
            $googlePagespeedData = file_get_contents("https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url=$url&screenshot=true&key=$API_KEY");
            $googlePagespeedData = json_decode($googlePagespeedData, true);
            $image = $googlePagespeedData['lighthouseResult']['audits']['final-screenshot']['details']['data'];
        }
        catch (Exception $e){
        }
    }

    $id=0;
    $urldata_json = file_get_contents('data.json');
	$urldata = json_decode($urldata_json, true);
    $id = count($urldata);
foreach ($urldata as $key => $value) {
		# code...
		if ($value['id'] == $id ) {
			$id++;
		}
	}

    //Return as Array
    return array(
        "id" => $id,
        "url" => $url,
        "title"=>$title,
        "description"=>$description,
        "keywords"=>$keywords,
        "openG"=>$openG,
        "lenTitle"=>$lenTitle ,
        "lenDes"=>$lenDes,
        "viewport"=>$viewport,
        "author"=>$author,
        "promo"=>$promo,
        "siteName"=>$siteName,
        "image" => $image
    );
}

function raino_trim($str){
    $str = Trim(htmlspecialchars($str));
    return $str;
}

function clean_url($site) {
    $site = strtolower($site);
    $site = str_replace(array(
        'http://',
        'https://',
        'www.'), '', $site);
    return $site;
}

if (isset($_GET['url'])) {
    $mypromo = '';
    $my_url = raino_trim($_GET['url']);
    // Make url clean
    $my_url = clean_url($my_url);
    $my_url = 'https://'.$my_url;
    if (isset($_GET['promo'])) {
         $mypromo = $_GET['promo'];
    }
    $filename = 'data.json';
    // If the JSON file not exists, create empty file.
    if (!file_exists($filename)) {
        file_put_contents($filename, '');
    }
    $siteData = file_get_contents($filename);
    $siteList = json_decode($siteData, true);
    if($siteList === null){
        $siteList = [];
    }
    $matchingSite = null;
    // Loop through the site list
    foreach ($siteList as $key => $site) {
        if ($site['url'] === $my_url) {
            // Found a match, assign the site to $matchingSite and break out of the loop
            if($mypromo != '')
            {
                $site['promo'] = $mypromo;
                $siteList[$key] =  $site;
                file_put_contents($filename, json_encode($siteList));
            }
            
            $matchingSite = $site;
            break;
        }
    }
    // If same url is found
    if($matchingSite){
        echo json_encode($matchingSite);
        return;
    }
    
    // If not
    if (filter_var($my_url, FILTER_VALIDATE_URL) === false) {
        $error = "Error";
    }else {
        $arr_meta = getMyMeta($my_url,$mypromo);
        if(is_array($arr_meta)){
            $siteList[] = $arr_meta;
            file_put_contents($filename, json_encode($siteList));
            echo json_encode($arr_meta);
        }
    }
}
