const form = document.querySelector('form');
const inputEmail = document.getElementById('input_email');
const inputPassword = document.getElementById('input_password');
const invalid_login_message = document.getElementById('invalid_login_message');

form.addEventListener('submit', function (event) {
    event.preventDefault();

    const email = inputEmail.value;
    const password = inputPassword.value;

    if (email == '' || password == '') {
        // invalid_login_message.style.display = 'block';
        // invalid_login_message.textContent = 'Email dan password harus diisi!';
        alert('Email dan password harus diisi!');
        return;
    }

    alert('Login berhasil!');
    window.location.href = 'index.html';
});