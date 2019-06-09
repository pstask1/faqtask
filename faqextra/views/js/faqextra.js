/**
 * Created by Inixweb Account on 2017-06-20.
 */
$(document).ready(function () {
           $('.slider-faq').sss({
               slideShow: true, // Set to false to prevent SSS from automatically animating.
               startOn: 0, // Slide to display first. Uses array notation (0 = first slide).
               transition: 400, // Length (in milliseconds) of the fade transition.
               speed: 6500, // Slideshow speed in milliseconds.
               showNav: true // Set to false to hide navigation arrows.
           });
});