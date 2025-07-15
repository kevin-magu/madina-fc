document.addEventListener('DOMContentLoaded', function () {
    const fixtureForm = document.getElementById('fixtureForm');
    

    fixtureForm.addEventListener('submit', async function (e) {
        e.preventDefault();

        const fixtureId = document.getElementById('fixtureId')?.value;
        const matchDate = document.getElementById('matchDate').value.trim();
        const matchTime = document.getElementById('matchTime').value.trim();
        const opponent = document.getElementById('opponent').value.trim();
        const stadium = document.getElementById('stadium').value.trim();
        const location = document.querySelector('input[name="location"]:checked')?.value;

        const formData = new FormData();
        formData.append('fixtureId', fixtureId);
        formData.append('matchDate', matchDate);
        formData.append('matchTime', matchTime);
        formData.append('opponent', opponent);
        formData.append('stadium', stadium);
        formData.append('location', location);

        // Debug log
        const formDataObj = {};
        for (const [key, value] of formData.entries()) {
            formDataObj[key] = value;
        }
        console.log('FormData being sent:', JSON.stringify(formDataObj, null, 2));

        try {
            const response = await fetch('processing/edit-fixture.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();
            console.log(result);

            if (result.success) {
                alert('Fixture updated successfully.');
                //window.location.href = 'fixtures.php'; // Redirect to fixture list
            } else {
                alert(result.message || 'Update failed.');
            }
        } catch (error) {
            console.error('Edit error:', error);
            alert('Something went wrong while updating the fixture.');
        }
    });
});
