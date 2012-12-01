$("#profile-form").live("submit",function(){

    $("div.content div.profile-form .status").empty();
    //client side validation
    var cbool_error = false;
    var first_name = '';
    var last_name = '';
    var pass = 'xx';
    var pass2 = 'xx';

    if($.trim($("div.content div.profile-form #first_name").val())==''){
        first_name = 'Firstname required.';
        cbool_error = true;
    }
    if($.trim($("div.content div.profile-form #last_name").val())==''){
        last_name = 'Lastname required.';
        cbool_error = true;
    }

    if(cbool_error)
    {
        var cdata = {"error":"true","first_name":first_name,"last_name":last_name,"pass":pass,"pass2":pass2};
        show_status2(cdata,true);
    }
    else
    {
        //server side validation
        var form_data = $("#profile-form").serialize();
        $.ajax({
            type	: "POST",
            cache	: false,
            url     : "/wp-content/plugins/wheels-my-wheels/ajax-call.php",
            data	: form_data,
            dataType: 'json',
            success: function(data) {
                show_status2(data,false);

            }
        });
    }
    return false;
});

function show_status2(data,ignore_pass)
{
    if(data.error=='true')
    {
        if(data.first_name!=''){
            $("div.content div.profile-form .first_name-status").removeClass("available");
            $("div.content div.profile-form .first_name-status").html(data.first_name);
            $("div.content div.profile-form .first_name-status").addClass("error");
        }
        if(data.last_name!=''){
            $("div.content div.profile-form .last_name-status").removeClass("available");
            $("div.content div.profile-form .last_name-status").html(data.last_name);
            $("div.content div.profile-form .last_name-status").addClass("error");
        }
        if(!ignore_pass){
            if(data.pass!=''){
                $("div.content div.profile-form .pass-status").removeClass("available");
                $("div.content div.profile-form .pass-status").html(data.pass);
                $("div.content div.profile-form .pass-status").addClass("error");
            }
            if(data.pass2!=''){
                $("div.content div.profile-form .pass2-status").removeClass("available");
                $("div.content div.profile-form .pass2-status").html(data.pass2);
                $("div.content div.profile-form .pass2-status").addClass("error");
            }
        }
        return false;
    }
    else
    {
        window.location = data.redir_url;
    }
}


