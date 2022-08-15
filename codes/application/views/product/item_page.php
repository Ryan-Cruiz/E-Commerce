<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" type="text/css" href="/assets/css/normalize.css" />
    <link rel="stylesheet" type="text/css" href="/assets/css/style.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>(Product Page) Tshirt | Lashopda</title>
    <script src="/assets/js/jquery.min.js"></script>
    <script src="/assets/js/all_function.js"></script>
    <script src="/assets/js/pagination.js"></script>
    <script>
        $(document).ready(function(){
            /*  For submission of forms & updating of cart quantity    */
            var cart_quantity = 0;
            $(".cart_quantity").text(cart_quantity);
            
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
    <header>
        <a href="products_page.html"><h2>Lashopda</h2></a>
        <a class="nav_end" href="cart_page.html"><h3>Shopping Cart (<span class="cart_quantity">4</span>)</h3></a>
    </header>
    <main>
        <section class="item_panel">
            <a class="go_back" href=""><p>Go Back</p></a>
            <div class="item_details">
                <aside class="img_section">
                    <img class="main_img" src="<?=$items[0]['url']?>" alt="<?=$items[0]['item_name']?>"/>        
                    <section>
<?php foreach($items as $item){?>
                        <img class="sub_img" src="<?=$item['url']?>" alt="<?=$item['item_name']?>"/>
<?php }?>
                    </section>
                </aside>
                <aside class="desc_section">
                    <h2><?=$items[0]['item_name']?></h2>
                    <p><?=$items[0]['description']?></p>
                    <form action="" method="post">
                        <input type="hidden" name="product_id" value="product_id"/>
                        <select class="new_order_qty">
                            <option>1 ($19.99)</option>
                            <option>2 ($39.98)</option>
                            <option>3 ($59.97)</option>
                        </select>
                        <input type="submit" value="Buy"/>
                        <p class="item_added_confirm">Item added to the cart.</p>
                    </form>
                </aside>
            </div>
        </section>
        <article class="similar_items_section">
            <h3>Similar Items</h3>
            <section class="products">
                <figure class="item">
                    <a href="item_page.html"><img src="/assets/img/products/0/products.jpg" alt="Tshirt"/></a>
                    <h4>$19.99</h4>
                </figure>
                <p>T-shirt</p>
            </section>
            <section class="products">
                <figure class="item">
                    <a href="item_page.html"><img src="/assets/img/products/0/products.jpg" alt="Tshirt"/></a>
                    <h4>$19.99</h4>
                </figure>
                <p>T-shirt</p>
            </section>
            <section class="products">
                <figure class="item">
                    <a href="item_page.html"><img src="/assets/img/products/0/products.jpg" alt="Tshirt"/></a>
                    <h4>$19.99</h4>
                </figure>
                <p>T-shirt</p>
            </section>
            <section class="products">
                <figure class="item">
                    <a href="item_page.html"><img src="/assets/img/products/0/products.jpg" alt="Tshirt"/></a>
                    <h4>$19.99</h4>
                </figure>
                <p>T-shirt</p>
            </section>
            <section class="products">
                <figure class="item">
                    <a href="item_page.html"><img src="/assets/img/products/0/products.jpg" alt="Tshirt"/></a>
                    <h4>$19.99</h4>
                </figure>
                <p>T-shirt</p>
            </section>
            <section class="products">
                <figure class="item">
                    <a href="item_page.html"><img src="/assets/img/products/0/products.jpg" alt="Tshirt"/></a>
                    <h4>$19.99</h4>
                </figure>
                <p>T-shirt</p>
            </section>
            <section class="products">
                <figure class="item">
                    <a href="item_page.html"><img src="/assets/img/products/0/products.jpg" alt="Tshirt"/></a>
                    <h4>$19.99</h4>
                </figure>
                <p>T-shirt</p>
            </section>
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