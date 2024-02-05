$(function() {

    get_day_data_list();

    /*********************************/
    /*登録*/
    /*********************************/
    $(document).on("change", 'select[name="daytime_select"]', function(){

        get_day_data_list();

        
    });

    function get_day_data_list(){
        
        let val = $('select[name="daytime_select"]').val();

        if(val != "none"){

            $('#data_table .data_tr').remove();

            // FormData オブジェクトを用意
            var fd = new FormData();
            fd.append("datetime",val);
            
            $.ajax({
                url: "./get_day_data.php",
                type: "POST",
                data: fd,
                processData: false,
                contentType: false,
                error: function(XMLHttpRequest, textStatus, errorThrown){
                    err=XMLHttpRequest.status+"\n"+XMLHttpRequest.statusText;
                    alert(err);
                },
                beforeSend: function(xhr){
                    xhr.overrideMimeType("text/html;charset=UTF-8");
                }
            })
            .done(function( res ) {
    
                var resAr = JSON.parse(res);

                if(resAr['mode'] == "success"){
                    $('#data_table').append(resAr['reg_html']);
                }
            });
        }

    }

});