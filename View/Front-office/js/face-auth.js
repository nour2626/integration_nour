const video = document.getElementById('video');
const faceAuthBtn = document.getElementById('face-auth-btn');

Promise.all([
    faceapi.nets.tinyFaceDetector.loadFromUri('/node_modules'),
    faceapi.nets.faceLandmark68Net.loadFromUri('/node_modules'),
    faceapi.nets.faceRecognitionNet.loadFromUri('/node_modules')
]).then(() => {
    faceAuthBtn.addEventListener('click', startVideo);
});

function startVideo() {
    video.style.display = 'block';
    navigator.getUserMedia(
        { video: {} },
        stream => video.srcObject = stream,
        err => console.error(err)
    );
}

video.addEventListener('play', async () => {
    const detections = await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks().withFaceDescriptors();
    if (detections.length > 0) {
        const faceDescriptor = detections[0].descriptor;
        const response = await fetch('face_login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ faceDescriptor })
        });
        const result = await response.json();
        if (result.success) {
            window.location.href = 'index.php';
        } else {
            alert('Face not recognized');
        }
    } else {
        alert('No face detected');
    }
});