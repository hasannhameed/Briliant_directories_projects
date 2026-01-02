<?php
global $dc;
$recurrentEvents = 0;
$isRecurringInstall = getAddOnInfo("recurring_events");
if (isset($isRecurringInstall['status']) && $isRecurringInstall['status'] == "success" && ( $dc['enable_recurring_events_addon'] == 1 || !isset($dc['enable_recurring_events_addon']) ) ) {
    $recurrentEvents = 1;
}
?>
<script type="text/javascript">
// creation of variables
var currentContainer = '',
    currentContainerId = '',
    numbersOfListings = '',
    numbersOfListingsCount = '',
    limitOfListings = '',
    userQuery = '',
    dataFilename = '',
    dataType = '',
    flag = false;

$(".clickToLoadMoreBtn").click(function () { // when you click a tab it will set the variables
    flagRecurrent = <?php echo $recurrentEvents;?>,
    amountOfResults = 0;
    clearVariables();//this will make sure of cleaning the global variables
    currentContainer = $(this);
    currentContainerId = $(this).attr("href");
    currentContainerId = currentContainerId.slice(1); // removing the # from the attr
    numbersOfListings = $(this).attr('data-start');
    numbersOfListingsCount = $(this).attr('data-end');
    limitOfListings = $(this).attr('data-total');
    userQuery = $(this).attr('data-query');
    dataFilename = $(this).attr('data-filename');
    dataType = $(this).attr('data-type');
    formName = $(this).attr('data-formname');
    finalDate = $(this).attr('data-finalDate');
    finalDate2 = $(this).attr('data-finalDate2');
    flag = true;
	addMorePost(this);
});

function addMorePost(button) { // this functions will add more listings
    numbersOfListings = parseInt(numbersOfListings) + parseInt(numbersOfListingsCount);
    if ((parseInt(numbersOfListings) < parseInt(limitOfListings))) {
        flag = false;
        var holder      = $(button).parent().parent();
        amountOfResults = $(holder).find('.search_result').length;
        $.ajax({
            type: "POST",
            url: "/wapi/widget",
            data: {
                "numbersOfListings": numbersOfListings,
                "numbersOfListingsCount": numbersOfListingsCount,
                "userQuery": userQuery,
                "dataFilename": dataFilename,
                "dataType": dataType,
                "formName":formName,
                "finalDate":finalDate,
                "finalDate2":finalDate2,
                "limitOfListings":limitOfListings,
                "amountOfResults":amountOfResults,
                "request_type": "POST",
                "header_type": "html",
                "widget_name": "Bootstrap - Search - Lazy Loader tabs",
            },
            success: function (data) {
                currentContainer.attr("data-start", numbersOfListings);
                if(dataType!=13) {
                    $('#clickToLoadMore-' + dataFilename).before(data);
                } else {
                    $('.loadReviews').before(data); // we need to add this class on the feature reviews
                }
                var holder      = $(button).parent().parent();
                amountOfResults = $(holder).find('.search_result').length;

                if (formName == "event_fields" && flagRecurrent == 1 &&  amountOfResults >= parseInt(limitOfListings)  ) {
                    $('#clickToLoadMore-' + dataFilename).remove();// there are no more post to bring the button wil be remove
                } else if ((numbersOfListings + parseInt(numbersOfListingsCount)) >= parseInt(limitOfListings)) {
                    $('#clickToLoadMore-' + dataFilename).remove();// there are no more post to bring the button wil be remove
                }
                favoriteOn ();
            }
        });
    }
}
function clearVariables() { // this will clean all variables
    currentContainer = '',
    currentContainerId = '',
    numbersOfListings = '',
    numbersOfListingsCount = '',
    limitOfListings = '',
    userQuery = '',
    dataFilename = '',
    dataType = '',
    flag = 'false';
}
</script>