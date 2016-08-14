<?php if($STEP == 'show'): ?>
<div id="action" class="message" style="display:none"></div>
<div class="demo_jui">
    <table id="list" class="tbl" style="width:100%;">
        <thead>
            <tr>
                <th colspan="3"><?=$this->lang->line('lang_view_show_languages')?></th>
                <th><a href="<?=base_url()?>langc/add"><img src="<?=$STYLE_FOLDER?>css/icon/add.png" alt="<?=$this->lang->line('lang_view_add_new_language')?>" title="<?=$this->lang->line('lang_view_add_new_language')?>" /></a></th>
            </tr>
            <tr>
                <th>#</th>
                <th><?=$this->lang->line('lang_view_language_name')?></th>
                <th><?=$this->lang->line('lang_view_language_ext')?></th>
                <th><?=$this->lang->line('lang_view_language_control')?></th>
            </tr>
        </thead>
        <tbody>
            <?php if(is_array($LANGS)): ?>
                <?php foreach ($LANGS as $row): ?>
                    <tr id="langc<?=$row->id?>">
                        <td><?=$row->id?></td>
                        <td><?=$row->name?></td>
                        <td><?=$row->ext?></td>
                        <td>
                            <a href="<?=base_url()?>langc/edit/<?=$row->id?>"><img src="<?=$STYLE_FOLDER?>css/icon/edit.png" alt="<?=$this->lang->line('global_edit')?>" title="<?=$this->lang->line('global_edit')?>" /></a>
                            <img id="deleteimg<?=$row->id?>" src="<?=$STYLE_FOLDER?>css/icon/del.png" onclick="action('<?=base_url()?>langc/action/delete/<?=$row->id?>','delete','langc<?=$row->id?>','<?=$row->id?>')" alt="<?=$this->lang->line('global_delete')?>" title="<?=$this->lang->line('global_delete')?>" />
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php elseif ($STEP == 'add') : ?>
<form method="post">
    <table class="tbl" style="width:90%;">
        <thead>
            <tr>
                <th colspan="2"><?=$this->lang->line('lang_view_add_new_language')?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?=$this->lang->line('lang_view_language_label_name')?></td>
                <td><input type="text" required="required" name="name" /></td>
            </tr>
            <tr>
                <td><?=$this->lang->line('lang_view_language_label_ext')?></td>
                <td><input type="text" required="required" name="ext" maxlength="2" /></td>
            </tr>
            <tr>
                <td><?=$this->lang->line('lang_view_language_folder')?></td>
                <td>
                    <select name="folder">
                        <?php if($FOLDER): ?>
                            <?php foreach ($FOLDER as $value): ?>
                                <option value="<?=$value?>"><?=$value?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </td>
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
                <th colspan="2"><?=$this->lang->line('lang_view_language_edit')?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?=$this->lang->line('lang_view_language_label_name')?></td>
                <td><input type="text" required="required" name="name" value="<?=$LANGNAME?>" /></td>
            </tr>
            <tr>
                <td><?=$this->lang->line('lang_view_language_label_ext')?></td>
                <td><input type="text" required="required" name="ext" value="<?=$LANGEXT?>" maxlength="2" /></td>
            </tr>
            <tr>
                <td><?=$this->lang->line('lang_view_language_folder')?></td>
                <td>
                    <select name="folder">
                        <?php if($FOLDER): ?>
                            <?php foreach ($FOLDER as $value): ?>
                                <option<?=($LANGFOLDER == $value)? ' selected="selected"':''?> value="<?=$value?>"><?=$value?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </td>
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

