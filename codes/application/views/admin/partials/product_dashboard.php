    <main>
        <p class="message_admin_products"></p>
        <section class="form_admin_products">
            <form class="form_admin_products_search" action="" method="post">
                <input type="search" name="admin_products_search" placeholder="&#x1F50D; search" />
            </form>
            <!-- <form class="form_admin_products_add" action="" method="post">
                <input class="btn_add_product" type="submit" name="add_product" value="Add new product" />
            </form> -->
            <!-- <form class="form_admin_products_add" action="" method="post"> -->
            <button class="btn_add_product" type="button">Add new product</button>
            <!-- </form> -->
            
        </section>
        <table class="admin_products_table">
            <thead>
                <tr>
                    <th>Picture</th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Inventory Count</th>
                    <th>Quantity Sold</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr class="color0 product_id_1">
                    <td><img src="/assets/img/products/0/products.jpg" alt="t-shirt"></td>
                    <td class="product_id">1</td>
                    <td>Shirt</td>
                    <td>123</td>
                    <td>1000</td>
                    <td>
                        <a href=""><p class="product_edit_link">edit</p></a>
                        <a href=""><p class="product_delete_link">delete</p></a>
                    </td>
                </tr>
                <tr class="color1 product_id_2">
                    <td><img src="/assets/img/products/0/products.jpg" alt="t-shirt"></td>
                    <td class="product_id">2</td>
                    <td>Hat</td>
                    <td>456</td>
                    <td>1000</td>
                    <td>
                        <a href=""><p class="product_edit_link">edit</p></a>
                        <a href=""><p class="product_delete_link">delete</p></a>
                    </td>
                </tr>
                <tr class="color0 product_id_3">
                    <td><img src="/assets/img/products/0/products.jpg" alt="t-shirt"></td>
                    <td class="product_id">3</td>
                    <td>Mug</td>
                    <td>789</td>
                    <td>1000</td>
                    <td>
                        <a href=""><p class="product_edit_link">edit</p></a>
                        <a href=""><p class="product_delete_link">delete</p></a>
                    </td>
                </tr>
            </tbody>
        </table>
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
    </main>
    <div class="admin_product_delete">
        <p>Are you sure you want to delete product "<span class="delete_product_name">Product Name</span>" (ID: <span class="delete_product_id">ID</span>)</p>
        <div>
            <form action="" method="post">
                <input class="product_id" type="hidden" name="product_id" value="id"/>
                <input type="submit" value="Yes" />
            </form>
            <button type="button">No</button>
        </div>
    </div>
    <div class="modal_bg_delete_product"></div>
    <div class="modal_bg"></div>
    <dialog class="admin_products_add_edit">
        <h3 class="add_edit_product_header">Edit Product - ID 0</h3>
        <button class="btn_close">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M13.854 2.146a.5.5 0 0 1 0 .708l-11 11a.5.5 0 0 1-.708-.708l11-11a.5.5 0 0 1 .708 0Z"/>
                <path fill-rule="evenodd" d="M2.146 2.146a.5.5 0 0 0 0 .708l11 11a.5.5 0 0 0 .708-.708l-11-11a.5.5 0 0 0-.708 0Z"/>
            </svg>
        </button>
        <form class="form_product_add_edit" action="" method="post">
            <p>Name: </p><input class="input_product_name" type="text" name="product_name"/>
            <p>Description: </p><textarea class="input_product_desc" name="product_desc"></textarea>
            <p>Categories: </p>
            <div class="select_tag_container">
                <button class="dummy_select_tag" type="button"><span></span><span>&#9660;</span></button>
                <ul class="product_categories">
                </ul>
            </div>
            <p>or add new category: </p><input type="text" name="product_add_category"/>
            <p>Price: </p><input class="input_product_price" type="number" name="product_price" min="0.01" step="0.01"/>
            <p>Quantity (Inventory): </p><input class="input_product_qty" type="number" name="product_qty"/>
            <p class="img_field_name">Images: </p><input id="img_upload" type="file" name="product_img_file" multiple accept=".png, .jpg, .jpeg" />
            <label class="file_upload_label" for="img_upload">Upload</label>
            <ul class="img_upload_container">
            </ul>
            <div class="products_add_edit_btn">
                <button class="btn_cancel_products_add_edit" type="button">Cancel</button>
                <button class="btn_preview_products_add_edit" type="button">Preview</button>
                <input class="product_id" type="hidden" name="product_id" value=""/>
                <input class="btn_submit_products_add_edit" type="submit" value="Update" />
            </div>
        </form>
        <div class="bg_category_confirm_delete">
            <div class="category_confirm_delete">
                <p>Are you sure you want to delete "<span class="category_name">Shirt</span>" category?</p>
                <div>
                    <form action="" method="post">
                        <input class="category_id" type="hidden" name="category_id" value="id"/>
                        <input type="submit" value="Yes" />
                    </form>
                    <button type="button">No</button>
                </div>
            </div>
        </div>
    </dialog>
    <script src="/assets/js/product_dashboard.js"></script>