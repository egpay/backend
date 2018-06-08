function PrintElem(elem, width, height) {
    Popup($(elem).html(), width, height);
}

function Popup(data, width, height) {
    var mywindow = window.open('', 'print', 'height='+height+',width='+width+'');
    mywindow.document.write('<html><head>');
    mywindow.document.write('<meta charset="UTF-8">');
    mywindow.document.write('<style>');
    mywindow.document.write('table{border-collapse:collapse;border: 1px solid black;width:100%;text-align:center;direction: rtl;}');
    mywindow.document.write('th, td{border-collapse:collapse;border: 1px solid black;text-align:center;direction: rtl;}');
    mywindow.document.write('.left,.floatclass,h1,h2,h3,h4,h5 {float: right;}.xfloatclass {float: left;}.notprinted,form {display:none;}');
    mywindow.document.write('</style>');
    mywindow.document.write('</head><body >');
    mywindow.document.write(data);
    mywindow.document.write('</body></html>');

    mywindow.print();
    mywindow.close();

    return true;
}