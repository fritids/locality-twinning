$(document).ready(function(){
    if($("#hdn-the-grid-edit-category").length != 0){
        $("form input[type='submit']").prop('disabled', false);

        $(".the-grid-map-marker-prop").change(function(){
            markerPropChange();
        });

        $("form input[type='submit']").click(function(){
            markerPropChange();
            if($("#hdn-the-grid-in-use").val() == '1'){
                return false;
            }
        });
    }
});//end ready

function markerPropChange(){

    $(".marker-prop-status").html('');
    $(".marker-prop-status").removeClass('cat-in-use');
    $(".marker-prop-status").removeClass('cat-available');
    var formdata = $("form").serialize();

    $.ajax({
        type: "POST",
        async: false,
        cache: false,
        url: BASE_URL+"/wp-content/plugins/the-grid/grid-color-cat.php",
        data: formdata,
        dataType: 'json',
        beforeSend:function () {
        },
        success:function (response) {

            if(response.in_use_color){
                $("#marker-prop-status-color").html('In use');
                $("#marker-prop-status-color").addClass('cat-in-use');
            }else{
                $("#marker-prop-status-color").html('Available');
                $("#marker-prop-status-color").addClass('cat-available');
            }
            if(response.in_use_icon){
                $("#marker-prop-status-icon").html('In use');
                $("#marker-prop-status-icon").addClass('cat-in-use');
            }else{
                $("#marker-prop-status-icon").html('Available');
                $("#marker-prop-status-icon").addClass('cat-available');
            }
            if(response.in_use_hicon){
                $("#marker-prop-status-hicon").html('In use');
                $("#marker-prop-status-hicon").addClass('cat-in-use');
            }else{
                $("#marker-prop-status-hicon").html('Available');
                $("#marker-prop-status-hicon").addClass('cat-available');
            }

            if(response.in_use_color || response.in_use_icon || response.in_use_hicon){
                $("form input[type='submit']").prop('disabled', true);
                $("#hdn-the-grid-in-use").val('1');
            }else{
                $("form input[type='submit']").prop('disabled', false);
                $("#hdn-the-grid-in-use").val('0');
            }
        }
    });
}