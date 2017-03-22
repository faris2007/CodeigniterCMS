<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title><?=$HEAD['TITLE']?></title>
    <link rel="stylesheet" href="<?=$STYLE_FOLDER?>css/jquery-ui.css" />
    <script type="text/javascript">
        <?=(@$DISABLE)?'' :"var Token = '".$this->core->token(TRUE)."';\n"?>
        var base_url = '<?=base_url()?>';
        var style_dir = '<?=$STYLE_FOLDER?>';
        var js_files = ["tinymce/tinymce.min","jquery","jquery.dataTables","jquery-ui","jquery.jcarousel.min","functions","jquery.popupWindow"];
        for (js_x in js_files){document.write('<script type="text/javascript" src="' + style_dir + '/js/' + js_files[js_x] + '.js"></' + 'script>');}
        document.write('<link type="text/css" rel="stylesheet" href="' + style_dir + '/css/style_<?=$LANG_EXT?>.css">');
    </script>
    <script type="text/javascript" src="<?=$STYLE_FOLDER?>js/jquery.validate.js"></script>
    
    <!--[if IE 6]>
    <style>
        body {behavior: url("PIE.htc");}
        #menu li .drop {background:url("img/drop.gif") no-repeat right 8px; 
    </style>
    <![endif]-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<?=meta($HEAD['META']['META'])?>
    <?=$HEAD['OTHER']?>
    <!--[if lt IE 8]> <link rel="stylesheet" href="<?=$STYLE_FOLDER?>css/style_IE_<?=$LANG_EXT?>.css" type="text/css" media="all" /> <![endif]-->
    <link rel="shortcut icon" type="image/x-icon" href="<?=$STYLE_FOLDER?>css/icon/favicon.ico" />
    <!--[if lt IE 9]>
                <script src="<?=$STYLE_FOLDER?>js/html5.js"></script>
            <![endif]-->
    <!--[if IE]>
    <script type="text/javascript" src="<?=$STYLE_FOLDER?>js/PIE/PIE.js"></script>
    <![endif]-->
    <!--[if lt IE 8]> <script src="<?=$STYLE_FOLDER?>js/IE8.js"></script> <![endif]-->
    <!--[if IE 6]><link rel="stylesheet" href="<?=$STYLE_FOLDER?>css/ie6.css" type="text/css" media="all" /><![endif]-->
</head>

<body>
    <!-- START PAGE SOURCE -->
<div class="shell">
  <div class="header">
      <h1 id="logo"><a href="."><img src ="<?=$STYLE_FOLDER?>css/images/sfh.png" class="logopic" /></a></h1>
    <div class="cl">
        &nbsp;
    </div>
  </div>
          <?=$MENU?>
        <?php if (@$NAV): ?>
        <div id="nav">
            <ul>
                <?php $i=1; foreach($NAV as $key => $value): ?>
                    <li><a href="<?=$key?>"><?=$value?></a></li>
                    <?php if($i != count($NAV)): ?>
                    <li class="arrow">&rsaquo;&rsaquo;</li>
                    <?php endif; ?>
                <?php $i++; endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>
        <div id="main_content">
            <?php if($ADMINMENU && $CONTENTFILE != 'msg'): ?>
                <div id="adminMenu">
                    <h4><?=$this->lang->line('admin_view_control_menu')?></h4>
                    <ul>
                        <?php foreach ($ADMINMENU as $key => $value):?>
                            <li><a href="<?=base_url().$key?>"><?=$value?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <div<?=(($CONTENTFILE != 'home' && !isset($HOME)) && $CONTENTFILE != 'login' && $CONTENTFILE != 'msg' && $CONTENTFILE != 'register')?' id="bodyContent"':''?>>
                <?=$CONTENT?><br />
            </div>
            <br />
            <div class="footer">
            <span class="lf">Copyright &copy; 2013 <a href="#">SFHP-Makkah</a> - All Rights Reserved</span>
            <span class="highlight">
                <select onchange="window.location.assign('?lang='+$('#lang_site').val())" id="lang_site">
                    <?php if($LANGS): ?>
                        <?php foreach ($LANGS as $row): ?>
                            <option<?=($LANG_EXT == $row->ext)? ' selected="selected"':''?> value="<?=$row->ext?>"><?=$row->name?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>   |
                <a href="<?=base_url()?>"><?=$this->lang->line('global_mainpage')?> -</a>
                <?php if(!$userInfo): ?>
                    <a href="<?=base_url()?>login"><?=$this->lang->line('global_login')?></a>
                <?php else: ?>
                    <?php if(@$userInfo->isAdmin): ?>
                        <a href="<?=base_url()?>admin"><?=$this->lang->line('global_cpanel')?> -</a>
                    <?php endif; ?>
                    <a href="<?=base_url()?>logout"><?=$this->lang->line('global_logout')?></a>
                <?php endif; ?>
            </span>
            <div style="clear:both;">
            </div>
            </div>
        </div>
</div>
</body>

</html>