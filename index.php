<?php

$BOT_TOKEN = "6748460867:AAFzQkFcCfg1kqISiV4499pGxIcPtu4qe1w";

// Function to send file to Telegram
function sendFileToTelegram($telegram_id, $file_path) {
    $url = "https://api.telegram.org/bot$BOT_TOKEN/sendDocument";
    
    $post_fields = array(
        'chat_id' => $telegram_id,
        'document' => new CURLFile(realpath($file_path))
    );

    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:multipart/form-data"));
    curl_setopt($ch, CURLOPT_URL, $url); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields); 
    $output = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($output, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['file']) && isset($_POST['telegramID'])) {
        $telegram_id = $_POST['telegramID'];
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_name = $_FILES['file']['name'];
        $upload_dir = 'uploads/';
        
        // Create upload directory if not exists
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_path = $upload_dir . basename($file_name);
        
        // Move uploaded file to upload directory
        if (move_uploaded_file($file_tmp, $file_path)) {
            // Send file to Telegram
            $response = sendFileToTelegram($telegram_id, $file_path);
            
            // Delete the uploaded file after sending
            unlink($file_path);
            
            if ($response['ok']) {
                echo json_encode(array('message' => 'File sent successfully to your Telegram!'));
            } else {
                echo json_encode(array('message' => 'Failed to send file. Check Telegram ID or Bot setup.'));
            }
        } else {
            echo json_encode(array('message' => 'File upload failed.'));
        }
    } else {
        echo json_encode(array('message' => 'Invalid request.'));
    }
}
?>