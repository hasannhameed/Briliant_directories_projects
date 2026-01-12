<?php

$fieldConfigurations = array(
    'widgets' => array(
        'Field - BDBlocks Gallery',
        'Field - Video Gallery'
    )
);

$urlParts = explode("/", $_SERVER['REQUEST_URI']);

if (
    ($pars[0] == 'account' && $pars[1] == 'profile' && empty($pars[2]))
    ||
    ($pars[0] == 'account' && $pars[1] == 'profile' && $urlParts[2] == 'contact' && !empty($pars[2]))
    ||
    (!empty($_POST['formname']) && $_POST['formname'] == 'member_contact_details')
) {

    $widgetData = array();
    foreach ($fieldConfigurations['widgets'] as $widgetName) {
        $widgetData[] = base64_encode(widget($widgetName));
    }

?>
<script>
    var customProfilePictureFields = {
		fieldElements : [
            <?php foreach ($widgetData as $widgetElement) { ?>
                `<?php echo $widgetElement; ?>`,
            <?php } ?>
        ],
        elementTemplate : `<div class='row'>
            <div class='col-md-12'>
                <div class='well'>
                    {element-data}
                </div>
            </div>
        </div>`,
        init : function() {
			customProfilePictureFields.fieldElements.forEach(function(value, index, array){
				var elementTemplate = customProfilePictureFields.elementTemplate;
				elementTemplate = elementTemplate.replace(/{element-data}/g, atob(value));
				$(elementTemplate).insertBefore(".next-step-container");
            });
        }
    };
	customProfilePictureFields.init();
</script>
<?php

}

?>