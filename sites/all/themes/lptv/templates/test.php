<?php
//print phpinfo();

include('Zend/Gdata/YouTube.php');
//require_once('/usr/share/php/library/Zend/Gdata/YouTube.php');
//set_include_path('get_include_path() . PATH_SEPARATOR . $clientLibraryPath');
//set_include_path($clientLibraryPath);

function printPlaylistListFeed($playlistListFeed, 
 $showPlaylistContents) 
 { 
   $count = 1; 
//   print_r($playlistListFeed);
   foreach ($playlistListFeed as $playlistListEntry) { 
     echo 'Entry # ' . $count . "\n"; 
     printPlaylistListEntry($playlistListEntry, $showPlaylistContents); 
     echo "\n"; 
     $count++; 
   } 

}

function printPlaylistListEntry($playlistListEntry, 
 $showPlaylistContents = false) 
 { 
//   echo 'Playlist: ' . $playlistListEntry->title->text . "\n";
//   print_r($playlistListEntry);
    echo $playlistListEntry->title->text . "\n";
    echo $playlistListEntry->summary->text . "\n";
    //echo $playlistListEntry->getPlaylistVideoFeedUrl() . "\n\n\n";
  //print_r($playlistListEntry);
//   echo "\tDescription: " . $playlistListEntry->summary->text . 
 "\n"; 
   if ($showPlaylistContents === true) { 
     getAndPrintPlaylistVideoFeed($playlistListEntry, "\t\t"); 
   } 

}

$pl = new Zend_Gdata_YouTube_PlaylistListFeed();
//$pl->newPlaylistQuery('soccer');
//print '<pre>';
//print_r($pl);
//print '</pre>';

$searchTerms = "Let's Play";
 $yt = new Zend_Gdata_YouTube();
  $yt->setMajorProtocolVersion(2);
//  $query = $yt->newVideoQuery();
//  $query->setOrderBy('viewCount');
//  $query->setSafeSearch('none');
//  $query->setVideoQuery($searchTerms);
 //print $query->getQueryUrl(2);
// $videoFeed = $yt->getPlaylistVideoFeed($query->getQueryUrl(2));
 //$nextFeed = $videoFeed->getNextFeed();
// printPlaylistListFeed($nextFeed, "Let's+Play");
//  $query->setCategory('game');
  $query = $yt->getPlaylistVideoFeed("http://gdata.youtube.com/feeds/api/playlists/snippets?q=Lets+Play&key=AI39si52rhsvMX3iP4PUZ1PUfPIA-whAnbi5vFLUlUSMKVm5RxvoiYWgczzX4DGUExp_HIrPu2ul2gqycnRPMcwqtMehM_1P4Q&v=2&max-results=50&safesearch=none&search_sort=video_date_uploaded");
 $nextFeed = $query->getNextFeed();
 printPlaylistListFeed($nextFeed, "Lets+Play");
//$yt->setVideoQuery($searchTerms);
//  $query = $yt->getPlaylistVideoFeed('soccer');
  //$q2 = $yt2->getPlaylistVideoFeed("soccer");
  //print $q2;
//printPlaylistListFeed($query, "LetsPlay");
//$class = new ReflectionClass('Zend_Gdata_YouTube');
//print_r( $class->getMethods());
//  print '<pre>';
//  print_r($query);
//  print '</pre>';
//  $query->setOrderBy('viewCount');
//  $query->setVideoQuery($searchTerms);
//  print $yt->getVideoFeed($query);
//  print $videoFeed;
//  print $searchTerms;
//  printVideoFeed($videoFeed, 'Search results for: ' . $searchTerms);

