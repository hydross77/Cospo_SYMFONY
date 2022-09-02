
function modifyAction(el) {
  let x = document.getElementById("videoHome");
  console.log(el);
  if (el.textContent == '▷') {
    el.textContent = 'll';
    x.play();
  } else {
    el.textContent = '▷';
    x.pause();
  }
}

const el = document.querySelector("#lecteur");
el.addEventListener("click", function(){modifyAction(el)}, false);


function bascule(id) 
{ 
	if (document.getElementById(id).style.display == "none")
			document.getElementById(id).style.display = "block"; 
	else	document.getElementById(id).style.display = "none"; 
} 