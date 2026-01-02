document.addEventListener('DOMContentLoaded', function () {
    // Delete button functionality
    $('.delete-btn').on('click', function (e) {
        e.preventDefault();

        let id = $(this).data('id');
        let name = $(this).data('name') || 'this record';

        Swal.fire({
            title: 'Are you sure?',
            text: `You are about to delete ${name}.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#FC6A57',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Delete it!',
            cancelButtonText: 'No, Keep it',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form with the matching ID
                $('#renew-' + id).submit();
            }
        });
    });

    // Image preview functionality
    const productImageInput = document.getElementById('product-image-viewer');
    if (productImageInput) {
        productImageInput.addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const productImg = document.getElementById('product-img');
                    if (productImg) {
                        productImg.src = e.target.result;
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Status Change Alert (Sweet Alert)
    $('.status_change_alert').on('change', function (event) {
        let url = $(this).data('url');
        let message = $(this).data('message');
        status_change_alert(url, message, event);
    });

    function status_change_alert(url, message, e) {
        e.preventDefault();
        let $target = $(e.target);
        $target.prop('disabled', true);

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
                $target.prop('checked', !$target.prop('checked'));
            }
            $target.prop('disabled', false); // Re-enable
        });
    }

    $(document).ready(function () {
        // Initialize Nepali Datepicker for .nepali-date inputs
        initializeNepaliDatepicker();

        // Optional: Reinitialize datepicker when the modal is opened
        $('#bluebookModal').on('shown.bs.modal', function () {
            initializeNepaliDatepicker();
        });
    });

    // Function to initialize Nepali Datepicker
    function initializeNepaliDatepicker() {
        $('.nepali-date').each(function () {
            if (!$(this).hasClass('ndp-initialized')) {
                $(this).NepaliDatePicker().addClass('ndp-initialized');
            }
        });
    }
});

