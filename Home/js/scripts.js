console.log(screen.width);
let checkeoAncho = setInterval(check,1000);
let ancho;

function check() {
    ancho= screen.width;
    
    if (ancho < 600) {
        crearCarruseles();
        clearInterval(checkeoAncho);
    }else{
        console.log(screen.width);
    } 
};


function crearCarruseles(params) {
    let aside = document.querySelector(".Noticias");
    let cards = document.querySelectorAll(".card");

    aside.classList.toggle("glider-contain");

   for (let i = 0; i < cards.length; i++) {
        cards[i].classList.toggle("glider");    
   }

    console.log(aside, cards)
}
