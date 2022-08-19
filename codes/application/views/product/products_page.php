<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="stylesheet" type="text/css" href="/assets/css/normalize.css" />
        <link rel="stylesheet" href="/assets/css/newStyle.css">
        <link rel="stylesheet" type="text/css" href="/assets/css/style.css" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Products | isStore</title>
        <script src="/assets/js/jquery.min.js"></script>
        <script src='/assets/js/product_page.js'></script>
        <script src='/assets/js/pagination.js'></script>
    </head>
    <body>
        <?php $this->load->view('partials/header') ?>
        <main>
            <aside class="category_panel">
            <form action="/shops/search_product" method="post" id='search_form'>
                <input type="search" name="product_name" placeholder="Product name" id='product_name' />
                <button type="submit" id='search_btn'>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 17 17">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                    </svg>
                </button>
                  <input type="hidden" name="<?= $this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash();?>">
            </form>
                <form action="/shops/get_the_page/" method="post" id='categories'>
                <section class="products_categories">
                <h4>Categories</h4>
<?php foreach ($categories as $category){ ?>
                    <label><input type="radio" class='categories' name="category" value="<?=$category['id']?>"><p><?= $category['category'] ?>(<?= $category['counts'] ?>)</p></label>
<?php } ?>
                    <label><a class="show_all_products" href="">All Products</a>
                <!-- <a id='<?=$category['id']?>' href="/shops/get_category/<?= $category['id'] ?>"><?= $category['category'] ?>(<?= $category['counts'] ?>)</a> -->
                </section>
            </aside>
                <article class="catalog">
                    <div class="subheader">
                        <h2><span class="category_name">All Products</span> (page <span class="page_number">1</span>)</h2>
                        <section class="pagination_top">
                            <a class="first_page" href="">first</a>
                            <a class="prev_page" href="">prev</a>
                            <p><span class="page_number">1</span></p>
                            <a class="next_page" href="">next</a>
                        </section>
                    </div>
                    <div class='sort_area'>
                        <label>Sorted by </label>
                        <select name="sort_by" id='sort_by'>
                            <option  value="1">Price: Low to High</option>
                            <option  value="2">Price: High to Low</option>
                            <option  value="3">Most Popular</option>
                        </select>
                    </div>
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash();?>">
                    <div class="products_container"></div>
                    <section class="pagination"></section>
                </article>
            </form>
        </main>
    </body>

</html>