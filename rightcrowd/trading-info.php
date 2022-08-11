<div class="row tradingrow">       
                    <div class="col-md-12">
                        <?php $shares = getAllShares(get_the_ID(), array('orderby' => 'cheapest')); ?>
                        <?php
                        $ch = getCheapestSharesInfos(get_the_ID());
                        ?>
                        <?php
                        $currentUserId = get_current_user_id();
                        $myshares = getAllShares(get_the_ID(), array('user_id' => $currentUserId, 'status' => 'notforsale'));
                        ?>
                        <h3>Trading</h3>

                        <?php if (true) { // @TODO change this to use actual membership check code
                            if ($myshares) {
                                ?>
                                <p>You have shares of this company which are not for sale yet. You can put them on sale by using Sell option.</p>
                                <div class="shareselbox">
                                    <div class="shares-notforsale <?php if ($shareMetas['thm_offering'][0] == 'bond') { echo 'bondtype'; } else { echo 'sharetype'; } ?>">
                                        <?php
                                        foreach ($myshares as $share) :
                                            $shareMetas = get_post_meta($share->ID);
                                            if ($shareMetas['thm_noshares'][0] > 0) {
                                                ?>
                                                <div class="sharerow" id="shrow<?php echo $share->ID ?>">
                                                    <div class="shr2">
                                                        No of Shares <strong><?php echo $shareMetas['thm_noshares'][0]; ?></strong>
                                                    </div>
                        <?php if ($shareMetas['thm_offering'][0] == 'bond') { ?>
                                                        <div class="shr2">
                                                            Coupon %
                                                            <strong>
                            <?php echo $shareMetas['thm_coupon'][0]; ?>
                                                            </strong>
                                                        </div>
                                                        <div class="shr3">
                                                            Expires 
                                                            <strong>
                            <?php echo $shareMetas['thm_expires'][0]; ?>
                                                            </strong>
                                                        </div>
                        <?php } ?>                  
                                                    <div class="shr3">
                                                        Sell Shares<sup>*</sup><br/>
														
                                                        <?php 
                                                          $offerPrice = getOfferPrices(get_the_ID());
                                                        ?>
                                                        <input class="sharesellPriceInput" value="<?php echo $offerPrice ? $offerPrice : '' ?>" type="text" placeholder="in pence" id="sellPrice<?php echo $share->ID ?>" />&nbsp;&nbsp;
                              <input class="sharesellPriceInput" type="number" oninput="checkMaxNumber(this);" max="<?php echo $shareMetas['thm_noshares'][0]; ?>" placeholder="No. of shares to sell" id="sellNumbers<?php echo $share->ID ?>" /><br/><br/>
                                                        <a href="javascript:void(0);" data-ajURL="<?php echo admin_url('admin-ajax.php') ?>"  data-s="<?php echo $share->ID ?>" data-cid="<?php echo get_the_ID(); ?>" class="btn btn-primary sell-share-button">SELL</a>
                                                        <a href="javascript:void(0);" onClick="toggleSellButton();" class="btn btn-primary cancel-share-button">CANCEL</a>
														<br/><em>Please have your Debit/Credit card details ready.</em>
                                                    </div>
                                                </div>
                                            <?php } ?>  
                <?php endforeach; ?>
                                    </div>  
                                </div>
                            <?php }
                            if ($shares):
                                ?>
                                
                                <div class="shareselbox" id="shares-forsale">   

                                    <?php if (get_post_meta(get_the_ID(), 'thm_offering', true) == 'bond') { ?>
                                        <?php
                                        foreach ($shares as $share) :

                                            $shareMetas = get_post_meta($share->ID);
                                            if ($shareMetas['thm_noshares'][0] > 0) {
                                                ?>
                                                <div class="sharerow shares-notforsale <?php echo $shareMetas['thm_offering'][0] == 'bond' ? 'has-bond' : '' ?>">
                                                    
                                                    <div class="shr2">
                                                        Buy Price<sup>*</sup> 
                                                        <strong>
                            <?php echo $shareMetas['thm_noshares'][0].' @ '.$shareMetas['thm_purchaseprice'][0]; ?>
                                                        </strong>
                                                        <div class="buyButton">
                                    <a href="<?php echo get_permalink(get_option('paypal_payment_checkout_page_id')); ?><?php echo "?project_id=" . get_the_ID(); ?>" data-wow-delay=".1s" class="btn btn-primary wow fadeInDown"><?php _e('Buy', 'themeum'); ?></a>
                                </div>
                                                    </div>
                                                    <div class="shr2">
                                                        Sell Price<sup>*</sup> 
                                                        <strong>
                            <?php //echo $shareMetas['thm_sellingprice'][0]; ?>
                                                <?php echo 'No trading history'; ?>

                                                        </strong>
                                                    </div>
                                                    <?php if ($shareMetas['thm_offering'][0] == 'bond') { ?>
                                                        <div class="shr2">
                                                            Coupon %
                                                            <strong>
                                <?php echo $shareMetas['thm_coupon'][0]; ?>
                                                            </strong>
                                                        </div>
                                                        <div class="shr3">
                                                            Expires 
                                                            <strong>
                                                        <?php echo $shareMetas['thm_expires'][0]; ?>
                                                            </strong>
                                                        </div>
                            <?php } ?>
                                                    <!--<div class="shr3">
                                                        Total Price 
                                                        <strong>£
                                                            <?php
                                                            $totalSharePrice = number_format($shareMetas['thm_noshares'][0] * $shareMetas['thm_purchaseprice'][0], 2);
                                                            echo $totalSharePrice;
                                                            ?>
                                                        </strong>
                                                    </div>-->
                                                </div>
                        <?php } ?>  
                    <?php endforeach; ?>
                <?php } else { ?>
                                        <div class="sharerow shares-notforsale">
                                            <div class="shr2">
                                                Sell Shares<sup>*</sup> 
                              <?php 
                              $offPrice = getOfferPrices(get_the_ID());
                              if($offPrice) { ?>
                              <strong>
  <?php echo $offPrice; ?>
                              </strong> 
                              <?php }else{ ?> 
                              <strong>
  <?php echo 'No Price Available'; //echo $ch['share_price']; ?>
                              </strong>
                              <?php } ?>
                                                <?php if($myshares) : ?>
                                                <div class="sellButton">
                                    <a href="javascript:void(0);" onClick="toggleSellButton();" data-wow-delay=".1s" class="btn btn-primary wow fadeInDown"><?php _e('Sell Now', 'themeum'); ?></a>
                                </div>
                                <?php endif; // end of my shares if ?>
                                            </div>
											
                                            <div class="shr2">
                                                Buy Shares<sup>*</sup> 
                                                <strong>
                            <?php echo $ch['total_shares'].' @ '.$ch['share_price']; ?>
                                                </strong>
                                                <?php 
                                                $myoffPrice = getOfferPrices(get_the_ID(),array('user_id' => $currentUserId));
                                                if($myoffPrice && $myoffPrice == $ch['share_price']) { ?>
                                                <div class="buyButton">
                                    <a href="<?php echo get_permalink(get_option('paypal_payment_checkout_page_id')); ?><?php echo "?project_id=" . get_the_ID(); ?>" data-wow-delay=".1s" class="btn btn-primary wow fadeInDown"><?php _e('Trade Arranged - Pay Now', 'themeum'); ?></a>
                                </div>  
                                                <?php }else{ ?>
                                                <div class="buyButton">
                                    <a href="<?php echo get_permalink(get_option('paypal_payment_checkout_page_id')); ?><?php echo "?project_id=" . get_the_ID(); ?>" data-wow-delay=".1s" class="btn btn-primary wow fadeInDown"><?php _e('Buy', 'themeum'); ?></a>
									<br/><em>Please have your Debit/Credit card details ready.</em>
                                </div>
                                <?php } ?>
                                            </div>
                                            
                                            <!--<div class="shr3">
                                                Total Price 
                                                <strong>
                    <?php
                    $totalSharePrice = round($ch['share_price'] * $ch['total_shares'], 2);
                    echo $totalSharePrice;
                    ?> p
                                                </strong>
                                            </div>-->
                                        </div>
                                    <?php } ?>
                                    <!--<?php
                    foreach ($shares as $share) :

                        $shareMetas = get_post_meta($share->ID);
                        if ($shareMetas['thm_noshares'][0] > 0) {
                                            ?>
                                                         <div class="sharerow">
                                                             <div class="shr1">
                                                                 No of Shares <strong><?php echo $shareMetas['thm_noshares'][0]; ?></strong>
                                                             </div>
                        <?php if ($shareMetas['thm_offering'][0] == 'bond') { ?>
                                                                              <div class="shr2">
                                                                                  Coupon %
                                                                                  <strong>
                                                <?php echo $shareMetas['thm_coupon'][0]; ?>
                                                                                  </strong>
                                                                              </div>
                                                                              <div class="shr3">
                                                                                  Expires 
                                                                                  <strong>
                            <?php echo $shareMetas['thm_expires'][0]; ?>
                                                                                  </strong>
                                                                              </div>
                        <?php } ?>
                                                             <div class="shr2">
                                                                 Buy Price<sup>*</sup> 
                                                                 <strong>£
                                            <?php echo $shareMetas['thm_purchaseprice'][0]; ?>
                                                                 </strong>
                                                             </div>
                                                             <div class="shr2">
                                                                 Sell Price<sup>*</sup> 
                                                                 <strong>£
                                            <?php echo $shareMetas['thm_sellingprice'][0]; ?>
                                                                 </strong>
                                                             </div>
                                                             <div class="shr3">
                                                                 Total Price 
                                                                 <strong>£
                                            <?php
                                            $totalSharePrice = number_format($shareMetas['thm_noshares'][0] * $shareMetas['thm_purchaseprice'][0], 2);
                                            echo $totalSharePrice;
                                            ?>
                                                                 </strong>
                                                             </div>
                                                         </div>
                                        <?php } ?>  
                <?php endforeach; ?>-->
                </div>
            <?php else: ?>
                  <div class="sharerow shares-notforsale">   
						<div class="shr2">
                              Sell Shares<sup>*</sup> 
                              <?php 
                              $offPrice = getOfferPrices(get_the_ID());
                              if($offPrice) { ?>
                              <strong>
  <?php echo $offPrice; ?>
                              </strong> 
                              <?php }else{ ?> 
                              <strong>
  <?php echo 'No Price Available'; //echo $ch['share_price']; ?>
                              </strong>
                              <?php } ?>
                              <?php 
                              if($myshares && $currentUserId) : ?>
                              <div class="sellButton">
                  <a href="javascript:void(0);" onClick="toggleSellButton();" data-wow-delay=".1s" class="btn btn-primary wow fadeInDown"><?php _e('Sell Now', 'themeum'); ?></a>
              </div>
              <?php endif; // end of my shares if ?>
                          </div>
						  
                          <div class="shr2">
                              Buy Shares<sup>*</sup>
                              <?php if($ch['share_price'] > 0) { ?> 
                              <strong>
          <?php echo $ch['total_shares'].' @ '.$ch['share_price']; ?>
                              </strong>
                              <div class="buyButton">
                  <a href="<?php echo get_permalink(get_option('paypal_payment_checkout_page_id')); ?><?php echo "?project_id=" . get_the_ID(); ?>" data-wow-delay=".1s" class="btn btn-primary wow fadeInDown"><?php _e('Buy', 'themeum'); ?></a>
				  <br/><em>Please have your Debit/Credit card details ready.</em>
              </div>
                <?php }else{ ?>
                  <strong>
                    Not currently for sale
                  </strong>
                  <div class="registerButton">
                  <div id="registeroffersection">
                    <input type="text" name="offer_price" id="offer_price" placeholder="Offer a price" /><br/>
                                      <a href="javascript:void(0);" id="sendoffer_button" data-pid="<?php echo get_the_ID(); ?>" data-ajURL="<?php echo admin_url('admin-ajax.php') ?>" data-wow-delay=".1s" class="btn btn-primary"><?php _e('Offer', 'themeum'); ?></a>
                                      <a href="javascript:void(0);" class="btn btn-primary" onClick="toggleOfferButton();">Cancel</a>
                  </div>
                  <div> 
                  <?php if($currentUserId): ?>
                  <a href="javascript:void(0);" id="offer_now_button" onClick="toggleOfferButton();" data-wow-delay=".1s" class="btn btn-primary wow fadeInDown"><?php _e('Register Interest', 'themeum'); ?></a>
                  <?php endif; ?>
                  </div>
                  </div>
                <?php } ?>
                          </div>
                          
                          <!--<div class="shr3">
                              Total Price 
                              <strong>£
  <?php
  $totalSharePrice = number_format($ch['share_price'] * $ch['total_shares'], 2);
  echo $totalSharePrice;
  ?>
                              </strong>
                          </div>-->
                      </div>
                        <?php if(!is_user_logged_in()){ ?>
                                    <p class="nosharealert">Company has no shares listed. If you have shares in this company - Login to your related account</p>
                                    <?php } ?>
                                    
                                <?php
                                endif;
                            } // end of top if to check if member is premium or not 
                            else {
                                ?>
                                <div class="sharerow">
                                    <p class="locktext"><i class="fa fa-lock"></i> Subscribe for just £2/month to unlock secondary tier</p>
                                </div>
                        <?php } ?>  
                    </div>
                </div> <!-- Shares Row / Trading END -->
