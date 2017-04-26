(function() {

  var streaming = false,
      video        = document.querySelector('#video'),
      cover        = document.querySelector('#cover'),
      canvas       = document.querySelector('#canvas'),
      photo        = document.querySelector('#photo'),
      startbutton  = document.querySelector('#startbutton'),
      savebutton  = document.querySelector('#savebutton'),
      img1 = document.querySelector('#img1'),
      width = 320,
      height = 0;
      data = 0;

  navigator.getMedia = ( navigator.getUserMedia ||
                         navigator.webkitGetUserMedia ||
                         navigator.mozGetUserMedia ||
                         navigator.msGetUserMedia);

  navigator.getMedia(
    {
      video: true,
      audio: false
    },
    function(stream) {
      if (navigator.mozGetUserMedia) {
        video.mozSrcObject = stream;
      } else {
        var vendorURL = window.URL || window.webkitURL;
        video.src = vendorURL.createObjectURL(stream);
      }
      video.play();
    },
    function(err) {
      console.log("An error occured! " + err);
    }
  );

  video.addEventListener('canplay', function(ev){
    if (!streaming) {
      height = video.videoHeight / (video.videoWidth/width);
      video.setAttribute('width', width);
      video.setAttribute('height', height);
      canvas.setAttribute('width', width);
      canvas.setAttribute('height', height);
      streaming = true;
    }
  }, false);

  function takePicture() {
    canvas.width = width;
    canvas.height = height;
    canvas.getContext('2d').drawImage(video, 0, 0, width, height);
    data = canvas.toDataURL('image/png');
  }

  function addImage() {
    var x = document.createElement("IMG");
    x.setAttribute("src", data);
    x.setAttribute("class", "minipic");
    var minipic = document.getElementById('side');
    minipic.insertBefore(x, minipic.childNodes[0]);
  }

  // function mergeImage(img, x, y) {
  //   base_image = new Image();
  //   base_image.src = img;
  //   base_image.onload = function(){
  //   canvas.getContext('2d').drawImage(base_image, x, y);
  //   }
  // }

  function savePicture() {
    var picData = data.replace("data:image/png;base64,", "");
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../app/savepic.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("pic="+encodeURIComponent(picData));
  }

  startbutton.addEventListener('click', function(ev){
      takePicture();
    ev.preventDefault();
  }, false);

  // img1.addEventListener('click', function(ev){
  //     mergeImage("../public/img/image1.png", 10, 10);
  //   ev.preventDefault();
  // }, false);
  //
  // img2.addEventListener('click', function(ev){
  //     mergeImage("../public/img/image2.png", 150, 170);
  //   ev.preventDefault();
  // }, false);
  //
  // img3.addEventListener('click', function(ev){
  //     mergeImage("../public/img/image3.png", 10, 10);
  //   ev.preventDefault();
  // }, false);


  savebutton.addEventListener('click', function(ev){
      savePicture();
      addImage();
    ev.preventDefault();
  }, false);


})();
