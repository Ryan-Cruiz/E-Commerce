<!--  status 1 shipping,2 complete,3 pending(user logged in), 0 cancelled -->
<?php $num=0;foreach($history as $h){?>
<?php if($num%2 == 0){?>
                    <tr>
<?php }else{?>
                    <tr class="color1">
<?php }?>
                        <td><a href="/order/detail"><?=$h['t_id']?></a></td>
                        <td><?=$h['first_name']?></td>
                        <td><?=$h['created_at']?></td>
                        <td><?=$h['address']?></td>
                        <td>₱<?=$h['total']?></td>
                        <td>
                            <form action="/admins/update_status/<?=$h['c_id']?>" method="post" id='update_status'>
                                <input type="hidden" name="product_id" value="product_id"/>
                                <select name="admin_orders_update" class="option_search">
<?php                           if($h['status_type'] == 1){?>
                                    <option value='1' selected>Order in process</option>
                                    <option value='2' >Shipped</option>
                                    <option value='0' >Cancelled</option>
<?php                           }else if($h['status_type'] == 2){?>
                                    <option value='1' >Order in process</option>
                                    <option value='2' selected>Shipped</option>
                                    <option value='0' >Cancelled</option>
<?php                           }else if($h['status_type'] == 0){?>
                                    <option value='1' >Order in process</option>
                                    <option value='2' >Shipped</option>
                                    <option value='0' selected>Cancelled</option>
<?php }?>
                                </select>
                                <input type="hidden" name="<?= $this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash();?>">
                            </form>
                        </td>
                    </tr>
<?php $num++;}?>