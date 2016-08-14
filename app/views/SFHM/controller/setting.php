<div>
    <form method="post"  accept-charset="utf-8">
        <table class="tbl" style="width:90%;">
            <thead>
                <tr>
                    <td colspan="2"><?= $this->lang->line('global_setting')?></td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td> <?= $this->lang->line('setting_view_site_name')?> </td>
                    <td><input type="text" name="sitename" value="<?=$SITENAME?>" /></td>
                </tr>
                <tr>
                    <td><?= $this->lang->line('setting_view_url')?></td>
                    <td><input type="text" name="siteurl" value="<?=$SITEURL?>" /></td>
                </tr>
                <tr>
                    <td><?= $this->lang->line('setting_email_admin')?></td>
                    <td><input type="text" name="siteemail" value="<?=$SITEEMAIL?>" /></td>
                </tr>
                <tr>
                    <td> <?= $this->lang->line('setting_defualt_lang')?></td>
                    <td>
                        <select name="lang" id="lang">
                            <option selected="selected" value=""><?= $this->lang->line('global_selected_language')?></option>
                            <?php if(is_array($LANGS)): ?>
                                <?php foreach($LANGS as $row): ?>
                                    <option<?=($SITELANG == $row->id)? ' selected="selected"':''?> value="<?=$row->id?>"><?=$row->name?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><?=$this->lang->line('setting_chose_desgin')?></td>
                    <td>
                        <select name="style">
                            <?php if($STYLE): ?>
                                <?php foreach ($STYLE as $value): ?>
                                    <option<?=($STYLEVALUE == $value)? ' selected="selected"':''?> value="<?=$value?>"><?=$value?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><?= $this->lang->line('global_mainpage')?></td>
                    <td>
                        <select name="homepage">
                            <option<?=($HOMEPAGE == 'home')? ' selected="selected"':''?> value="home"><?= $this->lang->line('global_defualt')?></option>
                            <?php if($PAGES): ?>
                                <?php foreach ($PAGES as $value): ?>
                                    <option<?=($HOMEPAGE == $value->id)? ' selected="selected"':''?> value="<?=$value->id?>"><?=$value->title?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><?= $this->lang->line('setting_active_site')?></td>
                    <td><input type="radio" name="siteenable"<?=($SITEENABLE == 1)?' checked="checked"':''?> value="1" /><?= $this->lang->line('global_yes')?> <input type="radio" name="siteenable"<?=($SITEENABLE == 0)?' checked="checked"':''?> value="0" /><?= $this->lang->line('global_no')?></td>
                </tr>
                <tr>
                    <td><?= $this->lang->line('setting_close_msg')?></td>
                    <td><textarea name="disable_msg"><?=$DISABLE_MSG?></textarea></td>
                </tr>
                <tr>
                    <td><?= $this->lang->line('setting_exception_group')?></td>
                    <td>
                        <select name="group_disable">
                            <?php if($GROUP): ?>
                                <?php foreach ($GROUP as $value): ?>
                                    <option<?=($GROUPDISABLE == $value->id)? ' selected="selected"':''?> value="<?=$value->id?>"><?=$value->name?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>
        <br /><?/*
        <table class="tbl" style="width:90%;">
            <thead>
                <tr>
                    <td colspan="2"><?= $this->lang->line('setting_email_setting')?></td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?= $this->lang->line('setting_server')?></td>
                    <td><input type="text" name="hostmail" value="<?=$HOSTMAIL?>" /></td>
                </tr>
                <tr>
                    <td><?= $this->lang->line('setting_email')?></td>
                    <td><input type="text" name="namemail" value="<?=$NAMEMAIL?>" /></td>
                </tr>
                <tr>
                    <td> <?= $this->lang->line('setting_password')?></td>
                    <td><input type="password" name="passmail" placeholder="<?= $this->lang->line('setting_empty_no_change')?>"  /></td>
                </tr>
                <tr>
                    <td><?= $this->lang->line('setting_port')?></td>
                    <td><input type="text" name="portmail" value="<?=$PORTMAIL?>" /></td>
                </tr>
            </tbody>
        </table>
        <br />*/?>
        <table class="tbl" style="width:90%;">
            <thead>
                <tr>
                    <td colspan="2"><?= $this->lang->line('setting_reg_setting')?></td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?= $this->lang->line('setting_active_reg')?></td>
                    <td><input type="radio" name="registerenable"<?=($REGISTERENABLE == 1)?' checked="checked"':''?> value="1" /><?= $this->lang->line('global_yes')?> <input type="radio" name="registerenable"<?=($REGISTERENABLE == 0)?' checked="checked"':''?> value="0" /><?= $this->lang->line('global_no')?></td>
                </tr>
                <tr>
                    <td><?= $this->lang->line('setting_group_for_new_member')?></td>
                    <td>
                        <select name="register_group">
                            <?php if($GROUP): ?>
                                <?php foreach ($GROUP as $value): ?>
                                    <option<?=($GROUPREGSITER == $value->id)? ' selected="selected"':''?> value="<?=$value->id?>"><?=$value->name?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><?= $this->lang->line('setting_auto_active')?></td>
                    <td><input type="radio" name="registeractive"<?=($REGISTERACTIVE == 1)?' checked="checked"':''?> value="1" /><?= $this->lang->line('global_yes')?> <input type="radio" name="registeractive"<?=($REGISTERACTIVE == 0)?' checked="checked"':''?> value="0" /><?= $this->lang->line('global_no')?></td>
                </tr>
                <tr>
                    <td colspan="2"><input type="submit" value="<?= $this->lang->line('global_save')?>" /></td>
                </tr>
            </tbody>
        </table>
    </form>
</div>
