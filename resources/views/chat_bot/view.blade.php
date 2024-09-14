<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Dashboard') }}</title>
    <link rel="stylesheet" href="/style.css?v={{ time() }}">
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

</head>

<body class="app_bg">


    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script>
        const start = [
            "Hello, my dear friend! I'm a Caesium chatbot here to help you with your questions.",
            "Greetings! I'm your Caesium chatbot companion, ready to assist you.",
            "Welcome! I'm the Caesium chatbot, designed to provide answers to your inquiries.",
            "Hi there! I'm your trusty Caesium chatbot, dedicated to resolving your queries.",
            "Good day! I'm the Caesium chatbot, at your service to assist with any questions you have.",
        ];

        const random = Math.floor(Math.random() * start.length);
        const welcomeMessage = (random, start[random]);
        var botmanWidget = {
            aboutText: '',
            title: "",
            isChatOpen: false,
            mainColor: '#2644d7',
            sendIconColor: '#ff4b14 ',
            bubbleBackground: '#2644d7',
            frameEndpoint: window.location.pathname.split('/')[0] + '/botman/chatfrane',
            introMessage: "<img class='chat_icon_logo' src='/assets/img/favicon.ico' alt=''>" + welcomeMessage
        };
    </script>

    <!-- <script defer src='https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js'></script> -->
    <script defer src='/widget.js?v={{ time() }}'></script>
    <script>
        function loadChat() {
            setTimeout(() => {
                // document.querySelector(".desktop-closed-message-avatar").click();
                // document.querySelector(".mobile-closed-message-avatar").click();
                //  $('.desktop-closed-message-avatar').click();
                // alert(55);

            }, 0);
        }
    </script>

</body>

</html>
