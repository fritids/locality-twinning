var currentForm;
function fillAnswerContent(json)
{
    // Set total answer count
    currentForm.parent().prev().parent().parent().parent().prev().find('.comment-count').text(json.totalAnswer);

    // If answer submitted by non-expert user
    if(json.type == 0)
    {
        currentForm.parent().prev().append(json.text);
        setTimeout(function(){
            currentForm.parent().prev().find('li:last').hide(1000, function(){
                jQuery(this).remove();
            });
        }, 5000);
    }else{
        currentForm.parent().prev().prepend(json.text);
        currentForm.parent().prev().parent().parent().parent().prev().find('.comments p').text(json.viewAnswer);
    }
}

function fillQuestionContent(json, questionContainer)
{
    if(json.status == 'success')
    {
        $("#ac-message #ac-message-title").html("Your question submitted successfully. You will be notified after approval. <br>Thanks");
    }else{
        $("#ac-message #ac-message-title").html("Your question does not submit successfully. Please try again later. <br>Thanks");
    }
    questionContainer.trigger($.Events.CLOSE);
    $("#ac-message").trigger($.Events.OPEN);
}

$.fn.AskQuestionMessageController = function(){
    this.each(function(){

        var $self =  $(this),
            $form = $('form', $self),
            $submitBtn = $('input[type="submit"]', $self);

        // set this up as a Message Controller
        $self.MessageController({});

        $form.bind('submit', form_Submit);

        $self
            .find('a.close')
            .bind('click', clearForm);

        function clearForm(e) {
            e.preventDefault();
            $self.find('textarea').val('');
        };

        function form_Submit(e){
            e.preventDefault();

            var thisFormError = new Array();
            var questionField = $form.find('textarea');

            if( jQuery.trim( questionField.val() ) == '' )
            {
                questionField.css('border', '1px solid #f00');
                thisFormError[0] = false;
            }else{
                questionField.css('border', 'none');
            }

            if( thisFormError.length > 0 ) return false;

            $submitBtn.after('<img src="/wp-content/themes/wheels/img/ajax-loader.gif" width="20">');

            $.ajax({
                type: 'post',
                data: $form.serialize(),
                url: WHEELS_AC_AJAX_ACTION,
                dataType: 'json',
                success: function(json){
                    $submitBtn.next().remove();
                    fillQuestionContent(json, $self);
                    $self.find('textarea').val('');
                },
                error: function(){
                    $submitBtn.next().remove();
                    $self.find('textarea').val('');
                }
            });

            return false;
        }

    });
};

$.fn.AccordionController2 = function(settings){
    var config = {
        collapseAll: true,
        expandFirst: true,
        expandAll: false
    };
    if (settings) $.extend(config, settings);
    this.each(function(){
        var $self =  $(this);
        $self.AccordionController(config);
    });
}

jQuery(document).ready(function(){

    jQuery('form.answerform').submit(function(){

        var formValid = new Array();
        currentForm = jQuery(this);
        var answerField = currentForm.find('textarea');
        var $submitBtn = currentForm.find('input[type="submit"]');

        if( jQuery.trim( answerField.val() ) == '' )
        {
            answerField.css('border', '1px solid #f00');
            formValid[0] = false;
        }else{
            answerField.css('border', 'none');
        }

        if( formValid.length > 0 ) return false;

        $submitBtn.after('<img src="/wp-content/themes/wheels/img/ajax-loader.gif" width="20">');
        var data = jQuery(this).serialize();
        jQuery.ajax({
            url:WHEELS_AC_AJAX_ACTION,
            type: 'POST',
            data: data,
            dataType:'json',
            beforeSubmit: function(){

            },
            success: function(json){
                $submitBtn.next().remove();
                currentForm.find('textarea').val('');
                fillAnswerContent(json);
            },
            error: function(){
                currentForm.find('textarea').val('');
            }
        });

        return false;

    });

    jQuery('#guides div.answer-centre .acc-menu').click(function(){
        $("#ask-question-message").trigger($.Events.CLOSE);
        $("#question").val('');
    });

    var hashName = window.location.hash.split('/');

    if( hashName[0] == '#answer-centre' )
    {
        jQuery('html, body').animate({ scrollTop: jQuery('a[name="answer-centre"]').offset().top }, 500);
    }
    if( jQuery('#question-id-'+hashName[1]).length)
    {
        var elementIndex = jQuery('li[id^="question-id-"]').index( jQuery('#question-id-'+hashName[1]) );
        if( elementIndex != 0 )
        {
            jQuery('.heading', jQuery( '#question-id-'+hashName[1] ) ).trigger('click');
        }
    }


});