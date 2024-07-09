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
            <div id="chat-container" class="box"></div>
            <div class="field is-grouped">
                <p class="control is-expanded">
                    <input id="user-input" class="input" type="text" placeholder="Enter your message">
                </p>
                <p class="control">
                    <button class="button is-primary" onclick="sendMessage()">Send</button>
                </p>
                <p class="control">
                    <button class="button is-danger" onclick="clearSession()">Clear</button>
                </p>
            </div>
            <div id="loader">
                <progress class="progress is-small is-primary" max="100"></progress>
            </div>
        </div>
    </section>

    <script src="script.js"></script>
</body>
</html>
