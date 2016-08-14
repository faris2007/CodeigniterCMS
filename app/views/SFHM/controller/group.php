<?php if($STEP == 'show'): ?>
<div id="action" class="message" style="display:none"></div>
<div class="demo_jui">
    <table id="list" class="tbl" style="width:100%;">
        <thead>
            <tr>
                <th colspan="2"><?=$this->lang->line('group_view_show_group')?></th>
                <th><a href="<?=base_url()?>group/add"><img src="<?=base_url()?>style/default/icon/add.png" alt="<?= $this->lang->line('group_view_add_new_group')?> "title="<?= $this->lang->line('group_view_add_new_group')?> "/></a></th>
            </tr>
            <tr>
                <th>#</th>
                <th><?=$this->lang->line('group_view_addrees')?></th>
                <th><?=$this->lang->line('group_view_control')?></th>
            </tr>
        </thead>
        <tbody>
            <?php if(is_array($GROUPS)): ?>
                <?php foreach ($GROUPS as $row): ?>
                    <tr id="group<?=$row->id?>">
                        <td><?=$row->id?></td>
                        <td><?=($row->isDelete == 1)? "<strike>".$row->name."</strike>":$row->name?></td>
                        <td>
                            <a href="<?=base_url()?>group/edit/<?=$row->id?>"><img src="<?=base_url()?>style/default/icon/edit.png" alt="<?=$this->lang->line('global_edit')?>" title="<?=$this->lang->line('global_edit')?>" /></a>
                            <a href="<?=base_url()?>group/permission/<?=$row->id?>"><img src="<?=base_url()?>style/default/icon/add_permission.png" alt="<?=$this->lang->line('group_view_permission')?>" title="<?=$this->lang->line('group_view_permission')?>" /></a>
                            <img id="deleteimg<?=$row->id?>" src="<?=base_url()?>style/default/icon/<?=($row->isDelete == 1)? 'restore':'del'?>.png" onclick="action('<?=base_url()?>group/action/<?=($row->isDelete == 1)? 'restore':'delete'?>/<?=$row->id?>','<?=($row->isDelete == 1)? 'restore':'delete'?>','group<?=$row->id?>','<?=$row->id?>')" alt="<?=($row->isDelete == 1)? $this->lang->line('global_restore'):$this->lang->line('global_delete')?>" title="<?=($row->isDelete == 1)? $this->lang->line('global_restore'):$this->lang->line('global_delete')?>" />
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4"><?=$this->lang->line('group_view_show_result')?>
                    <select id="available">
                        <option<?=($FILTER == 'all')? ' selected="selected"':''?> value="all"><?=$this->lang->line('group_view_all')?></option>
                        <option<?=($FILTER == 'delete')? ' selected="selected"':''?> value="delete"><?=$this->lang->line('group_view_delete')?></option>
                        <option<?=($FILTER == 'undelete')? ' selected="selected"':''?> value="undelete"><?=$this->lang->line('group_view_undelete')?></option>
                    </select>
                    <button onclick="window.location.assign('<?=base_url()?>group/show/'+$('#available').val())"><?=$this->lang->line('group_view_bottom_go')?></button>
                </td>
            </tr>
        </tfoot>
    </table>
</div>
<?php elseif ($STEP == 'add') : ?>
<form method="post">
    <table class="tbl" style="width:90%;">
        <thead>
            <tr>
                <th colspan="2"><?=$this->lang->line('group_view_add_new_group')?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?=$this->lang->line('group_view_group_name')?></td>
                <td><input type="text" required="required" name="name" /></td>
            </tr>
            <tr>
                <td><?=$this->lang->line('group_view_admin_group')?></td>
                <td><input type="radio" name="admin" checked="checked" value="1" /><?=$this->lang->line('group_view_radio_yes')?> <input type="radio" name="admin" value="0" /><?=$this->lang->line('group_view_radio_no')?></td>
            </tr>
            <?php if($ERROR): ?>
                <tr>
                    <td colspan="2"><?=$ERR_MSG?></td>
                </tr>
            <?php endif; ?>
            <tr>
                <td colspan="2"><input type="submit" value="<?=$this->lang->line('global_add')?>" /></td>
            </tr>
        </tbody>
    </table>
</form>
<?php elseif ($STEP == 'edit') : ?>
<form method="post">
    <table class="tbl" style="width:90%;">
        <thead>
            <tr>
                <th colspan="2"><?=$this->lang->line('group_view_edit_group')?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?=$this->lang->line('group_view_group_name')?></td>
                <td><input type="text" name="name" value="<?=$GROUPNAME?>" /></td>
            </tr>
            <tr>
                <td><?=$this->lang->line('group_view_admin_group')?></td>
                <td><input type="radio" name="admin"<?=($GROUPADMIN)? ' checked="checked"' :''?> value="1" /><?=$this->lang->line('group_view_radio_yes')?> <input type="radio" name="admin"<?=(!$GROUPADMIN)? ' checked="checked"' :''?> value="0" /><?=$this->lang->line('group_view_radio_no')?></td>
            </tr>
            <?php if($ERROR): ?>
                <tr>
                    <td colspan="2"><?=$ERR_MSG?></td>
                </tr>
            <?php endif; ?>
            <tr>
                <td colspan="2"><input type="submit" value="<?=$this->lang->line('global_edit')?>" /></td>
            </tr>
        </tbody>
    </table>
</form>
<?php elseif ($STEP == 'permission'): ?>
<div id="action" class="message" style="display:none"></div>
<div class="demo_jui">
    <table class="tbl" id="list" dataajax="permission/<?=$GROUPID?>" style="width:100%;">
        <thead>
            <tr>
                <th colspan="3"><?=$this->lang->line('group_view_permission_spec')?> <?=$GROUPNAME?></th>
                <th><img id="addNewPermission" val="open" src="<?=base_url()?>style/default/icon/add.png" alt="<?=$this->lang->line('group_view_permission_add')?>" /></th>
            </tr>
            <tr>
                <th><?=$this->lang->line('group_view_service_name')?></th>
                <th><?=$this->lang->line('group_view_service_type')?></th>
                <th><?=$this->lang->line('group_view_value')?></th>
                <th><?=$this->lang->line('group_view_control')?></th>
            </tr>
        </thead>
        <tbody>
            
        </tbody>
        <tfoot>
            <tr id="addnewHidden" style="display:none">
                <td><input type="hidden" id="groupId" value="<?=$GROUPID?>" />
                    <select id="service_name" name="service_name">
                        <option value="" disabled="disabled"><?=$this->lang->line('group_view_choess_service')?></option>
                        <?php if(isset($SERVICES)):?>
                            <?php foreach ($SERVICES as $key => $value): ?>
                                <option value="<?=$key?>"><?=$value?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </td>
                <td>
                    <select id="functions" name="functions">
                        <option disabled="disabled"><?=$this->lang->line('group_view_loading')?></option>
                    </select>
                </td>
                <td>
                    <select id="value" name="value">
                        <option value="all"><?=$this->lang->line('global_everyone')?></option>
                    </select>
                </td>
                <td>
                    <button id="addButton"><?=$this->lang->line('global_add')?></button>
                </td>
            </tr>
        </tfoot>
    </table>
</div>
<?php endif; ?>

