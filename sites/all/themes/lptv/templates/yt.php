<?php
header("Content-Type: application/atom+xml; charset=UTF-8");
//	if (!file_exists('./youtube.html')){
		//$playlists = file_get_contents('http://www.youtube.com/results?search_query=Let%27s+Play%2C+playlist');
		//file_put_contents('youtube.html', $playlists);
//		$curl = curl_init('http://google.com');
//		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
//		curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/534.10 (KHTML, like Gecko) Chrome/8.0.552.224 Safari/534.10');
//		$html = curl_exec($curl);
//		curl_close($curl);
//	}

/*
class AtomFeed { 
   function getFeed($site_title, $contact_email, $feed_location, $feed_items, $domain=false){ 
      if (!$domain) $domain = $_SERVER["SERVER_NAME"]; 
      $feed = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"; 
      $feed .= "<feed xmlns=\"http://www.w3.org/2005/Atom\">\n"; 
      $feed .= "<title>$site_title</title>\n<link rel=\"self\" href=\"$feed_location\"/>\n<updated>" . date('Y-m-d\TH:i:s', $feed_items[0]["EpochPublishDate"]) . "</updated>\n"; 
      $feed .= "<author>\n<name>$site_title</name>\n<email>$contact_email</email>\n<uri>http://$domain</uri>\n</author>\n"; 
      foreach ($feed_items as $item) { 
         $item["EpochPublishDate"] = date("D, d M Y H:i:s O", $item["EpochPublishDate"]); 
         $feed .= "<entry>\n<title>" . $item["ItemTitle"] . "</title>\n<link href=\"http://$domain" . $item["ItemAbsoluteURL"] . "\"/>\n<id>http://$domain" . $item["ItemAbsoluteURL"] . "</id>\n<updated>" . $item["EpochPublishDate"] . "</updated>\n<summary type=\"xhtml\">" . $item["ItemDescription"] . "</summary>\n</entry>\n"; 
      } 
      $feed .= "</feed>"; 
      return $feed; 
   } 
}
*/

//echo AtomFeed::getFeed($site_title, $site_description, $feed_url, $items);
print "<?xml version='1.0' encoding='utf-8'?>";
print "<feed xmlns='http://www.w3.org/2005/Atom'>";
print '<id>http://sportsscene.no-ip.org:8282/sites/all/themes/lptv/templates/yt.php</id>';
print '<title  type="text">Let\'s Play TV YouTube Playlists list</title>';
//print '<link href="http://sportsscene.no-ip.org:8282/sites/all/themes/lptv/templates/yt.php"></link>';
//print '<updated>2006-12-13T18:20:02Z</updated>';
//print '<author> <name>Your Name</name> <email>you@domain.com</email> </author>';
//print '<content>Returns all of YouTube\'s Playlists from Let\s Plays</content>';

for ($page = $_GET['start']; $page <= $_GET['end']; $page++){
$html = new DOMDocument();
@$html->loadHtmlFile('http://www.youtube.com/results?search_type=videos&search_query=Let%27s+Play%2C+playlist&search_sort=video_date_uploaded&page='.$page);
$xpath = new DOMXPath( $html );
$nodelist = $xpath->query( "//a[@class='yt-uix-tile-link']|//a[@class='yt-uix-tile-link']/@href|//a[@class='yt-user-name ']" );
$queries = 3;
foreach ($nodelist as $key => $n){
	$clean = htmlentities($n->nodeValue);
	switch ($key % $queries){
	case 0:
		echo "<entry>";
		echo '<title>'.html_entity_decode(htmlentities(trim(preg_replace("/Let'?s Play #?[0-9]?:?|[\(\[](\/?[PA|GC|GCN|PS|PS1|PS2|PS3|Wii|N64|SNES|NES|Atari|XBOX|XB|XB360|XBOX360|SMS|SMD|Genesis|SCD|Sega|Sega CD|Sega 32X|NeoGeo|Neo\-Geo|VB|AJ|Saturn|SS|PSone|3DO|DC|PSX|GB|GBA|GBX|NDS|DS|NDSi|DSi|3DS|WiiU|PSP|PSPGo|PC|Finished|complete|0-9+|0-9+\w?\-?\w?0-9+])*[\)\]]/", "", $clean)))).'</title>';
		//echo "<summary>REPLACE ME</summary>";
		break;
	case 1:
		//echo '<content type="application/atom+xml;type=feed" src="http://youtube.com'.$clean.'" />';
		echo "<id>http://youtube.com".$clean."</id>";
		echo "<link type='application/atom+xml' href='http://youtube.com".$clean."' />";
	        $xml = new DOMDocument();
		//echo "PEE https://gdata.youtube.com/feeds/api/playlists/".substr($n->nodeValue, -16)."?v=2";
		@$xml->loadHtmlFile("https://gdata.youtube.com/feeds/api/playlists/".substr($n->nodeValue, -16)."?v=2");
	        $xpathDetails = new DOMXPath( $xml );
        	$details = $xpathDetails->query("//subtitle");
		foreach ($details as $nn){
			print '<content type="text">'.$nn->nodeValue.'</content>';
		}
		break;
	case 2:
		echo "<contributor><name>$clean</name></contributor>";
                echo "<author><name>$clean</name><uri>www.youtube.com/user/$clean</uri></author>";
                // End
                echo "<updated>".time(RFC3339)."</updated>";
                //echo "<content>".$clean."</content>";
                echo "<category term='game' />";
                echo "</entry>";
		break;
	}
}	
}
print '</feed>';

//	@$xmldoc->loadHTML($html);
//	$xpath = new DOMXPath($dom);
//        $queryResult = $xpath->query('//a[@class="yt-uix-tile-link"]');
//        foreach($queryResult as $result){
//                echo $result->textContent;
//        }
?>
