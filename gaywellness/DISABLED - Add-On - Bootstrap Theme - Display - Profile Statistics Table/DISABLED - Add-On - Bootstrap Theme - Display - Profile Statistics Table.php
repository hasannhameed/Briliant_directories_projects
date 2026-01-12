<?php

$first_click_date=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT date_clicked FROM `users_clicks` WHERE user_id = '$_COOKIE[userid]' ORDER BY date_clicked ASC LIMIT 1");
$last_click_date=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT date_clicked FROM `users_clicks` WHERE user_id = '$_COOKIE[userid]' ORDER BY date_clicked DESC LIMIT 1");

$start_date_statistics=mysql_fetch_assoc($first_click_date);
$end_date_statistics=mysql_fetch_assoc($last_click_date);

$first_year = substr($start_date_statistics['date_clicked'], 0,4);
$last_year = substr($end_date_statistics['date_clicked'], 0,4);


?>
<div class="stats_years_container" style="margin-bottom: 15px!important;">
<?php
if($first_year > 0 && $last_year > 0){
    for ($i = $first_year; $i <= $last_year ; $i++) { ?>
        <?php if (($pars[2] == "" && $i == $last_year) || $pars[2] != "" && $i == $pars[2]){ ?>
            <a href="/account/stats/<?php echo $i ?>" class="label label-default stats-year"><?php echo $i ?></a>
        <?php } else { ?>
            <a href="/account/stats/<?php echo $i ?>" class="label label-primary stats-year"><?php echo $i ?></a>
        <?php } ?>
<?php } 
}?>

</div>
<!-- profile stats -->

<?php
$rowNumbers=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT click_type FROM `users_clicks` WHERE user_id = '$_COOKIE[userid]' GROUP BY click_type");

if(mysql_num_rows($rowNumbers) > 0){?>

    <?
    $headers_titles=array();
    ?>
    <div class="table-responsive">
        <table class="table stats_table">
            <tr>
                <th style="vertical-align:middle;">%%%month_label%%%</th>
                <?
                
                $statheaders=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT DISTINCT click_type FROM `users_clicks` WHERE user_id = '$_COOKIE[userid]' AND click_type != ''  GROUP BY click_type ORDER BY FIELD(click_type, 'Phone Number', 'Contact Form', 'Profile Views') DESC");
                
                while($headers_names=mysql_fetch_assoc($statheaders)){
                    array_push($headers_titles, $headers_names['click_type']);
                    ?>
                    <th class="text-center stats_header" data-header='<?php echo $headers_names['click_type']?>' style='vertical-align:middle;'>
                        <?php switch ($headers_names['click_type']) {
                            case 'Profile':
                                ?> %%%statistics_profile_views%%% <?php
                                break;
                            case 'Phone Number':
                                ?> %%%statistics_view_phone%%% <?php
                                break;
                            case 'Contact Form':
                                ?> %%%statistics_contact_form%%% <?php
                                break;
                            case 'website':
                                if($label['statistics_website_url'] != ''){
                                    ?>%%%statistics_website_url%%% <?php
                                } else {
                                    echo $headers_names['click_type'];
                                }
                                break;
                            case 'booking link':
                                if($label['statistics_booking_url'] != ''){
                                    ?>%%%statistics_booking_url%%% <?php
                                } else {
                                    echo $headers_names['click_type'];
                                }
                                break;
                            case 'View Post':
                                if($label['statistics_view_posts'] != ''){
                                    ?>%%%statistics_view_posts%%% <?php
                                } else {
                                    echo $headers_names['click_type'];
                                }
                                break;
                            case 'Search Results':
                                if($label['statistics_search_results'] != ''){
                                    ?>%%%statistics_search_results%%% <?php
                                } else {
                                    echo $headers_names['click_type'];
                                }
                                break;
                            default:
                                echo $headers_names['click_type'];
                                break;
                        }
                        ?>
                    </th>
                <? } ?>
            </tr>
            <?
            if($pars[2]!="" && $pars[3]!=""){
                $first_year = $pars[2];
                $first_month = $pars[3];

                $last_year = $pars[2];
                $last_month = $pars[3];
            } else if($pars[2] != "" && $pars[3] == "") {
                $first_year = $pars[2];
                $first_month = 01;

                $last_year = $pars[2];
                $last_month = 12;
            } else {
                
                /* The first and last date that there is a record of a click related to the profile */
                $first_year = substr($end_date_statistics[date_clicked], 0,4);
                $first_month = 01;

                $last_year = substr($end_date_statistics[date_clicked], 0,4);
                $last_month = 12;
            }
            ?>
            <? for($y=$first_year;$y<=$last_year;$y++){

                if($y == $last_year){

                    for($m=$first_month;$m<=$last_month;$m++){
                        if ($m<10 && strlen($m) == 1) { $m = "0".$m;}
                        echo "<tr>";
                        $monthName = date("F", mktime(0, 0, 0, $m, 10));

                        switch ($monthName){
                            case "January":
                                $monthName = "%%%january_month%%%";
                                break;
                            case "February":
                                $monthName = "%%%february_month%%%";
                                break;
                            case "March":
                                $monthName = "%%%march_month%%%";
                                break;
                            case "April":
                                $monthName = "%%%april_month%%%";
                                break;
                            case "May":
                                $monthName = "%%%may_month%%%";
                                break;
                            case "June":
                                $monthName = "%%%june_month%%%";
                                break;
                            case "July":
                                $monthName = "%%%july_month%%%";
                                break;
                            case "August":
                                $monthName = "%%%august_month%%%";
                                break;
                            case "September":
                                $monthName = "%%%september_month%%%";
                                break;
                            case "October":
                                $monthName = "%%%october_month%%%";
                                break;
                            case "November":
                                $monthName = "%%%november_month%%%";
                                break;
                            case "December":
                                $monthName = "%%%december_month%%%";
                                break;
                        };

                        if(($pars[2]=="" && $pars[3]=="") || $pars[3] == ""){
                            echo "<td><a href='/account/stats/".$y."/".$m."' class='month_stats' data-year-month=".$y.$m.">".$monthName."</a></td>";
                        } else {
                            
                            echo "<td>".$monthName."</td>";
                        }
                        if(($pars[2]=="" && $pars[3]=="") || $pars[3] == ""){
                            // this is the loop when displaying results of months when looking at the overall results
                            foreach ($headers_titles as $key => $value) {
                                $min_date = $y.$m."00000000";
                                $max_date = $y.$m."31235959";
                                echo "<td style='text-align: center;' data-click-type='".$value."' data-month='$m' data-year='$y' class='get-stat ".$y.$m."'><i class='fa fa-spin fa-spinner' aria-hidden='true'></i></td>" ;
                            }// END foreach 
                        } else {

                            for($d=1;$d<=31;$d++){
                                if ($d < 10 && strlen($d) == 1) { $d = "0".$d;}
                                $max_date = $y.$m.$d."235959";
                                $min_date = $y.$m.$d."000000";
                                $number_days_in_month = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT count(click_id) as count  FROM `users_clicks` WHERE click_type != '' AND user_id = $_COOKIE[userid] AND date_clicked <= $max_date AND date_clicked > $min_date");
                                $results_in_day=mysql_fetch_assoc($number_days_in_month);
                                if($results_in_day['count'] >= 0){
                                    $monthName = substr($monthName,0,3);
                                    echo "<tr>";
                                    echo "<td class='month_stats' data-year-month=".$y.$m.$d.">".$monthName.", ".$d."</td>";
                                    foreach ($headers_titles as $key => $value) {
                                        echo "<td style='text-align: center;' data-click-type='".$value."' data-month='$m' data-year='$y' class='get-stat ".$y.$m.$d."'><i class='fa fa-spin fa-spinner' aria-hidden='true'></i></td>" ;
                                    }// END foreach 
                                    echo "</tr>";
                                }
                            }
                        }
                        echo "</tr>";
                    } // END for loop
                } else {
                    for($m=$first_month;$m<=12;$m++){
                        if ($m<10 && strlen($m) == 1) { $m = "0".$m;}
                        echo "<tr>";
                        $monthName = date("F", mktime(0, 0, 0, $m, 10));

                        switch ($monthName){
                            case "January":
                                $monthName = "%%%january_month%%%";
                                break;
                            case "February":
                                $monthName = "%%%february_month%%%";
                                break;
                            case "March":
                                $monthName = "%%%march_month%%%";
                                break;
                            case "April":
                                $monthName = "%%%april_month%%%";
                                break;
                            case "May":
                                $monthName = "%%%may_month%%%";
                                break;
                            case "June":
                                $monthName = "%%%june_month%%%";
                                break;
                            case "July":
                                $monthName = "%%%july_month%%%";
                                break;
                            case "August":
                                $monthName = "%%%august_month%%%";
                                break;
                            case "September":
                                $monthName = "%%%september_month%%%";
                                break;
                            case "October":
                                $monthName = "%%%october_month%%%";
                                break;
                            case "November":
                                $monthName = "%%%november_month%%%";
                                break;
                            case "December":
                                $monthName = "%%%december_month%%%";
                                break;
                        };

                        echo "<td><a href='/account/stats/".$y."/".$m."' class='month_stats' data-year-month=".$y.$m.">".$monthName."</a></td>";
                        foreach ($headers_titles as $key => $value) {
                            $min_date = $y.$m."00000000";
                            $max_date = $y.$m."31235959";
                            echo "<td style='text-align: center;' data-click-type='".$value."' data-month='$m' data-year='$y' class='get-stat ".$y.$m."'><i class='fa fa-spin fa-spinner' aria-hidden='true'></i></td>" ;
                        } // END foreach
                        if($m == 12){$first_month = 1;}
                        echo "</tr>";
                    } // END for loop
                } // END else


                if(($pars[2] == "" && $pars[3]=="") || $pars[3] == ""){
                    echo "<tr>";
                    echo "<td class='bold-td'>".$y." %%%statistics_totals%%%</td>";
                    foreach ($headers_titles as $key => $value) {
                        $min_date = $y."0000000000";
                        $max_date = $y."1231235959";
                        $number_in_month_query = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT count(click_id) as count FROM `users_clicks` WHERE (click_type = '$value' OR click_name = '$value') AND user_id = '$_COOKIE[userid]' AND date_clicked <= $max_date AND date_clicked > $min_date");
                        $number_in_month=mysql_fetch_assoc($number_in_month_query);
                        if($number_in_month['count'] == 0){$number_in_month['count'] ="-";}
                        echo "<td style='text-align: center;' class='bold-td'>".$number_in_month['count']."</td>" ;
                    } // END foreach
                    echo "</tr>";
                }
            } ?>
            <?php if ($pars[2] != "" && $pars[3] != ""){ ?>
                <tr>
                    <td>%%%statistics_totals%%%</td>
                    <?
                    foreach ($headers_titles as $key => $value) {
                        if($pars[2]=="" && $pars[3]==""){
                            $number_totals_query = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT count(click_id) as count FROM `users_clicks` WHERE (click_type = '$value' OR click_name = '$value') AND user_id = '$_COOKIE[userid]'");
                        } else {
                            if ($pars[3]<10 && strlen($pars[3]) == 1) { $pars[3] = "0".$pars[3];}
                            $max_date = $pars[2].$pars[3]."31235959";
                            $min_date = $pars[2].$pars[3]."00000000";
                            $number_totals_query = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT count(click_id) as count FROM `users_clicks` WHERE (click_type = '$value' OR click_name = '$value') AND user_id = '$_COOKIE[userid]' AND date_clicked <= $max_date AND date_clicked > $min_date");
                        }
                        $total_clicks_numbers=mysql_fetch_assoc($number_totals_query);

                        echo "<td style='text-align: center;'>".$total_clicks_numbers['count']."</td>";
                    } // END foreach
                    ?>
                </tr>
            <?php } ?>
        </table>
    </div>
<?php
if (checkIfMobile(true)) {
    echo '<style> .account-form-box {width: 900px;overflow-x: auto;font-size: 12px;}</style>';
}
?>
<?php } else { ?>
    <div class="h2 alert alert-warning text-center">
        %%%statistics_no_stats%%%
        <hr>
        <a class="btn btn-success btn-lg" href="/account">&laquo; %%%back_to_dashboard%%%</a>
    </div>
<? } ?>

<script>
var countRequestArray = []
var count = 0;
$('.get-stat').each(function(){
    count++;
    var thisElement = $(this);
    var clickType = $(this).attr('data-click-type');
    var year = $(this).attr('data-year');
    var month = $(this).attr('data-month');

    var thisRequestData = [];

    thisRequestData = {"clickType" : clickType, "year" : year, "month" :  month}

    countRequestArray.push(thisRequestData);

});


var monthsArray = [];
$('.month_stats').each(function(){
    yearMonth = $(this).attr('data-year-month');
    monthsArray.push(yearMonth);
})

var headersMonthArray = [];
$.each(monthsArray, function(index,value){
    var headerValueEach = value;
    var tempArray = [];
    $('.stats_header').each(function(){
        tempArray.push($(this).attr('data-header') + "|" + headerValueEach);
    })
    headersMonthArray.push(tempArray);
});

var loadingOrder = 0;

ajaxStats(loadingOrder, headersMonthArray[loadingOrder]);

function ajaxStats(index, value){
    $.ajax(
    {
        url: '/api/widget/json/post/Add-On%20-%20Bootstrap%20Theme%20-%20Ajax-Get-Stats',
        method: 'post',
        data: {
            "info" : value,
            "user_id" : <?php echo $_COOKIE['userid']?>
        },
        success: function(result) {
            var statDate = result.date;
            $.each(result.response, function(index,value){
                $('.'+statDate+'[data-click-type="'+index+'"]').html(value);
            })
            loadingOrder++;
            if(loadingOrder < headersMonthArray.length){
                ajaxStats(loadingOrder, headersMonthArray[loadingOrder]);
            } else {
                $('.overlay_loading').remove();
            }
            
        }
    });
}



</script>