function stopYtVideoOnSlide(){
    var stopAllYouTubeVideos = () => { 
        var iframes = document.querySelectorAll('iframe');
        Array.prototype.forEach.call(iframes, iframe => { 
          iframe.contentWindow.postMessage(JSON.stringify({ event: 'command', 
        func: 'stopVideo' }), '*');
       });
      }
    stopAllYouTubeVideos();
}

document.addEventListener('DOMContentLoaded', function () {
    var swiper = new Swiper('.swiper-container-cpt', {
        loop: true,
        slidesPerView: 5,
        centeredSlides: true,
        spaceBetween: 30,
        pagination: false,
        allowTouchMove: true,
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        on: {
            slideChange: function () {
                var activeSlide = document.querySelectorAll('.swiper-slide')[this.activeIndex];
                document.querySelectorAll(".swiper-slide").forEach((e) => {
                    e.classList.remove("set-active");
                });
                activeSlide.classList.add("set-active");
                
                var description_id = activeSlide.dataset.id;
                document.querySelectorAll(".swiper-content").forEach((e) => {
                    if(e.dataset.id == description_id){
                        e.classList.add("set-show");
                    }
                    else{
                        e.classList.remove("set-show");
                    }
                });
                stopYtVideoOnSlide();
            },
            click: function() {  
                var activeSlide = document.querySelectorAll('.swiper-slide')[this.clickedIndex];
                document.querySelectorAll(".swiper-slide").forEach((e) => {
                    e.classList.remove("set-active");
                });
                activeSlide.classList.add("set-active");
                var description_id = activeSlide.dataset.id;
                document.querySelectorAll(".swiper-content").forEach((e) => {
                    if(e.dataset.id == description_id){
                        e.classList.add("set-show");
                    }
                    else{
                        e.classList.remove("set-show");
                    }
                });
                stopYtVideoOnSlide();
            }
        },
        breakpoints: {
            320: {
              slidesPerView: 1,
            },
            767: {
              slidesPerView: 2,
            },
            1024: {
              slidesPerView: 3,
            },
            1440: {
              slidesPerView: 4,
            }
        }
    });

});
