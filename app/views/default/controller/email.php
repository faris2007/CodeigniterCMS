<?php if( $STEP == "show"): ?>
<div class="demo_jui">
    
    <table id="list" class="tbl" style="width:100%;direction:rtl">
        <thead>
            <tr>
                <th colspan="3">أستعراض الرسائل</th>
                <th><a href="<?=base_url()?>email/send"><img src="<?=$STYLE_FOLDER?>icon/add.png" alt="رسالة جديدة" title="رسالة جديدة" /></a></th>
            </tr>
            <tr>
                <th>#</th>
                <th>عنوان الرسالة</th>
                <th>تاريخ الرسالة</th>
                <th>التحكم</th>
            </tr>
        </thead>
        <tbody>
            <?php if(is_array($EAMILS)): ?>
                <?php foreach ($EAMILS as $row): ?>
                    <tr id="course<?=$row->id?>">
                        <td><?=$row['sort_id']?></td>
                        <td><a href="<?=base_url()?>email/view/<?=trim($row['header']->Msgno)?>"><?=$row['header']->subject?></a></td>
                        <td><?=$row['header']->date?></td>
                        <td>
                            <a href="<?=base_url()?>email/send/replay/<?=trim($row['header']->Msgno)?>"><img src="<?=$STYLE_FOLDER?>icon/replay.png" alt="رد" title="رد" /></a>
                            <a href="<?=base_url()?>email/send/forward/<?=trim($row['header']->Msgno)?>"><img src="<?=$STYLE_FOLDER?>icon/forward.png" alt="أعادة توجيه" title="أعادة توجيه" /></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php elseif($STEP == 'view'): ?>
<div class="message">
    <ul>
        <li>العنوان : <?=$MSG_SUBJECT?></li>
        <li>المرسل : <?=$MSG_FROM?></li>
        <li>التاريخ : <?=$MSG_DATE?></li>
        <li>إلى : <?=$MSG_TO?></li>
    </ul>
</div>
<div class="main-tbl">
    <?=$MSG_BODY?>
</div>
<?php if($attach): ?>
    <div class="message">
        المرفقات:
        <ul>
            <?php foreach ($attach as $key => $value): ?>
            <li><a href="<?=base_url()?>email/getattach/<?=$MSG_ID?>/<?=$key?>"><?=$value?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<?php elseif($STEP == 'send'): ?>
<form method="post">
    <table class="tbl" style="width:95%">
        <thead>
            <tr>
                <td colspan="2">ارسال رسالة</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>العنوان :</td>
                <td><input type="text" name="subject" required="required" value="<?=$MAILSUBJECT?>" /></td>
            </tr>
            <tr>
                <td>ايميل المرسل إليه :</td>
                <td><input type="text" name="to" required="required" value="<?=$MAILTO?>" /></td>
            </tr>
            <tr>
                <td colspan="2">الرسالة :</td>
            </tr>
            <tr>
                <td colspan="2">
                    <textarea style="width:100%" name="message">
                        <?=$MAILBODY?>
                    </textarea>
                </td>
            </tr>
            <?php if($ERROR): ?>
                <tr>
                    <td colspan="2"><?=$ERR_MSG?></td>
                </tr>
            <?php endif; ?>
            <tr>
                <td colspan="2"><input type="submit" value="أرسال" /></td>
            </tr>
        </tbody>
    </table>
</form>
<?php elseif($STEP == 'error'): ?>
<div class="message">
    عفوا هنا خطأ في بيانات الايميل الرجاء التأكد من صحة البيانات و من ثم العودة 
</div>
<?php endif; ?>

