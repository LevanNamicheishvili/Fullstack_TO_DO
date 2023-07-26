const signupForm = document.getElementById('signup-form');
const signupButton = document.getElementById('signup-button');
const errorMessageDiv = document.getElementById('error-message');

// Function to check if any input field is empty
function checkFormValidity() {
    const inputs = signupForm.querySelectorAll('input');
    let hasEmptyField = false;

    for (const input of inputs) {
        if (input.value.trim() === '') {
            hasEmptyField = true;
            break;
        }
    }

    if (hasEmptyField) {
        errorMessageDiv.style.display = 'block';
        return false; // Prevent form submission
    } else {
        errorMessageDiv.style.display = 'none';
        return true; // Allow form submission
    }
}

// Attach event listener to the form's onsubmit event
signupForm.onsubmit = checkFormValidity;    