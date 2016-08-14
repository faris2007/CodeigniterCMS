<?php if($STEP == 'login'):?>
    <form method="post">
        <table class="tbl" style="width: 50%;">
            <thead>
                <tr>
                    <th colspan="2"><?=$this->lang->line('login_view_login')?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?=$this->lang->line('login_view_username')?> :</td>
                    <td><input type="text" name="username" required="required" placeholder="<?=$this->lang->line('login_view_username')?> ..." /></td>
                </tr>
                <tr>
                    <td><?=$this->lang->line('login_view_password')?> :</td>
                    <td><input type="password" name="password" required="required" placeholder="<?=$this->lang->line('login_view_password')?> ..." /></td>
                </tr>
                <?php if(@$ERROR): ?>
                    <tr>
                        <td colspan="2"><?=$this->lang->line('login_view_error_msg')?></td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td colspan="2"><input type="submit" value="<?=$this->lang->line('login_view_login')?>" /></td>
                </tr>
                <?php /*
                <tr>
                    <td colspan="2"><?=$this->lang->line('login_view_lost_password')?><a href="<?=base_url()?>login/resetpassword"><?=$this->lang->line('login_view_reset_password')?></a></td>
                </tr>
                 */?>
                <?php  if(@$REGISTER): ?>
                    <tr>
                        <td colspan="2"><?=$this->lang->line('login_view_new_register')?><a href="<?=base_url()?>register"><?=$this->lang->line('login_view_new_register')?></a></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </form>
<?php elseif($STEP == 'logout'): ?>
<div class="message">
   <?=$this->lang->line('login_view_logout')?>
</div>
<?php elseif($STEP == 'permission'): ?>
<div class="message">
    <?=$this->lang->line('login_view_permission')?>
</div>
<?php elseif($STEP ==  'resetpass'): ?>
<form method="post"><input type="hidden" name="token" value="<?=$this->core->token()?>" />
    <table class="tbl" style="width: 65%;">
        <thead>
            <tr>
                <th colspan="2"><?=$this->lang->line('login_view_reset_password')?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?=$this->lang->line('login_view_email')?>:</td>
                <td><input type="text" name="email" placeholder="E-Mail" /></td>
            </tr>
            <tr>
                <td><?=$this->lang->line('login_view_captcha')?></td>
                <td id="captchaimg"><?=$CAPTCHA?></td>
            </tr>
            <tr>
                <td><?=$this->lang->line('login_view_security_question')?></td>
                <td><input type="text" name="captcha" /></td>
            </tr>
            <?php if($ERROR): ?>
                <tr>
                    <td colspan="2" class="error"><?=@$ERR_MSG?></td>
                </tr>
            <?php endif; ?>
            <tr>
                <td colspan="2"><input type="submit" value="<?=$this->lang->line('login_view_reset_button')?>" /></td>
            </tr>
        </tbody>
    </table>
</form>
<?php endif; ?>

