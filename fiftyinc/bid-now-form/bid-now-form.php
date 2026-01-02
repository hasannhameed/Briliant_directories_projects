<?php 
$get_user = getUser($_COOKIE['userid'],$w);
    if (isset($_POST)) {
        if (isset($_POST['amount'])) {
            if ($get_user['subscription_id'] == 5) {
                $insert_bid = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"INSERT INTO `auctions` ( `post_id`, `user_id`, `amount`, `post_uri`) VALUES ('".$_POST['post_id']."', '".$_COOKIE['userid']."', '".$_POST['amount']."', '".$_POST['post_uri']."')");
                    echo json_encode(array('success' => true));
            } else {
                    echo json_encode(array('success' => false));
            }
            exit;
        }
    }
    $last_bid_sql = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `auctions` WHERE post_id = '".$group['group_id']."' ORDER BY amount DESC LIMIT 1 ");
    $last_bid = mysql_fetch_assoc($last_bid_sql);
 ?>

<div class="container">
    <div class="row">
        <div class="col-sm-10">
            <table class="table">
                 <?php if($group['group_category']!=''){ ?>
                <tr>
                    <td><strong>Category: </strong></td>
                    <td><?php echo ucfirst($group['group_category'])?></td>
                </tr>
                <?php }?>
                 
                      <?php if($group['post_promo']!=''){ ?>
                <tr>
                    <td><strong>Retail Price: </strong></td>
                    <td><?php echo $group['post_promo'] ?></td>
                </tr>
                
                
                <?php } ?>
                   <?php if($group['retail_price']!=''){ ?>
                <tr>
                    <td><strong>Retail Price: </strong></td>
                    <td>$<?php echo $group['retail_price'] ?></td>
                </tr>
                <?php }?>
                <?php if($group['listing_price']!=''){ ?>
                <tr class=''>
                    <td><strong>Listing Price:
                            </strong>
                        
                        </td>
                    <td><?php echo $group['listing_price'] ?><br>
                        </td>
                    
                </tr>
                 <?php }?>
                   
                  <?php if($group['car_post_shipping']!=''){ ?>
                
                
            
                <tr>
                    <td><strong>Shipping Cost: </strong></td>
                    <td><?php echo $group['car_post_shipping'] ?></td>
                </tr>
                <?php }?>
              
                    <?php if($group['condition']!=''){ ?>
                <tr>
                    <td><strong>Condition: </strong></td>
                    <td><?php echo $group['condition'] ?></td>
                </tr>
                <?php }?>
                  <?php if($group['dress_purchased']!=''){ ?>
                <tr>
                    <td><strong>Dress Purchased: </strong></td>
                    <td><?php echo $group['dress_purchased'] ?></td>
                </tr>
                <?php }?>
                    <?php if($group['retail_size']!=''){ ?>
                <tr>
                    <td><strong>Retail Size: </strong></td>
                    <td><?php echo str_replace(',', ', ', str_replace('zero', '0', $group['retail_size'])) ?></td>
                </tr>
                <?php }?>
                <?php if($group['shoe_style']!=''){ ?>
                <tr>
                    <td><strong>Shoe Style: </strong></td>
                    <td><?php echo $group['shoe_style'] ?></td>
                </tr>
                <?php }?>
                <?php if($group['shoe_size']!=''){ ?>
                <tr>
                    <td><strong>Shoe Size: </strong></td>
                    <td><?php echo $group['shoe_size'] ?></td>
                </tr>
                <?php }?>
                <?php if($group['custom_size']!=''){ ?>
                <tr>
                    <td><strong>Custom Size: </strong></td>
                    <td><?php echo $group['custom_size'] ?></td>
                </tr>
                <?php }?>
                <?php if($group['acce_type']!=''){ ?>
                <tr>
                    <td><strong>Type: </strong></td>
                    <td><?php echo $group['acce_type'] ?></td>
                </tr>
                <?php }?>
                <?php if($group['color']!=''){ ?>
                <tr>
                    <td><strong>Color Family: </strong></td>
                    <td><?php echo ucfirst($group['color']) ?></td>
                </tr>
                <?php }?>
                   <?php if($group['event']!=''){ ?>
                <tr>
                    <td><strong>Event: </strong></td>
                <td><?php echo str_replace(',', ', ', $group['event']) ?></td>
                </tr>
                <?php } ?>
                       <?php if($group['style']!=''){ ?>
                <tr>
                    <td><strong>Style: </strong></td>
                <td><?php echo ucfirst($group['style']) ?></td>
                </tr>
                <?php }?>
                   <?php if($group['design_style']!=''){ ?>
                <tr>
                    <td><strong>Design Details: </strong></td>
                    <td><?php echo str_replace(',', ', ', $group['design_style']) ?></td>
                </tr>
                <?php }?>
            
                 <?php if($group['fabric']!=''){ ?>
                <tr>
                    <td><strong>Fabric: </strong></td>
                <td><?php echo str_replace(',', ', ', $group['fabric']) ?></td>
                </tr>
                <?php }?>
                <?php if($group['dress_category']!=''){ ?>
                <tr>
                    <td><strong>Dress Category: </strong></td>
                    <td><?php echo ucfirst(str_replace(',', ', ', $group['dress_category'])) ?></td>
                </tr>
                <?php }?>
             
            
              
               
          
                
             
                <?php if($group['auction_end']!=''){ ?>
                <tr>
                    <td><strong>Auction End Date: </strong></td>
                    <td><?php echo $group['auction_end'] ?></td>
                </tr>
                <?php }?>
             
                <!--<tr class=''>
                        <td colspan="2"><a href="" data-target="#contactModal" data-toggle="modal">Contact</a> the selling member directly to buy this product now.</td>
                </tr>-->
               

                <tr class='hide'>
                    <td><strong>Minimum Bid Amount: </strong></td>
                    <td>$<?php echo $group['min_amount'] ?></td>
                </tr>
            </table>
        </div>
    </div>
    <?php if ($group['selling_type'] == 'Auction With Buy Now'): ?>
        <div class="row">
            <div class="col-sm-8 nolpad">
                <div class="bid-form-container well">
                    <form action="" id="bid-form">
                        <input type="hidden" name="post_uri" value="<?php echo $group['group_filename'] ?>">
                        <input type="hidden" name="post_id" value="<?php echo $group['group_id'] ?>">
                        <div class="form-group">
                            <h3 class="place-bid-head">Place your Bid here</h3>
                            <?php 
                                $last_bid_amount = ($last_bid['amount']) ? $last_bid['amount'] : $group['min_amount'];
                            ?>
                            <p>Last Bid: <strong>$<?php echo $last_bid_amount ?></strong></p>
                        </div>
                        <?php 
                            if(date('Y-m-d') > date('Y-m-d', strtotime($group['auction_end']))){ ?>
                                <div class="alert alert-danger bid-error-alert" role="alert"> <strong>The Auction has ended.</strong></div>
                            <?php } else { ?>
                                <div class="form-group">
                                    <p>Place your bid from <strong>$<?php echo $last_bid_amount + 1 ?></strong> and above.</p>
                                    <div class="row">
                                        <div class="col-sm-9 ammount-container">
                                            <label class="sr-only" for="bid_ammount">Amount (in dollars)</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">$</div>
                                                <input type="number" class="form-control" min="<?php echo $last_bid_amount + 1 ?>" value="<?php echo $last_bid_amount + 1 ?>" id="bid_ammount" placeholder="Amount" name="amount" required="">
                                                <div class="input-group-addon">.00</div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 nolpad">
                                            <button type="submit" name="new_bid" class="btn btn-primary">Bid now</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <p> <small>By placing a bid, you're committing to buy the product if you win and agree to our bidding terms and conditions</small> </p>
                                </div>
                            <?php }
                         ?>

                        
                        <div class="alert alert-primary bid-alerts bid-success-alert" role="alert"> <strong>Success</strong>
                            <p>Thank you for submitting your bid. Your bid of <strong>$<span id="bid-amount"></span>.00</strong> is registered. You can chose to place a higher bid now or keep visiting this page to check if your current bid is still the highest. If you are a higher bidder, you will be contacted by the registered seller.</p>
                        </div>
                        <div class="alert bid-alerts alert-danger bid-error-alert" role="alert"> <strong>
                            You don't have privilege to participate on the Auctions. Please <a target="_blank" href="/checkout/5">create a buyer Account </a> </strong>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif ?>
    


</div>
<script>
        $('.bid-alerts').hide();
        $('#bid-form').submit(function(event) {
            event.preventDefault();
            var data = $(this).serialize();
            $.ajax({
                url: '/api/widget/json/get/bid-now-form',
                type: 'POST',
                dataType: 'json',
                data: data,
                success: function (response) {
                    console.log(response);
                    if (response.success) {
                        $('#bid-amount').text($('input[name="amount"]').val());
                        $('.bid-success-alert').show();
                    } else {
                        $('.bid-error-alert').show();
                    }
                }
            });
                
        });
</script>
<style>
        .bid-form-container {
                margin-top: 30px;
                margin-bottom: 20px;
        }
        #bid_ammount, .input-group-addon {
                font-weight: bold;
        }
        .place-bid-head {
                margin-bottom: 20px;
        }
        .table > tbody > tr > td {
            border-color: #ccc !important;
        }
</style>
