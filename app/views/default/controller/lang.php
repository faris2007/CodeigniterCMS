<?php if($STEP == 'show'): ?>
<div id="action" class="message" style="display:none"></div>
<div class="demo_jui">
    <table id="list" class="tbl" style="width:100%;direction:rtl">
        <thead>
            <tr>
                <th colspan="3">عرض الأقسام</th>
                <th><a href="<?=base_url()?>langc/add"><img src="<?=$STYLE_FOLDER?>icon/add.png" alt="أضافة قسم جديدة" title="أضافة قسم جديدة" /></a></th>
            </tr>
            <tr>
                <th>#</th>
                <th>اللغة</th>
                <th>الأختصار</th>
                <th>التحكم</th>
            </tr>
        </thead>
        <tbody>
            <?php if(is_array($LANGS)): ?>
                <?php foreach ($LANGS as $row): ?>
                    <tr id="langc<?=$row->ID?>">
                        <td><?=$row->ID?></td>
                        <td><?=$row->Name?></td>
                        <td><?=$row->ext?></td>
                        <td>
                            <a href="<?=base_url()?>langc/edit/<?=$row->ID?>"><img src="<?=$STYLE_FOLDER?>icon/edit.png" alt="تعديل" title="تعديل" /></a>
                            <img id="deleteimg<?=$row->ID?>" src="<?=$STYLE_FOLDER?>icon/del.png" onclick="action('<?=base_url()?>langc/action/delete/<?=$row->ID?>','delete','langc<?=$row->ID?>','<?=$row->ID?>')" alt="حذف" title="حذف" />
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php elseif ($STEP == 'add') : ?>
<form method="post">
    <table class="tbl" style="width:90%;direction:rtl">
        <thead>
            <tr>
                <th colspan="2">أضافة لغة جديدة</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>أسم اللغة</td>
                <td><input type="text" required="required" name="name" /></td>
            </tr>
            <tr>
                <td>أختصار اللغة (اول حرفين من أسم اللغة بالأنجليزي)</td>
                <td><input type="text" required="required" name="ext" maxlength="2" /></td>
            </tr>
            <tr>
                <td>المجلد الخاص باللغة</td>
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
                <td colspan="2"><input type="submit" value="أضافة" /></td>
            </tr>
        </tbody>
    </table>
</form>
<?php elseif ($STEP == 'edit') : ?>
<form method="post">
    <table class="tbl" style="width:90%;direction:rtl">
        <thead>
            <tr>
                <th colspan="2">تعديل اللغة</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>أسم اللغة</td>
                <td><input type="text" required="required" name="name" value="<?=$LANGNAME?>" /></td>
            </tr>
            <tr>
                <td>أختصار اللغة (اول حرفين من أسم اللغة بالأنجليزي)</td>
                <td><input type="text" required="required" name="ext" value="<?=$LANGEXT?>" maxlength="2" /></td>
            </tr>
            <tr>
                <td>المجلد الخاص باللغة</td>
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
                <td colspan="2"><input type="submit" value="تعديل" /></td>
            </tr>
        </tbody>
    </table>
</form>
<?php endif; ?>

