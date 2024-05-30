

function displayLoader(id, show = true){
    if(show){
        $('#'+id).show();
    }else{
        $('#'+id).hide();
    }
}




function saveCookie(key, value){
    var expires = new Date();
    expires.setTime(expires.getTime() + (365 * 24 * 60 * 60 * 1000)); // Set expiration to 1 day from now
    document.cookie = key + '=' + value + ';expires=' + expires.toUTCString();
}




const getCookie = (key) => {
    return `; ${document.cookie}`.split(`; ${key}=`).pop().split(`;`).shift();
};




function ajax(url, method, token, success, fail){
    $.ajax({
        url: url,
        headers:{
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + token,
        },
        type: method,
        success: success,
    }).fail(fail);
}




function ajaxWithData(url, method, token, data = {}, success, fail){
    $.ajax({
        url: url,
        headers:{
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + token,
        },
        type: method,
        success: success,
        data: data
    }).fail(fail);
}




function checkAuth(){
    let response = ajax(host+'/api/user', 'GET', getCookie('api_token'));
    if(!response.success){

    }
}



function downloadFileAPI(url, filename, success, data = {}){
    let file = filename;

    $.ajax({
        url: url,
        type: 'GET',
        headers:{
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + getCookie('api_token'),
        },
        data: data,
        cache: false,
        xhr: function () {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 2) {
                    if (xhr.status == 200) {
                        xhr.responseType = "blob";
                    } else {
                        xhr.responseType = "text";
                    }
                }
            };
            return xhr;
        },
        success: function (data) {
            //Convert the Byte Data to BLOB object.
            var blob = new Blob([data], { type: "application/octetstream" });

            //Check the Browser type and download the File.
            var isIE = false || !!document.documentMode;
            if (isIE) {
                window.navigator.msSaveBlob(blob, file);
            } else {
                var url = window.URL || window.webkitURL;
                link = url.createObjectURL(blob);
                var a = $("<a />");
                a.attr("download", file);
                a.attr("href", link);
                $("body").append(a);
                a[0].click();
                $("body").remove(a);
            }

            success(data);
        }
    }).fail(function(data){
        console.log(data);
    });
}