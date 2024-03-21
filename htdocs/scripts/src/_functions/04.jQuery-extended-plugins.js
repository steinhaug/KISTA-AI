/**
 * Check if node has a specific attribute
 **/
$.fn.hasAttr = function(name) {  
    return this.attr(name) !== undefined;
};

/**
 * Force the browser to redraw the DOM
 **/
$.fn.redraw = function(){
    return $(this).each(function(){
        var redraw = this.offsetHeight;
    });
};

/**
 * Animate an element, with callback or destruct on complete
 **/
$.fn.extend({
    animateCss: function (animationName, callback) {
        this.addClass('animate__animated animate__' + animationName).one( whichTransitionEvent(), function() {
            $(this).removeClass('animate__animated animate__' + animationName);
            if (callback === true) {
                $(this).remove();
            } else if (callback) {
                callback(this);
            }
        });
        return this;
    }
});










/**
 * Playing sound files directly in the browser
 * 
 * @author Alexander Manzyuk <admsev@gmail.com>
 * Copyright (c) 2012 Alexander Manzyuk - released under MIT License
 * https://github.com/admsev/jquery-play-sound
 * Usage: $.playSound('http://example.org/sound')
 * $.playSound('http://example.org/sound.wav')
 * $.playSound('/attachments/sounds/1234.wav')
 * $.playSound('/attachments/sounds/1234.mp3')
 * $.stopSound();
**/
(function ($) {
    $.extend({
        playSound: function () {
            return $(
                   '<audio class="sound-player" autoplay="autoplay" style="display:none;">'
                     + '<source src="' + arguments[0] + '" />'
                     + '<embed src="' + arguments[0] + '" hidden="true" autostart="true" loop="false"/>'
                   + '</audio>'
                 ).appendTo('body');
        },
        stopSound: function () {
            $(".sound-player").remove();
        }
    });
})(jQuery);