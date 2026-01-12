<style>
	.member-login-container form h2:first-of-type, .member-login-container form h2:first-of-type + hr {
		display: none!important;
	}
	.bd-chat-pmb-reply-form-container {
		padding: 15px;
	}
	textarea#bd-chat-pmbrfc-message {
		border: 0px;
		width: 100%;
		outline: none !important;
	}
	.bd-chat-pmb-rfc-controls {
		padding-left: 16px;
		color: #969696;
	}
	.bd-chat-pmb-rfc-submit-container {
		text-align: right;
		padding-top: 10px;
	}
	.bd-chat-center-text {
		text-align: center;
	}
	.bd-chat-cu-form-container .btn-google, .bd-chat-cu-form-container .btn-facebook {
		padding: 10px;
	}
	.bd-chat-cu-form-container #containerFBLogin, .bd-chat-cu-form-container #containerGoogleLogin {
		width: 100%;
	}
	.bd-chat-cu-form-container #containerGoogleLogin {
		margin-top: 5px;
	}
	.bd-chat-cu-form-container .btn-facebook img, .bd-chat-cu-form-container .btn-google img {
		height: 40px !important;
		display: inline-block;
	}

	/* CSS When Login Form and Express Registration Rendered in Sidebar */
	.col-md-3 .bd-chat-well-container,.col-md-4 .bd-chat-well-container {
		padding: 15px;
	}
	.col-md-3 #bd-chat-pmb-sm-sm,.col-md-4 #bd-chat-pmb-sm-sm {
		display: block;
	}
</style>
<div class="well fpad-xl bd-chat-well-container">
    <?php
    if ($_COOKIE["userid"] > 0) {

        if ($user['user_id'] == $_COOKIE['userid']) {?>
            <h3 class="bd-chat-center-text">%%%member_lead_message_self_warning%%%</h3>

       <?php } else { ?>
            <div class="bd-chat-pmb-st-form">
                <h4>
					%%%chat_with_label%%% 
					<strong>
						<?php echo $user["full_name"]; ?>
					</strong>
					<span class="chat_with_label_append">
						%%%chat_with_label_append%%%
					</span>
				</h4>
                <div class="bd-chat-pmb-reply-form-container row">
                    <div class="bd-chat-pmb-rfc-fake-cont">
                        <textarea class="form-control chat-froala" name="message_content" id="bd-chat-pmbrfc-message"
                                  cols="30" rows="5" placeholder="%%%chat_textbox_placeholder%%%"></textarea>
                    </div>
                    <div class="bd-chat-pmb-rfc-submit-container">
                        <buton class="btn btn-success" id="bd-chat-pmb-sm-sm"
                               data-resptok="<?php echo $user["token"]; ?>">%%%member_chat_send_message%%%
                        </buton>
                    </div>
                </div>
                <?php echo widget("Form - Froala Editor Javascript"); ?>
            </div>
            <?php
        }

	} else { ?>
		<div class="login-to-chat">
			<h3 class="bold bd-chat-center-text">
				%%%login_chat_messages%%%
			</h3>
			<hr>
			[widget=Bootstrap Theme - Member Login Page]
			<div class="clearfix"></div>
		</div>
	<?php } ?>
</div>