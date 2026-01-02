<?php
require_once($w['flocation'].'/dev_refactor_oop_3.0/core/bd_string.php');

$filenameBdString = new bdString($_POST['dataFilename']);
$filenameBdString->urlencodeStringChain('/');
$_POST['dataFilename'] =  $filenameBdString->modifiedValue;

// this select will get the code from the loop of the search Results 
$listingSearch = mysql($w['database'], "SELECT profile_results_layout, search_results_div,data_filename,data_name FROM data_categories WHERE data_filename = '" . $_POST['dataFilename'] . "' LIMIT 1");
$listingSearchResults = mysql_fetch_array($listingSearch);
if (empty($listingSearchResults[0])) {
    $dataStructureInformation = $listingSearchResults[1];
} else {
    $dataStructureInformation = $listingSearchResults[0];
}

if ($_POST['numbersOfListings'] != "") {
    $userDataQuery = mysql($w['database'], stripslashes($_POST['userQuery']) . " Limit " . $_POST['numbersOfListings'] . "," . $_POST['numbersOfListingsCount']);
    $wa = getWebsiteLayout($w['website_id']);// will load the design settings
    switch ($_POST[dataType]) {
        case 4: //case for groups features
            while ($p = mysql_fetch_assoc($userDataQuery)) {
                $p = getMetaData("users_portfolio_groups", $p[group_id], $p, $w);
                $user = $user_data;
                $my_photo = mysql($w['database'], "SELECT `file`
                                                 FROM `users_portfolio`
                                                 WHERE group_id=$p[group_id]
                                                 ORDER BY `group_cover` DESC
                                                 LIMIT 1");
                $new_photo = mysql_fetch_assoc($my_photo);
                $p['group_picture'] = $new_photo["file"];
                echo eval("?>" . replaceChars($w, $dataStructureInformation) . "<?");
            }
            break;
        case 13: // case for reviews.
            while ($r = mysql_fetch_assoc($userDataQuery)) {
                echo eval("?>" . replaceChars($w, $dataStructureInformation) . "<?");
            }
            break;
        case 9:
        case 20: // case for post features and videos.
        default:
            $dataTypeInfo = getDataCategory($_POST['dataType'],'data_type',$w);
            $subscription = getSubscription($user_data['subscription_id'], $w);
            $recurringEventsAddOn = getAddOnInfo('recurring_events');

            //if we are doing events and we have recurring events
            if($_POST['formName'] == "event_fields" && isset($recurringEventsAddOn['status']) && $recurringEventsAddOn['status'] == 'success' && ($dataTypeInfo['enable_recurring_events_addon'] == 1 || !isset($dataTypeInfo['enable_recurring_events_addon']) ) ){

                $userDataQuery = mysql($w['database'], stripslashes($_POST['userQuery']));

                while ($post = mysql_fetch_assoc($userDataQuery)) {
                    $post               = getMetaData("data_posts",$post['post_id'],$post,$w);
                    $event              = new event($post);
                    $isEventDateInRange = $event->isEventDateInRange($_POST['finalDate'],$_POST['finalDate2']);
                    $isEventTimeExpire  = $event->isEventTimeExpire();
                    $currentTimeStamp   = $event->currentDateObject->getTimestamp();
                    $startTimeStamp     = $event->closestDate->getTimestamp();

                    //this for events in date range and the event hasnt start
                    if($isEventDateInRange && $currentTimeStamp < $startTimeStamp){
                        $objectsArray[] = $event;
                    }else if($isEventDateInRange && $currentTimeStamp >= $startTimeStamp && $isEventTimeExpire === false){//this is for events in date range and the event already started and the time hasnt expired
                        $objectsArray[] = $event;
                    }else{ //debug purspose
                        //debugDev::output($event);
                    }
                }

                $_GET['page'] = ceil((($_POST['amountOfResults']) / ($_POST['numbersOfListingsCount']+1)) + 1);
             
                    event::sortEvents($objectsArray);
                
                require_once($w['flocation'].'/dev_refactor_oop_3.0/classes/paginator.php');
                $paginatorObject = new paginator(($_POST['numbersOfListingsCount']+1),$_GET['page'],$objectsArray);
                $paginatorObject->build();

                //we re index the post array with the new order
                $postArray = array();

                $objectsArray = array_slice($objectsArray, $paginatorObject->getStart(),$paginatorObject->getRecordsPerPage());

                foreach ($objectsArray as $event) {
                    $postArray[] = $event->post;
                }

            }else{

                $postArray = array();

                while ($post = mysql_fetch_assoc($userDataQuery)) {
                    $post = getMetaData("data_posts", $post[post_id], $post, $w);
                    $postArray[] = $post;
                }

            }

            foreach ($postArray as $post) {
                $post = getMetaData("data_posts", $post[post_id], $post, $w);
                $post['post_caption'] = $post['post_content'];
                if ($_POST['dataType'] == 9) {
                    $user_data = getUser($post['user_id'], $w);
                    $user = $user_data;
                }
                $dc['data_filename'] = $listingSearchResults[2];

                $filenameBdString = new bdString($dc['data_filename']);
                $filenameBdString->urldecodeStringChain('/');
                $dc['data_filename'] =  $filenameBdString->modifiedValue;

                $post['post_video_link'] = $post['post_video'];
                if ($post['post_tags'] != "" && $tags == "") {
                    $post[post_tags] = explode(",", rtrim($post[post_tags], ", "));

                    foreach ($post[post_tags] as $tag) {
                        if ($tag != "") {
                            $tag = str_replace('"', "", $tag);
                            $tags[] = "<a href='/" . $listingSearchResults[2] . "?q=" . trim($tag) . "' title='" . $tag . " " . ucwords($listingSearchResults[3]) . "'>" . ucwords(strtolower(trim($tag))) . "</a>";
                        }
                    }
                    if (is_array($tags)) {
                        $post[post_tags] = implode(" ", $tags);
                        $tags = $post[post_tags];
                    }
                }
                if ($post['post_author'] != "") {
                    $user = getUser($post['user_id'], $w);
                }

                $filenameBdString = new bdString($user['filename']);
                $filenameBdString->urldecodeStringChain('/');
                $user['filename'] =  $filenameBdString->modifiedValue;

                $profession = getProfessionDetails($user['profession_id'],$w);

                echo eval("?>" . replaceChars($w, $dataStructureInformation) . "<?");
            }
            break;
    }
}
//For the features we need to change the variables  $post[post_caption] to $post[post_content] 
?>