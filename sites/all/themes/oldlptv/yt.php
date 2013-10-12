<?php
	if (!file_exists('youtube.html')){
		//$playlists = file_get_contents('http://www.youtube.com/results?search_query=Let%27s+Play%2C+playlist');
		//file_put_contents('youtube.html', $playlists);
		$curl = curl_init('http://www.youtube.com/results?search_query=Let%27s+Play%2C+playlist');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/534.10 (KHTML, like Gecko) Chrome/8.0.552.224 Safari/534.10');
		$html = curl_exec($curl);
		curl_close($curl);
	}
	
	
//	$xmldoc = new DOMDocument();
        //$xmldoc->load($playlists);

	//$xmldoc = load($curl);
        //$xpathvar = new Domxpath($xmldoc);
	//$xpathVAR = new DOMXPath($doc);
//	@$xmldoc->loadHTML($html);
//	$xpath = new DOMXPath($dom);
//        $queryResult = $xpath->query('//a[@class="yt-uix-tile-link"]');
//        foreach($queryResult as $result){
//                echo $result->textContent;
//        }
?>
