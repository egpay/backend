$(function(){
    $('.addinputfile').on('click',function(){
        var html="\n" +
            "<div class=\"div-with-files\">                                                <div class=\"form-group col-sm-5\">\n" +
            "                                                    <div class=\"controls\">\n" +
            "                                                        <label for=\"condition_data[amount_from][]\">Amount From:</label>\n" +
            "                                                        <input class=\"form-control\" step=\"0.01\" name=\"condition_data[amount_from][]\" type=\"number\" id=\"condition_data[amount_from][]\">\n" +
            "                                                    </div>\n" +
            "                                                    \n" +
            "                                                </div>\n" +
            "\n" +
            "                                                <div class=\"form-group col-sm-5\">\n" +
            "                                                    <div class=\"controls\">\n" +
            "                                                        <label for=\"condition_data[amount_to][]\">Amount To:</label>\n" +
            "                                                        <input class=\"form-control\" step=\"0.01\" name=\"condition_data[amount_to][]\" type=\"number\" id=\"condition_data[amount_to][]\">\n" +
            "                                                    </div>\n" +
            "                                                    \n" +
            "                                                </div>\n" +
            '<div style="padding-top: 40px;" class="col-sm-2 form-group">'+
            '<a style="color: red;" href="javascript:void(0);" class="remove-file"><i class="fa fa-trash"></i></a>'+
            '</div>'+
            "\n" +
            "                                                <div class=\"form-group col-sm-3\">\n" +
            "                                                    <div class=\"controls\">\n" +
            "                                                        <label for=\"condition_data[charge_type][]\">Charge Type:</label>\n" +
            "                                                        <select class=\"form-control\" id=\"condition_data[charge_type][]\" name=\"condition_data[charge_type][]\"><option value=\"fixed\">Fixed</option><option value=\"percent\">Percentage</option></select>\n" +
            "                                                    </div>\n" +
            "                                                    \n" +
            "                                                </div>\n" +
            "\n" +
            "                                                <div class=\"form-group col-sm-3\">\n" +
            "                                                    <div class=\"controls\">\n" +
            "                                                        <label for=\"condition_data[system_commission][]\">System Commission:</label>\n" +
            "                                                        <input class=\"form-control\" step=\"0.01\" name=\"condition_data[system_commission][]\" type=\"number\" id=\"condition_data[system_commission][]\">\n" +
            "                                                    </div>\n" +
            "                                                    \n" +
            "                                                </div>\n" +
            "\n" +
            "                                                <div class=\"form-group col-sm-3\">\n" +
            "                                                    <div class=\"controls\">\n" +
            "                                                        <label for=\"condition_data[agent_commission][]\">Agent Commission:</label>\n" +
            "                                                        <input class=\"form-control\" step=\"0.01\" name=\"condition_data[agent_commission][]\" type=\"number\" id=\"condition_data[agent_commission][]\">\n" +
            "                                                    </div>\n" +
            "                                                    \n" +
            "                                                </div>\n" +
            "\n" +
            "                                                <div class=\"form-group col-sm-3\">\n" +
            "                                                    <div class=\"controls\">\n" +
            "                                                        <label for=\"condition_data[merchant_commission][]\">Merchant Commission:</label>\n" +
            "                                                        <input class=\"form-control\" step=\"0.01\" name=\"condition_data[merchant_commission][]\" type=\"number\" id=\"condition_data[merchant_commission][]\">\n" +
            "                                                    </div>\n" +
            "                                                    \n" +
            "                                                </div>\n"+
            "<div class=\"col-sm-12\">\n" +
            "                                                    <hr />\n" +
            "                                                </div></div>";

        $(html).appendTo('.uploaddata');
    });

});
$('body').on('click','.remove-file',function(){
    $(this).parents('.div-with-files').remove();
});