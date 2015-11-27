/**
 * Zwraca ramkę requestu
 *
 * @param data
 * @param msg
 * @returns {{success: boolean, type: string, datetime: string, msg: string, data: {}}}
 */
function getRequestFrame(data, msg) {

    data = data || {};
    msg = msg || 'Request';

    var normalize = function(e) {
        return ('0' + e).replace(/^.*(\d\d)$/, '$1');
    };

    var d = new Date();
    var date = [
        d.getFullYear(),
        normalize(d.getMonth() + 1),
        normalize(d.getDate())
    ];
    var time = [
        d.getHours(),
        d.getMinutes(),
        d.getSeconds()
    ].map(normalize);
    
    var datetime = [date.join('-'), time.join(':')].join(' ');

    return {
        success: true,
        type: 'request',
        datetime: datetime,
        msg: msg,
        data: data
    };
}

/**
 * Zwraca ramkę requestu jako string w formacie JSON
 *
 * @param data
 * @param msg
 * @returns string JSON string
 */
function getRequestFrameJson(data, msg) {
    var frame = getRequestFrame(data, msg);

    return JSON.stringify(frame);
}

function sendJsonp(url, data, successCallback, async) {

    if(typeof async === 'undefined')  {
        async = true;
    }

    successCallback = successCallback || function(data) {};

    return $.ajax({
        dataType: 'jsonp',
        url: url,
        cache: false,
        async: async,
        data: {
            json: getRequestFrameJson(data)
        },
        success: function (resp) {
            if (resp.success) {
                successCallback(resp);
            } else {
                spinner('hide');
                if (resp.type == 'error') {
                    var msg = resp.msg;
                    if(resp.data.messages !== undefined) {
                        msg = resp.data.messages.join("\n")
                    }
                    Dialog.error(msg);
                } else {
                    //Dialog.warning(resp.msg);
                }
            }
        },
        error: function (resp) {
            if(resp.statusText == 'Forbiden') {
                spinner();
                location.href = Routing.generate('fos_user_security_logout');
                return;
            }

            Dialog.error(resp.statusText);
        }
    });
}
