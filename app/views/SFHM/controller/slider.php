<?php if($STEP == 'show'): ?>
<div id="action" class="message" style="display:none"></div>
<form method="post">
    <div class="demo_jui">
        <table id="list" class="tbl" style="width:100%;direction:rtl">
            <thead>
                <tr>
                    <th colspan="4"><?=$this->lang->line('slider_view_show_item')?></th>
                    <th><a href="<?=base_url()?>slider/add"><img src="<?=base_url()?>style/default/icon/add.png" alt="<?=$this->lang->line('slider_view_add_new_item')?>" title="<?=$this->lang->line('slider_view_add_new_item')?>" /></a></th>
                </tr>
                <tr>
                    <th>#</th>
                    <th><?= $this->lang->line('slider_view_name')?></th>
                    <th><?=$this->lang->line('slider_view_sort')?></th>
                    <th><?= $this->lang->line('slider_view_is_active')?> </th>
                    <th><?=$this->lang->line('slider_view_contrle')?></th>
                </tr>
            </thead>
            <tbody>
                <?php if(is_array($SLIDERS)): ?>
                    <?php foreach ($SLIDERS as $row): ?>
                        <tr id="slider<?=$row->id?>">
                            <td><?=$row->id?></td>
                            <td><?=($row->isDelete == 1)? "<strike>".$row->slider_name."</strike>":$row->slider_name?></td>
                            <td><input type="text" style="width:30px;" name="slider_<?=$row->id?>" value="<?=$row->sort_id?>" /></td>
                            <td><img id='enable<?=$row->id?>' src="<?=base_url()?>style/default/icon/<?=($row->isHidden == 0)? 'en':'dis'?>able.png" onclick="action('<?=base_url()?>slider/action/<?=($row->isHidden == 1)? 'enable':'disable'?>/<?=$row->id?>','<?=($row->isHidden == 1)? 'enable':'disable'?>','enable<?=$row->id?>','<?=$row->id?>')" alt="<?=($row->isHidden == 1)? $this->lang->line('global_enable'):$this->lang->line('global_disable')?>" title="<?=($row->isHidden == 1)? $this->lang->line('global_enable'):$this->lang->line('global_disable')?>" /></td>
                            <td>
                                <a href="<?=base_url()?>slider/edit/<?=$row->id?>"><img src="<?=base_url()?>style/default/icon/edit.png" alt="<?=$this->lang->line('global_edit')?>" title="<?=$this->lang->line('global_edit')?>" /></a>
                                <img id="deleteimg<?=$row->id?>" src="<?=base_url()?>style/default/icon/<?=($row->isDelete == 1)? 'restore':'del'?>.png" onclick="action('<?=base_url()?>slider/action/<?=($row->isDelete == 1)? 'restore':'delete'?>/<?=$row->id?>','<?=($row->isDelete == 1)? 'restore':'delete'?>','slider<?=$row->id?>','<?=$row->id?>')" alt="<?=($row->isDelete == 1)? $this->lang->line('slider_view_restore'):$this->lang->line('slider_view_delete')?>" title="<?=($row->isDelete == 1)? $this->lang->line('slider_view_restore'):$this->lang->line('slider_view_delete')?>" />
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2"></td>
                    <td><input type="submit" value="<?=$this->lang->line('global_save')?>" /></td>
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <td colspan="5"><?= $this->lang->line('slider_view_depent_on')?>
                        <select onchange="window.location.assign('<?=base_url()?>slider/show/'+$('#available').val())" id="available">
                            <option<?=($FILTER == 'all')? ' selected="selected"':''?> value="all"><?= $this->lang->line('slider_view_all')?></option>
                            <option<?=($FILTER == 'enable')? ' selected="selected"':''?> value="enable"><?= $this->lang->line('slider_view_active_item')?></option>
                            <option<?=($FILTER == 'disable')? ' selected="selected"':''?> value="disable"><?= $this->lang->line('slider_view_unactive_item')?></option>
                            <option<?=($FILTER == 'delete')? ' selected="selected"':''?> value="delete"><?= $this->lang->line('slider_view_deleted_pic') ?></option>
                            <option<?=($FILTER == 'undelete')? ' selected="selected"':''?> value="undelete"><?= $this->lang->line('slider_view_undeleted_pic') ?></option>
                        </select>
                    </td>
                </tr>
            </tfoot>
        </table>
        <div class="message">
            <img src="<?=base_url()?>style/default/icon/enable.png" /> <?=$this->lang->line('slider_view_show_if_active')?>
            | <img src="<?=base_url()?>style/default/icon/disable.png" /> <?=$this->lang->line('slider_view_show_if_unactive')?>
            <br />
            <?=$this->lang->line('slider_view_enable_disable')?>
        </div>
    </div>
</form>
<?php elseif ($STEP == 'add') : ?>
<form method="post" enctype="multipart/form-data">
    <table class="tbl" style="width:90%;direction:rtl">
        <thead>
            <tr>
                <th colspan="2"><?=$this->lang->line('slider_view_add_new_item')?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= $this->lang->line('slider_view_pic_name')?></td>
                <td><input type="text" required name="name" /></td>
            </tr>
            <tr>
                <td><?=$this->lang->line('global_url')?></td>
                <td><input type="text" required name="url" id="url" /></td>
            </tr>
            <tr>
                <td><?=$this->lang->line('slider_view_pic')?></td>
                <td><input type="file" name="userfile" /></td>
            </tr>
            <tr>
                <td><?= $this->lang->line('slider_view_descrption')?></td>
                <td><textarea name="desc" required>

                    </textarea></td>
            </tr>
            <tr>
                <td><?=$this->lang->line('slider_view_sort')?></td>
                <td><input type="text" required name="sort" value="1" /></td>
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
    <table class="tbl" style="width:90%;direction:rtl">
        <thead>
            <tr>
                <th colspan="2"><?=$this->lang->line('global_edit')?>  </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= $this->lang->line('slider_view_pic_name')?></td>
                <td><input type="text" value="<?=$SLIDER_NAME?>" required name="name" /></td>
            </tr>
            <tr>
                <td><?=$this->lang->line('global_url')?></td>
                <td><input type="text" value="<?=$SLIDER_URL?>" required name="url" id="url" /></td>
            </tr>
            <tr>
                <td><?=$this->lang->line('slider_view_pre_pic')?></td>
                <td><a target="_blank" href="<?=$SLIDER_PICTURE?>"> <?=$this->lang->line('slider_view_click')?></a></td>
            </tr>
            <tr>
                <td> <?=$this->lang->line('slider_view_update_pic')?></td>
                <td><input type="radio" name="update" onclick="$('#userfile').attr('disabled', false);" value="1" /><?=$this->lang->line('global_yes')?> <input type="radio" name="update" value="0"  onclick="$('#userfile').attr('disabled', true);" checked="checked" /><?=$this->lang->line('global_no')?></td>
            </tr>
            <tr>
                <td><?=$this->lang->line('slider_view_pic')?></td>
                <td><input type="file" name="userfile" id="userfile" disabled="disabled" /></td>
            </tr>
            <tr>
                <td><?= $this->lang->line('slider_view_descrption')?></td>
                <td>
                    <textarea name="desc"  required>
                        <?=$SLIDER_DESC?>
                    </textarea>
                </td>
            </tr>
            <tr>
                <td><?=$this->lang->line('slider_view_sort')?></td>
                <td><input type="text" required name="sort" value="<?=$SLIDER_SORT?>" /></td>
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

