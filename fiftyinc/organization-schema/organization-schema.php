<?php
$reviewschema_SQL = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `users_reviews` WHERE `review_status` = '2' and `user_id`='$user[user_id]'");
$countchema_SQL=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT Count(*) as count FROM  `users_reviews` WHERE `review_status` = '2' and `user_id`='$user[user_id]'");
$row_schema= mysql_fetch_assoc($countchema_SQL);
$schema_count= $row_schema[count];

$avg_reviews= mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT AVG(((rating_overall + rating_service + rating_response + rating_expertise + rating_results + rating_language) / 6) )as avg From users_reviews where user_id = '$user[user_id]'");
$row_schema_avg= mysql_fetch_assoc($avg_reviews);
$overallrating = $row_schema_avg[avg];


?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization"       
    <?php if($user['company'] !=''){ ?>
    ,"name": "<?php echo $user['company']?>"
    <?php } ?>

    <?php if($user['website'] !=''){ ?>
    ,"url" : "<?php echo 'https://%website_url%/'.$user['filename']?>"
    <?php } ?>
    <?php if($user['about_me'] !=''){ ?>
    ,"description" :"<?php echo strip_tags(str_replace('"', '',$user[about_me]));?>"
    <?php } ?>
    <?php if($user['experience'] !=''){ ?>    
    ,"foundingDate": "<?php echo $user['experience']?>"
    <?php } ?>
    <?php if($user['image_main_file'] !=''){ ?>
    ,"image" : "<?php echo 'https://%website_url%/'.$user['image_main_file']?>"
    <? } ?>,
    
  "address": {
    "@type": "PostalAddress"
    <?php if($user['address1'] !=''){ ?>
    ,"streetAddress": "<?php echo  $user['address1'] ?>"
    <?php } ?>
    <?php if($user['city'] !=''){ ?>
     ,"addressLocality": "<?php echo $user['city']?>"
    <?php } ?>
     <?php if($user['state_ln'] !=''){ ?>
     ,"addressRegion": "<?php echo $user['state_ln']?>"
    <?php } ?>
    <?php if($user['country_ln'] !=''){ ?>
    ,"addressCountry" : "<?php echo $user['country_ln'];?>"
    <?php } ?>
    <?php if($user['zip_code'] !=''){ ?>
    ,"postalCode": "<?php echo $user['zip_code'];?>"
    <?php } ?>
    
  },
   <?php if($user['email'] !=''){ ?>
    "email" : "<?php echo $user['email'];?>"
    <? } ?>
    <?php if($user['fax_number'] !=''){ ?>
    ,"faxNumber":  "<?php echo $user['fax_number'] ?>"
    <?php } ?>,
    "sameAs": [           


            <?php
              $social_links = array_filter([ $user['website'], $user['linkedin'], $user['twitter'], $user['facebook'], $user['youtube'], $user['blog'], $user['instagram'], $user['pinterest'] ]);
              $links = [];
              foreach ($social_links as $link) {
                $links[] = '"' . $link . '"';
              }
              echo implode(',', $links);

             ?>
        ] 
   <?php if($user['phone_number'] !=''){ ?>
    ,"telephone" : "<?php echo $user['phone_number'];?>"

    <? } ?>,
    "member": {
    "@type": "OrganizationRole",
    "member": {
    "@type": "Person" 
    <?php if( $user[position]!=''){?>
      ,"jobTitle": "<?php echo $user[position];?>"
    <?php } ?>
      <?php if($user['first_name'] !=''){?>
        ,"name":"<?php echo $user['first_name'].' '. $user['last_name'] ?>"
            <?php } ?>,
      "url" :  "<?php echo 'https://%website_url%/'.$user['filename']?>"
     }
    },
   
    "review": [
    
    <?php
    $loop_count = 0;
    while($reviews_data= mysql_fetch_assoc($reviewschema_SQL)) {
        $loop_count++;
        $review_date = date("m-d-y", strtotime($reviews_data[review_added]));
        $rating_review= $reviews_data[rating_overall];
        $ratingvalue_max = max($reviews_data[rating_overall],$reviews_data[rating_service],$reviews_data[rating_expertise],$reviews_data[rating_results],$reviews_data[rating_language],$reviews_data[rating_response]);
         $ratingvalue_min = min($reviews_data[rating_overall],$reviews_data[rating_service],$reviews_data[rating_expertise],$reviews_data[rating_results],$reviews_data[rating_language],$reviews_data[rating_response]);
         $ratingvalue_avg_review = ($reviews_data[rating_overall]+$reviews_data[rating_service]+$reviews_data[rating_expertise]+$reviews_data[rating_results]+$reviews_data[rating_language]+$reviews_data[rating_response])/6;?>
    
      {
      "@type": "Review",
       "name": "<?php echo $reviews_data[review_title]?>",
      "author": "<?php echo $reviews_data[review_name]?>",      
      "reviewBody": "<?php echo $reviews_data[review_description]?>",
      "datePublished": "<?php echo $review_date; ?>",
      "reviewRating": {
        "@type": "Rating",
        "bestRating": "<?php echo $ratingvalue_max;?>",
         "worstRating":"<?php echo $ratingvalue_min;?>",
        "ratingValue": "<?php echo round($ratingvalue_avg_review,1);?>"

      }
    }
    <?php echo $loop_count != $schema_count ? "," : "" ?>

  <?php } ?>
    
  
  ],
  "aggregateRating": {
            "@type": "AggregateRating",
          "reviewCount": "<?php echo $schema_count;?>"
          <?php if($rating_review !=0 ){?>
            , "ratingValue": "<?php echo round($overallrating,1);?>"
          <?php } ?>   
    },
	<?php
$service_area_schema_SQL = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `service_areas` WHERE user_id ='$user[user_id]'");
//$service_areas= mysql_fetch_assoc($reviewschema_SQL);
 //echo "SELECT * FROM `service_areas` WHERE user_id ='$user[user_id]'";
?>
                    
  
    "areaServed":[          
      <?php while($service_areas=mysql_fetch_assoc($service_area_schema_SQL)){?>
        <?php if($service_areas['location_type'] == 'City'){ 
            $serviceareacity =  $service_areas['address'];
            $servicearea_city = explode(",", $serviceareacity);
            if($user['city'] != $servicearea_city[0]){?>
            {
              "@type": "City"  
			  <?php if($servicearea_city[0]!=''){?>
             
              ,"name": "<?php echo $servicearea_city[0];?>"
             
            <?php } ?>
		   },
        <?php }}?>
      <?php } ?>
      {
        "@type": "City"  
        <?php if($user['city'] !=''){ ?>
          ,"name": "<?php echo $user['city'];?>"
        <?php } ?>
      }
      <?php  $states_services = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `service_areas` WHERE user_id ='$user[user_id]'");?>
        <?php while($service_areas_state=mysql_fetch_assoc($states_services)){?>
          <?php if($service_areas_state['location_type'] == 'State'){
            $serviceareastate=  $service_areas_state['address'];
            $servicearea_state = explode(",", $serviceareastate);?>
            ,{
              "@type": "State",
               "name": "<?php echo $servicearea_state[0];?>"
            }
          <?php }?>
        <?php } ?>
      
      <?php  $country_services = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT address FROM `service_areas` where location_type='Country' and user_id ='$user[user_id]'");?>
      <?php
      $index=0;
          $country_services_rows = mysql_num_rows($country_services);
      if ($country_services_rows>0) {echo ',';}
      while($country_service=mysql_fetch_assoc($country_services)){?>
                  
          {
            "@type": "Country",
            "name": "<?php echo $country_service['address'];?>"
          }
      <?php
          $index++;
          echo ($country_services_rows != $index) ? "," : "";

          ?>
        <?php } ?>    
    ]
}
</script>