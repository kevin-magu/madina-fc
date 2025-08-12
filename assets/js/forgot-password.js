document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('loginForm');
    const messageBox = document.getElementById('messageBox');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        // Collect form values
        const playerId = document.querySelector('input[name="username"]').value.trim();
        const newPassword = document.querySelectorAll('input')[1].value.trim(); // New Password
        const confirmPassword = document.querySelector('input[name="password"]').value.trim();

        // Clear previous message
        messageBox.textContent = '';
        messageBox.style.display = 'none';

        // Basic validation
        if (!playerId || !newPassword || !confirmPassword) {
            showMessage("All fields are required.", "red");
            return;
        }

        if (newPassword !== confirmPassword) {
            showMessage("Passwords do not match.", "red");
            return;
        }

        try {
            const response = await fetch('processing/forgot-password.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    clubId: playerId,
                    newPassword: newPassword,
                    confirmPassword: confirmPassword
                })
            });

            const data = await response.json();
            console.log(data);

            if (data.success) {
                showMessage(data.message || "Password updated successfully.", "green");
                setTimeout(() => {
                    window.location.href = 'login.php';
                }, 3000);
            } else {
                showMessage(data.message || "Password update failed.", "red");
            }

        } catch (error) {
            console.error('Password Reset Error:', error);
            showMessage('An error occurred. Please try again.', 'red');
        }
    });

    function showMessage(msg, color) {
        messageBox.textContent = msg;
        messageBox.style.color = color;
        messageBox.style.display = 'block';
    }
});
