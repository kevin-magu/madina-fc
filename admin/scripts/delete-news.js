document.addEventListener('DOMContentLoaded', () => {
    const deleteButtons = document.querySelectorAll('.delete-btn');

    deleteButtons.forEach(button => {
        button.addEventListener('click', async function () {
            const articleId = this.dataset.id;
            console.log(articleId)

            if (!confirm('Are you sure you want to delete this article?')) return;

            try {
                const response = await fetch(`processing/delete-news.php?id=${articleId}`, {
                    method: 'DELETE' // or POST if your PHP is expecting POST
                });

                const result = await response.json();
                if (result.success) {
                    alert('News article deleted.');
                    location.reload(); // or remove the card dynamically
                } else {
                    alert('Failed to delete: ' + result.message);
                }
            } catch (err) {
                console.error('Delete error:', err);
                alert('Something went wrong while deleting.');
            }
        });
    });
});
