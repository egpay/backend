$(function(){
    $('.addinputfile').on('click',function(){
        var html= "<div class=\"div-with-files\">";
        html += "";
        html += "    <div class=\"form-group col-sm-3\">";
        html += "        <div class=\"controls\">";
        html += "            <label>From Amount:<\/label>";
        html += "            <input type=\"number\" class=\"form-control\" name=\"list[from_amount][]\">";
        html += "        <\/div>";
        html += "    <\/div>";
        html += "";
        html += "    <div class=\"form-group col-sm-3\">";
        html += "        <div class=\"controls\">";
        html += "            <label>To Amount:<\/label>";
        html += "            <input type=\"number\" class=\"form-control\" name=\"list[to_amount][]\">";
        html += "        <\/div>";
        html += "    <\/div>";
        html += "";
        html += "    <div class=\"form-group col-sm-4\">";
        html += "        <div class=\"controls\">";
        html += "            <label>Point:<\/label>";
        html += "            <input type=\"number\" class=\"form-control\" name=\"list[point][]\">";
        html += "        <\/div>";
        html += "    <\/div>";
        html += "";
        html += "    <div style=\"padding-top: 40px;\" class=\"col-sm-2 form-group\">";
        html += "        <a style=\"color: red;\" href=\"javascript:void(0);\" class=\"remove-file\"><i class=\"fa fa-trash\"><\/i><\/a>";
        html += "    <\/div>";
        html += "";
        html += "<\/div>";


        $(html).appendTo('.uploaddata');
    });

});
$('body').on('click','.remove-file',function(){
    $(this).parents('.div-with-files').remove();
});
