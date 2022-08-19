<?php foreach($items as $item){?>
                <section class="products">
                    <figure class="item">
                        <a href="/shops/item_page/<?=$item['p_id']?>"><img src="<?=$item['url']?>" alt="<?=$item['item_name']?>"/></a>
                        <h4>₱<?=$item['price']?></h4>
                    </figure>
                    <p><?=$item['item_name']?></p>
                </section>
<?php }?>