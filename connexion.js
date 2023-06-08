function togglePasswordVisibility() {
    const passwordInput = document.getElementById('password');
    const toggleButton = document.getElementById('toggle-password');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleButton.textContent = 'Masquer le mot de passe';
    } else {
        passwordInput.type = 'password';
        toggleButton.textContent = 'Afficher le mot de passe';
    }
}
