var snowEditor = document.querySelectorAll(".snow-editor");

if (snowEditor) {
    Array.from(snowEditor).forEach(function (element) {
        var options = {
            theme: "snow",
            modules: {
                toolbar: [
                    [{ font: [] }, { size: [] }],
                    ["bold", "italic", "underline", "strike"],
                    [{ color: [] }, { background: [] }],
                    [{ script: "super" }, { script: "sub" }],
                    [{ header: [false, 1, 2, 3, 4, 5, 6] }, "blockquote", "code-block"],
                    [{ list: "ordered" }, { list: "bullet" }, { indent: "-1" }, { indent: "+1" }],
                    ["direction", { align: [] }],
                    ["link", "image", "video"],
                    ["clean"]
                ]
            }
        };

        // Initialize Quill editor
        var quill = new Quill(element, options);

        // Sync editor content to hidden input
        var hiddenInput = document.querySelector("#description");
        if (hiddenInput) {
            quill.on("text-change", function () {
                hiddenInput.value = quill.root.innerHTML;
            });

            // Set initial content if any
            hiddenInput.value = quill.root.innerHTML;
        }
    });
}