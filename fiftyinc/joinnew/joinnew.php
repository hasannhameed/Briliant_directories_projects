<script>

$(document).ready(function() {
	
var activeTab = localStorage.getItem('activeTab');
	if(activeTab){
		$('#lp-tabs a[href="' + activeTab + '"]').tab('show');


	}
		

if($(document).width() < 635){
	$("#scrolld").click(function() {	
		 $('html,body').animate({
        scrollTop: $("#content_0_ctl02_TabContent_0").offset().top -100 }, 'slow');
		
});
}
	
});

	
function SelectTabGroup(ID, initial) {

    var tabSelected = false;
	$('li.tab').hide();
	if (initial) {
	    var Hash = location.hash.toLowerCase().replace("#_tab_", "#tab_");
	    if (Hash.indexOf("#tab_") == 0 && Hash.split("_").length == 2) {
	        SelectTab(ID, Hash);
	        ScrollToTabArea();
			tabSelected = true;
			
	    }
	    else {
	        Hash = getStorage('selectedtab_' + window.location.pathname.toLowerCase(), 'session');
	        if (Hash) {
				location.hash = Hash;
	            Hash = Hash.replace("#_tab_", "#tab_");
	            if (Hash.indexOf("#tab_") == 0 && Hash.split("_").length ==1) {
	                SelectTab(ID, Hash);
					tabSelected = true;

	            }
	        }
	    }
    }
    if (!tabSelected) {
        $('li.toggle' + ID).show();
        $('li.toggle' + ID + '.default a').tab('show');
				

	}
    
	
}

</script>

<div id="Content" class="Content"> 
  
  <!-- Start Tab Groups -->
  <div id="content_0_ctl02_pnlSelector" class="selector-toggle color-section-blue" style="background-color:#337ab7;">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 tmargin text-center">
          <h3>What best describes your business?</h3>
          <br>
        </div>
        <div class="col-lg-12 text-center">
          <div class="btn-group radio-group btn-group-lg" id="scrolld">
            <input id="gr1-1" type="radio" name="Finder" checked="checked" value="1" onclick="SelectTabGroup(1);">
            <label for="gr1-1" class="btn tb2">Clubs</label>
            <input id="gr1-2" class="gr1-2" type="radio" name="Finder" value="2" onClick="SelectTabGroup(2); ">
            <label for="gr1-2" class="btn tb2">Dealers & Services</label>
          </div>
        </div>
        <div class="col-lg-12">
          <div class="bottom_aligner"></div>
          <!-- Start Page Tabs -->
          <ul id="lp-tabs"  style="display: none;">
            <li style="display: none;" class="tab default toggle1 active" > <a href="#tab_dat-truckersedge_1" data-toggle="tab" data-tabnumber="1" ></a> </li>
            <li  style="display: none;" class="inactive tab default toggle2" > <a href="#tab_dat-power_2" data-toggle="tab" data-tabnumber="2"></a> </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <div class="lp-tab-content">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="tab-content">
            <div class="tab-pane fade active in" id="tab_dat-truckersedge_1">
              <div class="container-fluid">
                <div id="content_0_ctl02_TabContent_0">
                  <div class="row row-content feature-type-bullets-cta-image">
                    <div class="col-lg-5 col-md-6 feature-text">
                      <h4>Make new connections today!</h4>
                      <h5>Cover loads easier.</h5>
                      <ul>
                        <li>Search by equipment, origin, destination</li>
                        <li>Match actual carrier lanes of service</li>
                        <li>Send a quote to multiple carriers</li>
                        <li>Meet new carriers </li>
                        <li>Choose which carrier to work with </li>
                        <li>Unlimited Searches and Quotes! </li>
                        <li>Setup in minutes </li>
                      </ul>
                    </div>
                    <div class="col-lg-7 col-md-6">
                      <ul class="pricing_menu">
                        <?=menuArray("club_menu",0,$w)?>
                      </ul>
                    </div>
                  </div>
                  <div class="row row-content feature-type-1col-text">
                    <div class="col-lg-12 col-md-12 col-xs-12 feature-text">
                      <h4></h4>
                      <p style="text-align: center;"><span style="text-align: center; font-size: 24px;"><strong>Have a question or would like to hear more about Rides for Charities?</strong> <br>
                        <a href="/about/contact" class="btn btn-success btn-lg vmargin">Click me</a>&nbsp;</span></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- tab2-->
            <div class="tab-pane fade tab_dat-power_2 " id="tab_dat-power_2">
              <div class="container-fluid">
                <div id="content_0_ctl02_TabContent_0">
                  <div class="row row-content feature-type-bullets-cta-image">
                    <div class="col-lg-5 col-md-6 feature-text" style="display:none;">
                      <h4>Make new connections today!</h4>
                      <h5><span id="docs-internal-guid-1e2537a9-a8dc-54e4-c83e-151efcc675ab">Let leads come to you. </span></h5>
                      <ul>
                        <li>Built for Owner Operators and Fleets</li>
                        <li>Create a dedicated and detailed profile</li>
                        <li>Add ACTUAL service lanes </li>
                        <li>Be found live based on lanes/equipment</li>
                        <li>Exact matching sales funnel 24/7</li>
                        <li>Emailed only on exact matches</li>
                        <li>Accept or Decline Leads</li>
                        <li>30 Mins of setup support included!</li>
                      </ul>
                    </div>
                    <div class="col-lg-7 col-md-offset-2 col-md-12">
                      <ul class="pricing_menu">
                        <?=menuArray("dealers_menu",0,$w)?>
                      </ul>
                    </div>
                  </div>
                  <div class="row row-header feature-type-1col-text">
                    <div class="col-lg-12 col-md-12 col-xs-12">
                      <h3></h3>
                      <h4></h4>
                    </div>
                  </div>
                  <div class="row row-header feature-type-3col-text">
                    <div class="col-lg-12 col-md-12 col-xs-12 text-center">
                      <h2>Dealers & Services Levels at Rides For Charities - Explained</h2>
                      <div class="row tmargin row-content" style="margin-right: 30px; margin-left: 30px;">
                        <p>Every Dealers & Services Joins as a Basic Member on RidesForCharities.com. In order to start climbing up the level system, we encourage all the Dealers & Services to work hard and play fair. Below are some of the requirements which every member at the specified level accomplishes.</p>
                      </div>
                    </div>
                  </div>
                  <hr>
                  <div class="row row-content feature-type-3col-text">
                    <div class="col-lg-4 col-md-4 col-xs-12 feature-text">
                      <h4><!--<img src="/images/Fotolia_70084366_XS.jpg" style="max-width:15%;margin-right:5px;">-->Basic</h4>
                      <!--<h5>Trailers for any kind of freight</h5>-->
                      <ul>
                        <p>Every Dealers & Services Joins as a Basic Member on RidesForCharities.com</p>
                      </ul>
                      <!--<p class="learn-more noborder"><a href="/resources/product-sheets/dat-load-boards" processbuttoniconsfromfield="3ColText_LeftColumn_ButtonIcon"><i class="fa fa-download"></i>&nbsp;See Product Sheet</a></p>--> 
                    </div>
                    <div class="col-lg-4 col-md-4 col-xs-12 feature-text">
                      <h4>Requirements</h4>
                      <!--<h5>See what other brokers paid</h5>-->
                      <ul>
                        <li>You should be a business offering products or services with in the categories we provide.</li>
                        <li>You need to verify your business up on request from our team.</li>
                        <li></li>
                        <li></li>
                      </ul>
                      <!--<p class="learn-more noborder"><a href="/freight-rates" processbuttoniconsfromfield="3ColText_MiddleColumn_ButtonIcon"><i class="fa fa-wrench"></i>&nbsp;Get the Best Pricing Tools</a></p>--> 
                    </div>
                    <div class="col-lg-4 col-md-4 col-xs-12 feature-text">
                      <h4>Benefits</h4>
                      <!-- <h5>Find trustworthy carriers</h5>-->
                      <ul>
                        <li>Get found by members who supports our mission.</li>
                        <li>Your Business Listing on RidesForCharities.com</li>
                        <li>Showcase all your Specialities</li>
                        <li>Receive Reviews</li>
                        <li>Free Classifieds Posts* </li>
                        <li>Free Community Articles* </li>
                        <li>Post Job Listings for the Vacancies you have* </li>
                      </ul>
                    </div>
                  </div>
                  <hr>
                  <div class="row row-content feature-type-3col-text">
                    <div class="col-lg-4 col-md-4 col-xs-12 feature-text">
                      <h4><img src="/images/verified_icon.png" style="max-width:15%;margin-right:5px;">Verified</h4>
                      <!--<h5>Trailers for any kind of freight</h5>-->
                      <ul>
                        <p>There are a few requirements you'll need to meet in order to reach Verified status.</p>
                        <br>
                        <p>This is an evaluation process - we look back at your performance, and if you meet the standards, you'll be ranked as a Verified Business.</p>
                      </ul>
                      <!--<p class="learn-more noborder"><a href="/resources/product-sheets/dat-load-boards" processbuttoniconsfromfield="3ColText_LeftColumn_ButtonIcon"><i class="fa fa-download"></i>&nbsp;See Product Sheet</a></p>--> 
                    </div>
                    <div class="col-lg-4 col-md-4 col-xs-12 feature-text">
                      <h4>Requirements</h4>
                      <!--<h5>See what other brokers paid</h5>-->
                      <ul>
                        <li>You will compulsorily require to submit your business and personal information on request so our team can verify your business and it's owners. </li>
                        <li>Complete at least 60 days as an active Business on RidesForCharities.com </li>
                        <li>Get atleast 5 Reviews on your Business Listing </li>
                        <li>Maintain atleast 4 Star Ratings </li>
                      </ul>
                      <!--<p class="learn-more noborder"><a href="/freight-rates" processbuttoniconsfromfield="3ColText_MiddleColumn_ButtonIcon"><i class="fa fa-wrench"></i>&nbsp;Get the Best Pricing Tools</a></p>--> 
                    </div>
                    <div class="col-lg-4 col-md-4 col-xs-12 feature-text">
                      <h4>Benefits</h4>
                      <!-- <h5>Find trustworthy carriers</h5>-->
                      <ul>
                        <li>Verified Icon along with your Name </li>
                        <li>Your Business Listing is above the Basic Members </li>
                        <li>Showcase all your Specialities </li>
                        <li>Receive Reviews </li>
                        <li>Free Classifieds Posts* </li>
                        <li>Free Community Articles* </li>
                        <li>Post Job Listings for the Vacancies you have* </li>
                        <li>Add your Products* </li>
                      </ul>
                    </div>
                  </div>
                  <hr>
                  <div class="row row-content feature-type-3col-text">
                    <div class="col-lg-4 col-md-4 col-xs-12 feature-text">
                      <h4><img  src="/images/BronzeMembership.png" style="max-width:15%;margin-right:5px;">Bronze</h4>
                      <!--<h5>Trailers for any kind of freight</h5>-->
                      <ul>
                        <p>It's time to step up your game. Bronze Level status isn't easy to achieve, but it's worth it.</p>
                        <br>
                        <p>This is an evaluation process - we look back at your performance, and if you meet the standards, you'll be ranked as a Bronze Level Business.</p>
                      </ul>
                      <!--<p class="learn-more noborder"><a href="/resources/product-sheets/dat-load-boards" processbuttoniconsfromfield="3ColText_LeftColumn_ButtonIcon"><i class="fa fa-download"></i>&nbsp;See Product Sheet</a></p>--> 
                    </div>
                    <div class="col-lg-4 col-md-4 col-xs-12 feature-text">
                      <h4>Requirements</h4>
                      <!--<h5>See what other brokers paid</h5>-->
                      <ul>
                        <li>Good Standing being a Verified Member </li>
                        <li>Complete at least 120 days as an active Business on RidesForCharities.com </li>
                        <li>Get atleast 20 Reviews on your Business Listing </li>
                        <li>Maintain atleast 4.5 Star Ratings </li>
                      </ul>
                      <!--<p class="learn-more noborder"><a href="/freight-rates" processbuttoniconsfromfield="3ColText_MiddleColumn_ButtonIcon"><i class="fa fa-wrench"></i>&nbsp;Get the Best Pricing Tools</a></p>--> 
                    </div>
                    <div class="col-lg-4 col-md-4 col-xs-12 feature-text">
                      <h4>Benefits</h4>
                      <!-- <h5>Find trustworthy carriers</h5>-->
                      <ul>
                        <li>Verified & Bronze Icons along with your Name </li>
                        <li>Your Business Listing is above the Verified Members </li>
                        <li>Showcase all your Specialities </li>
                        <li>Receive Reviews </li>
                        <li>Free Classifieds Posts* </li>
                        <li>Free Community Articles* </li>
                        <li>Post Job Listings for the Vacancies you have* </li>
                        <li>Add your Products* </li>
                      </ul>
                    </div>
                  </div>
                  <hr>
                  <div class="row row-content feature-type-3col-text">
                    <div class="col-lg-4 col-md-4 col-xs-12 feature-text">
                      <h4><img src='/images/Silver-Membership.png' style="max-width:15%;margin-right:5px;">Silver</h4>
                      <!--<h5>Trailers for any kind of freight</h5>-->
                      <ul>
                       <ul>
                        <p>It's time to step up your game. Silver Level status isn't easy to achieve, but it's worth it. It's above the Bronze.</p>
                        <br>
                        <p>This is an evaluation process - we look back at your performance, and if you meet the standards, you'll be ranked as a Silver Level Business.</p>
                      </ul>
                      </ul>
                      <!--<p class="learn-more noborder"><a href="/resources/product-sheets/dat-load-boards" processbuttoniconsfromfield="3ColText_LeftColumn_ButtonIcon"><i class="fa fa-download"></i>&nbsp;See Product Sheet</a></p>--> 
                    </div>
                    <div class="col-lg-4 col-md-4 col-xs-12 feature-text">
                      <h4>Requirements</h4>
                      <!--<h5>See what other brokers paid</h5>-->
                       <ul>
                        <li>Good Standing being a Bronze Member</li>
                        <li>Complete at least 180 days as an active Business on RidesForCharities.com </li>
                        <li>Get atleast 50 Reviews on your Business Listing </li>
                        <li>Maintain atleast 4.5 Star Ratings </li>
                      </ul>
                      <!--<p class="learn-more noborder"><a href="/freight-rates" processbuttoniconsfromfield="3ColText_MiddleColumn_ButtonIcon"><i class="fa fa-wrench"></i>&nbsp;Get the Best Pricing Tools</a></p>--> 
                    </div>
                    <div class="col-lg-4 col-md-4 col-xs-12 feature-text">
                      <h4>Benefits</h4>
                      <!-- <h5>Find trustworthy carriers</h5>-->
                      <ul>
                        <li>Verified & Silver Icons along with your Name</li>
                        <li>Your Business Listing is above the Verified Members </li>
                        <li>Showcase all your Specialities </li>
                        <li>Receive Reviews </li>
                        <li>Free Classifieds Posts* </li>
                        <li>Free Community Articles* </li>
                        <li>Post Job Listings for the Vacancies you have* </li>
                        <li>Add your Products* </li>
                      </ul>
                    </div>
                  </div>
                  <hr>
                  <div class="row row-content feature-type-3col-text">
                    <div class="col-lg-4 col-md-4 col-xs-12 feature-text">
                      <h4><img src="/images/gold-membership.png" style="max-width:15%;margin-right:5px;">Gold</h4>
                      <!--<h5>Trailers for any kind of freight</h5>-->
                      <ul>
                        <p>This elite group of Businesses enjoy a growing number of exclusive benefits, as they continue on providing our community members with an overall excellent experience
                        </p><br>
                        <p>Please note that this is a manual process - once you meet the following requirements, we will look back on your performance and rank you as Gold Level Business.</p>
                      </ul>
                      <!--<p class="learn-more noborder"><a href="/resources/product-sheets/dat-load-boards" processbuttoniconsfromfield="3ColText_LeftColumn_ButtonIcon"><i class="fa fa-download"></i>&nbsp;See Product Sheet</a></p>--> 
                    </div>
                    <div class="col-lg-4 col-md-4 col-xs-12 feature-text">
                      <h4>Requirements</h4>
                      <!--<h5>See what other brokers paid</h5>-->
                     
                       <ul>
                        <li>Good Standing being a Silver Member</li>
                        <li>Complete at least 180 days as an active Business on RidesForCharities.com </li>
                        <li>Get atleast 100 Reviews on your Business Listing </li>
                        <li>Maintain atleast 4.5 Star Ratings </li>
                      </ul>
                   
                      <!--<p class="learn-more noborder"><a href="/freight-rates" processbuttoniconsfromfield="3ColText_MiddleColumn_ButtonIcon"><i class="fa fa-wrench"></i>&nbsp;Get the Best Pricing Tools</a></p>--> 
                    </div>
                    <div class="col-lg-4 col-md-4 col-xs-12 feature-text">
                      <h4>Benefits</h4>
                      <!-- <h5>Find trustworthy carriers</h5>-->
                      <ul>
                        <li>Verified & Gold Icons along with your Name</li>
                        <li>Your Business Listing is above the Verified Members </li>
                        <li>Showcase all your Specialities </li>
                        <li>Receive Reviews </li>
                        <li>Free Classifieds Posts </li>
                        <li>Free Community Articles</li>
                        <li>Post Job Listings for the Vacancies you have </li>
                        <li>Add your Products </li>
                      </ul>
                    </div>
                  </div>
                  <hr>
                  <div class="row row-content feature-type-1col-text">
                    <div class="col-lg-12 col-md-12 col-xs-12 feature-text">
                      <h4></h4>
                      <p style="text-align: center;"><span style="text-align: center; font-size: 24px;"><strong>Have a question or would like to hear more about Rides for Charities?</strong> <br>
                        <a href="/about/contact" class="btn btn-success btn-lg vmargin">Click me</a>&nbsp;</span></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
