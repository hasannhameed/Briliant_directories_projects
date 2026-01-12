<?php
/*widget the generates the lazyload*/

if (!empty($_POST['dc_id'])) {
    global $w;
    $vars = array('removeLimit' => true,
        'lazyloadPage' => $_POST['currentPage'],
        'wrapResultsJsId' => true, 'queryString' => $_POST['queryString']);
    $result = array('status' => 'success');
    $dcId = array('data_id' => $_POST['dc_id']);
    $result['dataResult'] = showDataResults($dcId, $vars, $w, true);
    return $result['dataResult'];
} else if ($_ENV['totalresults'] > $_ENV['end']) {
    $pagination = '';
    $lvl = ($c['template_id'] == '232') ? $subscription['subscription_id'] : '';
    echo '<div class="col-md-12 text-center nopad clickToLoadMoreContainer">
       <div id="btnToLoadMorePost" class="btn btn-primary btn-block btn-lg bold clickToLoadMoreBtn " data-page="2" data-lvl="' . $lvl . '" data-type="' . $dc['data_type'] . '" data-dc="' . $dc['data_id'] . '">';
    echo $label['lazyLoadBtnText'] . '</div>
</div><div class="clearfix clearfix-lg"></div>
<style>
    .gridBtnLazyLoadOld {
        clear: both;
        position: absolute;
        bottom: -30px;
    }
	.loadingMore{
		 pointer-events: none;
	}
	.grid-container.visible.row hr {
         display: none;
    }
</style>';
}
?>