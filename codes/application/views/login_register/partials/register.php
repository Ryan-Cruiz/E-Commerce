        <main>
            <span class='error-msg'><?=$this->session->flashdata('errors');?></span>
            <div id='display'></div>
            <div class="login_register_page">
                <p class="message_register"></p>
                <form class="form_login_register" action="/users/register_process" method="post">
                    <h2>Register</h2>
                    <span><p>First Name: </p><input type="text" name="first_name"/></span>
                    <span><p>Last Name: </p><input type="text" name="last_name"/></span>
                    <span><p>Email Address: </p><input type="text" name="email"/></span>
                    <span><p>Contact Number: </p><input type="text" name="contact" placeholder="11-digit number"/></span>
                    <span><p>Password: </p><input type="password" name="password"/></span>
                    <span><p>Confirm Password: </p><input type="password" name="confirm_password"/></span>
                    <span class="btn_login_register"><input type="submit" value="Register"/></span>
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash();?>">
                </form>
            </div>
        </main>