document.addEventListener('DOMContentLoaded', function () {
    const editor = document.getElementById('newsContent');
    const toolbarButtons = document.querySelectorAll('.editor-toolbar button');
    const browseBtn = document.getElementById('browseBtn');
    const fileInput = document.getElementById('fileInput');
    const dropZone = document.getElementById('dropZone');
    const previewContainer = document.getElementById('previewContainer');

    // Focus the editor when the page loads
    editor.focus();

    // Handle toolbar formatting buttons
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

    // Click-to-browse file upload
    browseBtn.addEventListener('click', () => fileInput.click());

    // File input preview
    fileInput.addEventListener('change', function () {
        handleFiles(fileInput.files);
    });

    // Drag and drop logic
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
            fileInput.files = files; // update input so form can access file
            handleFiles(files);
        }
    });

    // Show preview
    function handleFiles(files) {
        previewContainer.innerHTML = ''; // Clear previous
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

    // Form submission via fetch
    document.getElementById('newsForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        const title = document.getElementById('newsTitle').value.trim();
        const content = document.getElementById('newsContent').innerHTML;

        const formData = new FormData();
        formData.append('newsTitle', title);
        formData.append('newsContent', content);

        if (fileInput.files.length > 0) {
            formData.append('image', fileInput.files[0]);
        }

        //console
         const formDataObj = {};
    for (const [key, value] of formData.entries()) {
      formDataObj[key] = value instanceof File
        ? { name: value.name, size: value.size, type: value.type }
        : value;
    }
    console.log('FormData being sent:', JSON.stringify(formDataObj, null, 2));


        try {
            const response = await fetch('processing/upload-news.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.text();
            console.log(result)
            alert(result.message);
        } catch (error) {
            console.error('Upload error:', error);
            alert('Something went wrong during upload.');
        }
    });
});
