// scripts/delete-player.js
document.addEventListener('DOMContentLoaded', () => {
    document.body.addEventListener('click', async function (e) {
        const button = e.target.closest('.delete-btn');
        if (!button) return;

        e.preventDefault();

        const playerId = button.getAttribute('data-id');
        if (!playerId) return;

        const confirmDelete = confirm('Are you sure you want to delete this player?');
        if (!confirmDelete) return;

        try {
            const response = await fetch('processing/delete-player.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: playerId })
            });

            const result = await response.json();
            if (result.success) {
                alert('Player deleted successfully!');
                window.location.reload(); // Or remove the element dynamically if preferred
            } else {
                alert('Error: ' + result.message);
            }
        } catch (error) {
            console.error('Delete failed:', error);
            alert('Failed to delete player.');
        }
    });
});
