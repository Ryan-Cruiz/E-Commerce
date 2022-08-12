    <main>
        <p class="message_admin_orders"></p>
        <form class="form_admin_orders" action="" method="post">
            <input type="search" name="admin_orders_search" placeholder="&#x1F50D; search" />
            <select name="admin_orders_status">
                <option value="0">Show All</option>
                <option value="1">Order in process</option>
                <option value="2">Shipped</option>
                <option value='3'>Cancelled</option>
            </select>
        </form>
        <table class="admin_orders_table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Billing Address</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><a href="/order/detail">100</a></td>
                    <td>Bob</td>
                    <td>9/6/2014</td>
                    <td>123 dojo way Bellevue WA 98005</td>
                    <td>$149.99</td>
                    <td>
                        <form action="" method="post">
                            <input type="hidden" name="product_id" value="product_id"/>
                            <select name="admin_orders_update" class="option_search">
                                <option>Order in process</option>
                                <option selected>Shipped</option>
                                <option>Cancelled</option>
                            </select>
                        </form>
                    </td>
                </tr>
                <tr class="color1">
                    <td><a class='details' href="/order/detail">99</a></td>
                    <td>Bob</td>
                    <td>9/6/2014</td>
                    <td>123 dojo way Bellevue WA 98005</td>
                    <td>$149.99</td>
                    <td>
                        <form action="" method="post">
                            <input type="hidden" name="product_id" value="product_id"/>
                            <select name="admin_orders_update" class="option_search">
                                <option>Order in process</option>
                                <option selected>Shipped</option>
                                <option>Cancelled</option>
                            </select>
                        </form>
                    </td>
                </tr>
                <tr>
                    <td><a href="/order/detail">98</a></td>
                    <td>Bob</td>
                    <td>9/6/2014</td>
                    <td>123 dojo way Bellevue WA 98005</td>
                    <td>$149.99</td>
                    <td>
                        <form action="" method="post">
                            <input type="hidden" name="product_id" value="product_id"/>
                            <select name="admin_orders_update" class="option_search">
                                <option value="1">Order in process</option>
                                <option value="2" selected>Shipped</option>
                                <option value="3">Cancelled</option>
                            </select>
                        </form>
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