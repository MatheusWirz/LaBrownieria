

window.addEventListener('load', function(){
  document.getElementById("loading").style.display = "none";
  document.getElementById("content").style.display = "inline";
})



const target = document.querySelectorAll('[data-anime]');
const animationClass = 'animate';

function animeLoad(){
    target.forEach(function(e){
            e.classList.add(animationClass);
        })
}

if(target.length){
    window.addEventListener('load', function(){
        animeLoad()
    })
}



const debounce = function(func, wait, immediate){
    let timeout;
    return function(...args){
        const context = this;
        const later = function(){
            timeout = null;
            if(!immediate) func.apply(context, args);
        };
        const callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if(callNow) func.apply(context, args);
    };
};


const Target = document.querySelectorAll('[data-animescroll]');
const AnimationClass = 'animate';

function animeLoadScroll(){
    const windowTop = window.pageYOffset + ((window.innerHeight * 3) / 4);
    Target.forEach(function(e){
            if((windowTop) > e.offsetTop){
                e.classList.add(AnimationClass);
            }else{
                e.classList.remove(AnimationClass);
            }
        })
}

if(Target.length){
    window.addEventListener('scroll', function(){
        animeLoadScroll()
    })
}





/*
// When the user scrolls the page, execute myFunction
window.onscroll = function() {myFunction()};

// Get the navbar
var searchbar = document.getElementById("search-box");

// Get the offset position of the navbar
var sticky = searchbar.offsetTop;

// Add the sticky class to the navbar when you reach its scroll position. Remove "sticky" when you leave the scroll position
function myFunction() {
  if (window.pageYOffset >= sticky) {
    searchbar.classList.add("sticky")
  } else {
    searchbar.classList.remove("sticky");
  }
} */





$('.slide').slick({
    speed: 400,
    slidesToShow: 5,
    slidesToScroll: 1,
    infinite: false,
    responsive: [
        {
          breakpoint: 1024,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 1,
            infinite: false
          }
        },
        {
          breakpoint: 800,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 1
          }
        },
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1
          }
        }
        // You can unslick at a given breakpoint now by adding:
        // settings: "unslick"
        // instead of a settings object
      ]
});


