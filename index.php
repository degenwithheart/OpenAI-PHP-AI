<!DOCTYPE html>
<html>
<head>
    <title>StewieMo Assistant</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <section class="section">
        <div class="container">
            <h1 class="title">StewieMo Assistant</h1>
            <div id="chat-container" class="box">
                <?php
                    // Start the session and include the stewiemo.php file
                    session_start();
                    include 'stewiemo.php';

                    // Enhanced logging function
                    function logMessage($message) {
                        $logFile = 'log.txt';
                        $timestamp = date('Y-m-d H:i:s');
                        file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
                    }

                    // Check if a message was posted
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
                        $input = $_POST['message'];
                        logMessage("User input: $input");

                        // Generate response from ChatGPT
                        $response = chatGPT([['role' => 'user', 'content' => $input]]);
                        logMessage("ChatGPT response: $response");

                        // Add response to session messages
                        $_SESSION['messages'][] = ['role' => 'user', 'content' => $input];
                        $_SESSION['messages'][] = ['role' => 'assistant', 'content' => $response];
                    }

                    // Display conversation history
                    if (isset($_SESSION['messages'])) {
                        foreach ($_SESSION['messages'] as $message) {
                            echo '<div class="' . $message['role'] . '">' . htmlspecialchars($message['content']) . '</div>';
                        }
                    }

                    // Handle session clear
                    if (isset($_POST['clear']) && $_POST['clear'] == '1') {
                        session_destroy();
                        header("Location: " . $_SERVER['PHP_SELF']);
                        exit();
                    }
                ?>
            </div>
            <form method="post">
                <div class="field is-grouped">
                    <p class="control is-expanded">
                        <input name="message" id="user-input" class="input" type="text" placeholder="Enter your message">
                    </p>
                    <p class="control">
                        <button class="button is-primary" type="submit">Send</button>
                    </p>
                    <p class="control">
                        <button class="button is-danger" type="submit" name="clear" value="1">Clear</button>
                    </p>
                </div>
            </form>
            <div id="loader">
                <progress class="progress is-small is-primary" max="100"></progress>
            </div>
        </div>
    </section>

    <script src="script.js"></script>
</body>
</html>
