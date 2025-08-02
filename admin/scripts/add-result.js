document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('resultForm');
    const scorerSelect = document.getElementById('scorerSelect');
    const scorerList = document.getElementById('scorerList');
    const scorersJson = document.getElementById('scorersJson');
    const homeScoreInput = document.getElementById('homeScore');

    const scorers = [];

    scorerSelect.addEventListener('change', handleScorerChange);
    homeScoreInput.addEventListener('input', validateScorerLimit);
    form.addEventListener('submit', handleFormSubmit);

    function handleScorerChange() {
        const playerId = scorerSelect.value;
        const playerName = scorerSelect.options[scorerSelect.selectedIndex]?.dataset.name;

        if (!playerId) return;

        const madinaGoals = parseInt(homeScoreInput.value || 0);
        if (scorers.length >= madinaGoals) {
            alert(`You can only add ${madinaGoals} goal scorer${madinaGoals !== 1 ? 's' : ''}.`);
            scorerSelect.value = '';
            return;
        }

        scorers.push({ id: playerId, name: playerName });
        updateScorerDisplay();
        scorerSelect.value = '';
    }

    function updateScorerDisplay() {
        scorerList.innerHTML = '';
        const goalCounts = {};

        scorers.forEach(({ id }) => {
            goalCounts[id] = (goalCounts[id] || 0) + 1;
        });

        Object.entries(goalCounts).forEach(([id, goals]) => {
            const scorer = scorers.find(s => s.id === id);
            const div = document.createElement('div');
            div.className = 'scorer-entry';
            div.innerHTML = `
                <span>${scorer.name} (${goals} goal${goals > 1 ? 's' : ''})</span>
                <button type="button" class="remove-btn" data-id="${id}">&times;</button>
            `;
            scorerList.appendChild(div);
        });

        scorersJson.value = JSON.stringify(scorers);
        attachRemoveHandlers();
    }

    function attachRemoveHandlers() {
        document.querySelectorAll('.remove-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const idToRemove = btn.dataset.id;
                const index = scorers.findIndex(s => s.id === idToRemove);
                if (index !== -1) {
                    scorers.splice(index, 1);
                    updateScorerDisplay();
                }
            });
        });
    }

    function validateScorerLimit() {
        const madinaGoals = parseInt(homeScoreInput.value || 0);
        if (scorers.length > madinaGoals) {
            alert("You've added more scorers than goals. Please remove extras.");
        }
    }

    async function handleFormSubmit(e) {
        e.preventDefault();

        const goalCount = parseInt(homeScoreInput.value || 0);
        if (scorers.length !== goalCount) {
            alert(`Please select exactly ${goalCount} goal scorer${goalCount !== 1 ? 's' : ''}.`);
            return;
        }

        const formData = new FormData(form);
        formData.append('scorers', JSON.stringify(scorers));

        try {
            const response = await fetch('processing/add-result.php', {
                method: 'POST',
                body: formData,
                credentials: "same-origin"
            });

            const text = await response.text();
            let data;
            try {
                data = JSON.parse(text);
            } catch (e) {
                console.error("Invalid JSON from server:", text);
                alert("Server returned unexpected response.");
                return;
            }

            const urlParams = new URLSearchParams(window.location.search);
            const id = urlParams.get('id');
            console.log(id)

            if (data.success === true) {
                alert('Result saved successfully!');

                // Delete fixture if ID exists
                if (id) {
                    const delResponse = await fetch('processing/delete-fixture.php', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ id })
                    });

                    const delResult = await delResponse.text();
                    console.log("Fixture deleted:", delResult);
                }
            } else {
                alert('Error: ' + (data.message || 'Unknown error'));
            }

        } catch (err) {
            console.error(err);
            alert('An error occurred while submitting.');
        }
    }
});
