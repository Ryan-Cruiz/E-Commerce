        <main>
            <span class='error-msg'><?=$this->session->flashdata('errors');?></span>
            <div id='display'></div>
            <div class="login_register_page">
                <p class="message_login"></p>
                <form class="form_login_register" action="/users/login_event" method="post">
                    <h2>Login</h2>
                    <span><p>Contact Number/Email: </p><input type="text" name="email_contact_number"/></span>
                    <span><p>Password: </p><input type="password" name="password"/></span>
                    <span class="btn_login_register"><input type="submit" value="Login"/></span>
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash();?>">
                    <!-- <span class="btn_login_register"><a href="">Don't have an account? Register</a></span> -->
                </form>
            </div>
        </main>