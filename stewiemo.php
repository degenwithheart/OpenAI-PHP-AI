<?php

// OpenAI API configuration
$apiKey = '...'; // Replace ... with your OpenAI API key

// Function to send request to OpenAI API
function callOpenAI($endpoint, $data) {
    global $apiKey;

    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey,
    ];

    $ch = curl_init($endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// Function to send requests related to ChatGPT to OpenAI API
function chatGPT($messages) {
    // Handling to clear session
    if ($messages[count($messages) - 1]['content'] === 'clear-session') {
        session_destroy(); // Destroy session
        return 'Session cleared. Please start a new conversation.'; // Inform user
    }

    $endpoint = 'https://api.openai.com/v1/chat/completions';
    $data = [
        'model' => 'gpt-3.5-turbo', // Specify the model to use
        'messages' => $messages,
        'max_tokens' => 500, // Maximum tokens for response (adjust as needed)
        'temperature' => 0.7, // Response diversity (set between 0.0 to 1.0)
        'n' => 1, // Number of responses to generate (adjust as needed)
    ];

    $response = callOpenAI($endpoint, $data);

    return $response['choices'][0]['message']['content'];
}

// Processing user input and generating ChatGPT response
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = $_POST['message'];

    // Retrieve conversation history from session
    session_start();
    $messages = isset($_SESSION['messages']) ? $_SESSION['messages'] : [];

    // Add user input to conversation history
    $messages[] = ['role' => 'user', 'content' => $input'];

    // Generate response from ChatGPT
    $response = chatGPT($messages);

    // Add ChatGPT response to conversation history
    $messages[] = ['role' => 'assistant', 'content' => $response'];

    // Save conversation history to session
    $_SESSION['messages'] = $messages;

    echo $response;
    exit(); // Exit after sending response
}

?>
