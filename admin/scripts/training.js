// scripts/attendance.js

document.addEventListener('DOMContentLoaded', () => {
    const attendanceContainer = document.getElementById('attendanceContainer');
    const attendanceDate = document.getElementById('attendanceDate').value;

    // Mark all present
    document.getElementById('markAllPresent').addEventListener('click', () => {
        document.querySelectorAll('.attendance-card').forEach(card => {
            markAttendance(card, 'present');
        });
    });

    // Mark all absent
    document.getElementById('markAllAbsent').addEventListener('click', () => {
        document.querySelectorAll('.attendance-card').forEach(card => {
            markAttendance(card, 'absent');
        });
    });

    // Search functionality
    document.getElementById('playerSearch').addEventListener('input', function () {
        const query = this.value.toLowerCase();
        document.querySelectorAll('.attendance-card').forEach(card => {
            const name = card.querySelector('.player-name').textContent.toLowerCase();
            card.style.display = name.includes(query) ? '' : 'none';
        });
    });

    // Individual click listeners
    attendanceContainer.addEventListener('click', async (e) => {
        if (e.target.closest('.attendance-btn')) {
            const button = e.target.closest('.attendance-btn');
            const status = button.dataset.status;
            const card = button.closest('.attendance-card');

            await markAttendance(card, status);
        }
    });

    async function markAttendance(card, status) {
        const playerId = card.dataset.playerId;

        // Highlight the selected button
        const presentBtn = card.querySelector('.present-btn');
        const absentBtn = card.querySelector('.absent-btn');

        if (status === 'present') {
            presentBtn.classList.add('active');
            absentBtn.classList.remove('active');

            await fetch('processing/training.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    player_id: playerId,
                    status: 'present',
                    date: attendanceDate
                })
            });
        } else if (status === 'absent') {
            absentBtn.classList.add('active');
            presentBtn.classList.remove('active');

            await fetch('processing/training.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    player_id: playerId,
                    status: 'absent',
                    date: attendanceDate
                })
            });
        }
    }
});
