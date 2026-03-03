var MarkIVFavorite = function(whom, whomId, funcOnPos, funcOnNeg){
    var send_data = {
        "whom": whom,
        "whomId": whomId
    };

    $.ajax({
        type: "POST",
        url: '/fav/',
        data: send_data
    }).done(function(resp) {
            if(resp !== null && resp !== ''){
                if(JSON.parse(resp)===true || JSON.parse(resp)==='t'){
                    if (funcOnPos != null) funcOnPos();
                } else {
                    if (funcOnNeg != null) funcOnNeg();
                }
            }
        });
}