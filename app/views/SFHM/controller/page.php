<?php if($STEP == 'view'): ?>
<div>
    <?=$CONTENTPAGE?>
</div>
<?php if(!isset($HOME)): ?>
<div id="relatedPage">
    <h4><?=$this->lang->line('page_view_related_page')?></h4>
    <ul>
        <li><a href="<?=base_url()?>page/view/<?=$PAGEID?>"><?=$TITLEPAGE?></a></li>
        <?php if(is_array($RELATEDPAGES)): ?>
            <?php foreach ($RELATEDPAGES as $row): ?>
                <li><a href="<?=base_url()?>page/view/<?=$row->id?>"><?=$row->title?></a></li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</div>
<?php endif; ?>
<?php elseif($STEP == 'show'): ?>
<div id="action" class="message" style="display:none"></div>
<div class="demo_jui">
    <table id="list" class="tbl" style="width:100%;">
        <thead>
            <tr>
                <th colspan="3"><?=$this->lang->line('page_view_show_page')?></th>
                <th><a href="<?=base_url()?>page/add<?=(is_null($PARENTPAGE))? '':'/'.$PARENTPAGE?>"><img src="<?=base_url()?>style/default/icon/add.png" alt="<?=$this->lang->line('page_view_add_new_page')?>" title="<?=$this->lang->line('page_view_add_new_page')?>" /></a></th>
            </tr>
            <tr>
                <th>#</th>
                <th><?=$this->lang->line('page_view_address')?></th>
                <th><?=$this->lang->line('page_view_activied')?></th>
                <th><?=$this->lang->line('page_view_control')?></th>
            </tr>
        </thead>
        <tbody>
            <?php if(is_array($PAGES)): ?>
                <?php foreach ($PAGES as $row): ?>
                    <tr id="page<?=$row->id?>">
                        <td><?=$row->id?></td>
                        <td><?=($row->isDelete == 1)? "<strike>".$row->title."</strike>":$row->title?></td>
                        <td><img id="enable<?=$row->id?>" src="<?=base_url()?>style/default/icon/<?=($row->isHidden == 0)? 'en':'dis'?>able.png" onclick="action('<?=base_url()?>page/action/<?=($row->isHidden == 1)? 'enable':'disable'?>/<?=$row->id?>','<?=($row->isHidden == 1)? 'enable':'disable'?>','enable<?=$row->id?>','<?=$row->id?>')" alt="<?=($row->isHidden == 1)? $this->lang->line('global_enable'):$this->lang->line('global_disable')?>" title="<?=($row->isHidden == 1)? $this->lang->line('global_enable'):$this->lang->line('global_disable')?>" /></td>
                        <td>
                            <a href="<?=base_url()?>page/edit/<?=$row->id?>"><img src="<?=base_url()?>style/default/icon/edit.png" alt="<?=$this->lang->line('global_edit')?>" title="<?=$this->lang->line('global_edit')?>" /></a>
                            <a href="<?=base_url()?>page/view/<?=$row->id?>" target="_blank"><img src="<?=base_url()?>style/default/icon/view.png" alt="<?=$this->lang->line('page_view_page_view')?>" title="<?=$this->lang->line('page_view_page_view')?>" /></a>
                            <a href="<?=base_url()?>page/show/<?=$row->id?>"><img src="<?=base_url()?>style/default/icon/show.png" alt="<?=$this->lang->line('page_view_page_show_content')?>" title="<?=$this->lang->line('page_view_page_show_content')?>" /></a>
                            <img id="deleteimg<?=$row->id?>" src="<?=base_url()?>style/default/icon/<?=($row->isDelete == 1)? 'restore':'del'?>.png" onclick="action('<?=base_url()?>page/action/<?=($row->isDelete == 1)? 'restore':'delete'?>/<?=$row->id?>','<?=($row->isDelete == 1)? 'restore':'delete'?>','page<?=$row->id?>','<?=$row->id?>')" alt="<?=($row->isDelete == 1)? $this->lang->line('global_restore'):$this->lang->line('global_delete')?>" title="<?=($row->isDelete == 1)? $this->lang->line('global_restore'):$this->lang->line('global_delete')?>" />
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4"><?=$this->lang->line('page_view_show_result')?>
                    <select id="available" onchange="window.location.assign('<?=base_url()?>page/show<?=(is_null($PARENTPAGE))? '/all':'/'.$PARENTPAGE?>/'+$('#available').val())">
                        <option<?=($FILTER == 'all')? ' selected="selected"':''?> value="all"><?=$this->lang->line('page_view_show_result')?></option>
                        <option<?=($FILTER == 'enable')? ' selected="selected"':''?> value="enable"><?=$this->lang->line('page_view_enable')?></option>
                        <option<?=($FILTER == 'disable')? ' selected="selected"':''?> value="disable"><?=$this->lang->line('page_view_disable')?></option>
                        <option<?=($FILTER == 'delete')? ' selected="selected"':''?> value="delete"><?=$this->lang->line('page_view_delete')?></option>
                        <option<?=($FILTER == 'undelete')? ' selected="selected"':''?> value="undelete"><?=$this->lang->line('page_view_undelete')?></option>
                    </select> <?=$this->lang->line('page_view_lang_menu')?>
                    <select onchange="window.location.assign('<?=base_url()?>page/show<?=(is_null($PARENTPAGE))? '/all':'/'.$PARENTPAGE?>/'+$('#langvalue').val())" id="langvalue">
                        <option<?=($FILTER == 'all')? ' selected="selected"':''?> value="all"><?=$this->lang->line('global_everyone')?></option>
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
        <img src="<?=base_url()?>style/default/icon/enable.png" /> <?=$this->lang->line('page_view_enable_note')?>
        | <img src="<?=base_url()?>style/default/icon/disable.png" /> <?=$this->lang->line('page_view_disable_note')?>
        <br />
         <?=$this->lang->line('page_view_note')?>
    </div>
</div>
<?php elseif($STEP == 'add'): ?>
<form method="post">
    <table class="tbl" style="width: 100%;direction: rtl">
        <thead>
            <tr>
                <th colspan="2"><?=$this->lang->line('page_view_add_new_page')?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?=$this->lang->line('page_view_address')?></td>
                <td><input type="text" name="title" required="required" /></td>
            </tr>
            <tr>
                <td><?=$this->lang->line('page_view_page_lang')?></td>
                <td>
                    <select name="lang" id="lang">
                        <option selected="selected" value=""><?=$this->lang->line('page_view_page_chose_lang')?> ...</option>
                        <?php if(is_array($LANGS)): ?>
                            <?php foreach($LANGS as $row): ?>
                                <option value="<?=$row->id?>"><?=$row->name?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2"><?=$this->lang->line('page_view_page_content')?></td>
            </tr>
            <tr>
                <td colspan="2">
                    <textarea id="content" name="content" style="width: 100%"></textarea>
                </td>
            </tr>
            <tr>
                <td colspan="2"><?=$this->lang->line('page_view_search_engin')?></td>
            </tr>
            <tr>
                <td><?=$this->lang->line('page_view_keyword')?></td>
                <td><input type="text" name="keyword" /></td>
            </tr>
            <tr>
                <td><?=$this->lang->line('page_view_descrption')?></td>
                <td><input type="text" name="desc" /></td>
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
<?php elseif($STEP == 'edit'): ?>
<form method="post">
    <table class="tbl" style="width: 100%;direction: rtl">
        <thead>
            <tr>
                <th colspan="2"><?=$this->lang->line('page_view_edit_page')?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?=$this->lang->line('page_view_address')?></td>
                <td><input type="text" name="title" required="required" value="<?=$PAGETITLE?>" /></td>
            </tr>
            <tr>
                <td><?=$this->lang->line('page_view_page_lang')?></td>
                <td>
                    <select name="lang" id="lang">
                        <option selected="selected" value=""><?=$this->lang->line('page_view_page_chose_lang')?> ...</option>
                        <?php if(is_array($LANGS)): ?>
                            <?php foreach($LANGS as $row): ?>
                                <option<?=($PAGELANG == $row->id)? ' selected="selected"':''?> value="<?=$row->id?>"><?=$row->name?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2"><?=$this->lang->line('page_view_page_content')?></td>
            </tr>
            <tr>
                <td colspan="2">
                    <textarea id="content" name="content" style="width: 100%"><?=$PAGECONTENT?></textarea>
                </td>
            </tr>
            <tr>
                <td colspan="2"><?=$this->lang->line('page_view_search_engin')?></td>
            </tr>
            <tr>
                <td><?=$this->lang->line('page_view_keyword')?></td>
                <td><input type="text" name="keyword" value="<?=$PAGEKEY?>" /></td>
            </tr>
            <tr>
                <td><?=$this->lang->line('page_view_descrption')?></td>
                <td><input type="text" name="desc" value="<?=$PAGEDESC?>" /></td>
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
<?php elseif($STEP == 'error'): ?>
<div class="message">
    <?=$this->lang->line('page_view_error_page')?>
    <br />
    <a href="<?=base_url()?>"><?=$this->lang->line('page_view_return_home')?></a>
</div>
<?php endif; ?>

