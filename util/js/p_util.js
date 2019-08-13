function retornarParametro(){
    var query = window.location.search.substring(1);
    var param = query.split('&');
    var val = "";
    var arrayParams = new Array();
    var j=0;

    for (var i=0; i < param.length; i++) {
        var pos = param[i].indexOf('=');
        if (pos > 0) {            
            val = param[i].substring(pos + 1);
            param_decode = window.atob(val);            
            val = param_decode;
            arrayParams.push(val);
        }
    }
    return arrayParams;
}