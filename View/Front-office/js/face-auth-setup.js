const video = document.getElementById('video');
const setupFaceAuthBtn = document.getElementById('setup-face-auth-btn');

Promise.all([
    faceapi.nets.tinyFaceDetector.loadFromUri('/node_modules'),
    faceapi.nets.faceLandmark68Net.loadFromUri('/node_modules'),
    faceapi.nets.faceRecognitionNet.loadFromUri('/node_modules')
]).then(() => {
    setupFaceAuthBtn.addEventListener('click', startVideo);
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
        const response = await fetch('save_face_descriptor.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ faceDescriptor })
        });
        const result = await response.json();
        if (result.success) {
            alert('Face authentication setup successful');
        } else {
            alert('Failed to save face descriptor');
        }
    } else {
        alert('No face detected');
    }
});