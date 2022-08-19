$(document).ready(function(){
    $('.a_end').hide();
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
    $(document).on('click','.products_categories > label',function(){
        categoryName = $(this).text().split("(")[0];
        $(".category_name").text(categoryName);
        pageNumHighlight(pageNum);
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
            $('.page_number').text($(this).text());
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
            $.get('/shops/page/0', function(data){
                $('.pagination').html(data);
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
            $.get('/shops/page/1', function(data) {
                $('.pagination').html(data);
            });
            pageNumHighlight(pageNum);
            return false;
        });
        $(document).on('change keyup','#search_form',function(){
            $.post($(this).attr('action'),$(this).serialize(),function(res){
                $('.products_container').html(res);
                return false;
            });
            $.get('/shops/page/1', function(data) {
                $('.pagination').html(data);
                return false;
            });
            pageNumHighlight(pageNum);
            return false;
        });
        $('.first_page').click(); // initiate the pages // undefined coz of this crazy mess above didn't check first the consequence of separate forms
    });
    return false;
});