<!-- slider -->
<div class="slider">
    <div class="slider-holder">
      <ul>
          <?php if(isset($SLIDERS)): ?>
                <?php foreach ($SLIDERS as $row): ?>
                    <li>
                        <div class="img">
                                <img src="<?=$row->picture?>" alt="<?=$row->slider_name?>" />
                        </div>
                    </li>   
                <?php endforeach; ?>
        <?php endif; ?>
        </ul>
    </div>
    <a href="#" class="slider-navigation prev notext">prev</a> <a href="#" class="slider-navigation next notext">next</a>
</div>	
    <!-- end of slider -->
