import './bootstrap';

function ajax_request_(data, url, successCall) {
    $.ajax({
        url: url,
        method: 'POST',
        data: data,
        // processData: false, // Prevent jQuery from transforming FormData into a string
        // contentType: false, // Let the browser set the content type
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            $('body .alert-danger').remove();
            $('body .alert-success').remove();
            $('button[type="submit"]').removeClass('d-none');
            $('.spinner-border').addClass('d-none');

            if (response.error === true) {
                $(".div-loader-after-btn-clicked").hide(); // b2c booking form laoder
                $('html, body').animate({ scrollTop: 0 }, 'fast');
                $(".booking-submit").show();
                if ($('body').find('form.ct_form').length > 0) {
                    $('body form.ct_form').prepend(`
                    <div class="alert alert-danger" role="alert">
                        ${response.message}
                    </div>
                `);

                    // Do something if a form element is found
                } else {

                    $('body table').prepend(`
                    <div class="alert alert-danger" role="alert">
                        ${response.message}
                    </div>
                `);
                    // Do something if no form element is found
                }

            } else {
                if (successCall && typeof successCall === 'function') {
                    successCall(response);
                    return;
                }
                $('html, body').animate({ scrollTop: 0 }, 'fast');
                if ($('body').find('form.ct_form').length > 0) {
                    $('body form.ct_form').prepend(`
                        <div class="alert alert-success" role="alert">
                            ${response.message}
                        </div>
                    `);
                    // Do something if a form element is found
                } else {
                    $('body table').prepend(`
                    <div class="alert alert-success" role="alert">
                        ${response.message}
                    </div>
                `);
                    // Do something if no form element is found
                }
            }
        },
        error: function (xhr, status, error) {
            console.log(error);

        }
    });
}

$('.login_form').submit(function (e) {
    e.preventDefault();

    var data = $(this).serialize();
    var url = '/api/login';
    var successCall = function (response) {
        // window.location.href = response.redirect_url;
        alert('success');
    }
    // alert('login_form');
    ajax_request_(data, url, successCall);
})