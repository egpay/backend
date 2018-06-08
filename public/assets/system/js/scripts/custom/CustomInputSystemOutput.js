$(function(){
    $('.addinputfile').on('click',function(){
        var html="<div class='div-with-files'>";
        html += "                                        <div class=\"form-group col-sm-10\">";
        html += "                                            <div class=\"controls\">";
        html += "                                                <label>Key (Snake Case)<\/label>";
        html += "                                                <input type=\"text\" class=\"form-control\" name=\"key[]\">";
        html += "                                            <\/div>";
        html += "                                        <\/div>";
        html += '<div style="padding-top: 40px;" class="col-sm-2 form-group">';
        html += '<a style="color: red;" href="javascript:void(0);" class="remove-file"><i class="fa fa-trash"></i></a>';
        html += '</div>';
        html += "";
        html += "                                        <div class=\"form-group col-sm-6\">";
        html += "                                            <div class=\"controls\">";
        html += "                                                <label>Language (AR)<\/label>";
        html += "                                                <input type=\"text\" class=\"form-control\" name=\"language[ar][]\">";
        html += "                                            <\/div>";
        html += "                                        <\/div>";
        html += "";
        html += "                                        <div class=\"form-group col-sm-6\">";
        html += "                                            <div class=\"controls\">";
        html += "                                                <label>Language (EN)<\/label>";
        html += "                                                <input type=\"text\" class=\"form-control\" name=\"language[en][]\">";
        html += "                                            <\/div>";
        html += "                                        <\/div>";
        html += "</div>";




        $(html).appendTo('.uploaddata');
    });

});
$('body').on('click','.remove-file',function(){
    $(this).parents('.div-with-files').remove();
});
