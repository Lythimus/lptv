<?php

print "<link>http://sportsscene.no-ip.org:8282/sites/all/themes/lptv/templates/you.php</link>";
print "<updated>2006-12-13T18:20:02Z</updated>";
print "<author> <name>Your Name</name> <email>you@domain.com</email> </author>";
print "<description>Returns all of YouTube\'s Playlists from Let\s Plays</description>";
print "<language>en-us</language>";

$html = new DOMDocument();
@$html->loadHtmlFile('http://www.youtube.com/results?search_query=Let%27s+Play%2C+playlist');
$xpath = new DOMXPath( $html );
$nodelist = $xpath->query( "//a[@class='yt-uix-tile-link']|//a[@class='yt-uix-tile-link']/@href" );
foreach ($nodelist as $n){
echo "<entry>";
echo "<title>".$n->nodeValue."</title>";
echo "<link>".$n->nodeValue."</link>";
echo "<updated>".time()."</updated>";
echo "<content>".$n->nodeValue."</content>";
echo "</entry>";
}	

print '</feed>';

//	@$xmldoc->loadHTML($html);
//	$xpath = new DOMXPath($dom);
//        $queryResult = $xpath->query('//a[@class="yt-uix-tile-link"]');
//        foreach($queryResult as $result){
//                echo $result->textContent;
//        }
?>
