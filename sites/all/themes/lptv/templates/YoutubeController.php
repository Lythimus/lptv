<?php
//print phpinfo();

include('Zend/Gdata/YouTube.php');
//require_once('/usr/share/php/library/Zend/Gdata/YouTube.php');
//set_include_path('get_include_path() . PATH_SEPARATOR . $clientLibraryPath');
//set_include_path($clientLibraryPath);
/*
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
   echo 'Playlist: ' . $playlistListEntry->title->text . "\n";
  //print_r($playlistListEntry);
//   echo "\tDescription: " . $playlistListEntry->summary->text . 
 "\n"; 
   if ($showPlaylistContents === true) { 
     getAndPrintPlaylistVideoFeed($playlistListEntry, "\t\t"); 
   } 

}
*/

//class YoutubeController extends Zend_Controller_Action
//{
    $_apiKey = 'AI39si52rhsvMX3iP4PUZ1PUfPIA-whAnbi5vFLUlUSMKVm5RxvoiYWgczzX4DGUExp_HIrPu2ul2gqycnRPMcwqtMehM_1P4Q';
 
    function _process(Zend_Gdata_YouTube_VideoFeed $videoFeed)
    {
 
        /* @var $entry Zend_Gdata_YouTube_VideoEntry */
        foreach ($videoFeed as $entry) {
            /* @var $published Zend_Gdata_App_Extension_Published */
            $published = $entry->getPublished();
            $thumbnails = $entry->getVideoThumbnails();
 
            // set date format
            $date = new Zend_Date($published->getText());
 
            // array
            $tags = $entry->getVideoTags();
 
            $data['title'] = $entry->getVideoTitle();
            $data['description'] = $entry->getVideoDescription();
            $data['date'] = $date->get('Y-MM-d hh:mm:ss');
            $data['thumb'] = @$thumbnails[3]['url'];
            $data['id'] = $entry->getVideoId();
            $data['flash_player'] = $entry->getFlashPlayerUrl();
            $data['tags'] = implode(',', $tags) . ', ' . $entry->getVideoCategory();
            // do whatever you want with the data
        }
    }
 
    function readRssAction()
    {
        $userId = 'some_user';
        $client = new Zend_Http_Client();
        $gdata = new Zend_Gdata_YouTube($client, 'my-app', null, $this->_apiKey);
        $videoFeed = $gdata->getUserUploads($userId);
 
        // save the last items
        $this->_process($videoFeed);
 
        try {
            while ($videoFeed = $videoFeed->getNextFeed()) {
                $this->_process($videoFeed);
            }
        } catch (Zend_Gdata_App_Exception $e) {
            echo $e->getMessage();
        }
    }




//$pl = new Zend_Gdata_YouTube_PlaylistListFeed();
//$pl->newPlaylistQuery('soccer');
//print '<pre>';
//print_r($pl);
//print '</pre>';

//$searchTerms = 'soccer';
// $yt = new Zend_Gdata_YouTube();
//  $query = $yt->getPlaylistVideoFeed("http://gdata.youtube.com/feeds/api/playlists/snippets?q=Let's+Play&key=AI39si52rhsvMX3iP4PUZ1PUfPIA-whAnbi5vFLUlUSMKVm5RxvoiYWgczzX4DGUExp_HIrPu2ul2gqycnRPMcwqtMehM_1P4Q&v=2&start-index=100&max-results=50");
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

