<?php
$uploadDir = 'uploads/';
$chunkIndex = $_POST['chunkIndex'];
$totalChunks = $_POST['totalChunks'];

$location = __DIR__ . "/uploads";
if (!file_exists($location)) {
    if (!mkdir($location, 0777, true)) {
        verbose(0, "Failed to create $location");
    }
}

$tempFilePath = $uploadDir . 'chunk_' . $chunkIndex;
var_dump($_FILES['fileChunk']);
if (isset($_FILES['fileChunk'])) {
    if (move_uploaded_file($_FILES['fileChunk']['tmp_name'], $tempFilePath)) {
        
        $finalFilePath = $uploadDir . time() . "." . $_POST['extension']; 
        $fp = fopen($finalFilePath, 'ab'); 
        fwrite($fp, file_get_contents($tempFilePath));
        fclose($fp);
        
        unlink($tempFilePath);
        
        if ($chunkIndex + 1 == $totalChunks) {
            echo json_encode(['status' => 'complete']);
        } else {
            echo json_encode(['status' => 'success']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to upload chunk.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No chunk received.']);
}
?>