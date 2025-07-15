// scripts/edit-player.js
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('playerForm');
    const fileInput = document.getElementById('photoInput');
    const browseBtn = document.getElementById('photoBrowseBtn');
    const dropZone = document.getElementById('photoDropZone');
    const previewContainer = document.getElementById('photoPreviewContainer');

    // Handle form submission
    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

        try {
            const response = await fetch('processing/edit-player.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();
            if (result.success) {
                alert('Player updated successfully!');
                // window.location.href = 'admin-players.php';
            } else {
                alert('Error: ' + result.message);
                console.error(result);
            }
        } catch (error) {
            console.error('Request failed:', error);
            alert('Failed to update player.');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save"></i> Save Changes';
        }
    });

    // Click-to-browse trigger
    browseBtn.addEventListener('click', () => {
        fileInput.click();
    });

    // File input change handler
    fileInput.addEventListener('change', function () {
        handleFiles(fileInput.files);
    });

    // Drag over drop zone
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('dragover');
    });

    // Drag leave drop zone
    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('dragover');
    });

    // Drop files into drop zone
    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('dragover');

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files; // so form can send the file
            handleFiles(files);
        }
    });

    // Preview uploaded image
    function handleFiles(files) {
        previewContainer.innerHTML = ''; // Clear previous previews
        const file = files[0];

        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = 'Photo Preview';
                img.style.maxWidth = '100%';
                img.style.borderRadius = '8px';
                previewContainer.appendChild(img);
            };
            reader.readAsDataURL(file);
        } else {
            previewContainer.textContent = 'Only image files are allowed.';
        }
    }
});
