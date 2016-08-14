<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<textarea class="message" rows="2" cols="30"></textarea>
<span class="countdown"></span>
<script type="text/javascript">
function isArabic(char){
    var pattern = /[a-zA-Z0-9\-$ ]/;
    return pattern.test(char);
}

function updateCountdown() {
    // 140 is the max message length
    var val = jQuery('.message').val();
    var characterReg = /^\s*[a-zA-Z0-9,\s]+\s*$/;
    var MaximumCharcter = (!characterReg.test(val))? 70:160;
    var numberOfMessages = Math.ceil(jQuery('.message').val().length / MaximumCharcter);
    var remaining = (MaximumCharcter*numberOfMessages) - jQuery('.message').val().length;
    jQuery('.countdown').text(remaining + ' characters remaining. '+numberOfMessages+' message');
}
jQuery(document).ready(function($) {
    updateCountdown();
    $('.message').change(updateCountdown);
    $('.message').keyup(updateCountdown);
});
</script>
<br />
<br />
<script>
/*$(document).ready(function(){

    part1Count = 160;
    part2Count = 145;
    part3Count = 152;

    $('#message').keyup(function(){
        var chars = $(this).val().length;
            messages = 0;
            remaining = 0;
            total = 0;
        if (chars <= part1Count) {
            messages = 1;
            remaining = part1Count - chars;
        } else if (chars <= (part1Count + part2Count)) { 
            messages = 2;
            remaining = part1Count + part2Count - chars;
        } else if (chars > (part1Count + part2Count)) { 
            moreM = Math.ceil((chars - part1Count - part2Count) / part3Count) ;
            remaining = part1Count + part2Count + (moreM * part3Count) - chars;
            messages = 2 + moreM;
        }
        $('#remaining').text(remaining);
        $('#messages').text(messages);
        $('#total').text(chars);
        if (remaining > 1) $('.cplural').show();
            else $('.cplural').hide();
        if (messages > 1) $('.mplural').show();
            else $('.mplural').hide();
        if (chars > 1) $('.tplural').show();
            else $('.tplural').hide();
    });
    $('#message').keyup();
});*/
    $(document).ready(function(){
    var $remaining = $('#remaining'),
        $messages = $remaining.next();

    $('#message').keyup(function(){
        var chars = this.value.length;
        var messages = Math.ceil(chars / MaximumCharcter);
        var remaining = messages * MaximumCharcter - (chars % (messages * MaximumCharcter) || messages * MaximumCharcter);

        $remaining.text(remaining + ' characters remaining');
        $messages.text(messages + ' message(s)');
    });
    $('#message').keyup(function() {
    $('span.error-keyup-2').remove();
    var inputVal = $(this).val();
    var characterReg = /^\s*[a-zA-Z0-9,\s]+\s*$/;
    if(!characterReg.test(inputVal)) {
        $(this).after('<span class="error error-keyup-2">No special characters allowed.</span>');
    }
});
});
</script>

<textarea name="message" value="" id="message"></textarea>
<div>
    <div><span id="remaining">160</span>&nbsp;Character<span class="cplural">s</span> Remaining</div>
    <div>Total&nbsp;<span id="messages">1</span>&nbsp;Message<span class="mplural">s</span>&nbsp;<span id="total">0</span>&nbsp;Character<span class="tplural">s</span></div>
</div>