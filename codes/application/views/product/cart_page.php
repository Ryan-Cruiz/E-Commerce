<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" type="text/css" href="/assets/css/normalize.css" />
    <link rel="stylesheet" type="text/css" href="/assets/css/style.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart | isStore</title>
    <script src="/assets/js/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.a_end').hide();
            /*  For submitting forms, redirect to page    */
         
            // $(document).on("submit", "form", function(){
            //     window.location = $(this).attr("action");
            //     return false;
            // });
            /**********************************************/
            $('.cart_total_amount').change(function(){
                if($(this).val() == 0){
                    $('.cart_billing_section').hide();
                    $('.empty_section').show();
                }else{
                    $('.cart_billing_section').show();
                    $('.empty_section').hide();
                }
            });
            $(document).on('click','#billing_checkbox',function(){
                checkBox = document.getElementById('billing_checkbox');
                if(checkBox.checked){
                    //alert('checked');
                    $('.first_b').val($('.first_s').val());
                    $('.last_b').val($('.last_s').val());
                    $('.add_b').val($('.add_s').val());
                    $('.add2_b').val($('.add2_s').val());
                    $('.city_b').val($('.city_s').val());
                    $('.state_b').val($('.state_s').val());
                    $('.zip_b').val($('.zip_s').val());
                }else{
                   // alert('not');
                   $('.first_b').val('');
                    $('.last_b').val('');
                    $('.add_b').val('');
                    $('.add2_b').val('');
                    $('.city_b').val('');
                    $('.state_b').val('');
                    $('.zip_b').val('');
                }
            })

            $(document).on('click','#submit_all',function(){
                // $(this).parent().siblings('form').submit();
                $(this).parent().submit(function(){
                    return false;
                });
               
            });
            /*  Delete product when clicked    */
            $(document).on("click", ".btn_delete_product", function(){
                $(this).
                $(this).parent().parent().parent().remove();
                return false;
            });
            /**********************************************/
        });
    </script>
</head>
<body>
<?php $this->load->view('partials/header')?>
    <main>
        <section class="cart_table_section">
            <table class="cart_table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
<?php $num = 0;foreach($item_bought as $key=> $item){?>
<?php if($num%2 == 0){?>
                    <tr class="color0">
<?php }else{?>
                    <tr class="color1">
<?php }?>
                        <td><?=$item['name']?></td>
                        <td><?=$item['price']?></td>
                        <td>
                            <span><?=$item['qty']?></span>
                            <a class="update_product" href="/shops/item_page/<?=$key?>">Update</a>
                            <form action="/shops/destroy/<?=$key?>" method="post">
                                <input type="hidden" name="product_id" value="<?=$key?>"/>
                                <button class="btn_delete_product" type="submit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                        <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                    </svg>
                                </button>
                                <input type="hidden" name="<?= $this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash();?>">
                            </form>
                        </td>
                        <td>₱<?=$item['item_total']?></td>
                    </tr>
<?php $num++;}?>
                </tbody>
            </table>
            <section class="cart_total_section">
                <h4>Total: <span class="cart_total_amount">₱<?=$this->session->userdata('total_price')?></span></h4>
                <p><a class="btn_continue_shopping" href="/shops/index">Continue Shopping</a></p>
            </section>
        </section>
<?php if($this->session->userdata('total_price') != 0){?>
<?=$this->session->flashdata('errors')?> <!-- Not working i think too much of validation form lol-->
        <section class="cart_billing_section">
        <form role="form" action="/shops/handlePayment" method="post" class="form-validation"
                 data-cc-on-file="false" 
                 data-stripe-publishable-key="<?php echo $this->config->item('stripe_key') ?>" id="payment-form">
                <h2>Shipping Information</h2>
                <span><p>First Name: </p><input type="text" name="first_name_ship" class='first_s'/></span>
                <span><p>Last Name: </p><input type="text" name="last_name_ship" class='last_s'/></span>
                <span><p>Address: </p><input type="text" name="address_ship" class='add_s'/></span>
                <span><p>Address 2: </p><input type="text" name="address2_ship" class='add2_s'/></span>
                <span><p>City: </p><input type="text" name="city_ship" class='city_s'/></span>
                <span><p>State: </p><input type="text" name="state_ship" class='state_s'/></span>
                <span><p>Zipcode: </p><input type="number" name="zipcode_ship"  class='zip_s'/></span>
                <h2>Billing Information</h2>
                <span>
                    <input id="billing_checkbox" type="checkbox" name="billing_info" value="same_shipping" />
                    <label for="billing_checkbox">Same as Shipping</label>
                </span>
                <span><p>First Name: </p><input type="text" name="first_name_bill" class='first_b'/></span>
                <span><p>Last Name: </p><input type="text" name="last_name_bill" class='last_b'/></span>
                <span><p>Address: </p><input type="text" name="address_bill" class='add_b'/></span>
                <span><p>Address 2: </p><input type="text" name="address2_bill" class='add2_b'/></span>
                <span><p>City: </p><input type="text" name="city_bill" class='city_b'/></span>
                <span><p>State: </p><input type="text" name="state_bill" class='state_b'/></span>
                <span><p>Zipcode: </p><input type="number" name="zipcode_bill" class='zip_b'/></span>
<?php if(!$this->session->userdata('curr_user')){?>
                <span class="card_billing"><p>Email: </p><input type="text" name='email' autocomplete='off' /></span>
<?php }?>
                <span class="card_billing"><p>Card: </p><input type="text"  class='card-number  required' name='card_number'autocomplete='off' /></span>
                <span><p>Security Code: </p><input type="text" autocomplete='off' name='card_cvc' class='card-cvc required' placeholder="Ex. 311"/></span>
                <span class="card_exp">
                    <p>Expiration: </p><input type="text" name='card_month' autocomplete='off' class='card-expiry-month required' placeholder="(MM)"/>
                    <p>/</p><input type="text" name='card_year' autocomplete='off' class='card-expiry-year required' placeholder="(YYYY)"/>
                </span>
                <span class="btn_billing"><input type="submit" id='submit_all' value="Pay"/></span>
                <input type="hidden" name="<?= $this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash();?>">
            </form>
        </section>
<?php }else{?>
        <section class='empty_section'>
            <h1>You don't have any items in the cart</h1>
        </section>
<?php }?>
    </main>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">
	$(function(){
		var $stripeForm = $(".form-validation");
		$('form.form-validation').bind('submit', function (e){
			var $stripeForm = $(".form-validation"),
				inputSelector = ['input[type=email]', 'input[type=password]',
					'input[type=text]', 'input[type=file]',
					'textarea'
				].join(', '),
				$inputs = $stripeForm.find('.required').find(inputSelector),
				$errorMessage = $stripeForm.find('div.error'),
				valid = true;
			$errorMessage.addClass('hide');
			$('.has-error').removeClass('has-error');
			$inputs.each(function (i, el) {
				var $input = $(el);
				if ($input.val() === '') {
					$input.parent().addClass('has-error');
					$errorMessage.removeClass('hide');
					e.preventDefault();
				}
			});
			if(!$stripeForm.data('cc-on-file')){
				e.preventDefault();
				Stripe.setPublishableKey($stripeForm.data('stripe-publishable-key'));
				Stripe.createToken({
					number: $('.card-number').val(),
					cvc: $('.card-cvc').val(),
					exp_month: $('.card-expiry-month').val(),
					exp_year: $('.card-expiry-year').val()
				}, stripeResponseHandler);
			}
		});
		function stripeResponseHandler(status, res){
			if (res.error){
				$('.error')
					.removeClass('hide')
					.find('.alert')
					.text(res.error.message);
			}else{
				var token = res['id'];
				$stripeForm.find('input[type=text]').empty();
				$stripeForm.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
				$stripeForm.get(0).submit();
			}
		}
	});
</script>
</html>