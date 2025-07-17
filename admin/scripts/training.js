// training.js

const attendanceContainer = document.getElementById('attendanceContainer');
const markAllPresentBtn = document.getElementById('markAllPresent');
const markAllAbsentBtn = document.getElementById('markAllAbsent');
const saveAttendanceBtn = document.getElementById('saveAttendance');
const attendanceDateInput = document.getElementById('attendanceDate');
const playerSearchInput = document.getElementById('playerSearch');

const attendanceStatus = {};

attendanceContainer.addEventListener('click', (e) => {
    if (e.target.closest('.attendance-btn')) {
        const btn = e.target.closest('.attendance-btn');
        const card = btn.closest('.attendance-card');
        const playerId = card.dataset.playerId;
        const status = btn.dataset.status;

        card.querySelectorAll('.attendance-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        attendanceStatus[playerId] = status;
        updateAttendance(playerId, status);
    }
});

markAllPresentBtn.addEventListener('click', () => {
    document.querySelectorAll('.attendance-card').forEach(card => {
        const playerId = card.dataset.playerId;
        attendanceStatus[playerId] = 'present';
        updateAttendance(playerId, 'present');
        card.querySelectorAll('.attendance-btn').forEach(b => b.classList.remove('active'));
        card.querySelector(".present-btn").classList.add('active');
    });
});

markAllAbsentBtn.addEventListener('click', () => {
    document.querySelectorAll('.attendance-card').forEach(card => {
        const playerId = card.dataset.playerId;
        attendanceStatus[playerId] = 'absent';
        updateAttendance(playerId, 'absent');
        card.querySelectorAll('.attendance-btn').forEach(b => b.classList.remove('active'));
        card.querySelector(".absent-btn").classList.add('active');
    });
});

function updateAttendance(playerId, status) {
    const date = attendanceDateInput.value;
    fetch('processing/training.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ playerId, status, date })
    })
    .then(res => res.json())
    .then(data => console.log(data))
    .catch(err => console.error(err));
}

document.getElementById('loadDate').addEventListener('click', () => {
    const date = attendanceDateInput.value;
    const searchTerm = playerSearchInput.value;
    
    fetch('processing/search-player2.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `query=${encodeURIComponent(searchTerm)}&date=${encodeURIComponent(date)}`
    })
    .then(res => res.text())
    .then(html => {
        attendanceContainer.innerHTML = html;
    });
});

playerSearchInput.addEventListener('input', () => {
    const searchTerm = playerSearchInput.value.toLowerCase();
    document.querySelectorAll('.attendance-card').forEach(card => {
        const name = card.querySelector('.player-name').textContent.toLowerCase();
        card.style.display = name.includes(searchTerm) ? '' : 'none';
    });
});

saveAttendanceBtn.addEventListener('click', () => {
    alert('Attendance has been saved and synced in real-time.');
});
