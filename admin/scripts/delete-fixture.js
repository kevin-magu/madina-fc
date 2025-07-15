document.addEventListener('DOMContentLoaded', () => {
    const deleteButtons = document.querySelectorAll('.delete-btn');

    deleteButtons.forEach(button => {
        button.addEventListener('click', async function () {
            const fixtureId = this.dataset.id;
            console.log('Deleting fixture with ID:', fixtureId);

            if (!confirm('Are you sure you want to delete this fixture?')) return;

            try {
                const response = await fetch(`processing/delete-fixture.php?id=${fixtureId}`, {
                    method: 'DELETE'
                });

                const result = await response.json(); // ✅ FIXED: parse as JSON

                if (result.success) {
                    console.log(result)
                    // alert('Fixture deleted successfully.');
                   // window.location.reload();
                } else {
                    console.error('Delete failed:', result);
                    alert('Failed to delete: ' + (result.message || 'Unknown error.'));
                }
            } catch (err) {
                console.error('Delete error:', err);
                alert('Something went wrong while deleting the fixture.');
            }
        });
    });
});
