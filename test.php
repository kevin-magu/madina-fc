<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Select Players</title>
    <link rel="stylesheet" href="test.css">
</head>
<body>
    <div class="form-container">
        <h2>Select Players</h2>
        <select id="playerSelect">
            <option value="">-- Choose a player --</option>
            <option value="Mohamed Salah">Mohamed Salah</option>
            <option value="Alisson Becker">Alisson Becker</option>
            <option value="Virgil van Dijk">Virgil van Dijk</option>
            <option value="Trent Alexander-Arnold">Trent Alexander-Arnold</option>
            <option value="Andrew Robertson">Andrew Robertson</option>
            <option value="Jordan Henderson">Jordan Henderson</option>
            <option value="Fabinho">Fabinho</option>
            <option value="Thiago Alcântara">Thiago Alcântara</option>
            <option value="Darwin Núñez">Darwin Núñez</option>
            <option value="Diogo Jota">Diogo Jota</option>
            <option value="Luis Díaz">Luis Díaz</option>
            <option value="Cody Gakpo">Cody Gakpo</option>
        </select>

        <div id="selectedPlayers" class="player-name-display"></div>
    </div>

    <script>
        const playerSelect = document.getElementById('playerSelect');
        const selectedPlayersDiv = document.getElementById('selectedPlayers');
        const selectedPlayers = [];

        playerSelect.addEventListener('change', () => {
            const selectedValue = playerSelect.value;
            if (selectedValue) {
                selectedPlayers.push(selectedValue);

                // Remove selected option from dropdown
                const selectedOption = playerSelect.querySelector(`option[value="${selectedValue}"]`);
                if (selectedOption) {
                    selectedOption.remove();
                }

                // Reset dropdown to default
                playerSelect.selectedIndex = 0;

                // Update display
                selectedPlayersDiv.innerHTML = selectedPlayers.map(p => `<span>${p}</span>`).join(', ');
            }
        });
    </script>
</body>
</html>
