<?php if($STEP == 'show'): ?>
<div id="action" class="message" style="display:none"></div>
<form method="post">
    <div class="demo_jui">
        <table id="list" class="tbl" style="width:100%;">
            <thead>
                <tr>
                    <th colspan="4"><?= $this->lang->line('menu_view_show_menu');?> <?=$TYPEMENU?></th>
                    <th><a href="<?=base_url()?>menu/add<?=(is_null($PARENTMENU))? '':'/'.$PARENTMENU?>"><img src="<?=base_url()?>style/default/icon/add.png" alt= "<?$this->lang->line('menu_add_new_menu')?>"  title= "<?$this->lang->line('menu_add_new_menu')?> " /></a></th>
                </tr>
                <tr>
                    <th>#</th>
                    <th><?= $this->lang->line("menu_view_address"); ?></th>
                    <th><?= $this->lang->line('menu_view_sort'); ?></th>
                    <th><?= $this->lang->line('menu_view_activied'); ?></th>
                    <th><?= $this->lang->line('menu_view_controll'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if(is_array($MENUS)): ?>
                    <?php foreach ($MENUS as $row): ?>
                        <tr id="menu<?=$row->id?>">
                            <td><?=$row->id?></td>
                            <td><?=($row->isDelete == 1)? "<strike>".$row->title."</strike>":$row->title?></td>
                            <td><input type="text" style="width:30px;" name="menu_<?=$row->id?>" value="<?=$row->sort_id?>" /></td>
                            <td><img id='enable<?=$row->id?>' src="<?=base_url()?>style/default/icon/<?=($row->isHidden == 0)? 'en':'dis'?>able.png" onclick="action('<?=base_url()?>menu/action/<?=($row->isHidden == 1)? 'enable':'disable'?>/<?=$row->id?>','<?=($row->isHidden == 1)? 'enable':'disable'?>','enable<?=$row->id?>','<?=$row->id?>')" alt="<?=($row->isHidden == 1)? $this->lang->line('global_enable'):$this->lang->line('global_disable')?>" title="<?=($row->isHidden == 1)? $this->lang->line('global_enable'):$this->lang->line('global_disable')?>" /></td>
                            <td>
                                <a href="<?=base_url()?>menu/edit/<?=$row->id?>"><img src="<?=base_url()?>style/default/icon/edit.png" alt="<?= $this->lang->line('global_edit')?>" title="<?= $this->lang->line('global_edit'); ?>" /></a>
                                <a href="<?=base_url()?>menu/show/<?=$row->id?>"><img src="<?=base_url()?>style/default/icon/show.png" alt="<?= $this->lang->line('menu_view_whatch_content')?>" title="<?= $this->lang->line('menu_view_whatch_content')?>" /></a>
                                <img id="deleteimg<?=$row->id?>" src="<?=base_url()?>style/default/icon/<?=($row->isDelete == 1)? 'restore':'del'?>.png" onclick="action('<?=base_url()?>menu/action/<?=($row->isDelete == 1)? 'restore':'delete'?>/<?=$row->id?>','<?=($row->isDelete == 1)? 'restore':'delete'?>','menu<?=$row->id?>','<?=$row->id?>')" alt="<?=($row->isDelete == 1)? $this->lang->line('global_restore'):$this->lang->line('global_delete')?>" title="<?=($row->isDelete == 1)? $this->lang->line('global_restore'):$this->lang->line('global_delete')?>" />
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2"></td>
                    <td><input type="submit" value="<?= $this->lang->line('global_save') ?>" /></td>
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <td colspan="5"><?=$this->lang->line('menu_view_whatch_content') // here?>
                        <select onchange="window.location.assign('<?=base_url()?>menu/show<?=(is_null($PARENTMENU))? '/all':'/'.$PARENTMENU?>/'+$('#available').val())" id="available">
                            <option<?=($FILTER == 'all')? ' selected="selected"':''?> value="all"><?= $this->lang->line('global_everyone')?></option>
                            <option<?=($FILTER == 'enable')? ' selected="selected"':''?> value="enable"><?= $this->lang->line('menu_view_active_menu')?></option>
                            <option<?=($FILTER == 'disable')? ' selected="selected"':''?> value="disable"><?= $this->lang->line('menu_view_deactive_menu')?></option>
                            <option<?=($FILTER == 'delete')? ' selected="selected"':''?> value="delete"><?= $this->lang->line('menu_view_delete')?></option>
                            <option<?=($FILTER == 'undelete')? ' selected="selected"':''?> value="undelete"><?= $this->lang->line('menu_view_undelete')?></option>
                        </select>
                        <?= $this->lang->line('menu_view_lang_menu') ?> 
                        <select onchange="window.location.assign('<?=base_url()?>menu/show<?=(is_null($PARENTMENU))? '/all':'/'.$PARENTMENU?>/'+$('#langvalue').val())" id="langvalue">
                            <option<?=($FILTER == 'all')? ' selected="selected"':''?> value="all"><?=$this->lang->line('global_langc') ?></option>
                            <?php if($LANGS): ?>
                                <?php foreach ($LANGS as $row): ?>
                                    <option<?=($FILTER == $row->ext)? ' selected="selected"':''?> value="lang/<?=$row->ext?>"><?=$row->name?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </td>
                </tr>
            </tfoot>
        </table>
        <div class="message">
            <img src="<?=base_url()?>style/default/icon/enable.png" /> 
            | <img src="<?=base_url()?>style/default/icon/disable.png" /><?= $this->lang->line('menu_view_if_active')?>
            <br />
            <?= $this->lang->line('menu_view_control_activtion')?>
        </div>
    </div>
</form>
<?php elseif ($STEP == 'add') : ?>
<form method="post">
    <table class="tbl" style="width:90%;">
        <thead>
            <tr>
                <th colspan="2"><?= $this->lang->line('menu_view_new_menu')?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= $this->lang->line('menu_view_address') ?></td>
                <td><input type="text" name="title" /></td>
            </tr>
            <tr>
                <td><?= $this->lang->line('menu_view_url_type')?></td>
                <td>
                    <select name="type_url" id="type_url">
                        <option value="external"> <?= $this->lang->line('menu_view_external_url')?></option>
                        <option value="page"><?= $this->lang->line('menu_view_page')?></option>
                    </select>
                </td>
            </tr>
            <tr id="select_page">
                <td><?=$this->lang->line('menu_view_select_page')?></td>
                <td>
                    <select name="page_num" id="page_num">
                        <option selected="selected" value="" disabled="disabled"><?=$this->lang->line('menu_view_select_spic_page')?></option>
                        <?php if(is_array($PAGES)): ?>
                            <?php foreach($PAGES as $row): ?>
                                <option value="<?=$row->id?>"><?=$row->title?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><?= $this->lang->line('menu_view_url')?></td>
                <td><input type="text" name="url" id="url" disabled="disabled" /></td>
            </tr>
            <tr>
                <td><?= $this->lang->line('global_language')?></td>
                <td>
                    <select name="lang" id="lang">
                        <option selected="selected" value=""><?= $this->lang->line('global_selected_language')?> </option>
                        <?php if(is_array($LANGS)): ?>
                            <?php foreach($LANGS as $row): ?>
                                <option value="<?=$row->id?>"><?=$row->name?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><?= $this->lang->line('menu_view_sort')?></td>
                <td><input type="text" name="sort" /></td>
            </tr>
            <?php if($ERROR): ?>
                <tr>
                    <td colspan="2"><?=$ERR_MSG?></td>
                </tr>
            <?php endif; ?>
            <tr>
                <td colspan="2"><input type="submit" value="<?= $this->lang->line('global_add')?>" /></td>
            </tr>
        </tbody>
    </table>
</form>
<?php elseif ($STEP == 'edit') : ?>
<form method="post">
    <table class="tbl" style="width:90%;">
        <thead>
            <tr>
                <th colspan="2"><?= $this->lang->line('menu_view_edit_menu')?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= $this->lang->line('menu_view_address')?></td>
                <td><input type="text" name="title" value="<?=$MENUTITLE?>" /></td>
            </tr>
            <tr>
                <td><?= $this->lang->line('menu_view_url_type')?></td>
                <td>
                    <select name="type_url" id="type_url">
                        <option value="external"><?= $this->lang->line('menu_view_external_url')?></option>
                        <option value="page"><?= $this->lang->line('menu_view_page')?></option>
                    </select>
                </td>
            </tr>
            <tr id="select_page">
                <td><?= $this->lang->line('menu_view_select_page')?></td>
                <td>
                    <select name="page_num" id="page_num">
                        <option selected="selected" value="" disabled="disabled"><?= $this->lang->line('menu_view_select_spic_page')?></option>
                        <?php if(is_array($PAGES)): ?>
                            <?php foreach($PAGES as $row): ?>
                                <option value="<?=$row->id?>"><?=$row->title?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </td>
            </tr>
            <tr id="select_url">
                <td><?= $this->lang->line('global_url')?></td>
                <td><input type="text" name="url" id="url" value="<?=$MENUURL?>" /></td>
            </tr>
            <tr>
                <td><?= $this->lang->line('global_language')?></td>
                <td>
                    <select name="lang" id="lang">
                        <option selected="selected" value=""><?= $this->lang->line('global_selected_language')?></option>
                        <?php if(is_array($LANGS)): ?>
                            <?php foreach($LANGS as $row): ?>
                                <option<?=($MENULANG == $row->id)? ' selected="selected"':''?> value="<?=$row->id?>"><?=$row->name?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><?= $this->lang->line('menu_view_sort')?></td>
                <td><input type="text" name="sort" value="<?=$MENUSORT?>" /></td>
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
<?php endif; ?>

