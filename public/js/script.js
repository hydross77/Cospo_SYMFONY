
function modifyAction(el) {
  let x = document.getElementById("videoHome");
  console.log(el);
  if (el.textContent == '▷') {
    el.textContent = 'II';
    x.play();
  } else {
    el.textContent = '▷';
    x.pause();
  }
}

const el = document.querySelector("#lecteur");
el.addEventListener("click", function(){modifyAction(el)}, false);