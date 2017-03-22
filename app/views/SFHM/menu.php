<div id="navigation">
<ul class="nav">
    <li><a href="<?=base_url()?>"<?=($ISHOME)? ' class="current"':''?>><?=$this->lang->line('global_mainpage')?></a></li>
    <?php if(isset($MAINMENU) && is_array($MAINMENU)): $i = count($MAINMENU);?>
        <?php for($j = 0;$j < $i ;$j++): ?>
            <?php if($j < 6):?> 
                <li><?=$MAINMENU[$j]?></li>
            <?php else: ?>
                <li class="lastitem"><?=$this->lang->line('global_menu_more')?>
                    <ul>
                    <?php for($d = $j;$d < $i; $d++): ?>
                        <li><?=$MAINMENU[$d]?></li>
                    <?php endfor; ?>
                    </ul>
                </li>
            <?php break; endif; ?>
        <?php endfor; ?>
    <?php endif; ?>
</ul>
<div class="clear"></div>
</div>