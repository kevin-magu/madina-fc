document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('loginForm');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const username = document.getElementById('username').value.trim();
        const password = document.getElementById('password').value.trim();
        const remember = form.querySelector('input[name="remember"]').checked;

        try {
            const response = await fetch('processing/login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ username, password, remember })
            });

            const data = await response.json();
            console.log(data)

            if (data.success) {
                localStorage.setItem('admin_token', data.token);
                localStorage.setItem('admin_name', data.admin.name);
                localStorage.setItem('admin_id', data.admin.id);
                localStorage.setItem('token_expiry', data.expires);

                //alert('Login successful');
               window.location.href = 'index.php'; // redirect to dashboard
            } else {
                alert(data.message || 'Login failed');
            }

        } catch (error) {
            console.error('Login Error:', error);
            alert('An error occurred. Please try again.');
        }
    });
});
