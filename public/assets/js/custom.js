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
    const productImageInput = document.getElementById('product-image-input');
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

    // Set Limit for Packages
    const orderUnlimitedRadio = document.getElementById('orderUnlimitedRadio');
    const orderUseLimitRadio = document.getElementById('orderUseLimitRadio');
    const orderLimitInputWrapper = document.getElementById('orderLimitInputWrapper');

    const itemUnlimitedRadio = document.getElementById('itemUnlimitedRadio');
    const itemUseLimitRadio = document.getElementById('itemUseLimitRadio');
    const itemLimitInputWrapper = document.getElementById('itemLimitInputWrapper');

    function toggleLimitInput(wrapper, useLimitRadio) {
        wrapper.style.display = useLimitRadio.checked ? 'block' : 'none';
    }

    if (orderUnlimitedRadio && orderUseLimitRadio && orderLimitInputWrapper) {
        orderUnlimitedRadio.addEventListener('change', () => toggleLimitInput(orderLimitInputWrapper, orderUseLimitRadio));
        orderUseLimitRadio.addEventListener('change', () => toggleLimitInput(orderLimitInputWrapper, orderUseLimitRadio));
    }

    if (itemUnlimitedRadio && itemUseLimitRadio && itemLimitInputWrapper) {
        itemUnlimitedRadio.addEventListener('change', () => toggleLimitInput(itemLimitInputWrapper, itemUseLimitRadio));
        itemUseLimitRadio.addEventListener('change', () => toggleLimitInput(itemLimitInputWrapper, itemUseLimitRadio));
    }


    // Allergy Multi Select
    $('#selectAllergy').select2({
        placeholder: "Type your content and press enter",
        allowClear: true,
        tags: true,
        createTag: function (params) {
            var term = $.trim(params.term);
            if (term === '') {
                return null;
            }
            return {
                id: term.toLowerCase().replace(/\s+/g, '-'),
                text: term,
                newTag: true
            };
        },
        language: {
            noResults: function () {
                return "No results found";
            }
        }
    });


    // Status Change Alert
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


});