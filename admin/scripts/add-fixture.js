document.addEventListener('DOMContentLoaded', function () {
    const fixtureForm = document.getElementById('fixtureForm');

    fixtureForm.addEventListener('submit', async function (e) {
        e.preventDefault();

        const matchDate = document.getElementById('matchDate').value;
        const matchTime = document.getElementById('matchTime').value;
        const opponent = document.getElementById('opponent').value.trim();
        const stadium = document.getElementById('stadium').value.trim();
        const location = document.querySelector('input[name="location"]:checked')?.value;

        const formData = new FormData();
        formData.append('matchDate', matchDate);
        formData.append('matchTime', matchTime);
        formData.append('opponent', opponent);
        formData.append('location', location);
        formData.append('stadium', stadium);

        try {
            const response = await fetch('processing/add-fixture.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                alert('Fixture added successfully!');
                fixtureForm.reset();
            } else {
                alert('Failed to add fixture: ' + result.message);
                console.error(result.text());
            }

        } catch (error) {
            console.error('Error during fixture upload:', error);
            alert('Something went wrong while submitting the fixture.');
        }
    });
});
