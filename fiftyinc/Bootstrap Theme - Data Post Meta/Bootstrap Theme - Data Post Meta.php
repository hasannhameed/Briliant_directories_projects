<?  
/**

* This widget follows BD code standards 
* Widget Name: Bootstrap Theme - Data Post Meta
* Short Description: This widget runs in the function "showDataCategoryResults()" when the software version is 4 and the section is for member features hat are not albumns
* Version: 0.1
* Developed By: Matt Brooks
* Collaborators: Oscar Esquivel
* Last Edited By: 
* -- Oscar Esquivel 11/20/2015
*/
$post = $_ENV['post'];
$user = getUser($post['user_id'],$w);
$post = getMetaData("data_posts",$post['post_id'],$post,$w);

$filenameBdString = new bdString($user['filename']);
$filenameBdString->urldecodeStringChain('/');
$user['filename'] = $filenameBdString->modifiedValue;

foreach ($post as $key => $value) {
    $post[$key] = stripslashes($value);
} 
$post['image'] = showImage($post['post_image'],"data_posts",$w);
$_ENV['results_loop_image'][] = $post['post_image'];

if ($post['post_content'] == "" && $post['post_article'] != "") { 
    $post['post_content'] = $post['post_article']; 
}
//$photo = getProfilePhoto($post[user_id],"thumbnail",$w);

if ($post['post_caption'] == "" && $post['post_content'] != "") { 
    $post['post_caption'] = limitWords(strip_tags($post['post_content']),$dc['caption_length']);
}
if ($post['post_start_date'] != "") { 
    $post['month'] = date("M",strtotime($post['post_start_date']));
    $post['day'] = date("d",strtotime($post['post_start_date']));
}
if ($post['distance'] > 0) { 
    
    if ($post['distance'] > 1) { 
        $post['post_distance'] .= number_format($post['distance'],1)." ".$w['distance_full']."s away"; 
    
    } else { 
        $post['post_distance'] .= "Within 1 ".ucfirst($w['distance_full']); 
    }
    $post['post_distance']="<img src='/images/green-check.png' style='vertical-align:middle;' /> ".$post['post_distance']."<br />";
}
$tags = array();
/// make sure the count is 0 instead of checking the string
if ($post['post_tags'] != "" && count($tags) == 0) { 
    $post['post_tags'] = explode(",",rtrim($post['post_tags'],", "));
    
    foreach ($post['post_tags'] as $tag) {
      
        if ($tag != "") { 
            $_ENV['tag'] = str_replace('"',"",$tag);
            $tags[] = widget("Bootstrap Theme - Tag Link","",$w['website_id'],$w);
        }
    } 
    if (is_array($tags)) { 
        $post['post_tags'] = implode(" ",$tags); 
        $tags = $post['post_tags']; 
    }
}
$post['post_video_link'] = $post['post_video'];
$videoOembed = new oembed_controller($post['post_video_link'],null,$post['post_image']);
$videoData = $videoOembed->getOembedData();
if (!empty($videoData)) {
    $post['post_video'] = $videoData->html;
    $post['post_video_oembed'] = (array)$videoData;
    $post['post_video_thumbnail'] = $videoOembed->getThumbnailHtml($post);
    $post['post_video_type'] = $videoOembed->getType();

}

$_ENV['post'] = $post;
?>