(function($) {

  doTime();

  function doTime(){

    var d = new Date();
    var hours = d.getHours();
    var mins = d.getMinutes();
    var secs = d.getSeconds();

    var h = mins*6;
    var s = secs+40; // avoid grays

    /*
    Lightness should provide maximum contrast at midday
    Lightness against white: 18-30 (max contrast: 18)
    Lightness against black: 60-84 (max contrast: 84)
    */
    var lw;
    var lb;
    if ( hours < 12) {
      lw = 30-hours;
      lb = 60+hours*2;
    } else {
      lw = hours+6;
      lb = 108-hours*2;
    }

    var colorW = 'hsl('+h+','+s+'%,'+lw+'%)';
    var colorB = 'hsl('+h+','+s+'%,'+lb+'%)';

    $( 'a, .color' ).not( '.main-nav a, .language-switch a').css( 'color', colorW );
    $( '.color-bg' ).css( 'background-color', colorW );
    $( '.color-against-black' ).css( 'color', colorB );

    setTimeout(doTime, 1000);
  }


})(jQuery);