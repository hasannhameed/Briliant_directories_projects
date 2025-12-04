<script>
    jQuery(document).ready(function($) {
        $('.vegas-wrapper .container').html('<div class="row-fluid homepage_settings"> <div class="clearfix"></div><div class="col-md-12 search_box fpad img-rounded"> <div class="clearfix"></div><div class="form-group nomargin hidden-sm hidden-xs col-md-5"> <label class="nomargin"> What are you looking for? </label> </div><div class="clearfix"></div><form class="tpad form-inline" name="frm1" action="/search_results"> <div class="form-group col-sm-12 col-md-6 bmargin"> <div class="input-group input-group-lg col-sm-12 large-autosuggest"> <div class="input-group-addon"> <i class="fa fa-fw fa-search"></i> </div><span class="twitter-typeahead" style="position: relative; display: inline-block;"><span class="input_wrapper"><input type="text" class="member_search form-control input-lg large-autosuggest-input tt-input" name="q" value="" placeholder="Name or Keyword" autocomplete="off" spellcheck="false" dir="auto" style="position: relative; vertical-align: top;"></span><pre aria-hidden="true" style="position: absolute; visibility: hidden; white-space: pre; font-family: Roboto; font-size: 18px; font-style: normal; font-variant: normal; font-weight: 400; word-spacing: 0px; letter-spacing: 0px; text-indent: 0px; text-rendering: auto; text-transform: none;"></pre><div class="tt-menu" style="position: absolute; top: 100%; left: 0px; z-index: 100; display: none;"><div class="tt-dataset tt-dataset-1"></div></div></span> </div></div><div class="form-group col-sm-12 col-md-6 bmargin"> <button type="submit" class="btn btn-lg btn-block btn_home_search">Search</button> </div><div class="clearfix"></div></form> <div class="clearfix"></div></div><div class="clearfix"></div></div>');
    });
</script>
<script type="text/javascript">
        $(document).ready(function() {
            var member_searchEngine = new Bloodhound({
                initialize:  false,
                datumTokenizer: function(d) {
                    return Bloodhound.tokenizers.whitespace(value);
                },
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                remote: {
                    url:  '/api/suggest/json/get/89,list_services,Sub Categories,1,0,1,1,1,10|88,list_professions,Main Categories,1,1,1,1,1,6|1,users_data,Automotive Suppliers,1,1,1,1,0,5?tID=16&q=%QUERY',
                    wildcard:  '%QUERY',
                    filter: function(response) {
                        
                        if (response != null) {
                            //var response = response.slice(1);
                            $.each(
                                response, function( key, value ) {
                                    response[key]['value'] = decodeHtml(value['value']);
                                    response[key]['comes_f'] = decodeHtml(value['comes_f']);
                                    response[key]['location'] = decodeHtml(value['location']);
                                    
                                    if (response[key]['value'].indexOf('-/-') >= 0){
                                        var splitResult = response[key]['value'].split('-/-');
                                        response[key]['value'] = splitResult[0];
                                    }
                                }
                            );
                            
                        } else {
                            response = {error: "null"};
                        }
                        
                        return response;
                    }
                }
            });
            var member_searchDataSrc = member_searchEngine.initialize();

            member_searchDataSrc
            .done(function() { /*console.log('Autosuggestions engine initialized.'); */})
            .fail(function() { /*console.log('err, something went wrong while initializing autosuggestions engine:(');*/})

            $('.member_search').typeahead({
                    minLength:  2,
                    highlight: true,
                    hint: false                },
                {
                    display:  'value',
                    source: member_searchEngine.ttAdapter(),
                    limit: 21,
                    templates: {
                        empty: [
                                '<div class="empty-message">',
                                'Result not found',
                                '</div>'
                                ].join(" "),
                        notFound: [
                                    '<div class="notFound-message">',
                                    'Result not found',
                                    '</div>'
                                ].join(" "),
                        suggestion: function(data) {
                            
                            if (data == "null") {
                                strTemplate = '<div class="empty-message">Result not found</div>';
                                
                            } else {
                                strTemplate = '<a class="suggest-link" href="' + data.link + '" data-state="' + data.link + '" data-heading="' + data.comes_f + '"> <div class="left-suggest-col" data-photo="' + data.photo + '"> <img src="' + data.photo + '" data-state="' + data.photo + '"> </div> <div class="right-suggest-col" data-photo="' + data.photo + '"> <p class="media-heading" data-state="' + data.value + '" data-heading="' + data.comes_f + '">' + data.value + '</p> <p class="suggest-origin" data-state="' + data.comes_f + '">' + data.comes_f + '</p> <p class="location" data-state="' + data.location + '">' + data.location + '</p> </div> </a> ';
                                
                                if ((data.link == undefined) || (data.link == "") || (data.link == "novalue")) {
                                    strTemplate = strTemplate.replace(/href=".*?"/, "href='#'");
                                }
                                
                                if ((data.photo == undefined) || (data.photo == "") || (data.photo == "novalue")) {
                                    strTemplate = strTemplate.replace(/<img src=".*?>/, "");
                                }
                            }
                            
                            return strTemplate;
                        }
                    },
            }).on('typeahead:selected', function (obj, datum) {
                /*
                console.log(obj);
                console.log(datum);
                */
            });

            $('.member_search').each(function() {
                var inputWidth = $(this).outerWidth();
                $(this).siblings('.tt-dropdown-menu').css('width',inputWidth+'px');
            });
        });
    </script>