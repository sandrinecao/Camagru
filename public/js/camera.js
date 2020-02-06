var canvas = document.getElementById('canvas');
var context = canvas.getContext('2d');
var video = document.getElementById('video');
var snap = document.getElementById('snap');
var overlay_image = document.getElementById("overlay");

navigator.mediaDevices.getUserMedia({ audio: false, video: { width: 640, height: 480 } }).then(mediaStream => {
    video.srcObject = mediaStream
    video.onloadedmetadata = function(e) {
        video.play();
        snap.style.display = "block";
    };
},
function(err) {
    console.log("An error occured! " + err);
});

snap.addEventListener('click', function() {
    var currentSticker = stickerSelector();
    document.getElementById('sticker').value = currentSticker.src;
    context.drawImage(video, 0, 0, 640, 480);
    context.drawImage(currentSticker, 0, 0, 265, 250);
    canvas.getContext('2d').drawImage(video, 0, 0, 640, 480);
});

function stickerSelector() {
    var active_photo = document.getElementsByClassName("active");
    return active_photo[0];
}

var stickerDisplay = document.getElementById("sticker_div");
var stickerImg = stickerDisplay.getElementsByClassName("stickerImg");
for (var i = 0; i < stickerImg.length; i++) {
    stickerImg[i].addEventListener("click", function() {
        active_photo = document.getElementsByClassName("active");
        active_photo[0].className = active_photo[0].className.replace(" active", "");
        this.className += " active";
        overlay_image.src = this.src;
    });
}

function takePhoto(){
    var canvas = document.getElementById("canvas");
    var photo =  document.getElementById("photo");
    photo.value = canvas.toDataURL();
}