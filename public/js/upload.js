// Initialize all variables according to HTML elements
var canvas = document.getElementById('canvas');
var canvasCopy = document.getElementById("canvasCopy");
var context = canvas.getContext('2d');
var picture = document.getElementById('upload_img');
var upload = document.getElementById('uploadBtt');
var overlay_image = document.getElementById("overlay");


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

document.getElementById('uploadPic').onchange = function(e) {
    var output = document.getElementById('upload_img');
    var saveBtt = document.getElementById('uploadBtt');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.style.display="block";
    saveBtt.style.display="block";
  };

function uploadPhoto(){
    var canvas = document.getElementById("canvasCopy");
    var photo =  document.getElementById("photo");
    photo.value = canvas.toDataURL();
}


upload.addEventListener('click', function() {
    var currentSticker = stickerSelector();
    document.getElementById('sticker').value = currentSticker.src;
    console.log(currentSticker.src);
    context.drawImage(picture, 0, 0, 640, 480);
    context.drawImage(currentSticker, 0, 0, 265, 250); 
    canvasCopy.getContext('2d').drawImage(picture, 0, 0, 640, 480);
});