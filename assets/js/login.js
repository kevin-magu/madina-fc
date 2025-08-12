document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('loginForm');
    const messageBox = document.getElementById('messageBox'); // Add this div in HTML

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const playerId = document.getElementById('username').value.trim();
        const password = document.getElementById('password').value.trim();
        const remember = form.querySelector('input[name="remember"]').checked;

        // Clear previous message
        messageBox.textContent = '';
        messageBox.style.display = 'none';

        try {
            const response = await fetch('processing/login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ clubId: playerId, password, remember })
            });

            const data = await response.json();
            console.log(data);

            if (data.success) {
                localStorage.setItem('player_token', data.token);
                localStorage.setItem('player_id', data.player.id || '');
                localStorage.setItem('player_name', data.player.name || '');
                localStorage.setItem('token_expiry', data.expires);

                window.location.href = 'profile.php'; // redirect to player dashboard
            } else {
                // Inject PHP message into the page
                messageBox.textContent = data.message || 'Login failed';
                messageBox.style.display = 'block';
                messageBox.style.color = 'red';

                // Optional: handle redirect for forgot password
                if (data.redirect) {
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 3000);
                }
            }

        } catch (error) {
            console.error('Login Error:', error);
            messageBox.textContent = 'An error occurred. Please try again.';
            messageBox.style.display = 'block';
            messageBox.style.color = 'red';
        }
    });
});
