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

  if (comment.value === "")
    return ;
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "../app/savecomment.php", true);
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send("id_pic="+id+"&comment="+comment.value);
  xhr.onreadystatechange = function () {
    if(xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
      var div = document.createElement("DIV");
      div.setAttribute("class", "allcomments");
      div.innerHTML = "<b>"+login+"</b> "+comment.value;
      document.getElementById('firstcomment_'+id).appendChild(div);
      comment.value = "";
    }
  }

}
