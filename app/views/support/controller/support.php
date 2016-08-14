<?php if($STEP == 'finish') : ?>
<div class="demo_jui">
    <table id="list" class="tbl" style="width: 820px;">
    <thead>
        <tr>
            <th colspan="9"><?=$this->lang->line('support_view_finish') ?></th>
        </tr>
        <tr>
            <th>#</th>
            <th><?=$this->lang->line('support_view_problem') ?></th>
            <th><?=$this->lang->line('support_view_dept') ?></th>
            <th><?=$this->lang->line('support_view_receiver') ?></th>
            <th><?=$this->lang->line('support_view_priority') ?></th>
            <th><?=$this->lang->line('support_view_date') ?> &AMP; <?=$this->lang->line('support_view_time') ?></th>
            <th> <?=$this->lang->line('support_view_done') ?></th>
        </tr>
    </thead>
    <tbody>
        <?php if(is_array($finishedProblems)): ?>
            <?php foreach ($finishedProblems as $row): ?>
                <tr>
                    <td><?=$row->id?></td>
                    <td><?=$row->type?></td>
                    <td><?=$row->dept_name?></td>
                    <td><?=$row->receiver?></td>
                    <td><?=$row->priority?></td>
                    <td><?=$row->_date?> | <?=$row->_time?></td>
                    <td><?=$row->done?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
</div>
<?php elseif($STEP == 'add'): ?>
    <form method="post">
        <table class="tbl" style="width:80%;margin-top: 20px;">
            <thead>
                <tr>
                    <td colspan="2">Add new problem</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Ext. :</td>
                    <td><input type="number" name="ext" maxlength="4" /></td>
                </tr>
                <tr>
                    <td>Problem type</td>
                    <td>
                        <select name="type">
                            <option value="PC">PC</option>
                            <option value="Printer">Printer</option>
                            <option value="Scanner">Scanner</option>
                            <option value="Label Printer">Label Printer</option>
                            <option value="Network">Network</option>
                            <option value="Email">Email</option>
                            <option value="HIS">HIS</option>
                            <option value="Pacs">Pacs</option>
                            <option value="Internet">Internet</option>
                            <option value="Other">Other</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Department : </td>
                    <td>
                        <select name="cat">
                            <?php if(is_array($DEPARTMENTS)): ?>
                                <?php foreach ($DEPARTMENTS as $row): ?>
                                    <option value="<?=$row->id?>"><?=$row->dept_name?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Priority</td>
                    <td>
                        <select name="priority">
                            <option value="H">High</option>
                            <option value="N">Normal</option>
                            <option value="L">Low</option>
                        </select>
                    </td>
                </tr>
                <?php if(@$ERROR): ?>
                    <tr>
                        <td colspan="2"><?=$ERR_MSG?></td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td colspan="2"><input type="submit" value="Add"/></td>
                </tr>
            </tbody>
        </table>
    </form>
<?php elseif ($STEP == 'startReport') : ?>
<table style="width:90%" class="tbl">
    <thead>
        <tr>
            <th colspan="2">Chose Type of Report</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Report By :</td>
            <td>
                <select name="type" id="type">
                    <option value="users">Users</option>
                    <option value="type">Problem Type</option>
                    <option value="dept">Department</option>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2"><button onclick="window.location.assign('<?=base_url()?>support/report/'+$('#type').val())">Go</button></td>
        </tr>
    </tbody>
</table>
<?php elseif($STEP == 'report'): ?>
<table id="list" style="width: 820px" class="tbl">
    <thead>
        <tr>
            <th><?=$TYPE?></th>
            <th>Value</th>
        </tr>
    </thead>
    <tbody>
        <?php if(is_array($REPORTS)): ?>
            <?php foreach ($REPORTS as $row): ?> 
                <tr>
                    <td><?=$row->type?></td>
                    <td><?=$row->count?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
<?php else: ?>
<div class="demo_jui">
<table id="list" style="width: 100%" class="tbl">
    <thead>
        <tr>
            <th colspan="10">New Problems</th>
        </tr>
        <tr>
            <th>#</th>
            <th>Problem</th>
            <th>Department</th>
            <th>Extension</th>
            <th>Priority</th>
            <th>Date &AMP; Time</th>
            <th>Receiver</th>
            <th>Control</th>
        </tr>
    </thead>
    <tbody>
        <?php if(is_array($problems)): ?>
            <?php foreach ($problems as $row): ?>
                <tr>
                    <td><?=$row->id?></td>
                    <td><?=$row->type?></td>
                    <td><?=$row->dept_name?></td>
                    <td><?=$row->ext?></td>
                    <td><?=$row->priority?></td>
                    <td><?=$row->_date?> | <?=$row->_time?></td>
                    <td><?=$row->receiver?></td>
                    <td><button onclick="window.location.assign('<?=base_url()?>support/finish/<?=$row->id?>')">Done</button></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
</div>
<?php endif; ?>