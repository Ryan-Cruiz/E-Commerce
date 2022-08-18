<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success | isStore</title>
    <script src="/assets/js/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/assets/css/normalize.css" />
    <link rel="stylesheet" type="text/css" href="/assets/css/style.css" />
    <script>
        $(document).ready(function(){
            $('.a_end').hide();
            // $(document).on("submit", "form", function(){
                
            // });
        });
    </script>
</head>
<body>
<?php $this->load->view('partials/header')?>
    <main>
        <div class="login_register_page">
            <h1>Transaction</h1>
            <table>
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Item Price</th>
                        <th>Total Item Price</th>
                    </tr>
                </thead>
                <tbody>
<?php foreach($this->session->userdata('all_cart') as $item){?>
            <tr>
                <td><?=$item['name']?></td>
                <td><?=$item['qty']?></td>
                <td><?=$item['price']?></td>
                <td><?=$item['item_total']?></td>
            </tr>
<?php }$this->session->unset_userdata('success_page')?>
                </tbody>
            </table>
            <h2>Total Items: <span><?=$this->session->userdata('total_cart') ?></span></h2>
            <p>----------------------------------------------------------------</p>
            <h2>Total Price: <span>₱<?=$this->session->userdata('total_price') ?></span></h2>
            <p>----------------------------------------------------------------</p>
            <h2>Thank you for buying!</h2>
            <p>----------------------------------------------------------------</p>
        </div>
    </main>
</body>
</html>
<?php 
    $this->session->unset_userdata('email');
    $this->session->unset_userdata('total_cart');
    $this->session->unset_userdata('total_price');
    $this->session->unset_userdata('all_cart');
?>