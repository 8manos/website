(function(a){a.fn.lavaLamp=function(b){b=a.extend({fx:"linear",speed:500,click:function(){}},b||{});return this.each(function(){var f=a(this),e=function(){},h=a('<li class="back"><div class="left"></div></li>').appendTo(f),i=a("li",this),g=a("li.current_page_item",this)[0]||a("li.current_page_parent",this)[0]||a(i[0]).addClass("current_page_item")[0];i.not(".back").hover(function(){c(this)},e);a(this).hover(e,function(){c(g)});i.click(function(j){d(this);return b.click.apply(this,[j,this])});d(g);function d(j){h.css({left:j.offsetLeft+"px",width:j.offsetWidth+"px"});g=j}function c(j){h.each(function(){a(this).dequeue()}).animate({width:j.offsetWidth,left:j.offsetLeft},b.speed,b.fx)}})}})(jQuery);