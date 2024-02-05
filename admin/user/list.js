$(function() {

    $(document).on("change", '#sort_from select[name="sort"]', function(){
        $('#sort_from').submit();
    });

    $(document).on("click", '.del_form input[name="delete"]', function(){
        let text = $(this).data("text");
        if(confirm(text + "を削除していいですか？")){
            return true;
        }else{
            return false;
        }
    });

    /*種別*/
    $(document).ready(function() {

        var originalValues = {};

        $('.user_type_select').each(function() {

            originalValues[this.id] = $(this).val();

            $(this).on('change', function() {
                
                var selectedId = this.id;
                var selectedValue = $(this).val();


                let uname = $(this).data("name");
                let tname = $(this).find("option:selected").data("name");


                var confirmed = confirm(uname + 'を'+tname+'へ変更しますか？');

                if (!confirmed) {
                    
                    $(this).val(originalValues[this.id]);

                } else {
                    
                    let id = $(this).data("id");
                    let type = $(this).val();
            
                    let box = $(this).parents("tr");
            
                    // FormData オブジェクトを用意
                    var fd = new FormData();
                    fd.append("user_id",id);
                    fd.append("user_type",type);
                    
                    $.ajax({
                        url: "./edit_type.php",
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
                            $('body').append('<div id="resu_pop_text"><p>更新しました</p></div>');
                            $("#resu_pop_text").show();
                            $("#resu_pop_text").fadeOut(1500).queue(function() {
                                $("#resu_pop_text").remove();
                            });
                            box.find(".cnt_text").html(resAr["cnt"]);

                            originalValues[selectedId] = selectedValue;

                        }
                    });
                }
            });
        });
    });
});