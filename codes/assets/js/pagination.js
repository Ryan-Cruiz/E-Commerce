$(document).ready(function(){
    /**********************************************/
    /*  Pagination at catalog header    */
    $(document).on("click", ".first_page", function(){
        pageNum = 1;
        $(".page_number").text(pageNum);
        pageNumHighlight(pageNum);
        return false;
    });
    $(document).on("click", ".prev_page", function(){
        if(pageNum > 1){
            pageNum--;
        }
        $(".page_number").text(pageNum);
        pageNumHighlight(pageNum);
        return false;
    });
    /**********************************************/

    /*  For submission of forms    */
    $(document).on("submit", "form", function(){
        return false;
        pageNumHighlight(pageNum);
    });
    /**********************************************/
});