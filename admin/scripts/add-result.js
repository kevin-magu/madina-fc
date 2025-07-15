document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('resultForm');
    const scorerSelect = document.getElementById('scorerSelect');
    const scorerList = document.getElementById('scorerList');
    const scorersJson = document.getElementById('scorersJson');
    const homeScoreInput = document.getElementById('homeScore');

    const scorers = [];

    scorerSelect.addEventListener('change', () => {
        const playerId = scorerSelect.value;
        const playerName = scorerSelect.options[scorerSelect.selectedIndex].dataset.name;

        if (!playerId) return;

        const madinaGoals = parseInt(homeScoreInput.value || 0);
        if (scorers.length >= madinaGoals) {
            alert(`You can only add ${madinaGoals} goal scorer${madinaGoals !== 1 ? 's' : ''}.`);
            scorerSelect.value = '';
            return;
        }

        // Add scorer
        scorers.push({ id: playerId, name: playerName });
        updateScorerDisplay();
        scorerSelect.value = '';
    });

    function updateScorerDisplay() {
        scorerList.innerHTML = '';
        const count = {};

        // Count goals per player
        scorers.forEach(s => {
            count[s.id] = count[s.id] ? count[s.id] + 1 : 1;
        });

        Object.entries(count).forEach(([id, goalCount]) => {
            const scorer = scorers.find(s => s.id === id);
            const div = document.createElement('div');
            div.className = 'scorer-entry';
            div.innerHTML = `
                <span>${scorer.name} (${goalCount} goal${goalCount > 1 ? 's' : ''})</span>
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

    homeScoreInput.addEventListener('input', () => {
        const madinaGoals = parseInt(homeScoreInput.value || 0);
        if (scorers.length > madinaGoals) {
            alert("You've added more scorers than goals. Please remove extras.");
        }
    });

    // Final form submission using fetch
    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const goalCount = parseInt(homeScoreInput.value || 0);
        if (scorers.length !== goalCount) {
            alert(`Please select exactly ${goalCount} goal scorer${goalCount !== 1 ? 's' : ''}.`);
            return;
        }

        const formData = new FormData(form);
        formData.append('scorers', JSON.stringify(scorers));

        try {
            const res = await fetch('processing/add-result.php', {
                method: 'POST',
                body: formData
            });

            const data = await res.json();

            if (data) {
                alert('Result saved successfully!');
             //   window.location.href = 'admin-results.php';
                console.log('This is data from the database',data);
            } else {
                alert('Error: ' + data.message);
                console.log('This is data from the database',data);
            }
        } catch (err) {
            console.error(err);
            alert('An error occurred while submitting.');
        }
    });
});
