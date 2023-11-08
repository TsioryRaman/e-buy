import './jquery.min.js'
import 'slick-carousel/slick/slick.min.js'
import 'slick-carousel/slick/slick-theme.css'
import 'slick-carousel/slick/slick.css'

$(document).ready(function () {
    $('.carousel').slick({
        autoplay: true,
        autoplaySpeed: 5000,
        fade: true,
        draggable: false,
        mobileFirst: true
    });
});