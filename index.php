<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript">
         window.onload = () => {
            async function uploadFile(file) {
                const chunkSize = 1024 * 1024;
                const totalChunks = Math.ceil(file.size / chunkSize);
                
                for (let currentChunk = 0; currentChunk < totalChunks; currentChunk++) {
                    const start = currentChunk * chunkSize;
                    const end = Math.min(start + chunkSize, file.size);
                    const chunk = file.slice(start, end);
                    const formData = new FormData();
                    formData.append('fileChunk', chunk);
                    formData.append('chunkIndex', currentChunk);
                    formData.append('totalChunks', totalChunks);
                    formData.append('extension', file.name.split('.').pop());
                    try {
                        await fetch('upload.php', {
                            method: 'POST',
                            body: formData,
                        });
                        console.log(`Chunk ${currentChunk + 1} of ${totalChunks} uploaded successfully.`);
                    } catch (error) {
                        console.error(`Error uploading chunk ${currentChunk + 1}:`, error);
                        currentChunk--; // Повторить загрузку
                    }
                }
                
                console.log('File upload completed successfully.');
            }

            // Пример использования
            const fileInput = document.querySelector('input[type="file"]');
            fileInput.addEventListener('change', (event) => {
                const file = event.target.files[0];
                if (file) {
                    uploadFile(file);
                }
            });
        }
    </script>
    <title>Document</title>
</head>
<body>
    
</body>
</html>

<form enctype="multipart/form-data" onsubmit="uploadFile(file)">
    <input type="file" name="fileChunk">
    <input type="button" id="pick" value="Upload">
</form>