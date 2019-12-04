// Initialize all variables according to HTML elements
var canvas = document.getElementById('canvas');
var canvasCopy = document.getElementById("canvasCopy");
var context = canvas.getContext('2d');
var video = document.getElementById('video');
var snap = document.getElementById('snap');
var overlay_image = document.getElementById("overlay");

//activates webcam
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

// Detects which filter was chosen
snap.addEventListener('click', function() {
    var currentSticker = stickerSelector();
    document.getElementById('sticker').value = currentSticker.src;
    context.drawImage(video, 0, 0, 640, 480);
    context.drawImage(currentSticker, 0, 0, 265, 250);
    canvasCopy.getContext('2d').drawImage(video, 0, 0, 640, 480);
});


// Changes class of selected sticker
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

// Return currently selected sticker
function stickerSelector() {
    var header = document.getElementById("sticker_div");
    var selectedSticker = header.getElementsByClassName("active");
    return selectedSticker[0];
}

function takePhoto(){
    var canvas = document.getElementById("canvasCopy");
    var photo =  document.getElementById("photo");
    photo.value = canvas.toDataURL();
}