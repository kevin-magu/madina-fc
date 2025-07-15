document.addEventListener('DOMContentLoaded', function () {
    const editor = document.getElementById('newsContent');
    const toolbarButtons = document.querySelectorAll('.editor-toolbar button');
    const browseBtn = document.getElementById('browseBtn');
    const fileInput = document.getElementById('fileInput');
    const dropZone = document.getElementById('dropZone');
    const previewContainer = document.getElementById('previewContainer');
    const currentImageInput = document.querySelector('input[name="current_image"]');
    const newsId = document.getElementById('newsForm').dataset.articleId; // assumes <form id="newsForm" data-article-id="...">

    // Focus the editor
    editor.focus();

    // Handle formatting toolbar
    toolbarButtons.forEach(button => {
        button.addEventListener('click', function () {
            const command = button.getAttribute('data-command');

            if (command === 'createLink') {
                const url = prompt('Enter the URL:');
                if (url) {
                    document.execCommand(command, false, url.startsWith('http') ? url : 'https://' + url);
                }
            } else {
                document.execCommand(command, false, null);
            }

            editor.focus();
        });
    });

    // Browse button triggers file input
    browseBtn.addEventListener('click', () => fileInput.click());

    // File input preview
    fileInput.addEventListener('change', function () {
        handleFiles(fileInput.files);
    });

    // Drag-and-drop
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('dragover');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('dragover');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('dragover');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            handleFiles(files);
        }
    });

    // Preview handler
    function handleFiles(files) {
        previewContainer.innerHTML = '';
        const file = files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = 'Preview';
                img.style.maxWidth = '100%';
                previewContainer.appendChild(img);
            };
            reader.readAsDataURL(file);
        } else {
            previewContainer.textContent = 'Only image files are allowed.';
        }
    }

    // Submit updated news
    document.getElementById('newsForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        const title = document.getElementById('newsTitle').value.trim();
        const content = editor.innerHTML;
        const currentImage = currentImageInput?.value || '';

        const formData = new FormData();
        formData.append('newsId', newsId);
        formData.append('newsTitle', title);
        formData.append('newsContent', content);
        formData.append('current_image', currentImage);

        if (fileInput.files.length > 0) {
            formData.append('image', fileInput.files[0]);
        }

        // Debugging
        const formDataObj = {};
        for (const [key, value] of formData.entries()) {
            formDataObj[key] = value instanceof File
                ? { name: value.name, size: value.size, type: value.type }
                : value;
        }
        console.log('FormData being sent:', JSON.stringify(formDataObj, null, 2));

        try {
            const response = await fetch('processing/edit-news.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json(); // Assuming JSON returned
            console.log(result);

            if (result.success) {
                alert('News updated successfully.');
                window.location.href = 'news.php'; // Redirect to admin news list
            } else {
                alert(result.message || 'Update failed.');
            }
        } catch (error) {
            console.error('Edit error:', error);
            alert('Something went wrong while updating.');
        }
    });
});
