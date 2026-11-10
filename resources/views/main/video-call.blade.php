<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Call - Task Hive</title>
    
    <!-- Bootstrap & FontAwesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <style>
        body {
            margin: 0;
            background-color: #18191a;
            color: #e8eaed;
            font-family: 'Arial', sans-serif;
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
        }

        #video-grid {
            flex: 1;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 10px;
            padding: 20px;
            justify-content: center;
        }

        .video-container {
            position: relative;
            background-color: black;
            border-radius: 15px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.5);
        }

        .video-container video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

    
        #controls {
    position: fixed;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    background-color: rgba(32, 33, 36, 0.9);
    padding: 15px 25px;
    border-radius: 50px;
    display: flex;
    align-items: center; /* Aligns buttons vertically */
    justify-content: center; /* Centers buttons horizontally */
    gap: 20px;
}

.control-button {
    background-color: #3c4043;
    border: none;
    border-radius: 50%;
    width: 60px;
    height: 60px;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    color: #e8eaed;
    font-size: 20px; /* Adjusted size */
    line-height: 60px; /* Helps center icon vertically */
    transition: background-color 0.3s;
}


        .control-button:hover {
            background-color: #5f6368;
        }

        .control-button.leave {
            background-color: #ea4335;
        }
        @media (max-width: 600px) {
    #controls {
        flex-wrap: wrap;
        padding: 10px;
        gap: 10px;
    }
    
    .control-button {
        width: 50px;
        height: 50px;
        font-size: 18px;
    }
}

    </style>
</head>
<body>
    <div id="video-grid"></div>
    <div id="controls">
        <button id="mute" class="control-button" title="Mute/Unmute"><i class="fas fa-microphone"></i></button>
        <button id="toggle-video" class="control-button" title="Stop/Start Video"><i class="fas fa-video"></i></button>
        <button id="share-screen" class="control-button" title="Share Screen"><i class="fas fa-desktop"></i></button>
        <button id="leave-call" class="control-button leave" title="Leave Call"><i class="fas fa-phone-slash"></i></button>
    </div>

    <!-- Twilio SDK -->
    <script src="https://media.twiliocdn.com/sdk/js/video/releases/2.23.0/twilio-video.min.js"></script>

    <script>
        let twilioRoom = null;
        let localTracks = [];
        let screenTrack = null;
        let isAudioEnabled = true;
        let isVideoEnabled = true;
        const videoGrid = document.getElementById("video-grid");

        async function startVideoCall() {
            const roomName = prompt("Enter a room name:");
            if (!roomName) return;

            const response = await fetch('/generate-token', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ room: roomName })
            });
            const { token } = await response.json();

            localTracks = await Twilio.Video.createLocalTracks({ video: true, audio: true });
            twilioRoom = await Twilio.Video.connect(token, { name: roomName, tracks: localTracks });
            addParticipantVideo(twilioRoom.localParticipant);
            twilioRoom.participants.forEach(addParticipantVideo);
            twilioRoom.on('participantConnected', addParticipantVideo);
            twilioRoom.on('participantDisconnected', removeParticipantVideo);
        }

        function addParticipantVideo(participant) {
            const container = document.createElement('div');
            container.classList.add('video-container');
            container.id = participant.sid;
            participant.tracks.forEach(publication => {
                if (publication.track) container.appendChild(publication.track.attach());
            });
            participant.on('trackSubscribed', track => container.appendChild(track.attach()));
            videoGrid.appendChild(container);
        }

        function removeParticipantVideo(participant) {
            document.getElementById(participant.sid)?.remove();
        }

        document.getElementById('mute').addEventListener('click', () => {
            isAudioEnabled = !isAudioEnabled;
            localTracks.forEach(track => { if (track.kind === 'audio') track.enable(isAudioEnabled); });
        });

        document.getElementById('toggle-video').addEventListener('click', () => {
            isVideoEnabled = !isVideoEnabled;
            localTracks.forEach(track => { if (track.kind === 'video') track.enable(isVideoEnabled); });
        });

        document.getElementById('share-screen').addEventListener('click', async () => {
    try {
        if (screenTrack) {
            // Stop sharing and revert to the camera
            twilioRoom.localParticipant.unpublishTrack(screenTrack);
            screenTrack.stop();
            screenTrack = null;

            // Restart original camera feed
            const newVideoTrack = await Twilio.Video.createLocalVideoTrack();
            twilioRoom.localParticipant.publishTrack(newVideoTrack);
            replaceTrackInUI(newVideoTrack);
        } else {
            // Get screen stream
            const screenStream = await navigator.mediaDevices.getDisplayMedia({ video: true });
            screenTrack = new Twilio.Video.LocalVideoTrack(screenStream.getTracks()[0]);

            // Publish the screen track
            twilioRoom.localParticipant.publishTrack(screenTrack);

            // Ensure the screen video replaces the current video in UI
            replaceTrackInUI(screenTrack);
        }
    } catch (error) {
        console.error('Error in screen sharing:', error);
    }
});

// Helper function to replace the displayed video in the UI
function replaceTrackInUI(newTrack) {
    const localVideoContainer = document.querySelector('.video-container');
    if (localVideoContainer) {
        localVideoContainer.innerHTML = '';
        localVideoContainer.appendChild(newTrack.attach());
    }
}

        document.getElementById('leave-call').addEventListener('click', () => {
            if (twilioRoom) twilioRoom.disconnect();
            window.close();
        });

        startVideoCall();
    </script>
</body>
</html>