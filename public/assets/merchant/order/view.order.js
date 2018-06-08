$('form#changeStatus input[type="submit"]').on('click', function(e){
    e.preventDefault();
    var status = $('select#status').val();
    var comment = $('#comment').val();
    var action = $(this).parent('form').attr('action');
    if(status == 'undefined')
        alertError(__('Must select order status'))
    $.getJSON(action,{status:status,comment:comment},function(data){
        if(data.status)
            alertSuccess(data.msg);
        else
            alertError(data.msg);
    });
});


function GenerateQR(url){
    $.get(url,{},function(data){
        if(data.image){
            $('#generate-qr').html(
                $('<img>',{'text-align':'center',src:data.image})
            );
        }
    },'json');
}