document.addEventListener('DOMContentLoaded', function () {
    var header = document.querySelector('.site-header');
    if (header) {
        var scrollHandler = function () {
            if (window.scrollY > 50) {
                header.classList.add('header-scrolled');
            } else {
                header.classList.remove('header-scrolled');
            }
        };
        scrollHandler();
        window.addEventListener('scroll', scrollHandler);
    }

    if (window.AOS) {
        AOS.init({ duration: 900, once: true, mirror: false });
    }
});
