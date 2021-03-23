const loadModal = data => {
    let popUp = $('.popUpModal');
    $(popUp).empty();
    $(popUp).html(data);
    $('.modal').modal('show');
}

const ajaxOnLoad = (uri, method, body, callBackSuccess, callBackError) => {
    $('.loader').removeClass('hide-load');
    $.ajax({
        url: uri,
        body: body,
        method: method,
        success: (res) => {
            $('.loader').addClass('hide-load');
            callBackSuccess(res);
        },
        error: res => {
            $('.loader').addClass('hide-load');
            callBackError(res);
        }
    });
}

const showError = (msg="Something wrong happen", actionAgain=null) => {
    $.confirm({
        title: 'Error!',
        content: msg,
        type: 'red',
        typeAnimated: true,
        buttons: {
            tryAgain: {
                text: 'Try again',
                btnClass: 'btn-red',
                action: actionAgain
            },
            close: function () {
            }
        }
    });
}

const showSuccess = (msg="This action successfully") => {
    $.confirm({
        title: 'Success',
        content: msg,
        type: 'green',
        typeAnimated: true,
        buttons: {
            close: function () {
            }
        }
    });
}
