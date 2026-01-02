 <script type="text/javascript">
        $(document).ready(function() {
            var member_search1Engine = new Bloodhound({
                initialize:  false,
                datumTokenizer: function(d) {
                    return Bloodhound.tokenizers.whitespace(value);
                },
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                remote: {
                    url:  '/api/suggest/json/get/1,users_data,Listings,1,1,1,1,1,6|88,list_professions,Main Categories,1,1,1,1,1,6|89,list_services,Sub Categories,1,1,1,1,1,6?tID=16&q=%QUERY',
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
           var member_search1DataSrc = member_search1Engine.initialize();

            member_search1DataSrc
            .done(function() { /*console.log('Autosuggestions engine initialized.'); */})
            .fail(function() { /*console.log('err, something went wrong while initializing autosuggestions engine:(');*/})

            $('.member_search1').typeahead({
                    minLength:  2,
                    highlight: true,
                    hint: false                },
                {
                    display:  'value',
                    source: member_search1Engine.ttAdapter(),
                    limit: 10,
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
                                strTemplate = '<a class="suggest-link" href="#" data-state="' + data.link + '" data-heading="' + data.comes_f + '"> <div class="left-suggest-col" data-photo="' + data.photo + '"> <img src="' + data.photo + '" data-state="' + data.photo + '"> </div> <div class="right-suggest-col" data-photo="' + data.photo + '"> <p class="media-heading" data-state="' + data.value + '" data-heading="' + data.comes_f + '">' + data.value + '</p> <p class="suggest-origin" data-state="' + data.comes_f + '">' + data.comes_f + '</p> <p class="location" data-state="' + data.location + '">' + data.location + '</p> </div> </a> ';
                                

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

            $('.member_search1').each(function() {
                var inputWidth = $(this).outerWidth();
                $(this).siblings('.tt-dropdown-menu').css('width',inputWidth+'px');
            });
        });
    </script>
