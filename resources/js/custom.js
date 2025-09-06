document.addEventListener('DOMContentLoaded', function () {

    // Status Change Alert (Sweet Alert)
    $('.status_change_alert').on('change', function (event) {
        let url = $(this).data('url');
        let message = $(this).data('message');
        status_change_alert(url, message, event);
    });

    function status_change_alert(url, message, e) {
        e.preventDefault();
        Swal.fire({
            title: 'Are you sure?',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            cancelButtonColor: '#d33',
            confirmButtonColor: '#FC6A57',
            cancelButtonText: 'No',
            confirmButtonText: 'Yes',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            } else {
                // Revert checkbox state if user cancels
                $(e.target).prop('checked', !$(e.target).prop('checked'));
            }
        });
    }

    
})