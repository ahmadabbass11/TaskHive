<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Slack Design - Task Hive</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap">

    <!-- Socket.io -->
    <script src="https://cdn.jsdelivr.net/npm/socket.io-client@2.4.0/dist/socket.io.js"></script>
    <script src="//{{Request::getHost() }}:6001/socket.io/socket.io.js"></script>
    <!-- Axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    {{-- If you’re using Vite + React, keep the line below; otherwise remove it --}}
    @vite('resources/js/app.jsx')

    <style>
        /* Global styles */
        :root {
            --primary-purple: #8e24aa;
            --secondary-purple: #6a1b9a;
            --hover-purple: #7b1fa2;
            --accent-blue: #1e88e5;
            --light-text: #f3f3f3;
            --lighter-text: #e0e0e0;
            --white: #ffffff;
            --black: #000000;
            --border-color: #ddd;
            --bg-grey: #f8f9fa;
            --bubble-grey: #ececec;
            --highlight-green: #26C281;
        }

        body {
            background: linear-gradient(135deg, #8e24aa, #6a1b9a);
            color: var(--light-text);
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            font-size: 1rem;
        }

        /* Header */
        .header {
            background: linear-gradient(90deg, var(--primary-purple), var(--secondary-purple));
            padding: 10px;
            display: flex;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 99;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header .brand {
            color: var(--white);
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0;
        }

        .header .search-bar {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Sidebar */
        .sidebar {
            background: linear-gradient(180deg, var(--primary-purple), var(--secondary-purple));
            height: 100vh;
            overflow-y: auto;
            padding: 15px;
        }

        .sidebar .section-title {
            text-transform: uppercase;
            font-weight: 600;
            color: var(--light-text);
            margin-bottom: 0.75rem;
            margin-top: 1rem;
            letter-spacing: 0.5px;
        }

        .channel,
        .dm {
            color: var(--lighter-text);
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 8px;
            margin-bottom: 8px;
            transition: background-color 0.3s, transform 0.2s;
            display: flex;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            background-color: rgba(255, 255, 255, 0.05);
        }

        .channel:hover,
        .dm:hover {
            background-color: var(--hover-purple);
            transform: translateX(5px);
        }

        .active-channel {
            background-color: var(--accent-blue) !important;
            color: var(--white) !important;
        }

        /* Chat Section */
        .chat {
            background-color: var(--white);
            color: var(--black);
            height: calc(100vh - 60px);
            display: flex;
            flex-direction: column;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .chat-header {
            border-bottom: 1px solid var(--border-color);
            padding: 15px;
            background-color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .chat-header .chat-header-content {
            display: flex;
            align-items: center;
        }

        .chat-header .user-avatar img {
            object-fit: cover;
            border-radius: 50%;
            width: 45px;
            height: 45px;
            border: 2px solid var(--accent-blue);
        }

        .chat-header .user-avatar {
            margin-right: 15px;
        }

        .chat-header .user-display-name {
            font-weight: 600;
            color: var(--primary-purple);
            margin: 0;
            font-size: 1.3rem;
        }

        .chat-header .channel-subtitle {
            font-size: 0.9rem;
            color: #777;
            margin-top: 3px;
        }

        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            background: #f1f2f6;
        }

        .chat-messages .message {
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            opacity: 0;
            animation: fadeIn 0.3s forwards;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }

        .chat-messages .message.you {
            justify-content: flex-end;
        }

        .chat-messages .message-content {
            max-width: 65%;
            padding: 12px 18px;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .chat-messages .message.you .message-content {
            background-color: var(--accent-blue);
            color: var(--white);
        }

        .chat-messages .message.others .message-content {
            background-color: var(--bubble-grey);
            color: var(--black);
        }

        .message-info {
            font-size: 0.8rem;
            color: #666;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
        }

        .message-info .sender-name {
            font-weight: 600;
            margin-right: 10px;
            color: var(--black);
        }

        .message.you .sender-name {
            color: var(--highlight-green);
        }

        .message-info .timestamp {
            font-size: 0.75rem;
            color: #999;
        }

        /* Message Input */
        .message-input {
            border-top: 1px solid var(--border-color);
            padding: 15px;
            display: flex;
            align-items: center;
            gap: 15px;
            background-color: #fff;
        }

        .message-box {
            flex: 1;
            background-color: var(--bg-grey);
            color: var(--black);
            padding: 12px 15px;
            font-size: 1rem;
            border-radius: 8px;
            border: 1px solid #ccc;
            transition: border-color 0.3s;
        }

        .message-box:focus {
            outline: none;
            border-color: var(--accent-blue);
        }

        .btn-send {
            background-color: var(--accent-blue);
            border: none;
            font-size: 1rem;
            padding: 12px 25px;
            cursor: pointer;
            color: var(--white);
            border-radius: 8px;
            transition: background-color 0.3s, transform 0.2s;
        }

        .btn-send:hover {
            background-color: #1565c0;
            transform: scale(1.05);
        }

        #startVideoCall {
            background-color: var(--accent-blue);
            border: none;
            font-size: 1rem;
            padding: 12px 18px;
            cursor: pointer;
            color: var(--white);
            border-radius: 8px;
            transition: background-color 0.3s, transform 0.2s;
        }

        #startVideoCall:hover {
            background-color: #1565c0;
            transform: scale(1.05);
        }

        /* Details Section */
        .details {
            background: linear-gradient(180deg, var(--secondary-purple), var(--primary-purple));
            height: 100vh;
            color: var(--light-text);
            padding: 25px;
            overflow-y: auto;
        }

        .details h6 {
            font-size: 1.3rem;
            margin-bottom: 1.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .profile-section {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 1.2rem;
        }

        .profile-avatar {
            width: 70px;
            height: 70px;
            background-color: var(--light-text);
            border-radius: 50%;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--accent-blue);
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-info {
            font-size: 0.95rem;
        }

        .profile-info .profile-name {
            color: var(--white);
            font-weight: 600;
            margin-bottom: 5px;
        }

        .profile-info .profile-email {
            color: var(--lighter-text);
            margin-bottom: 5px;
        }

        .channel-info {
            font-size: 0.95rem;
            line-height: 1.4;
            color: var(--lighter-text);
        }

        .channel-info h4 {
            color: var(--white);
            margin-bottom: 10px;
        }

        /* Search results dropdown styling */
        #searchResults {
            border: 1px solid #ccc;
            border-radius: 8px;
            display: none;
            position: absolute;
            top: 55px;
            right: 50%;
            z-index: 1000;
            width: 320px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.3s;
        }

        #searchResults p,
        #searchResults li {
            font-family: 'Poppins', sans-serif;
        }

        /* Responsive adjustments */
        @media (max-width: 992px) {

            .col-2,
            .col-7,
            .col-3 {
                flex: 0 0 100% !important;
                max-width: 100% !important;
            }

            .details {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="row align-items-center w-100 m-0 header" style="height: 60px;">
            <!-- Left side: Logo + Brand -->
            <div class="col-auto d-flex align-items-center">
                <img src="{{Vite::asset('resources/assets/taskhivelogo.png') }}" alt="Task Hive Logo"
                    style="width: 60px; height: 55px; object-fit: contain;" />
                <h5 class="brand mb-0 ms-2 text-white">Task Hive</h5>
            </div>

            <!-- Center: Search Bar -->
            <div class="col d-flex justify-content-center position-relative">
                <div class="search-bar d-flex bg-white shadow-sm rounded-pill px-2" style="width: 300px; height: 42px;">
                    <input type="text" class="form-control border-0 shadow-none bg-transparent flex-grow-1 px-3"
                        id="searchInput" placeholder="Search..." style="outline: none;" />
                    <button class="btn btn-dark rounded-pill px-3" id="searchBtn"
                        style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                        Search
                    </button>
                </div>
                <!-- Search Results Container -->
                <div id="searchResults" class="mt-5 bg-white">
                    <!-- Filled dynamically via JS -->
                </div>
                @if (auth()->user()->role === "admin")
                    <a href="{{ route('admin.index') }}">
                        <div class="position-absolute top-0 end-0 mt-0 me-3 adminBtn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="currentColor"
                                class="bi bi-person-circle" viewBox="0 0 16 16">
                                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                                <path fill-rule="evenodd"
                                    d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                            </svg>
                        </div>
                    </a>
                @endif
                @if (auth()->user()->role === "user")
                    <a href="{{ route('user.projects') }}">
                        <div class="position-absolute top-0 end-0 mt-0 me-3 adminBtn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="currentColor"
                                class="bi bi-person-circle" viewBox="0 0 16 16">
                                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                                <path fill-rule="evenodd"
                                    d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                            </svg>
                        </div>
                    </a>
                @endif
            </div>
        </div>

        <div class="row">
            <!-- Sidebar -->
            <div class="col-2 sidebar">
                <div class="section-title">Channels</div>
                <!-- Example: channels iteration (group channels) -->
                @foreach ($group_channels as $channel)
                    <div class="channel" data-id="{{ $channel->id }}">
                        <span>#{{ $channel->name }}</span>
                        <span class="channeldesc" data-id="{{$channel->description}}"></span>
                    </div>
                @endforeach

                <div class="section-title">Direct Messages</div>
                <!-- Use an ID so we can dynamically insert new DMs here -->
                <div id="dmList">
                    @foreach ($private_conversations as $private)
                        <div class="dm channel" data-id="{{ $private->conversation_id }}">
                            <span>{{ $private->name }}</span>
                            <span class="receiverId d-none" data-id="{{ $private->user_id }}"></span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Chat Section -->
            <div class="col-7 chat d-flex flex-column">
                <!-- Chat Header with avatar + name -->
                <div class="chat-header">
                    <div class="chat-header-content">
                        <div class="user-avatar">
                            <!-- Default or dynamic profile image -->
                            <img class="changing-photo" src="https://via.placeholder.com/45" alt="Profile Picture" />
                        </div>
                        <h5 class="mb-0 user-display-name">
                            <!-- Will be updated dynamically -->
                            Select a Channel or DM
                        </h5>
                    </div>
                    <div class="channel-subtitle"></div>
                </div>

                <!-- Messages Container -->
                <div class="chat-messages">
                    <!-- Default placeholder before a channel is selected -->
                    <div class="d-flex flex-column align-items-center justify-content-center h-100">
                        <h1 class="text-muted" style="font-size: 1.8rem;">Start a Conversation!</h1>
                        <p class="text-muted">Choose a channel or DM on the left to begin.</p>
                    </div>
                </div>


                <!-- Input for new messages -->
                <div class="message-input">
                    <input type="file" id="fileInput" class="d-none" />
                    <label for="fileInput" class="btn btn-secondary">
                        <i class="fas fa-paperclip"></i>
                    </label>
                    <input type="text" class="form-control message-box" placeholder="Type your message..." />
                    <button class="btn-send">Send</button>
                    <button id="startVideoCall" class="btn btn-primary">
                        <i class="fas fa-video"></i>
                    </button>
                </div>
            </div>

            <!-- Details Section -->
            <div class="col-3 details">
                <h6>Details</h6>
                <p class="text-muted">
                    Select a user or channel to see more information.
                </p>
            </div>
        </div>

        <!-- Hidden span with the authenticated user ID -->
        <span class="unique d-none" data-id="{{Auth::id() }}"></span>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://media.twiliocdn.com/sdk/js/video/releases/2.23.0/twilio-video.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // -----------------------------------------------------------------------
            // SELECTORS & VARIABLES
            // -----------------------------------------------------------------------
            const channels = document.querySelectorAll(".channel");
            const userId = document.querySelector(".unique")?.getAttribute("data-id") ?? null;
            const description = document.querySelector(".channeldesc")?.getAttribute("data-id") ?? null;
            let activeChannelId = null;
            let currentChannel = null;

            // Chat header elements
            const userDisplayNameEl = document.querySelector('.user-display-name');
            const changingPhoto = document.querySelector('.changing-photo');
            const userAvatarEl = document.querySelector('.user-avatar img');
            const chatSubtitle = document.querySelector('.channel-subtitle');
            const chatMessagesDiv = document.querySelector('.chat-messages');

            // Input elements
            const messageInput = document.querySelector(".message-box");
            const sendButton = document.querySelector(".btn-send");

            // Details section
            const detailsDiv = document.querySelector(".details");

            // Search elements
            const searchBtn = document.getElementById('searchBtn');
            const searchInput = document.getElementById('searchInput');
            const searchResults = document.getElementById('searchResults');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            console.log(csrfToken);

            document.getElementById("startVideoCall").addEventListener("click", () => {
                // Opens the video call page in a new window.
                window.open('/video-call?type=video', '_blank', 'width=1280,height=720');
            });

            // -----------------------------------------------------------------------
            // FUNCTIONS
            // -----------------------------------------------------------------------

            // Helper: Create message bubble HTML
            function createMessageBubble(isCurrentUser, senderName, time, content, messageType, filePath, mimeType) {
                const localTime = new Date(time).toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit'
                });

                let messageContent = "";

                if (messageType === "file" && filePath) {
                    if (mimeType.startsWith("image/")) {
                        // If it's an image, display it
                        messageContent = `<img src="${filePath}" class="img-fluid rounded" style="max-width: 250px;">`;
                    } else {
                        // If it's a document, provide a download button
                        messageContent = `<a href="${filePath}" target="_blank" class="btn btn-sm btn-outline-primary">
                                📄 Download File
                              </a>`;
                    }
                } else {
                    // If it's a text message, display it normally
                    messageContent = `<p class="m-0">${content}</p>`;
                }

                return `
        <div class="message ${isCurrentUser ? 'you' : 'others'}">
            <div class="message-content">
                <div class="message-info">
                    <span class="sender-name">${isCurrentUser ? 'You' : senderName}</span>
                    <span class="timestamp">${localTime}</span>
                </div>
                ${messageContent}
            </div>
        </div>
    `;
            }


            function subscribeToChannel(channelId) {
                if (currentChannel) {
                    window.Echo.leaveChannel(currentChannel.name);
                }

                activeChannelId = channelId;
                currentChannel = window.Echo.channel("chat." + channelId);

                console.log("Subscribed to:", "chat." + channelId);

                currentChannel.listen(".MessageSent", (e) => {
                    console.log("Real-time event received:", e);

                    let messageHtml = `
            <div class="message ${e.sender_id == userId ? 'you' : 'others'}">
                <div class="message-content">
                    <div class="message-info">
                        <span class="sender-name">${e.sender_id == userId ? 'You' : e.sender_name}</span>
                        <span class="timestamp">${new Date().toLocaleTimeString()}</span>
                    </div>
        `;

                    if (e.message_type === "file") {
                        if (e.mime_type.startsWith("image/")) {
                            messageHtml += `<img src="${e.file_path}" style="max-width: 200px; border-radius: 5px; margin-top: 5px;">`;
                        } else {
                            messageHtml += `<a href="${e.file_path}" download class="btn btn-secondary btn-sm mt-2">
                    <i class="fas fa-download"></i> ${e.file_path.split('/').pop()}
                </a>`;
                        }
                    } else {
                        messageHtml += `<p>${e.content}</p>`;
                    }

                    messageHtml += `</div></div>`;
                    chatMessagesDiv.innerHTML += messageHtml;
                    chatMessagesDiv.scrollTop = chatMessagesDiv.scrollHeight;
                }).error((error) => {
                    console.error("Echo Subscription Error:", error);
                });
            }




            // Update details section
            function updateDetailsSection(receiverId, channelName) {
                console.log("receiver id: ", receiverId);
                if (receiverId) {
                    fetch(`/user/${receiverId}`)
                        .then(response => response.json())
                        .then(user => {
                            changingPhoto.src = `storage/${user.profile_picture}`;
                            detailsDiv.innerHTML = `
                <h6>User Details</h6>
                <div class="profile-section">
                  <div class="profile-avatar">
                    <img src="storage/${user.profile_picture}" alt="Profile Picture">
                  </div>
                  <div class="profile-info">
                    <div class="profile-name">${user.name}</div>
                    <div class="profile-email">${user.email}</div>
                  </div>
                </div>
              `;
                        })
                        .catch(err => console.error('Error fetching user:', err));
                } else {
                    detailsDiv.innerHTML = `
            <h6>Channel Details</h6>
            <div class="channel-info">
              <h4>${channelName.replace('#', '')}</h4>
              <p>
                This is the official channel for <strong>${channelName}</strong>.<br/>
              </p>
              <p>
                <em>channel description: ${description}</em>
                <br/><span>Discuss relevant tasks, strategy, or any updates.</span>
              </p>
            </div>
          `;
                }
            }

            // Send message to the server
            function sendMessage() {
                if (!activeChannelId) {
                    alert("Please select a channel first!");
                    return;
                }

                let messageContent = messageInput.value.trim();
                let file = fileInput.files[0];

                if (!messageContent && !file) {
                    alert("Please enter a message or upload a file.");
                    return;
                }

                let formData = new FormData();
                formData.append("channel_id", activeChannelId);

                if (messageContent) {
                    formData.append("message", messageContent);
                }

                if (file) {
                    formData.append("file", file);
                }

                axios.post("/messages/send", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                        "X-CSRF-TOKEN": csrfToken,
                    },
                })
                    .then(response => {
                        console.log("Message sent:", response.data);

                        // Immediately display message locally for sender
                        let newMessage = response.data.data; // Extract message data
                        let messageHtml = `
            <div class="message you">
                <div class="message-content">
                    <div class="message-info">
                        <span class="sender-name">You</span>
                        <span class="timestamp">${new Date().toLocaleTimeString()}</span>
                    </div>
        `;

                        if (newMessage.message_type === "file") {
                            if (newMessage.mime_type.startsWith("image/")) {
                                messageHtml += `<img src="${newMessage.file_path}" style="max-width: 200px; border-radius: 5px; margin-top: 5px;">`;
                            } else {
                                messageHtml += `<a href="${newMessage.file_path}" download class="btn btn-secondary btn-sm mt-2">
                    <i class="fas fa-download"></i> ${newMessage.file_path.split('/').pop()}
                </a>`;
                            }
                        } else {
                            messageHtml += `<p>${newMessage.message_content}</p>`;
                        }

                        messageHtml += `</div></div>`;
                        chatMessagesDiv.innerHTML += messageHtml;
                        chatMessagesDiv.scrollTop = chatMessagesDiv.scrollHeight;

                        messageInput.value = "";
                        fileInput.value = "";
                        fileInput.type = "";
                        fileInput.type = "file";
                    })
                    .catch(error => {
                        console.error("Error sending message:", error);
                    });
            }



            /**
             * Handle clicking on a channel or DM
             */
            function handleChannelClick(channelElement) {
                document.querySelectorAll('.channel').forEach(ch => {
                    ch.classList.remove('active-channel');
                });
                channelElement.classList.add('active-channel');

                const channelName = channelElement.textContent.trim();
                const channelId = channelElement.getAttribute('data-id');
                activeChannelId = channelId;

                userDisplayNameEl.textContent = channelName;
                userAvatarEl.src = "";
                chatSubtitle.textContent = "";
                chatMessagesDiv.innerHTML = "";

                fetch(`/messages/${channelId}`)
                    .then(response => response.json())
                    .then(messages => {
                        chatMessagesDiv.innerHTML = "";
                        messages.forEach(msg => {
                            const isCurrentUser = (msg.sender_id == userId);
                            chatMessagesDiv.innerHTML += createMessageBubble(
                                isCurrentUser,
                                msg.sender_name,
                                msg.created_at,
                                msg.message_content,
                                msg.message_type,  // ✅ Add message type
                                msg.file_path,     // ✅ Add file path (if it's a file)
                                msg.mime_type      // ✅ Add mime type (for file preview)
                            );
                        });
                        chatMessagesDiv.scrollTop = chatMessagesDiv.scrollHeight;
                    })
                    .catch(err => console.error('Error fetching messages:', err));

                const receiverSpan = channelElement.querySelector(".receiverId");
                const receiverId = receiverSpan ? receiverSpan.getAttribute("data-id") : null;
                updateDetailsSection(receiverId, channelName);

                if (!currentChannel || currentChannel.name !== `chat.${channelId}`) {
                    if (currentChannel) {
                        currentChannel.stopListening('.MessageSent');
                    }
                    subscribeToChannel(channelId);
                }
            }

            // -----------------------------------------------------------------------
            // EVENT LISTENERS
            // -----------------------------------------------------------------------

            channels.forEach(channel => {
                channel.addEventListener("click", () => {
                    handleChannelClick(channel);
                });
            });

            sendButton.addEventListener("click", sendMessage);
            messageInput.addEventListener("keydown", (e) => {
                if (e.key === "Enter") {
                    e.preventDefault();
                    sendMessage();
                }
            });

            searchBtn.addEventListener('click', () => {
                const term = searchInput.value.trim();
                if (!term) {
                    searchResults.style.display = 'none';
                    searchResults.innerHTML = '';
                    return;
                }

                searchResults.style.display = 'block';

                fetch(`/search-users?term=${encodeURIComponent(term)}`)
                    .then(response => response.json())
                    .then(users => {
                        if (!Array.isArray(users) || users.length === 0) {
                            searchResults.innerHTML = `<p class='p-2 mb-0'>No users found.</p>`;
                        } else {
                            let html = `<ul class="list-group">`;
                            users.forEach(user => {
                                html += `
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                      <strong>${user.name}</strong><br>
                      <small>${user.email}</small>
                    </div>
                    <button 
                      class="btn btn-sm btn-primary startChatBtn" 
                      data-partner-id="${user.id}"
                      data-partner-name="${user.name}"
                    >
                      Start Chat
                    </button>
                  </li>
                `;
                            });
                            html += `</ul>`;
                            searchResults.innerHTML = html;
                        }
                    })
                    .catch(err => {
                        console.error('Search error:', err);
                        searchResults.innerHTML = `<p class='p-2 mb-0 text-danger'>Error occurred. See console.</p>`;
                    });
            });

            searchResults.addEventListener('click', (e) => {
                if (e.target.classList.contains('startChatBtn')) {
                    const partnerId = e.target.getAttribute('data-partner-id');
                    const partnerName = e.target.getAttribute('data-partner-name');

                    fetch('/create-conversation', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({ partner_id: partnerId })
                    })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(errData => {
                                    throw new Error(errData.error || 'Unknown error.');
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            const { conversation_id } = data;
                            const dmList = document.getElementById('dmList');
                            const newDM = document.createElement('div');
                            newDM.classList.add('dm', 'channel');
                            newDM.setAttribute('data-id', conversation_id);
                            newDM.textContent = partnerName;
                            const receiverSpan = document.createElement('span');
                            receiverSpan.classList.add('receiverId', 'd-none');
                            receiverSpan.setAttribute('data-id', partnerId);
                            newDM.appendChild(receiverSpan);
                            dmList.appendChild(newDM);
                            newDM.addEventListener("click", () => {
                                handleChannelClick(newDM);
                            });
                            newDM.click();
                            searchResults.style.display = 'none';
                        })
                        .catch(err => {
                            alert(err.message);
                            console.error('Create conversation error:', err);
                        });
                }
            });

            document.addEventListener('click', (e) => {
                if (!e.target.closest('.search-bar') && !e.target.closest('#searchResults')) {
                    searchResults.style.display = 'none';
                }
            });
        });
    </script>
</body>

</html>