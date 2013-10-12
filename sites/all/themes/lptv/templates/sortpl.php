function sortPlaylist($playlist){ 
    //where $yt is a fully authenticated service object
    $yt = new Zend_Gdata_YouTube($httpClient); 
    $feed = $yt->getPlaylistListFeed('default'); 
    $playlistListEntry = null; 
    foreach($feed as $playlistEntry) { 
        if ($playlistEntry->getTitleValue() == $playlist) { 
            $playlistListEntry = $playlistEntry; 
            break; 
        } 
    } 
    $playlistVideoFeed = $yt->getPlaylistVideoFeed($playlistListEntry->getPlaylistVideoFeedUrl()); 
    $playlistVideoFeed = $yt->retrieveAllEntriesForFeed($playlistVideoFeed); 
    $videolist = array(); 
    $vn = 0;
    //add each video in Playlist to array
    foreach ($playlistVideoFeed as $playlistVideoEntry) { 
        $videolist[$vn]['title'] = $playlistVideoEntry->getVideoTitle(); 
        $videolist[$vn]['object'] = $playlistVideoEntry; 
        $vn++; 
 
    } 
    
    //sort array in reverse
    $videolist = msort($videolist,"title"); 
    $vn = 0; 
    foreach($videolist as $varray) { 
        $playlistVideoEntryToBeModified = $varray['object']; 
        $playlistVideoEntryToBeModified->setPosition($yt->newPosition(1));
        $yt->updateEntry($playlistVideoEntryToBeModified, $playlistVideoEntryToBeModified->getEditLink()->getHref()); 
        $vn++; 
        if ($vn/10 == floor($vn/10)) { 
            sleep(100);
            //otherwise you'll get a "too_many_recent_requests" error 
        } 
 
    } 
}

function msort($array, $id="title") { 
    $temp_array = array(); 
    while(count($array)>0) { 
        $lowest_id = 0; 
        $index=0; 
        foreach ($array as $item) { 
            if (isset($item[$id]) && $array[$lowest_id][$id]) { 
                if ($item[$id]>$array[$lowest_id][$id]) { 
                    $lowest_id = $index; 
                } 
            } 
            $index++; 
        } 
        $temp_array[] = $array[$lowest_id]; 
        $array = array_merge(array_slice($array, 0,$lowest_id), array_slice($array, $lowest_id+1)); 
    } 
    return $temp_array; 
}
