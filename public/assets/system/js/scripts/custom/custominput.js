$(function(){
    $('.addinput').on('click',function(){
        var inputtype = $(this).attr('data-type');

        if(inputtype == 'email')
            var inputrealtype = 'email';
        else
            var inputrealtype = 'text';
        var typename = inputtype.charAt(0).toUpperCase() + inputtype.slice(1);
        var html = '<div class="form-group col-sm-12">';
        html += '<div class="controls">';
        html += '<label for="'+inputtype+'">'+typename+':</label>';
        html += '<div class="input-group">';
        html += '<input class="form-control" name="contact['+inputtype+'][]" class="'+inputtype+'" type="'+inputrealtype+'">';
        html += '<a class="input-group-addon btn btn-danger delcontactinfo"><i class="fa fa-trash"></i></a>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        $(html).appendTo('.contactdata');
    });
});

$('body').on('click','.delcontactinfo',function(){
    $(this).parents('.form-group').remove();
});

$('body').on('click','.remove-file',function(){
    $(this).parents('.div-with-files').remove();
});

$(function(){
    $('.addinputimg').on('click',function(){
        var html = '<div class="col-sm-12">';

        html += '<div class="col-sm-6 form-group">';
        html += '<label for="title[]">Title</label>';
        html += '<input type="text" class="form-control" name="title[]">';
        html += '</div>';

        html += '<div class="col-sm-6 form-group">';
        html += '<label for="image[]">Image</label>';
        html += '<input class="form-control" name="image[]" type="file">';
        html += '</div>';

        html += '<hr></div>';
        $(html).appendTo('.uploaddata');
    });
});

$(function(){
    $('.addinputfile').on('click',function(){
        var html = $('#contractPapers').clone().html();
        $(html).appendTo('.uploaddata');
    });
});




// /**
//  * Created by Tech2 on 8/29/2017.
//  */
// $(function(){
//     $('.addinput').on('click',function(){
//         var inputtype = $(this).attr('data-type');
//
//         if(inputtype == 'email')
//             var inputrealtype = 'email';
//         else
//             var inputrealtype = 'text';
//         var typename = inputtype.charAt(0).toUpperCase() + inputtype.slice(1);
//         var html = '<div class="form-group col-sm-12">';
//         html += '<div class="controls">';
//         html += '<label for="'+inputtype+'">'+typename+':</label>';
//         html += '<div class="input-group">';
//         html += '<input class="form-control" name="contact['+inputtype+'][]" class="'+inputtype+'" type="'+inputrealtype+'">';
//         html += '<a class="input-group-addon btn btn-danger delcontactinfo"><i class="fa fa-trash"></i></a>';
//         html += '</div>';
//         html += '</div>';
//         html += '</div>';
//         $(html).appendTo('.contactdata');
//     });
// });
//
//
// $('body').on('click','.delcontactinfo',function(){
//     $(this).parents('.form-group').remove();
// });
//
//
//
// $(function(){
//     $('.addinputfile').on('click',function(){
//         var html = '<div class="col-sm-12">';
//         html += '<div class="col-sm-6 form-group">';
//         html += '<label for="title[]">Title</label>';
//         html += '<input type="text" class="form-control" name="title[]">';
//         html += '</div>';
//         html += '<div class="col-sm-6 form-group">';
//         html += '<label for="image[]">Image</label>';
//         html += '<input class="form-control" name="image[]" type="file">';
//         html += '</label>';
//         html += '</div>';
//
//         html += '</div><hr>';
//         $(html).appendTo('.uploaddata');
//     });
// });