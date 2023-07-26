const signupButton = document.getElementById('signup-button');
 
 function goToSignup() {
    window.location.href = "signupform.php";
}

signupButton.addEventListener('click', goToSignup);