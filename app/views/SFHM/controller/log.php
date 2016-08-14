<?php if($STEP == 'init'):?>
<form method="post">
    <table class="tbl" style="width:100%;">
        <thead>
            <tr>
                <td colspan="2"><?=$this->lang->line('log_view_log')?></td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?=$this->lang->line('log_view_member_name')?></td>
                <td>
                    <select name="users">
                        <option value="all"><?=$this->lang->line('global_everyone')?></option>
                        <?php if(isset($USERS)): ?>
                            <?php foreach ($USERS as $row): ?>
                                <option value="<?=$row->id?>"><?=$row->username?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><?=$this->lang->line('log_view_time')?></td>
                <td>
                    <select name="time">
                        <option value="all"><?=$this->lang->line('log_view_all_log')?></option>
                        <option value="day"><?=$this->lang->line('log_view_per_day')?></option>
                        <option value="month"><?=$this->lang->line('log_view_per_month')?></option>
                        <option value="year"><?=$this->lang->line('log_view_per_year')?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" value="<?=$this->lang->line('log_view_show_log')?>" /></td>
            </tr>
        </tbody>
    </table>
</form>
<?php elseif($STEP == 'view'): ?>
<div class="demo_jui">
    <table id="list" class="tbl" style="width:100%;">
        <thead>
            <tr>
                <td colspan="5"><?=$this->lang->line('log_view_show_log')?></td>
            </tr>
            <tr>
                <th>#</th>
                <th><?=$this->lang->line('log_view_date')?></th>
                <th><?=$this->lang->line('log_view_active')?></th>
                <th><?=$this->lang->line('log_view_ip')?></th>
                <th><?=$this->lang->line('log_view_member')?></th>
            </tr>
        </thead>
        <tbody>
            <?php if(is_array($LOGS)): ?>
                <?php foreach($LOGS as $row): ?>
            <tr>
                <td><?=$row->id?></td>
                <td><?=date('Y-m-d H:i',$row->date)?></td>
                <td><?=$row->activity?></td>
                <td><?=$row->ip?></td>
                <td><?=$this->users->getUsername($row->users_id)?></td>
            </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

