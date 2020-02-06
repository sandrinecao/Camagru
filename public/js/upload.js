var canvas = document.getElementById('canvas');
var context = canvas.getContext('2d');
var picture = document.getElementById('upload_img');
var upload = document.getElementById('uploadBtt');
var overlay_image = document.getElementById("overlay");

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

function stickerSelector() {
    var active_photo = document.getElementsByClassName("active");
    return active_photo[0];
}

document.getElementById('uploadPic').onchange = function(e) {
    var output = document.getElementById('upload_img');
    var saveBtt = document.getElementById('uploadBtt');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.style.display="block";
    saveBtt.style.display="block";
  };

upload.addEventListener('click', function() {
    var currentSticker = stickerSelector();
    document.getElementById('sticker').value = currentSticker.src;
    context.drawImage(picture, 0, 0, 640, 480);
    context.drawImage(currentSticker, 0, 0, 265, 250); 
    canvas.getContext('2d').drawImage(picture, 0, 0, 640, 480);
});

function uploadPhoto(){
    var canvas = document.getElementById("canvas");
    var photo = document.getElementById("photo");
    photo.value = canvas.toDataURL();
}