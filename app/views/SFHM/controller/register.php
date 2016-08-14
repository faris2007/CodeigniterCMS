<script>
    $(document).ready(function(){
        jQuery.validator.addMethod("checkusername", function(username, element) {
                var check =  $.get('<?=base_url()?>register/check/username/'+username,function(data){
                    if(data == '0'){
                        $('#checkusername').removeClass();
                        $('#checkusername').addClass('strong');
                        $('#checkusername').html("<?=$this->lang->line('register_view_available')?>");
                    }else{
                        $('#checkusername').removeClass();
                        $('#checkusername').addClass('short');
                        if(data == '2')
                            $('#checkusername').html("<?=$this->lang->line('register_view_error_unkown')?>");
                        else if(data == '3')
                            $('#checkusername').html("<?=$this->lang->line('register_view_error_already_register')?>");
                        else
                            $('#checkusername').html("<?=$this->lang->line('register_view_error_nonenglish_char')?>");
                    }
                    return data;
                });
                if(check == '0')
                    return false;
                else
                    return true;
       },'');
        jQuery.validator.addMethod("checkemail", function(email, element) {
                var check = $.post('<?=base_url()?>register/check',{ type: "email", value: email },function(data){
                    if(data == '0'){
                        $('#checkemail').removeClass();
                        $('#checkemail').addClass('strong');
                        $('#checkemail').html("<?=$this->lang->line('register_view_available')?>");
                    }else{
                        $('#checkemail').removeClass();
                        $('#checkemail').addClass('short');
                        if(data == '2')
                            $('#checkemail').html("<?=$this->lang->line('register_view_error_unkown')?>");
                        else if(data == '3')
                            $('#checkemail').html("<?=$this->lang->line('register_view_error_already_register')?>");
                        else
                            $('#checkemail').html("<?=$this->lang->line('register_view_error_nonenglish_char')?>");
                    }
                    return data;
                });
                if(check == '0')
                    return false;
                else
                    return true;
        },'');

        $("#register").validate({
            rules: {
                repassword: {
                    equalTo: "#password"
                },
                email: {
                    required: true,
                    email: true,
                    checkemail:true
                },
                username:{
                    minlength:4,
                    required: true,
                    checkusername:true
                }
            },
            messages: {
                username: {
                  required:  "<?=$this->lang->line("register_view_username")?>",
                  minlength: jQuery.format("<?=$this->lang->line('register_view_format_username')?>")
                },
                email: {
                required: "<?=$this->lang->line('register_view_needed_email')?>",
                email: "<?=$this->lang->line('register_view_format_email')?>"
                },
                repassword: {
                    equalTo: "<?=$this->lang->line('register_view_not_match_password')?>"
                }
            }
        });
        $('#password').keyup(function(){
            $('#result').html(checkStrength($('#password').val()));
        })
        $('#repassword').keyup(function(){
            if($(this).equalTo('password')){
                $('#resultre').removeClass();
                $('#resultre').addClass('strong');
                $('#resultre').html("<?=$this->lang->line('register_view_right_word')?>");
            }else{
                $('#resultre').removeClass();
                $('#resultre').addClass('short');
                $('#resultre').html("<?=$this->lang->line('register_view_wrong_word')?>");
            }
        }) 

        function checkStrength(password){

            //initial strength
            var strength = 0;

            //if the password length is less than 6, return message.
            if (password.length < 6) {
                $('#result').removeClass();
                $('#result').addClass('short');
                return "<?=$this->lang->line('register_view_error_small_password')?>";
            }

            //length is ok, lets continue.

            //if length is 8 characters or more, increase strength value
            if (password.length > 7) strength += 1;

            //if password contains both lower and uppercase characters, increase strength value
            if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/))  strength += 1;

            //if it has numbers and characters, increase strength value
            if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/))  strength += 1;

            //if it has one special character, increase strength value
            if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/))  strength += 1;

            //if it has two special characters, increase strength value
            if (password.match(/(.*[!,%,&,@,#,$,^,*,?,_,~].*[!,",%,&,@,#,$,^,*,?,_,~])/)) strength += 1;

            //now we have calculated strength value, we can return messages

            //if value is less than 2
            if (strength < 2 ) {
                $('#result').removeClass();
                $('#result').addClass('weak');
                return "<?=$this->lang->line('register_view_week_word')?>";
            } else if (strength == 2 ) {
                $('#result').removeClass();
                $('#result').addClass('good');
                return "<?=$this->lang->line('register_view_good_word')?>";
            } else {
                $('#result').removeClass();
                $('#result').addClass('strong');
                return "<?=$this->lang->line('register_view_strong_word')?>";
            }
        }
    });
</script>

<form method="post" id="register"><input type="hidden" name="token" value="<?=$this->core->token()?>" />
    <div>
        <table class="tbl" style="width:90%">
            <thead>
                <tr>
                    <th colspan="3"><?=$this->lang->line('register_view_form_name')?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?=$this->lang->line('register_view_username')?></td>
                    <td><input type="text" name="username" required id="username" /></td>
                    <td id="checkusername"></td>
                </tr>
                <tr>
                    <td><?=$this->lang->line('register_view_form_full_name')?></td>
                    <td colspan="2"><input type="text" name="fullName" required id="fullName" /></td>
                </tr>
                <tr>
                    <td><?=$this->lang->line('register_view_form_email')?></td>
                    <td><input type="text" name="email" required id="email" /></td>
                    <td id="checkemail"></td>
                </tr>
                <tr>
                    <td><?=$this->lang->line('register_view_form_mobile')?></td>
                    <td colspan="2"><input type="text" name="mobile" id="mobile" /></td>
                </tr>
                <tr>
                    <td><?=$this->lang->line('register_view_form_password')?></td>
                    <td><input type="password" name="password" required id="password" /></td>
                    <td id="result"></td>
                </tr>
                <tr>
                    <td><?=$this->lang->line('register_view_form_repassword')?></td>
                    <td><input type="password" name="repassword" required id="repassword" /></td>
                    <td id="resultre"></td>
                </tr>
                <tr>
                    <td><?=$this->lang->line('register_view_form_captcha_img')?></td>
                    <td colspan="2" id="captchaimg"><?=$CAPTCHA?></td>
                </tr>
                <tr>
                    <td><?=$this->lang->line('register_view_form_captcha_question')?></td>
                    <td colspan="2"><input type="text" required name="captcha" /></td>
                </tr>
                <?php if($ERROR): ?>
                    <tr>
                        <td colspan="3" class="msg"><?=$ERR_MSG?></td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td colspan="3"><input type="submit" value="<?=$this->lang->line('register_view_form_button')?>" /></td>
                </tr>
            </tbody>
        </table>
    </div>
</form>
