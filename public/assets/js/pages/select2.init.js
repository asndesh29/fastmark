$(document).ready(function () {
    // Basic single select
    $(".js-example-basic-single").select2();

    // Basic multiple select with tag support
    $(".js-example-basic-multiple").select2({
        allowClear: true,
        tags: true, // Enable custom tags
        createTag: function (params) {
            var term = $.trim(params.term);
            if (term === '') {
                return null;
            }
            return {
                id: term.toLowerCase().replace(/\s+/g, '-'), // Unique ID for the tag
                text: term,
                newTag: true // Mark as a new tag
            };
        },
        language: {
            noResults: function () {
                return "No results found";
            }
        }
    });

    // Data array select
    $(".js-example-data-array").select2({
        data: [
            { id: 0, text: "enhancement" },
            { id: 1, text: "bug" },
            { id: 2, text: "duplicate" },
            { id: 3, text: "invalid" },
            { id: 4, text: "wontfix" }
        ]
    });

    // Templating with flags (result)
    function formatState(e) {
        return e.id ? $('<span><img src="assets/images/flags/select2/' + e.element.value.toLowerCase() + '.png" class="img-flag rounded" height="18" /> ' + e.text + "</span>") : e.text;
    }
    $(".js-example-templating").select2({
        templateResult: formatState
    });

    // Templating with flags (selection)
    function formatStateSelection(e) {
        var t;
        return e.id ? ((t = $('<span><img class="img-flag rounded" height="18" /> <span></span></span>')).find("span").text(e.text), t.find("img").attr("src", "assets/images/flags/select2/" + e.element.value.toLowerCase() + ".png"), t) : e.text;
    }
    $(".select-flag-templating").select2({
        templateSelection: formatStateSelection
    });

    // Disabled selects
    $(".js-example-disabled").select2();
    $(".js-example-disabled-multi").select2();

    // Programmatic enable/disable
    $(".js-programmatic-enable").on("click", function () {
        $(".js-example-disabled").prop("disabled", false);
        $(".js-example-disabled-multi").prop("disabled", false);
    });
    $(".js-programmatic-disable").on("click", function () {
        $(".js-example-disabled").prop("disabled", true);
        $(".js-example-disabled-multi").prop("disabled", true);
    });
});