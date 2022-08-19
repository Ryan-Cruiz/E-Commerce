<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" type="text/css" href="/assets/css/normalize.css" />
    <link rel="stylesheet" type="text/css" href="/assets/css/style.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$product[0]['item_name']?> | isStore</title>
    <script src="/assets/js/jquery.min.js"></script>
    <script src="/assets/js/pagination.js"></script>
    <script>
        $(document).ready(function(){
            $('.a_end').hide();
            /*  For submission of forms & updating of cart quantity    */
            var e = document.getElementById('order_quantity');
            var value = e.value;
            $(document).on('change','#order_quantity',function(){
                if(e.value <= 0){
                    $(".item_error").show().fadeOut(3000);
                }else{
                    var value = e.value;
                   // console.log(value);
                    $('#order_qty').val(value);
                }
                return false;
            });
            $(document).on("submit", "form", function(){
                $(".item_added_confirm").show().fadeOut(3000);
                pageNumHighlight(pageNum);

                cart_quantity += parseInt($(".new_order_qty").val().split(" ")[0]);
                $(".cart_quantity").text(cart_quantity);
                
                return false;
            });
            /**********************************************/

            /*  For changing the big image    */
            $(".default_main_img").css("outline", "1px solid rgba(8, 0, 167, 0.7)");
            $(document).on("mouseover", ".sub_img", function(){
                $(".sub_img").css("outline", "none");
                $(".main_img").attr("src", $(this).attr("src"));
                $(this).css("outline", "1px solid rgba(8, 0, 167, 0.7)");
            });
            /**********************************************/
        });
    </script>
</head>
<body>
<?php $this->load->view('partials/header')?>
    <main>
        <section class="item_panel">
            <a class="go_back" href=""><p>Go Back</p></a>
            <div class="item_details">
                <aside class="img_section">
                    <img class="main_img" src="<?=$product[0]['url']?>" alt="<?=$product[0]['item_name']?>"/>        
                    <section>
<?php foreach($product as $products){?>
                        <img class="sub_img" src="<?=$products['url']?>" alt="<?=$products['item_name']?>"/>
<?php }?>
                    </section>
                </aside>
                <aside class="desc_section">
                    <h2><?=$product[0]['item_name']?></h2>
                    <p><?=$product[0]['description']?></p>
                    <form action="/shops/add_cart/<?=$product[0]['p_id']?>" method="post">
                        <input type="hidden" name="product_id" value="product_id"/>
                        <select class="new_order_qty" name='order_quantity' id='order_quantity'>
                            <option value='1'>1 (₱<?=$product[0]['price']?>)</option>
                            <option value='2'>2 (₱<?=$product[0]['price']*2?>)</option>
                            <option value='3'>3 (₱<?=$product[0]['price']*3?>)</option>
                        </select>
                        <input type="hidden" name="order_qty" id='order_qty' value='1'>
                        <input type="submit" value="Buy"/>
                        <p class="item_added_confirm">Item added to the cart.</p>
                        <p class='item_error'>There's an Error!</p>
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash();?>">
                    </form>
                </aside>
            </div>
        </section>
        <article class="similar_items_section">
            <h3>Similar Items</h3>
<?php $this->load->view('partials/items')?>
            <section class="pagination">
                <a href="">1</a><!--
             --><a href="">2</a><!--
             --><a href="">3</a><!--
             --><a href="">4</a><!--
             --><a href="">5</a><!--
             --><a href="">6</a><!--
             --><a href="">7</a><!--
             --><a href="">8</a><!--
             --><a href="">9</a><!--
             --><a href="">10</a><!--
             --><a class="next_page" href="">&rsaquo;</a>
            </section>
        </article>
    </main>
</body>
</html>