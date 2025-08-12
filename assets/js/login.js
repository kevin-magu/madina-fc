document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('loginForm');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const playerId = document.getElementById('username').value.trim();
        const password = document.getElementById('password').value.trim();
        const remember = form.querySelector('input[name="remember"]').checked;

        try {
            const response = await fetch('processing/login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ playerId, password, remember })
            });

            const data = await response.json();
            console.log(data);

            if (data.success) {
                localStorage.setItem('player_token', data.token);
                localStorage.setItem('player_id', data.player.id);
                localStorage.setItem('player_name', data.player.name || '');
                localStorage.setItem('token_expiry', data.expires);

                window.location.href = 'index.php'; // redirect to player dashboard
            } else {
                alert(data.message || 'Login failed');
            }

        } catch (error) {
            console.error('Login Error:', error);
            alert('An error occurred. Please try again.');
        }
    });
});
