        <main>
            <p class="message_admin_orders"></p>
            <form class="form_admin_orders" action="/Admins/search_status" method="post" id='searh_form'>
                <input type="search" name="admin_orders_search" id='search_input'placeholder="&#x1F50D; search" />
                <select name="admin_orders_status" id='select_status'>
                    <option value="">Show All</option>
                    <option value="1">Order in process</option>
                    <option value="2">Shipped</option>
                    <option value='0'>Cancelled</option>
                </select>
                <input type="submit" value="Search">
                <a href="/admin">Clear selection</a>
                <input type="hidden" name="<?= $this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash();?>">
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
                <tbody id='tbody'>
<?php $this->load->view('admin/partials/table_part');?>
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
        <script>
            $(document).on('change','#update_status',function(){
                $.post($(this).attr('action'),$(this).serialize(),function(){
                    alert('Status Edit Success!');
                    return false;
                });
            });
        </script>
    </body>
</html>