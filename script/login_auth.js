const form = document.querySelector('form');
const inputUsername = document.getElementById('input_username');
const inputPassword = document.getElementById('input_password');
const invalid_login_message = document.getElementById('invalid-login-message');

form.addEventListener('submit', function (event) {
    event.preventDefault();

    const email = inputUsername.value;
    const password = inputPassword.value;

    if (email == '' || password == '') {
        if (invalid_login_message.classList.contains('hidden')) {
            invalid_login_message.classList.remove('hidden');
        }
        invalid_login_message.textContent = 'Username dan password harus diisi!';
        return;
    }

    form.submit();
});