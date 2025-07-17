document.getElementById('registrationForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const fullName = document.getElementById('fullName').value.trim();
    const email = document.getElementById('email').value.trim();
    const phone = document.getElementById('phone').value.trim();
    const nationalId = document.getElementById('nationalId').value.trim();
    const location = document.getElementById('location').value.trim();
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirmPassword').value;

    if (password !== confirmPassword) {
        alert('Passwords do not match.');
        return;
    }

    const payload = {
        fullName,
        email,
        phone,
        nationalId,
        location,
        password,
        confirmPassword
    };

    try {
        const response = await fetch('processing/register-admin.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });

        const result = await response.json();

        if (result.success) {
            alert('Admin registered successfully!');
            window.location.href = 'login.php';
        } else {
            alert(result.message || 'Registration failed.');
        }
    } catch (error) {
        console.error('Registration error:', error);
        alert('Something went wrong. Please try again.');
    }
});
