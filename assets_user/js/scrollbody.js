var swiper = new Swiper('.flash-sale-swiper', {
    slidesPerView: 5,
    spaceBetween: 20,
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
    pagination: {
        el: '.swiper-pagination',
        clickable: true,
    },
    grabCursor: true,
});