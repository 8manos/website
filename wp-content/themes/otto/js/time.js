(function($) {

  doTime();

  function doTime(){

    var d = new Date();
    var hours = d.getHours();
    var mins = d.getMinutes();
    var secs = d.getSeconds();

    /*
    Hue changes every 10 seconds
    */
    var h = (mins*6) + Math.floor(secs/10);

    /*
    Saturation range 40-100 to avoid grays
    0secs -> 40%, 30secs -> 100%, 60secs -> 40%
    */
    var s = secs+40;
    if ( secs < 30) {
      s = (2*secs)+40;
    } else {
      s = 160-(secs*2);
    }

    /*
    Lightness should provide maximum contrast at midday
    Lightness against white: 18-30 (max contrast: 18)
    Lightness against black: 60-84 (max contrast: 84)
    */
    var lw;
    var lb;
    if ( hours < 12) {
      lw = 30-hours;
      lb = 60+(hours*2);
    } else {
      lw = hours+6;
      lb = 108-(hours*2);
    }

    var colorW = 'hsl('+h+','+s+'%,'+lw+'%)';
    var colorB = 'hsl('+h+','+s+'%,'+lb+'%)';

    $( 'a, .color' ).not( '.main-nav a, .language-switch a').css( 'color', colorW );
    $( '.color-bg, .owl-prev, .owl-next' ).css( 'background-color', colorW );
    $( '.color-against-black' ).css( 'color', colorB );
    $( '#otto').css('fill', colorB );

    setTimeout(doTime, 1000);
  }

})(jQuery);