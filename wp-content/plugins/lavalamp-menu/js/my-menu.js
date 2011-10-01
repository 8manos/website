// JavaScript Document
/*
        $(function() {
            $("#menu1").lavaLamp({
                fx: "backout", 
                speed: 700,
                click: function(event, menuItem) {
                    //return false;
                }
            });
        });
*/
	jQuery.noConflict();
        jQuery(function() {
            jQuery(".lavaLamp").lavaLamp({
                fx: "backout", 
                speed: 700,
                click: function(event, menuItem) {
                    //return false;
                }
            });
        });
		
sfHover = function() {
	if (!document.getElementsByTagName) return false;
	var sfEls = jQuery(".lava_menu").getElementsByTagName("li");

	for (var i=0; i<sfEls.length; i++) {
		sfEls[i].onmouseover=function() {
			this.className+=" sfhover";
		}
		sfEls[i].onmouseout=function() {
			this.className=this.className.replace(new RegExp(" sfhover\\b"), "");
		}
	}

}
if (window.attachEvent) window.attachEvent("onload", sfHover);