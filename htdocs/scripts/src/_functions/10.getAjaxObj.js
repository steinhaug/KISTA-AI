/**
 * Generix AJAX call with callback v1
 */
function getAjaxObj( cmd, mod, meta, callback ){

    if( typeof mod === 'object' ){
        var data = {
            'data' : meta,
            'command' : cmd,
            'module' : mod.mod
        };
        var AjaxObjURL = mod.url;
    } else {
        var data = {
            'data' : meta,
            'command' : cmd,
            'module' : mod
        };
        var AjaxObjURL = (KistaJS && KistaJS.ajaxurl) ? KistaJS.ajaxurl : 'ajax.php';
    }

    var CSSID = meta.CSSID;

    $.ajax({

        type: 'post',
        url: AjaxObjURL,
        data: data,
        cache: false,

        error: function(response, status, errorThrown){
            if (isEmpty(response.responseJSON)){
                $('#ajaxErrorModal').find(".modal-body").html( response.responseText );
                $('#ajaxErrorModal').find('.modal-header .modal-title').html( response.status + ': ' + errorThrown.toString() );
                $('#ajaxErrorModal').modal('show');
            } else {
                console.log('error here');
            }
            callback('Error occured');
        },
        success: function (response, status, obj) {
            console.log('ajax complete');
            if( response.status == 100 ){
                callback(null, response);
            } else {
                callback(response.status, response);
            }
        }
    }).always(function (data) {
        //$('#spinner').hide();
    });
}

/**
 * Generix AJAX call with callback v2
 * All error handling is done before any callback
 * 
 * DEPENDENCIES: jQuery, quickToast and modal markup #ajaxErrorModal
 * 
 * TODO: getAjaxObj2 should replace getAjaxObj, usage needs to be checked but probably all usage can be slapped over
 */
function getAjaxObj2( cmd, mod, meta, callback ){

    if( typeof mod === 'object' ){
        var data = {
            'data' : meta,
            'command' : cmd,
            'module' : mod.mod
        };
        var AjaxObjURL = mod.url;
    } else {
        var data = {
            'data' : meta,
            'command' : cmd,
            'module' : mod
        };
        var AjaxObjURL = (KistaJS && KistaJS.ajaxurl) ? KistaJS.ajaxurl : 'ajax.php';
    }

    //var CSSID = meta.CSSID;

    $.ajax({

        type: 'post',
        url: AjaxObjURL,
        data: data,
        cache: false,

        error: function(response, status, errorThrown){
            if (isEmpty(response.responseJSON)){
                $('#ajaxErrorModal').find(".modal-body").html( response.responseText );
                $('#ajaxErrorModal').find('.modal-header .modal-title').html( response.status + ': ' + errorThrown.toString() );
                $('#ajaxErrorModal').modal('show');
            } else if( response.responseJSON.hasOwnProperty('errormsg') && response.responseJSON.hasOwnProperty('errorcode') ){
                quickToast('alert', 'AJAX Error, code #' + response.responseJSON.errorcode, '', response.responseJSON.errormsg);
                //callback(response.responseJSON.errorcode, {errorcode: response.responseJSON.errorcode, errormsg: response.responseJSON.errormsg});
            } else {
                quickToast('alert', 'AJAX Generic error', '', 'This is not a good enough error, fix when you see me!');
                //callback('Error occured');
            }
        },
        success: function (response, status, obj) {

            // Error handling, parse errors or other typical dev problems
            if (isEmpty(obj.responseJSON)){
                if(!!document.getElementById('ajaxErrorModal')){
                    $('#ajaxErrorModal').find('.modal-header .modal-title').html( 'AJAX ERROR - DUMP' );
                    $('#ajaxErrorModal').find('.modal-body').html( obj.responseText );
                    $('#ajaxErrorModal').modal('show');
                } else {
                    $('body').prepend('<div style="position:fixed;top:0;left:0;width:100%;text-align:center;z-index:99999"><div style="background-color:red;border:2px solid black;padding:1em;margin:1em;color:white;"><h3>MISSING ajaxErrorModal!</h3>' + obj.responseText + '</div></div>');
                }
                return '';
            }

            // Error handling
            if( !isEmpty(response.responseJSON) && response.responseJSON.hasOwnProperty('errormsg') && response.responseJSON.hasOwnProperty('errorcode') ){
                quickToast('alert', 'AJAX Error, code #' + response.responseJSON.errorcode, '', response.responseJSON.errormsg);
                return '';
            }

            // No errors, return the data to callback
            callback(response);
        }
    }).always(function (data) {
        //$('#spinner').hide();
    });
}

/**
 * generic error handler using the #ajaxErrorModal
 **/
function __ajaxErrorModal(response, status, errorThrown) {
    console.log('error __ajaxErrorModal init');
    if( $('#ajaxErrorModal').length ){
        if (isEmpty(response.responseJSON) || !response.responseJSON.hasOwnProperty('errorcode') ){
            $('#ajaxErrorModal').find(".modal-body").html( response.responseText );
            $('#ajaxErrorModal').find('.modal-header .modal-title').html( response.status + ': ' + errorThrown.toString() );
            $('#ajaxErrorModal').modal('show');
        } else {
            $('#ajaxErrorModal').find(".modal-body").html( response.responseJSON.errormsg );
            $('#ajaxErrorModal').find('.modal-header .modal-title').html( errorThrown.toString() + ' ' + response.responseJSON.errorcode );
            $('#ajaxErrorModal').modal('show');
        }
    } else {
        if (window.bootstrap && bootstrap.Toast) {
            quickToast('#ajaxErrorModal ??','akkurat nå','Det mangler et modalvindu i siden, #ajaxErrorModal. Denne er påkrevd når __ajaxErrorModal callback brukes.');
        } else {
            alert('#ajaxErrorModal is missing!');
        }
        console.log('ERROR! ' + response.status + ': ' + errorThrown.toString());
    }
}
