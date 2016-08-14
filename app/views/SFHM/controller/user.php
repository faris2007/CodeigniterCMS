<?php if($STEP == 'show'):?>
<div id="action" class="message" style="display:none"></div>
<div class="demo_jui">
    <table id="list" class="tbl" style="width:100%;">
        <thead>
            <tr>
                <th colspan="4"><?=$this->lang->line('user_view_show_users')?></th>
                <th><a href="<?=base_url()?>user/add"><img src="<?=base_url()?>style/default/icon/add.png" alt="<?=$this->lang->line('user_view_add_new_user')?>" title="<?=$this->lang->line('user_view_add_new_user')?>" /></a></th>
            </tr>
            <tr>
                <th>#</th>
                <th><?=$this->lang->line('user_view_username')?></th>
                <th><?=$this->lang->line('user_view_full_name')?></th>
                <th><?=$this->lang->line('user_view_active')?></th>
                <th><?=$this->lang->line('user_view_contorl')?></th>
            </tr>
        </thead>
        <tbody>
            <?php if(is_array($USERS)): ?>
                <?php foreach ($USERS as $row): ?>
                    <tr id="user<?=$row->id?>">
                        <td><?=$row->id?></td>
                        <td><?=($row->isDelete == 1)? "<strike>".$row->username."</strike>":$row->username?></td>
                        <td><?=$row->full_name?></td>
                        <td><img id='enable<?=$row->id?>' src="<?=base_url()?>style/default/icon/<?=($row->isActive == 1)? 'en':'dis'?>able.png" onclick="action('<?=base_url()?>user/action/<?=($row->isActive == 0)? 'enable':'disable'?>/<?=$row->id?>','<?=($row->isActive == 0)? 'enable':'disable'?>','enable<?=$row->id?>','<?=$row->id?>')" alt="<?=($row->isActive == 1)? $this->lang->line('global_enable'):$this->lang->line('global_disable')?>" title="<?=($row->isActive == 1)? $this->lang->line('global_enable'):$this->lang->line('global_disable')?>" /></td>
                        <td>
                            <a href="<?=base_url()?>user/edit/<?=$row->id?>"><img src="<?=base_url()?>style/default/icon/edit.png" alt="<?=$this->lang->line('global_edit')?>" title="<?=$this->lang->line('global_edit')?>" /></a>
                            <img id="deleteimg<?=$row->id?>" src="<?=base_url()?>style/default/icon/<?=($row->isDelete == 1)? 'restore':'del'?>.png" onclick="action('<?=base_url()?>user/action/<?=($row->isDelete == 1)? 'restore':'delete'?>/<?=$row->id?>','<?=($row->isDelete == 1)? 'restore':'delete'?>','group<?=$row->id?>','<?=$row->id?>')" alt="<?=($row->isDelete == 1)? $this->lang->line('global_restore'):$this->lang->line('global_delete')?>" title="<?=($row->isDelete == 1)? $this->lang->line('global_restore'):$this->lang->line('global_delete')?>" />
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5"><?=$this->lang->line('user_view_filter_note')?>
                    <select id="available"  onchange="window.location.assign('<?=base_url()?>user/show/'+$('#available').val())">
                        <option<?=($FILTER == 'all')? ' selected="selected"':''?> value="all"><?=$this->lang->line('global_everyone')?></option>
                        <option<?=($FILTER == 'enable')? ' selected="selected"':''?> value="enable"><?=$this->lang->line('user_view_filter_enable_users')?></option>
                        <option<?=($FILTER == 'disable')? ' selected="selected"':''?> value="disable"><?=$this->lang->line('user_view_filter_disable_users')?></option>
                        <option<?=($FILTER == 'delete')? ' selected="selected"':''?> value="delete"><?=$this->lang->line('user_view_filter_delete_users')?></option>
                        <option<?=($FILTER == 'undelete')? ' selected="selected"':''?> value="undelete"><?=$this->lang->line('user_view_filter_undelete_users')?></option>
                    </select>
                </td>
            </tr>
        </tfoot>
    </table>
    <div class="message">
        <img src="<?=base_url()?>style/default/icon/enable.png" /> <?=$this->lang->line('user_view_note_enable_users')?>
        | <img src="<?=base_url()?>style/default/icon/disable.png" /> <?=$this->lang->line('user_view_note_disable_users')?>
        <br />
        <?=$this->lang->line('user_view_note_more')?>
    </div>
</div>
<?php elseif($STEP == 'add'): ?>
<form method="post" id="register">
    <div>
        <table class="tbl" style="width:90%">
            <thead>
                <tr>
                    <th colspan="2"><?=$this->lang->line('user_view_add_new_user')?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?=$this->lang->line('user_view_username')?>:</td>
                    <td><input type="text" name="username" required id="username" /></td>
                </tr>
                <tr>
                    <td><?=$this->lang->line('user_view_full_name')?>:</td>
                    <td><input type="text" name="fullName" required id="fullName" /></td>
                </tr>
                <tr>
                    <td><?=$this->lang->line('user_view_email')?>:</td>
                    <td><input type="text" name="email" required id="email" /></td>
                </tr>
                <tr>
                    <td><?=$this->lang->line('user_view_mobile')?>:</td>
                    <td><input type="text" name="mobile" id="mobile" /></td>
                </tr>
                <tr>
                    <td><?=$this->lang->line('user_view_password')?>:</td>
                    <td><input type="password" name="password" required id="password" /></td>
                </tr>
                <tr>
                    <td><?=$this->lang->line('user_view_repassword')?>:</td>
                    <td><input type="password" name="repassword" required id="repassword" /></td>
                </tr>
                <tr>
                    <td><?=$this->lang->line('user_view_group')?></td>
                    <td>
                        <select name="group_id">
                            <?php if(isset($GROUPS)): ?>
                                <?php foreach ($GROUPS as $row): ?>
                                    <option value="<?=$row->id?>"><?=$row->name?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </td>
                </tr>
                <?php if($ERROR): ?>
                    <tr>
                        <td colspan="2" class="msg"><?=$ERR_MSG?></td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td colspan="2"><input type="submit" value="<?=$this->lang->line('global_add')?>" /></td>
                </tr>
            </tbody>
        </table>
    </div>
</form>
<?php elseif($STEP == 'edit'): ?>
<form method="post" id="register">
    <div>
        <table class="tbl" style="width:90%">
            <thead>
                <tr>
                    <th colspan="3"><?=$this->lang->line('user_view_edit_user')?><?=$fullname?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?=$this->lang->line('user_view_username')?>:</td>
                    <td><input type="text" name="username" required id="username" value="<?=$username?>" /></td>
                    <td id="checkusername"></td>
                </tr>
                <tr>
                    <td><?=$this->lang->line('user_view_full_name')?>:</td>
                    <td colspan="2"><input type="text" name="fullName" required id="fullName" value="<?=$fullname?>" /></td>
                </tr>
                <tr>
                    <td><?=$this->lang->line('user_view_email')?>:</td>
                    <td><input type="text" name="email" required id="email" value="<?=$email?>" /></td>
                    <td id="checkemail"></td>
                </tr>
                <tr>
                    <td><?=$this->lang->line('user_view_mobile')?>:</td>
                    <td colspan="2"><input type="text" name="mobile" id="mobile" value="<?=$mobile?>" /></td>
                </tr>
                <tr>
                    <td><?=$this->lang->line('user_view_password')?>: <?=$this->lang->line('user_view_edit_password_note')?></td>
                    <td><input type="password" name="password" id="password" /></td>
                    <td id="result"></td>
                </tr>
                <tr>
                    <td><?=$this->lang->line('user_view_repassword')?> :</td>
                    <td><input type="password" name="repassword" id="repassword" /></td>
                    <td id="resultre"></td>
                </tr>
                <?php if($ADMIN): ?>
                <tr>
                    <td><?=$this->lang->line('user_view_group')?></td>
                    <td>
                        <select name="group_id">
                            <?php if(isset($GROUPS)): ?>
                                <?php foreach ($GROUPS as $row): ?>
                                    <option<?=($group_id == $row->id)? ' selected="selected"':''?> value="<?=$row->id?>"><?=$row->name?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </td>
                </tr>
                <?php endif; ?>
                <?php if($ERROR): ?>
                    <tr>
                        <td colspan="3" class="msg"><?=$ERR_MSG?></td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td colspan="3"><input type="submit" value="<?=$this->lang->line('global_edit')?>" /></td>
                </tr>
            </tbody>
        </table>
    </div>
</form>
<?php endif; ?>

