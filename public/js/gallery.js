
function addLike(id) {

  var src = document.getElementById('like_'+id).attributes.getNamedItem("src").value;
  if (src == "../public/img/like.png") {
    document.getElementById('like_'+id).src = "../public/img/like_red.png";
    var elem = document.getElementById('nblike_'+id);
    var nb = elem.innerHTML;
    nb = parseInt(nb);
    nb++;
    elem.innerHTML = nb+' j\'aime';
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "../app/savelike.php?id_pic="+id, true);
    xhr.send();
  }
  else if (src == "../public/img/like_red.png") {
    document.getElementById('like_'+id).src = "../public/img/like.png";
    var elem = document.getElementById('nblike_'+id);
    var nb = elem.innerHTML;
    nb = parseInt(nb);
    nb--;
    elem.innerHTML = nb+' j\'aime';
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "../app/deletelike.php?id_pic="+id, true);
    xhr.send();
  }

}
