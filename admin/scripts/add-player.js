document.addEventListener('DOMContentLoaded', function () {
    const photoBrowseBtn = document.getElementById('photoBrowseBtn');
    const photoInput = document.getElementById('photoInput');
    const photoDropZone = document.getElementById('photoDropZone');
    const photoPreviewContainer = document.getElementById('photoPreviewContainer');

    // Click-to-browse
    photoBrowseBtn.addEventListener('click', () => photoInput.click());

    // File input preview
    photoInput.addEventListener('change', function () {
        handleFiles(photoInput.files);
    });

    // Drag & drop
    photoDropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        photoDropZone.classList.add('dragover');
    });

    photoDropZone.addEventListener('dragleave', () => {
        photoDropZone.classList.remove('dragover');
    });

    photoDropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        photoDropZone.classList.remove('dragover');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            photoInput.files = files;
            handleFiles(files);
        }
    });

    function handleFiles(files) {
        photoPreviewContainer.innerHTML = '';
        const file = files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = 'Preview';
                img.style.maxWidth = '100%';
                photoPreviewContainer.appendChild(img);
            };
            reader.readAsDataURL(file);
        } else {
            photoPreviewContainer.textContent = 'Only image files are allowed.';
        }
    }

    // Submit form via Fetch
    document.getElementById('playerForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        const formData = new FormData();
        formData.append('playerName', document.getElementById('playerName').value.trim());
        formData.append('jerseyNumber', document.getElementById('jerseyNumber').value.trim());
        formData.append('position', document.getElementById('position').value);
        formData.append('nationality', document.getElementById('nationality').value.trim());
        formData.append('dob', document.getElementById('dob').value);
        formData.append('height', document.getElementById('height').value);
        formData.append('weight', document.getElementById('weight').value);

        if (photoInput.files.length > 0) {
            formData.append('playerPhoto', photoInput.files[0]);
        }
        console.log('This is the player submission page')
        // Debugging
        const debugData = {};
        for (const [key, value] of formData.entries()) {
            debugData[key] = value instanceof File
                ? { name: value.name, size: value.size, type: value.type }
                : value;
        }
        console.log('Uploading player data:', JSON.stringify(debugData, null, 2));

        try {
            const response = await fetch('processing/add-player.php', {
                method: 'POST',
                body: formData,
                credentials: "same-origin",
            });

            const result = await response.text();
            console.log(result);

            if (result) {
                alert('Player added successfully.');
               // window.location.href = 'players.php';
            } else {
                alert(result.message || 'Upload failed.');
            }
        } catch (error) {
            console.error('Upload error:', error);
            alert('Something went wrong during upload.');
        }
    });
});
