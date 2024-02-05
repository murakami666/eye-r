$(function() {
    /*********************************/
    /*解除*/
    /*********************************/
    $(document).on("click", ".del_btn", function(){

        let name = $(this).data("name");

        if(confirm(name + "の配信を解除してよろしいですか？")){

            let id = $(this).data("id");
            let box = $(this).closest('dl');

            // FormData オブジェクトを用意
            var fd = new FormData();
            fd.append("id",id);
            fd.append("mode","del");
            
            $.ajax({
                url: "/user/brand/edit.php",
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
                    
                    $('.p_cancell').remove();

                }else{
                    alert("解除に失敗しました");
                }
            });
        
        }
    });

});