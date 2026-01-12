<?php $memberSubCategories = getMemberSubCategory($user_data['user_id'],"all","first",intval($w['profile_services_display_limit']),"text");
					?>
				<div class=" nomargin sub_category_in_results">
					<div class="list-subs-profile bmargin">
						<?php echo getMemberSubCategory($user[user_id], "sub", "first", "all", "linked");?>
					</div>
				</div>