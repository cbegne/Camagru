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

function addComment(id, comment, login) {

  com = htmlEntities(comment.value);
  if (com.trim() === "")
    return ;
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "../app/savecomment.php", true);
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send("id_pic="+id+"&comment="+com);
  xhr.onreadystatechange = function () {
    if(xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
      var div = document.createElement("DIV");
      div.setAttribute("class", "allcomments");
      div.innerHTML = "<b>"+login+"</b> "+com;
      document.getElementById('firstcomment_'+id).appendChild(div);
      comment.value = "";
    }
  }
}

function htmlEntities(str) {
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    //converts special characters (like <) into their escaped/encoded values (like &lt;).
}

function deletePicture(id) {
  document.getElementById('delete_'+id).parentNode.remove();
  var xhr = new XMLHttpRequest();
  xhr.open("GET", "../app/deletepic.php?id_pic="+id, true);
  xhr.send();
}
