<?php if($STEP == 'init'):?>
<form method="post" action="<?=base_url()?>install/step/1">
    <div class="message">
        <span><?=$this->lang->line('install_view_welcome')?><br />
            <?=$this->lang->line('install_view_db')?>
        <?=$this->lang->line('install_view_start')?><br />
        <input type="submit" value="<?=$this->lang->line('install_view_button_start')?>" name="submit" /></span>
    </div>
</form>
<?php elseif ($STEP == "checkdb") : ?>
<div  class="message">
    <?php if(@$ERROR): ?>
        <form method="post" action="<?=base_url()?>install">
            <span style="color:#FF0000"><?=$this->lang->line('install_view_db_problem')?><br />
            &nbsp;<br />
            <?=$this->lang->line('install_view_check_config')?>
            <br /><input type="submit" value="<?=$this->lang->line('install_view_return_back')?>" name="B1" /></span>
        </form>
    <?php else: ?>
        <form method="post" action="<?=base_url()?>install/step/2">
            <span align="center"><?=$this->lang->line('install_view_con_db')?><br />
            <input type="submit" value="<?=$this->lang->line('insatll_view_button_next')?>" name="B1" /></span>
        </form>
    <?php endif; ?>
</div>
<?php elseif($STEP == 'createtable'): ?>
<form method="post" action="<?=base_url()?>install/step/3">
    <div class="message">
        <?=$this->lang->line('install_view_bulid_table')?>
        <br />
        <ul>
            <?php foreach (@$tables as $value): ?>
                <li><?=$this->lang->line('install_view_create_table')?><?=$value?></li>
            <?php endforeach; ?>
        </ul>
        <?=$this->lang->line('install_view_table_done')?>
        <br />
        <input type="submit" value="<?=$this->lang->line('insatll_view_button_next')?>" name="B1" />
    </div>
</form>
<?php elseif ($STEP == "addinfo"): ?>
    <form method="post" action="<?=base_url()?>install/step/4">
        <table class="tbl">
            <tr>
                <td colspan="2" class="alt1"><?=$this->lang->line('install_view_enter_info_db')?></td>
            </tr>
            <tr>
                <td colspan="2" class="alt2"><?=$this->lang->line('install_view_fill_info')?></td>
            </tr>
            <tr>
                <td class="alt1"><?=$this->lang->line('install_view_site_name')?>: </td>
                <td class="alt1"><input type="text" name="nameurl" size="20"></td>
            </tr>
            <tr>
                <td class="alt2"><?=$this->lang->line('install_view_site_link')?>:</td>
                <td class="alt2"><input type="text" name="url" size="20" value="<?=base_url()?>"></td>
            </tr>
            <tr>
                <td class="alt1"><?=$this->lang->line('install_view_username')?>: </td>
                <td class="alt1"><input type="text" name="useradmin" size="20"></td>
            </tr>
            <tr>
                <td class="alt2"><?=$this->lang->line('install_view_password')?>:</td>
                <td class="alt2"><input  type="password" name="pass1" size="20"></td>
            </tr>
            <tr>
                <td class="alt2"><?=$this->lang->line('install_view_email')?>:</td>
                <td class="alt2"><input type="text" name="email" size="20" /></td>
            </tr>
            <tr>
                <td colspan="2" class="alt1"><input type="submit" value="<?=$this->lang->line('insatll_view_button_next')?>" name="B1"></td>
            </tr>
        </table>
    </form>
<?php elseif($STEP == "insertinfo"): ?>
    <form method="post" action="<?=base_url()?>admin">
        <div class="message">
            <ul>
                <li><?=$this->lang->line('install_view_add_setting')?></li>
                <li><?=$this->lang->line('install_view_add_admins')?></li>
                <li><?=$this->lang->line('install_view_premssion')?></li>
                <li><?=$this->lang->line('install_view_add_info_admins')?></li>
            </ul>
            <span style="text-align:center"><?=$this->lang->line('install_view_delete_install')?></span>
            <br /><input type="submit" value="<?=$this->lang->line('global_cpenal')?>" name="B1" />
        </div>
    </form>
<?php endif; ?>

