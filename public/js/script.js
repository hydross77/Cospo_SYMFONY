
/* BURGER MENU */
var sidenav = document.getElementById("mySidenav");
var openBtn = document.getElementById("openBtn");
var closeBtn = document.getElementById("closeBtn");

openBtn.onclick = openNav;
closeBtn.onclick = closeNav;

function openNav() {
  sidenav.classList.add("active");
}

function closeNav() {
  sidenav.classList.remove("active");
}
/* FIN BURGER MENU */

/* PLAY AND PAUSE POUR LA VIDEO D'ACCUEIL */
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
/* FIN PLAY AND PAUSE POUR LA VIDEO D'ACCUEIL */

/* AFFICHER LE FORMULAIRE D'EVENEMENT */
function bascule(id) 
{ 
	if (document.getElementById(id).style.display == "none")
			document.getElementById(id).style.display = "block"; 
	else	document.getElementById(id).style.display = "none"; 
} 
/* FIN AFFICHER LE FORMULAIRE D'EVENEMENT */





