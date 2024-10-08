async function uploadFile(file) {
    const chunkSize = 1024 * 1024; // 1 MB
    const totalChunks = Math.ceil(file.size / chunkSize);
    
    for (let currentChunk = 0; currentChunk < totalChunks; currentChunk++) {
        const start = currentChunk * chunkSize;
        const end = Math.min(start + chunkSize, file.size);
        const chunk = file.slice(start, end);
        
        const formData = new FormData();
        formData.append('fileChunk', chunk);
        formData.append('chunkIndex', currentChunk);
        formData.append('totalChunks', totalChunks);
        
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