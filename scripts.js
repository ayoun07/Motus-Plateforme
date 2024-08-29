document.addEventListener('DOMContentLoaded', function() {
    const signupForm = document.getElementById('signupForm');
    const loginForm = document.getElementById('loginForm');

    signupForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(signupForm);
        
        fetch('signup.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'game.php';
            } else {
                document.getElementById('signupError').textContent = data.message;
            }
        });
    });

    loginForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(loginForm);

        fetch('login.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'game.php';
            } else {
                document.getElementById('loginError').textContent = data.message;
            }
        });
    });
});
