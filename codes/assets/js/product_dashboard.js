    
        /*  For pagination highlight    */
        function pageNumHighlight(pageNum){
            $(".pagination > a").css("background-color", "white").css("color", "blue");
            for(var i = 0; i < document.querySelectorAll(".pagination > a").length; i++){
                if(pageNum == $(".pagination > a:nth-child(" + i + ")").text()){
                    $(".pagination > a:nth-child(" + i + ")").css("background-color", "#1975ff").css("color", "white");
                }
            }
        }
        /**********************************************/

        /*  Reset the UI/Display of product categories    */
        function resetCategoryDisplay(){
            $(".product_categories").hide();
            $(".product_category_text_input").attr("readonly", true).css("outline", "none").css("cursor", "default");
            $(".dummy_select_tag").css("border", "0.3px solid rgb(118, 118, 118)");
            $(".bg_category_confirm_delete").hide();
            $(".waiting_icon").css("visibility", "hidden");
        }
        /**********************************************/

        /*  Reset the attributes of checkbox    */
        function resetCheckbox(){
            $(".img_upload_section input[type=checkbox]").attr("disabled", false);
            $(".img_upload_section input[type=checkbox]").siblings("label").css("color", "black");
        }
        /**********************************************/

        /*  Reset the attributes of checkbox    */
        function hideDialogBox(){
            $("dialog.admin_products_add_edit").hide();
            $(".admin_product_delete").hide();
            $(".modal_bg").hide();
        }
        /**********************************************/

        $(document).ready(function(){

            hideDialogBox();

            /*  Open add new product dialog box    */
            $(document).on("click", ".btn_add_product", function(){
                $('.form_product_add_edit').attr('action','/admins/add_item');
                $(".add_edit_product_header").text("Add a new product");
                $(".input_product_name").val("");
                $(".input_product_desc").val("");
                $(".dummy_select_tag span:first-child").text("");
                $(".input_product_price").val("");
                $(".input_product_qty").val("");
                $(".img_upload_container").html("");
                $(".btn_submit_products_add_edit").val("Add");
                $(".btn_submit_products_add_edit").removeClass("edit_product_submit");
                $(".btn_submit_products_add_edit").addClass("add_product_submit");
                $(".modal_bg").show();
                $("dialog.admin_products_add_edit").show();
            });
            /**********************************************/

            /*  Clicking add button will submit the form using ajax    */
            $(document).on("submit", ".add_product_submit", function(){
                $.post($(this).attr('action'),$(this).serialize(),function(res){
                    res.preventDefault();
                    return false;
                });
                var className = $("tbody tr:last-child").attr("class").split(" ");
                var colorNum = className[0].split("color")[1];
                var productID = className[1].split("product_id_")[1];
                var newProductID = parseInt(productID) + 1;
                var newClassName = "color" + ((colorNum + 1) % 2) + " product_id_" + newProductID;

                // almost same as edit product and in preview
                var productName = $(".input_product_name").val();
                var productInventory = $(".input_product_qty").val();

                var imgUpload = $(".img_upload_section > figure > img");
                var imgCheckbox = $(".img_upload_section > input[type=checkbox]");
                var prevProductImg = [];
                var mainIndexImg = 0;
                for(var i = 0; i < imgUpload.length; i++){
                    prevProductImg[i] = imgUpload[i].currentSrc;
                    if(imgCheckbox[i].checked){
                        mainIndexImg = i;
                    }
                }
                var productImgSrc = prevProductImg[mainIndexImg];
                var productImgAlt = "img";
                
                var newProductHtmlStr = '' +
                    '<tr class="' + newClassName + '">' +
                        '<td><img src="' + productImgSrc + '" alt="img"></td>' +
                        '<td class="product_id">' + newProductID + '</td>' +
                        '<td>' + productName + '</td>' +
                        '<td>' + productInventory + '</td>' +
                        '<td>0</td>' +
                        '<td>' +
                            '<a href=""><p class="product_edit_link">edit</p></a>' +
                            '<a href=""><p class="product_delete_link">delete</p></a>' +
                        '</td>' +
                    '</tr>';

                $("tbody").append(newProductHtmlStr);

                // ajax
                // display message
               
                hideDialogBox();
                return false;
            });
            /********************************************************************/

            /*  Open delete product dialog box    */
            $(document).on("click", ".product_delete_link", function(){

                var productID = $(this).parent().parent().siblings(".product_id").text();
                var productName = $(this).parent().parent().siblings(".product_id + td").text();
                $(".admin_product_delete .product_id").val(productID);
                $(".delete_product_id").text(productID);
                $(".delete_product_name").text(productName);
                $(".modal_bg_delete_product").show();
                $(".admin_product_delete").show();
                $('#delete_product').attr('action','/admins/delete_item/'+productID);
                return false
            });

            $(document).on("click", ".admin_product_delete > div > button, .modal_bg_delete_product", function(){
                $(".admin_product_delete").hide();
                $(".modal_bg_delete_product").hide();
            });

            // submit delete form using the general ajax. Not this!
            $(document).on("click", ".admin_product_delete input[type=submit]", function(){
                $(".product_id_" + $(this).siblings().val()).remove();
                $(this).parent().submit(function(){
                    $.post($(this).attr('action'),$(this).serialize(),function(){
                        return false;
                    });
                    return false; 
                });
                $(".admin_product_delete").hide();
                $(".modal_bg_delete_product").hide();
            });
            /**********************************************/

             /*  Open edit product dialog box  
             EDIT: I USE THE JSON FORMAT TO THROW THE DATA INTO THE EDIT */
             $(document).on("click", ".product_edit_link", function(){
                var product_id = $(this).parent().parent().siblings(".product_id").text();
                $.get("/Admins/get_edit/"+$(this).parent().parent().siblings(".product_id").text(), function(res){
                    $('.form_product_add_edit').attr('action','/admins/update_item/'+product_id);
                    var productID = res.data[0].id;
                    var headerStr = "Edit Product - ID " + productID;
                    var productName = res.data[0].item_name;
                    var productDesc = res.data[0].description;
                    var productCategory = res.data[0].category;
                    var productPrice = res.data[0].price;
                    var productInventory = res.data[0].stock;
                    var htmlImgStr = "";
                    for(var i =0;i<res.data.length;i++){
                    var productImgSrc = res.data[i].url;
                    var productImgAlt = res.data[0].item_name;
                    htmlImgStr += '<li class="img_upload_section">' +
                        '<figure>' +
                            '<img src="' + productImgSrc + '" alt="' + productImgAlt + '" />' +
                        '</figure>' +
                        '<p class="img_filename">' + productImgAlt + '</p>' +
                        '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash btn_img_upload_delete" viewBox="0 0 16 16">' +
                            '<path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>' +
                            '<path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>' +
                        '</svg>' +
                        '<input type="hidden" name="image_name" value="'+productImgAlt+'" />' +
                        '<input type="checkbox" class="main_img" name="img_upload_main_id[]" value="'+res.data[0].is_main+'" />' +
                        '<label>main</label>' +
                    '</li>';
                    }
                  
                    $(".add_edit_product_header").text(headerStr);
                    $(".input_product_name").val(productName);
                    $(".input_product_desc").val(productDesc);
                    $(".dummy_select_tag span:first-child").text(productCategory);
                    $(".current_category").val(productCategory);
                    $(".input_product_price").val(productPrice);
                    $(".input_product_qty").val(productInventory);
                    $(".img_upload_container").html(htmlImgStr);

                    $(".products_add_edit_btn .product_id").val(productID);
                    $(".btn_submit_products_add_edit").val("Update");
                    $(".btn_submit_products_add_edit").removeClass("add_product_submit");
                    $(".btn_submit_products_add_edit").addClass("edit_product_submit");
                    $(".modal_bg").show();
                    $("dialog.admin_products_add_edit").show();
                },"json");
                return false;
               
            });
            /**********************************************/
            /*  Clicking add button will submit the form using ajax    */
            // submit form using the general ajax. Not this!
            $(document).on("click", ".edit_product_submit", function(){
                var productIdEdited = ".product_id_" + $(".products_add_edit_btn .product_id").val();
                var productName = $(".input_product_name").val();
                var productInventory = $(".input_product_qty").val();

                var imgUpload = $(".img_upload_section > figure > img");
                var imgCheckbox = $(".img_upload_section > input[type=checkbox]");
                var prevProductImg = [];
                var mainIndexImg = 0;
                for(var i = 0; i < imgUpload.length; i++){
                    prevProductImg[i] = imgUpload[i].currentSrc;
                    if(imgCheckbox[i].checked){
                        mainIndexImg = i;
                    }
                }
                var productImgSrc = prevProductImg[mainIndexImg];
                var productImgAlt = "img";

                $(productIdEdited).children(".product_id + td").text(productName);
                $(productIdEdited).children(".product_id + td + td").text(productInventory);
                $(productIdEdited).children("td:first-child").find("img").attr("src", productImgSrc);
                $(productIdEdited).children("td:first-child").find("img").attr("alt", productImgAlt);
                $(this).parent().parent().submit(function(){ return false; });
                hideDialogBox();
                return false;
            });
            /**********************************************/


            /*  Initializing the content of product categories    */
            $.get("/Admins/get_category", function(res){
              //  console.log(res);
                var categoriesOption = "<form></form>";
                var selectOptions = "";
                for(var i = 0; i < res.data.length; i++){
                    categoriesOption += 
                        '<li class="product_category_edit_delete_section ' + (i+1) + '">' +
                            '\n\t<form class="form_product_category_edit form_category" action="/Admins/edit_category/'+res.data[i].id+'" method="post">' +
                            '\n\t\t<input class="product_category_id" type="hidden" name="product_category_id" value="' + res.data[i].id + '"/>' +
                                '\n\t\t<input class="product_category_text_input" name="category" readonly type="text" value="' + res.data[i].category + '"/>' +
                                 '<input type="hidden" name="'+res.csrf.name+'" value="'+res.csrf.hash+'"/>'+
                            '\n\t</form>' +
                            '\n\t<div class="product_category_btn">' +

                                '\n\t\t<div class="waiting_icon"><img src="/assets/img/ajax-loading-icon.gif" alt="waiting icon"></div>' +

                                '\n\t\t<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill btn_product_category_edit" viewBox="0 0 16 16">' +
                                    '\n\t\t\t<path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>' +
                                '\n\t\t</svg>' +

                                '\n\t\t<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash btn_product_category_delete" viewBox="0 0 16 16">' +
                                    '\n\t\t\t<path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>' +
                                    '\n\t\t\t<path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>' +
                                '\n\t\t</svg>' +

                            '\n\t</div>' +

                        '\n</li>';

                    selectOptions += '<option value="' + res.data[i].category + '">' + res.data[i].category + '</option>';
                }
                $(".product_categories").html(categoriesOption);
                resetCategoryDisplay();
            
                /**********************************************/

                /*  Show the options/categories for the dummy select tag    */
                $(document).on("click", ".dummy_select_tag", function(){
                    $(this).css("border", "2px solid black");
                    $(".product_categories").toggle();
                });
    
                /**********************************************/

                /*  Show the edit/delete buttons on hover.    */
                $(document)
                    .on("mouseenter", ".product_category_edit_delete_section",  function(){
                        $(this).children(".product_category_btn").css("visibility", "visible");
                        $(this).children("form").children().css("cursor", "default").css("background-color", "#00aff8");
                        $(this).css("cursor", "default").css("background-color", "#00aff8");
                        if($(this).children("form").children("input[type=text]").attr("readonly") == null){
                            $(this).children("form").children("input[type=text]").css("cursor", "text");
                        }
                    })
                    .on("mouseleave", ".product_category_edit_delete_section",  function(){
                        $(this).children(".product_category_btn").css("visibility", "hidden");
                        $(this).children("form").children().css("background-color", "white");
                        $(this).css("background-color", "white");
                    });
                /**********************************************/

                /*  Assign the value of selected option to the dummy select tag    */
                $(document).on("click", ".form_product_category_edit", function(){
                    if($(this).children(".product_category_text_input").attr("readonly") != null){
                        $(".current_category").val($(this).children(".product_category_text_input").val());
                        $(".dummy_select_tag span:first-child").text($(this).children(".product_category_text_input").val());
                        resetCategoryDisplay();
                    }
                });
                /**********************************************/

                /*  Edit the value of the specific category    */
                $(document).on("click", ".btn_product_category_edit", function(){
                    $(".product_category_text_input").attr("readonly", true).css("outline", "none");
                    $(this).parent().siblings("form").children(".product_category_text_input").attr("readonly", false).css("outline", "2.5px solid black").css("cursor", "text");
                });
                
                /* This should be on ajax
                EDIT: I CHANGED THE THIS INTO FORM INSTEAD OF INPUT COZ EVERY TIME A ENTRY IS SET IT ADDS UP
                E.G is EDITING ONE INPUT MULTIPLE TIMES WILL ADD THE SUBMISSION AND RESUBMIT IT 
                -First edit is okay
                -second edit will resubmit 2 times (this will also apply on 3rd and nth edits in just one entry)*/
                $(document).on("change submit", ".form_category", function(){
                    if($(this).children().attr("readonly") != "readonly"){
                        // display waiting icon before sending
                        $(this).siblings(".product_category_btn").children(".waiting_icon").css("visibility", "visible");
                        // SEND FORM IF THERE'S A CHANGE
                        $.post($(this).attr('action'), $(this).serialize(), function(){
                            // I don't get it but it needs to perform this action to send it to the database!
                            alert('Edit Successfully!'); // INDICATOR
                            $(".product_category_text_input").attr("readonly", true).css("outline", "none"); // RESET AND GO THE THE READONLY 
                            return false;
                        });
                        // hide waiting icon after receiving ang changing all data.
                        setTimeout(function(){
                            $(".waiting_icon").css("visibility", "hidden");
                        }, 500);
                        return false;
                    }
                    return false;
                });
                /**********************************************/
                
                /*  Show the delete category confirmation box to confirm delete    */
                $(document).on("click", ".btn_product_category_delete", function(){
                    resetCategoryDisplay();

                    var categoryName = $(this).parent().siblings("form").children(".product_category_text_input").val();
                    var categoryID = $(this).parent().siblings("form").children(".product_category_id").val();
                    $(".category_name").text(categoryName);
                    $(".category_id").val(categoryID);
                    $('#delete_category').attr('action','/admins/delete_category/'+categoryID);
                    $(".bg_category_confirm_delete").show();
                });

                $(document).on("click", ".category_confirm_delete > div > button, .bg_category_confirm_delete", function(){
                    $(".bg_category_confirm_delete").hide();
                });
                  // submit delete form using the general ajax. Not this!
                  $(document).on("submit", "#delete_category", function(res){
                    $("." + $(this).children('.category_confirm_delete input[type=submit]').siblings().val()).remove();
                        $.post($(this).attr('action'),$(this).serialize(),function(){
                          //   alert('DELETED SUCCESSFULLY');
                        });
                        resetCategoryDisplay();
                        res.preventDefault();
                        // return false;
                    });
                  
                /**********************************************/

                /*  Stop propagation of clicks on dummy select tag and the confirm date box. And reset display when click outside of these elements    */
                $(document).on("click", ".select_tag_container, .category_confirm_delete", function(e){
                    e.stopPropagation();
                });

                $(document).on("click", "html", function(){
                    resetCategoryDisplay();
                });
        },"json");
            /**********************************************/

            /*  DOCU: This function read the uploaded image files.
                This codes used the FileReader from JavaScript.
                This will render the preview of uploaded images.
            */
            var temp = 0;
            function readURL(input) {
                if(!input.files || !input.files[0]){
                    return false;
                }
                else if($(".img_upload_section").length + input.files.length > 4){
                    alert("Only four (4) images in total are allowed to be upload.");
                    return false;
                }
                var reader = new FileReader();
                var onLoadCounter = 0;
                reader.addEventListener('load', function(e){
                // reader.onload = function (e) {
                    console.log(e);
                 
                   var htmlStr =''+
                        '<li class="img_upload_section">' +
                            '<figure>' +
                                '<img src="' + e.target.result + '" alt="' + input.files[onLoadCounter].name + '" />' +
                            '</figure>' +
                            '<p class="img_filename">' + input.files[onLoadCounter].name + '</p>' +
                            '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash btn_img_upload_delete" viewBox="0 0 16 16">' +
                                '<path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>' +
                                '<path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>' +
                            '</svg>' +
                            '<input type="checkbox"  class="main_img" name="img_upload_main_id" value="0" />' +
                            '<label>main</label>' +
                        '</li>';
                     temp++;   
                     onLoadCounter++;
                    $("ul.img_upload_container").append(htmlStr);
                    alert(temp);
                // }
                });
                reader.readAsDataURL(input.files[0]);
              
                var counter = 1;
                reader.onloadend = function(e){
                  //  console.log('Uploaded files same time counter: '+counter); // ACTIVATE THIS BAD BOI AND PICK 4 PICTURE SAME TIME!(NOT MY CODE THO -ryan)
                    if(counter < input.files.length){
                        reader.readAsDataURL(input.files[counter]); 
                        counter++;
                        $('.img_upload_container  .img_upload_section').css('font-weight','normal');
                        $('.img_upload_container  .img_upload_section:first-child').css('font-weight','bold');
                        $('.img_upload_container  .img_upload_section').children('.main_img').val('0');
                        $('.img_upload_container > .img_upload_section:first-child').children('.main_img').val('1');
                    }
                }
            }

            $(document).on("change", "#img_upload", function(){
                readURL(this); // javascript solution
            });

            /********************************************************************/
            

            /*  Disable other checkbox when a checkbox is checked  
            edit: and also if checkbox is check set it to 1(main) 0(not)  */
            $(document).on("click", ".img_upload_section input[type=checkbox]", function(){
                $('.img_upload_section input[type=checkbox]').siblings().css('font-weight','normal');
                $('.img_upload_section input[type=checkbox]').val('0');
                $(this).siblings().css('font-weight','bold');
                $(this).val('1');
                if($(".img_upload_section input[type=checkbox]").not(this).attr("disabled")){
                    resetCheckbox();
                    $(this).siblings().css('font-weight','normal');
                   // $(this).val('0');
                    
                }
                else{
                    $(".img_upload_section input[type=checkbox]").not(this).attr("disabled", true);
                    $(".img_upload_section input[type=checkbox]").not(this).siblings("label").css("color", "gray");
                    $('.img_upload_container  .img_upload_section').css('font-weight','normal');
                    $('.img_upload_container  .img_upload_section:first-child').css('font-weight','bold');
                }
            });
            /********************************************************************/

            /*  Remove the uploaded photo and verify if checkbox is checked to reset the checkbox    */
            $(document)
                .on("mouseover", ".img_upload_section",  function(){
                    $(this).children(".btn_img_upload_delete").css("visibility", "visible");
                    $(this).children(".btn_img_upload_delete").css("cursor", "pointer");
                    $(this).css("outline", "2px solid #1975ff");
                })
                .on("mouseleave", ".img_upload_section",  function(){
                    $(this).children(".btn_img_upload_delete").css("visibility", "hidden");
                    $(this).children(".btn_img_upload_delete").css("cursor", "default");
                    $(this).css("outline", "none");
                });
                
            $(document).on("click", ".btn_img_upload_delete", function(){
                if(!$(this).siblings("input[type=checkbox]").attr("disabled")){
                    resetCheckbox();
                }
                $(this).parent().remove();
            });
            /********************************************************************/

            /*  For sorting/arrangement of photo    */
            $(document)
                .on("mouseover", ".img_upload_section figure",  function(){
                    $(this).parent().parent().sortable({
                        start: function(e, ui){
                            ui.placeholder.height(ui.item.height());
                        }
                    });
                    $('.img_upload_container  .img_upload_section').css('font-weight','normal');
                    $('.img_upload_container > .img_upload_section:first-child').css('font-weight','bold');
                    $('.img_upload_container  .img_upload_section').children('.main_img').val('0');
                    $(this).parent().parent().sortable("enable");
                    $(this).css("cursor", "grab");
                    $('.img_upload_container > .img_upload_section:first-child').children('.main_img').val('1');
                })
                .on("mouseleave", ".img_upload_section figure",  function(){
                    $(this).parent().css("background-color", "white");
                    $(this).parent().parent().sortable("disable");
                })
                .on("mousedown", ".img_upload_section figure",  function(){
                    $(this).css("cursor", "grabbing");
                })
                .on("mouseup", ".img_upload_section figure",  function(){
                    $(this).css("cursor", "grab");
                });
            /********************************************************************/
            
             /*  Clicking cancel or close will close the dialog box    */
            $(document).on("click", ".btn_cancel_products_add_edit, .btn_close", function(){
                hideDialogBox();
                return false;
            });
            /********************************************************************/

            /*  Clicking preview button will display a new tab of Preview page    */
            $(document).on("click", ".btn_preview_products_add_edit", function(){
                var prevProductName = $(".form_product_add_edit").children(".input_product_name").val();
                var prevProductDesc = $(".form_product_add_edit").children(".input_product_desc").val();
                var prevProductPrice = $(".form_product_add_edit").children(".input_product_price").val();

                var totalOptions = 3;
                var prevProductPriceOption = [];
                for(var i = 0; i < totalOptions; i++){
                    prevProductPriceOption[i] = i + 1 + ' ($' + prevProductPrice * (i + 1) + ')';
                }

                var imgUpload = $(".img_upload_section > figure > img");
                var imgCheckbox = $(".img_upload_section > input[type=checkbox]");
                var prevProductImg = [];
                var mainIndexImg = 0;
                for(var i = 0; i < imgUpload.length; i++){
                    prevProductImg[i] = imgUpload[i].currentSrc;
                    if(imgCheckbox[i].checked){
                        mainIndexImg = i;
                    }
                }

                preview(prevProductName, prevProductDesc, prevProductPriceOption, prevProductImg, mainIndexImg);
            });
            /********************************************************************/

           
        });
   
        /*  Function for Preview in new tab    */
        function preview(prevProductName, prevProductDesc, prevProductPriceOption, prevProductImg, mainIndexImg){
            var previewWindowHTML = '' +
                '<!DOCTYPE html>' +
                '<html lang="en">' +
                '<head>' +
                    '<meta charset="UTF-8">' +
                    '<meta http-equiv="X-UA-Compatible" content="IE=edge">' +
                    '<meta name="viewport" content="width=device-width, initial-scale=1.0">' +
                    '<title>(Preview Page) '+ prevProductName +' | isStore</title>' +
                    
                    '<link rel="stylesheet" type="text/css" href="/assets/css/normalize.css" />' +
                    '<link rel="stylesheet" type="text/css" href="/assets/css/style.css" />' +
                '</head>' +
                '<body>' +
                '<header>'+
                '<a><h2>isStore</h2></a>'+
                '<div class="div_end">'
                    +'<a ><h3>Shopping Cart (<span class="cart_quantity">4</span>)</h3></a>'
                    +'<a ><h3>Login</h3></a>'
                    +'</div>'
                +'</header>'+
                    '<main>' +
                        '<section class="item_panel">' +
                            '<a class="go_back" href=""><p>Go Back</p></a>' +
                            '<div class="item_details">' +
                                '<aside class="img_section">' +
                                    '<img class="main_img" src="' + prevProductImg[mainIndexImg] + '" alt="img"/>' +
                                    '<section>';
            for(var i = 0; i < prevProductImg.length; i++){
                previewWindowHTML += '<img class="sub_img" src="' + prevProductImg[i] + '" alt="img"/>'
            }
            previewWindowHTML += '' +
                                    '</section>' +
                                '</aside>' +
                                '<aside class="desc_section">' +
                                    '<h2>' + prevProductName + '</h2>' +
                                    '<p>' + prevProductDesc + '</p>' +
                                    '<form method="post">' +
                                        '<input type="hidden" name="product_id" value="product_id"/>' +
                                        '<select class="new_order_qty">' +
                                            '<option>' + prevProductPriceOption[0] + '</option>' +
                                            '<option>' + prevProductPriceOption[1] + '</option>' +
                                            '<option>' + prevProductPriceOption[2] + '</option>' +
                                        '</select>' +
                                        '\n<input type="button" value="Buy"/>' +
                                        '<p class="item_added_confirm">Item added to the cart.</p>' +
                                    '</form>' +
                                '</aside>' +
                            '</div>' +
                        '</section>' +
                    '</main>' +
                '</body>';

            var previewWindow = window.open("", "Preview");
            previewWindow.document.write(previewWindowHTML);
        }