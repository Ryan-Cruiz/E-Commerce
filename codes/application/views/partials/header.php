      
<?php if($this->session->userdata('curr_user') && $this->session->userdata('is_admin') && $this->session->userdata('logged_in')){?>
        <header class="header_admin">
            <a href="/admin"><h2>Dashboard</h2></a>
            <a href="/admin"><h3>Orders</h3></a>
            <a href="/products/1"><h3>Products</h3></a>
            <a class="nav_end" href="/logOut"><h3>Log off</h3></a>
        </header>
<?php }else if(!$this->session->userdata('curr_user') && !$this->session->userdata('logged_in')){?>
        <header>
            <a class='store_page' href="/anonymous"><h2>isStore</h2></a>
            <a class='nav_end a_end' href="<?=$url?>"><h3><?=$title?></h3></a>
            <div class="div_end">
                <a href="/mycart"><h3>Shopping Cart (<span class="cart_quantity"><?=$this->session->userdata('total_cart')?></span>)</h3></a>
                <a class='log_reg' href="<?=$url?>"><h3><?=$title?></h3></a>
            </div>
        </header>
<?php }else{?>
        <header>
            <a href="/dashboard"><h2>isStore</h2></a>
            <div class="div_end">
                <a href="/mycart"><h3>Shopping Cart (<span class="cart_quantity"><?=$this->session->userdata('total_cart')?></span>)</h3></a>
                <a class='log_reg' href="<?=$url?>"><h3><?=$title?></h3></a>
            </div>
        </header>
<?php }?>
       