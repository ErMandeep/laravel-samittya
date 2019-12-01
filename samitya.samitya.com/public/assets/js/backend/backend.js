
    $('body').on('click', '#report_user ', function (e) {
        e.preventDefault();


        var cancel =  'Cancel';
        var confirm =  'Report';
        var title =  'Are you sure you want to Report this User?';

        swal({
            title: title,
            showCancelButton: true,
            confirmButtonText: confirm,
            cancelButtonText: cancel,
            type: 'warning'
        }).then(function (result) {
            result.value && $('#report').submit();
        });
    })


$('body').on('submit', 'form[name=ignore_user]', function (e) {
        e.preventDefault();

        var form = this;
        var link = $('a[data-method="ignore"]');
        var cancel = link.attr('data-trans-button-cancel') ? link.attr('data-trans-button-cancel') : 'Cancel';
        var confirm = link.attr('data-trans-button-confirm') ? link.attr('data-trans-button-confirm') : 'Yes';
        var title = link.attr('data-trans-title') ? link.attr('data-trans-title') : 'Are you sure ?';

        swal({
            title: title,
            showCancelButton: true,
            confirmButtonText: confirm,
            cancelButtonText: cancel,
            type: 'question'
        }).then(function (result) {
            result.value && form.submit();
        });
    })
