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
        <script>
            $(document).ready(function(){
                /* LOAD THE PAGES */  
                /*
                LITTLE REMINDER AS YOU TRAVERSE THIS MAKE SURE YOU TURN ON THE profiler IN THE shops/get_the_page
                - method name = get_the_page
                - first number = page number/index
                - second number = order from low,high,most
                - third number = category selected
                - lastly sorry for the spaghetti codes
                that's all
                */
                var e = document.getElementById("sort_by"); // initiate the select option
                var value = e.value; // get the initial value which is 1(low to high)
                // var category =  $('#categories input[type=radio]:checked').val();
                var category =0;
                var pageNum = 1;
                /* initiate the page */
                $.get('/shops/page', function(data) {
                    $('.pagination').html(data);
                });
                /*  Product categories selection    */
                $(document).on("click", ".products_categories > a", function(){
                    categoryName = $(this).text().split("(")[0]; // TAKES THE NAME OF CATEGORY AND WRITE IT ON .CATEGORY_NAME
                    $(".category_name").text(categoryName);
                    pageNumHighlight(pageNum);
                    $.get($(this).attr('href'), $(this).serialize(), function(data){
                        $('.products_container').html(data);
                    })
                    return false;
                });
                /* TOTAL PAGE IN ITEM */
                $.get('/shops/get_total_page', function(res){
                    /* CLICK THESE BADBOI HREFS */
                    $(document).on('click', '.page', function(){
                        if(! $('#categories input[type=radio]:checked').val()){
                            var category = 0;
                        }else{
                            var category =  $('#categories input[type=radio]:checked').val();
                        }
                        var value = e.value;
                        $.get($(this).attr('href')+'/'+value+'/'+category, $(this).serialize(), function(data){ // GET THE CURRENT HREF AND APPEND THE VALUE AND CATEGORY
                            $('.products_container').html(data);
                        });
                        pageNum = $(this).text();
                    });
                    /* FIRST PAGE PAGINATION */
                    $(document).on('click', '.first_page', function(){
                        if(! $('#categories input[type=radio]:checked').val()){
                            var category = 0;
                        }else{
                            var category =  $('#categories input[type=radio]:checked').val();
                        }
                        var value = e.value;
                        pageNum = 1;
                        $.get('/shops/get_the_page/'+pageNum+'/'+value+'/'+category, $(this).serialize(), function(data){ // GO TO THE FIRST PAGE W/O RESETING THE OTHER TWO
                            $('.products_container').html(data);
                        });
                    });
                    /* NEXT AND PREV PAGE (CHECK THE PREV IF IT'S LESS THAN OR EQUAL 1 AND NEXT PAGE CHECK IF THE PAGENUM IS GREATER THAN CURRENT TOTAL PAGE)*/
                    $(document).on('click', '.prev_page,.next_page', function(){
                        if(! $('#categories input[type=radio]:checked').val()){
                            var category = 0;
                        }else{
                            var category =  $('#categories input[type=radio]:checked').val();
                        }
                        var value = e.value;
                        if($(this).hasClass('prev_page')){
                            if(pageNum <= 1){
                                return false;
                            } else{
                                pageNum--;
                            }
                        }else if($(this).hasClass('next_page')){
                            if (pageNum >= res) {
                                return false;
                            }else{
                                pageNum++;
                            }
                        }
                        $('.page_number').html(pageNum);
                        $.get('/shops/get_the_page/' +pageNum+'/'+value+'/'+category, $(this).serialize(), function(data){
                            $('.products_container').html(data);
                        });
                        return false;
                    });
                     /**********************************************/
                    /*  For submission of forms  
                    EDIT: for every change the var value is going to append to the url for the page retain
                     low-high,high-low,most popular */
                    $(document).on("change", "#sorter", function(){
                        // alert('Hey ma, ITS WORKING !'); // check if it's working!
                        if(! $('#categories input[type=radio]:checked').val()){
                            var category = 0;
                        }else{
                            var category =  $('#categories input[type=radio]:checked').val();
                        }
                        var value = e.value;
                        var category =  $('#categories input[type=radio]:checked').val();
                        $.post($(this).attr('action')+pageNum+'/'+value+'/'+category,$(this).serialize(),function(res){
                            $('.products_container').html(res);
                        });
                        pageNumHighlight(pageNum);
                        return false;
                    });
                    /* GET THE CATEGORY SELECTED BY RADIO BUTTON */
                    $(document).on('change','#categories',function(){
                        $('#categories input').siblings('p').css('font-weight','normal');
                        $('#categories input[type=radio]:checked').siblings('p').css('font-weight','bold');
                        var category =  $('#categories input[type=radio]:checked').val();
                        var value = e.value;
                        $.post($(this).attr('action')+pageNum+'/'+value+'/'+category,$(this).serialize(),function(res){
                            $('.products_container').html(res);
                        });
                        pageNumHighlight(pageNum);
                        return false;
                    })
                    $('.first_page').click(); // initiate the pages // undefined coz of this crazy mess above didn't check first the consequence of separate forms
                });
            });
        </script>
        <script src='/assets/js/pagination.js'></script>
    </head>
    <body>
        <?php $this->load->view('partials/header') ?>
        <main>
        <form action="/shops/get_the_page/" method="post" id='categories'>
            <aside class="category_panel">
                <input type="search" name="product_name" placeholder="Product name" />
                <button type="submit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 17 17">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                    </svg>
                </button>
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
                        <h2><span class="category_name">T-shirts</span> (page <span class="page_number">1</span>)</h2>
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