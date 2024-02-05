$(function() {
    /*********************************/
    /*検索*/
    /*********************************/
    
    var timer = true;
    var o_word;

    $('#search_word').keydown(function(e){
		if (timer) {
			clearInterval(timer);
			timer = setInterval(checkChange, 500);
		}
    });

    function checkChange(){
        
        if ($('#search_word').val() == o_word) {
			return;
        }

        var word = $('#search_word').val();
        o_word = word;

        if (word.length < 1) {
			$('#search_word_list').empty();
			return;
        }
        
        $('#search_word_list').empty();

        // FormData オブジェクトを用意
        var fd = new FormData();
        fd.append("s_word",word);

        $.ajax({
            url: "./s_word.php",
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
                
                $('#search_word_list').show();
                $('#search_word_list').append(resAr['brand_list_text']);
                
            }else{
                $('#search_word_list').hide();
            }
        });


    }

    function brand_zero_check(){

        let num = $('.brand_list dl').length;

        if(num > 1){
            $('.brand_list_zoro').hide();
        }else{
            $('.brand_list_zoro').show();
        }
    }

    brand_zero_check();


    /*********************************/
    /*登録*/
    /*********************************/
    $(document).on("click", ".reg_btn", function(){

        if(confirm("登録してよろしいですか?")){

            let id = $(this).data("id");
            let box = $(this).closest('dl');

            // FormData オブジェクトを用意
            var fd = new FormData();
            fd.append("id",id);
            fd.append("mode","reg");
            
            $.ajax({
                url: "./edit.php",
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
                    
                    box.find(".btbox").html('<em class="non_reg">登録済</em>');
                    $('.brand_list').append(resAr['reg_html']);

                    brand_zero_check();

                }else{
                    if(resAr['error_text'].length > 0){
                        alert(resAr['error_text']);
                    }else{
                        alert("登録に失敗しました");
                    }
                }
            });
        
        }
    });

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
                url: "./edit.php",
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
                    
                    box.remove();

                    brand_zero_check();
                    
                }else{
                    alert("解除に失敗しました");
                }
            });
        
        }
    });

});