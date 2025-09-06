document.addEventListener('DOMContentLoaded', function () {
    // Delete button functionality
    const deleteButtons = document.querySelectorAll('.remove-item-btn');
    const deleteConfirmButton = document.getElementById('delete-record');

    if (deleteConfirmButton) {
        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const formId = this.getAttribute('data-id');
                deleteConfirmButton.setAttribute('data-form-id', formId);
            });
        });

        deleteConfirmButton.addEventListener('click', function () {
            const formId = this.getAttribute('data-form-id');
            const form = document.getElementById(formId);
            if (form) {
                form.submit();
            }
        });
    }

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
});