document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("travelForm");

    // Form fields and error messages
    const nameField = document.getElementById("user-name");
    const locationField = document.getElementById("location");
    const commentField = document.getElementById("user-comment");
    const nameError = document.getElementById("nameError");
    const locationError = document.getElementById("locationError");
    const commentError = document.getElementById("commentError");

    // Real-time validation for name field
    nameField.addEventListener("input", () => {
        if (!nameField.value.trim()) {
            nameError.textContent = "Name is required.";
        } else if (nameField.value.trim().length < 3) {
            nameError.textContent = "Name must be at least 3 characters long.";
        } else {
            nameError.textContent = "";
        }
    });

    // Real-time validation for location field
    locationField.addEventListener("input", () => {
        if (!locationField.value.trim()) {
            locationError.textContent = "Location is required.";
        } else {
            locationError.textContent = "";
        }
    });

    // Real-time validation for comment field
    commentField.addEventListener("input", () => {
        if (!commentField.value.trim()) {
            commentError.textContent = "Comment is required.";
        } else if (commentField.value.trim().length > 300) {
            commentError.textContent = "Comment cannot exceed 300 characters.";
        } else {
            commentError.textContent = "";
        }
    });

    // Final validation on form submission
    form.addEventListener("submit", (e) => {
        let isValid = true;

        // Validate name
        if (!nameField.value.trim()) {
            nameError.textContent = "Name is required.";
            isValid = false;
        } else if (nameField.value.trim().length < 3) {
            nameError.textContent = "Name must be at least 3 characters long.";
            isValid = false;
        }

        // Validate location
        if (!locationField.value.trim()) {
            locationError.textContent = "Location is required.";
            isValid = false;
        }

        // Validate comment
        if (!commentField.value.trim()) {
            commentError.textContent = "Comment is required.";
            isValid = false;
        } else if (commentField.value.trim().length > 300) {
            commentError.textContent = "Comment cannot exceed 300 characters.";
            isValid = false;
        }

        // Prevent form submission if validation fails
        if (!isValid) {
            e.preventDefault();
        }
    });
});
