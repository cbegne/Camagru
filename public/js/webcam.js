(function() {

  var streaming = false,
      video        = document.querySelector('#video'),
      cover        = document.querySelector('#cover'),
      canvas       = document.querySelector('#canvas'),
      photo        = document.querySelector('#photo'),
      startbutton  = document.querySelector('#startbutton'),
      savebutton  = document.querySelector('#savebutton'),
      img1 = document.querySelector('#img1'),
      img2 = document.querySelector('#img2'),
      img3 = document.querySelector('#img3'),
      width = 320,
      height = 0;
      data = 0;
      imgselected = 0;

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
    if (imgselected != 0) {
      var error = document.getElementById('error');
      if (error !== null) {
        error.remove();
      }
      canvas.width = width;
      canvas.height = height;
      canvas.getContext('2d').drawImage(video, 0, 0, width, height);
      data = canvas.toDataURL('image/png');
      var picData = data.replace("data:image/png;base64,", "");
      var xhr = new XMLHttpRequest();
      xhr.open("POST", "../app/mergepic.php", true);
      xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhr.send("pic="+encodeURIComponent(picData)+"&img="+imgselected);
      xhr.onreadystatechange = function () {
        if(xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
          var response = JSON.parse(xhr.responseText);
          response = "data:image/png;base64,"+response;
          // console.log(response);
          base_image = new Image();
          base_image.src = response;
          canvas.getContext('2d').drawImage(base_image, 0, 0, width, height);
          canvas.toDataURL('image/png');
          data = base_image.src;
        }
      }
    }
    else if (document.getElementById('error') === null) {
      var error = document.createElement("DIV");
      error.setAttribute("id", "error");
      error.innerHTML = "Sélectionnez une image pour prendre <br />une photo.";
      document.getElementById('column1').appendChild(error);
    }
  }

  function addMinipic(id) {
    var div = document.createElement("DIV");
    div.setAttribute("class", "displaypic");

    var pic = document.createElement("IMG");
    pic.setAttribute("src", data);
    pic.setAttribute("class", "minipic");
    var x = document.createElement("IMG");
    x.setAttribute("src", "../public/img/delete.png");
    x.setAttribute("class", "deletepic");
    x.setAttribute("id", "delete_"+id);
    x.setAttribute("onclick", "deletePicture("+id+")");

    var minipic = document.getElementById('side');
    minipic.insertBefore(div, minipic.childNodes[0]);
    div.insertBefore(x, div.childNodes[0]);
    div.insertBefore(pic, div.childNodes[0]);
  }

  function savePicture() {
    var picData = data.replace("data:image/png;base64,", "");
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../app/savepic.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("pic="+encodeURIComponent(picData));
    xhr.onreadystatechange = function () {
      if(xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
        var response = JSON.parse(xhr.responseText);
        var id_pic = response['id_pic'];
        addMinipic(id_pic);
      }
    }
  }

  startbutton.addEventListener('click', function(ev){
      takePicture();
    ev.preventDefault();
  }, false);

  img1.addEventListener('click', function(ev){
    imgselected = 1;
    ev.preventDefault();
  }, false);

  img2.addEventListener('click', function(ev){
    imgselected = 2;
    ev.preventDefault();
  }, false);

  img3.addEventListener('click', function(ev){
    imgselected = 3;
    ev.preventDefault();
  }, false);


  savebutton.addEventListener('click', function(ev){
    if (data != 0)
      savePicture();
    ev.preventDefault();
  }, false);


})();

 function deletePicture(id) {
   var elem = document.getElementById('delete_'+id).parentNode.remove();
   var xhr = new XMLHttpRequest();
   xhr.open("GET", "../app/deletepic.php?id_pic="+id, true);
   xhr.send();
 }
