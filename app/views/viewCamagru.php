
<div class="center">
    <video id="video" width="640px" autoplay></video>
    <canvas class="center" id="canvas" value="pic" height="480px" width="640px"></canvas>
    <div id="cam-button">
        <button id="snap" type="button" class="btn-round rounded"><i class="fas fa-camera"></i></button>
        <form action="<?= URL ?>?url=camagru&submit=pic" method="POST">
            <button id="snap-push" type="submit" name="pic" class="btn-round rounded"><i class="fas fa-check"></i></button>
        </form>
    </div>
</div>

<script>
    // Grab elements, create settings, etc.
    var video = document.getElementById('video');
    var preview = document.getElementById('canvas');

    // Get access to the camera!
    if(navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        // Not adding `{ audio: true }` since we only want video now
        navigator.mediaDevices.getUserMedia({ video: true }).then(function(stream) {
            //video.src = window.URL.createObjectURL(stream);
            video.srcObject = stream;
            video.play();
        });
    }

    else if (navigator.getUserMedia) { // Standard
        navigator.getUserMedia({ video: true }, function(stream) {
            video.src = stream;
            video.play();
        }, errBack);
    } else if(navigator.webkitGetUserMedia) { // WebKit-prefixed
        navigator.webkitGetUserMedia({ video: true }, function(stream){
            video.src = window.webkitURL.createObjectURL(stream);
            video.play();
        }, errBack);
    } else if(navigator.mozGetUserMedia) { // Mozilla-prefixed
        navigator.mozGetUserMedia({ video: true }, function(stream){
            video.srcObject = stream;
            video.play();
        }, errBack);
    }

    var canvas = document.getElementById('canvas');
    var context = canvas.getContext('2d');
    var video = document.getElementById('video');
    var save =   document.getElementById('snap-push');
    var cam = true;

    // Trigger photo take
    document.getElementById("snap").addEventListener("click", () => {
        if (cam) {
            context.drawImage(video, 0, 0, 640, 480);
            video.style.display = 'none';
            preview.style.display = 'block';
            preview.style.margin = 'auto';
            document.getElementById('snap-push').style.display = 'block';
            cam = false;
        } else {
            video.style.display = 'block';
            preview.style.display = 'none';
            save.style.display = 'none';
            cam = true;
        }
    });
</script>