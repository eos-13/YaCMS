// -----------------------------------------------------------------------------------
// http://wowslider.com/
// JavaScript Wow Slider is a free software that helps you easily generate delicious 
// slideshows with gorgeous transition effects, in a few clicks without writing a single line of code.
// Generated by WOW Slider 7.7
//
//***********************************************
// Obfuscated by Javascript Obfuscator
// http://javascript-source.com
//***********************************************
jQuery.extend(jQuery.easing,{easeInOutBack:function(e,f,a,i,h,g){if(g==undefined){g=1.70158}if((f/=h/2)<1){return i/2*(f*f*(((g*=(1.525))+1)*f-g))+a}return i/2*((f-=2)*f*(((g*=(1.525))+1)*f+g)+2)+a}});function ws_cube_over(l,j,b){var e=jQuery,i=e(this),a=/WOW Slider/g.test(navigator.userAgent),f=e(".ws_list",b),c=l.perspective||/MSIE|Trident/g.test(navigator.userAgent)?1000:2000,d={position:"absolute",backgroundSize:"cover",left:0,top:0,width:"100%",height:"100%",backfaceVisibility:"hidden"};var k=/AppleWebKit/.test(navigator.userAgent)&&!/Chrome/.test(navigator.userAgent);var h=e("<div>").css(d).css({transformStyle:"preserve-3d",perspective:(k&&!a?"none":c),zIndex:8});e("<div>").addClass("ws_effect ws_cube_over").css(d).append(h).appendTo(b);if(!l.support.transform&&l.fallback){return new l.fallback(l,j,b)}var g;this.go=function(x,t){var p=e(j[t]);p={width:p.width(),height:p.height(),marginTop:parseFloat(p.css("marginTop")),marginLeft:parseFloat(p.css("marginLeft"))};function o(B,F,E,G){B.parent().css("perspective",c);var C=B.width(),D=B.height();wowAnimate(B,{scale:1,translate:[0,0,(k&&!a)?E:0]},{scale:0.85,translate:[0,0,(k&&!a)?E:0]},l.duration*0.4,"easeInOutBack",function(){wowAnimate(B,{scale:0.85,translate:[0,0,(k&&!a)?E:0]},{scale:1,translate:[0,0,(k&&!a)?E:0]},l.duration*0.4,l.duration-l.duration*0.8,"easeInOutBack",G)});wowAnimate(F.front.item,{rotateY:0,rotateX:0},{rotateY:F.front.rotateY,rotateX:F.front.rotateX},l.duration,"easeInOutBack");wowAnimate(F.back.item,{rotateY:F.back.rotateY,rotateX:F.back.rotateX},{rotateY:0,rotateX:0},l.duration,"easeInOutBack");wowAnimate(F.side.item,{rotateY:F.side.rotateY,rotateX:F.side.rotateX},{rotateY:-F.side.rotateY,rotateX:-F.side.rotateX},l.duration,"easeInOutBack")}if(l.support.transform&&l.support.perspective){if(g){g.stop()}var z=b.width(),u=b.height();var s={left:[z/2,0,0,180,0,-180],right:[z/2,0,0,-180,0,180],down:[u/2,-u/2,180,0,-180,0],up:[u/2,u/2,-180,0,180,0]}[l.direction||["left","right","down","up"][Math.floor(Math.random()*4)]];var A=e("<img>").css(p),r=e("<img>").css(p).attr("src",j.get(x).src);var m=e("<div>").css({overflow:"hidden",transformOrigin:"50% 50% -"+s[0]+"px"}).css(d).append(A).appendTo(h);var n=e("<div>").css({overflow:"hidden",transformOrigin:"50% 50% -"+s[0]+"px"}).css(d).append(r).appendTo(h);var y=e('<div class="ws_cube_side">').css({transformOrigin:"50% 50% -"+s[0]+"px"}).css(d).appendTo(h);A.on("load",function(){f.hide()});A.attr("src",j.get(t).src).load();h.parent().show();g=new o(h,{front:{item:m,rotateY:s[5],rotateX:s[4]},back:{item:n,rotateY:s[3],rotateX:s[2]},side:{item:y,rotateY:s[3]/2,rotateX:s[2]/2}},-s[0],function(){i.trigger("effectEnd");h.empty().parent().hide();g=0})}else{h.css({position:"absolute",display:"none",zIndex:2,width:"100%",height:"100%"});h.stop(1,1);var q=(!!((x-t+1)%j.length)^l.revers?"left":"right");var m=e("<div>").css({position:"absolute",left:"0%",right:"auto",top:0,width:"100%",height:"100%"}).css(q,0).append(e(j[t]).clone().css({width:100*p.width/b.width()+"%",height:100*p.height/b.height()+"%",marginLeft:100*p.marginLeft/b.width()+"%"})).appendTo(h);var v=e("<div>").css({position:"absolute",left:"100%",right:"auto",top:0,width:"0%",height:"100%"}).append(e(j[x]).clone().css({width:100*p.width/b.width()+"%",height:100*p.height/b.height()+"%",marginLeft:100*p.marginLeft/b.width()+"%"})).appendTo(h);h.css({left:"auto",right:"auto",top:0}).css(q,0).show();h.show();f.hide();v.animate({width:"100%",left:0},l.duration,"easeInOutExpo",function(){e(this).remove()});m.animate({width:0},l.duration,"easeInOutExpo",function(){i.trigger("effectEnd");h.empty().hide()})}}};// -----------------------------------------------------------------------------------
// http://wowslider.com/
// JavaScript Wow Slider is a free software that helps you easily generate delicious 
// slideshows with gorgeous transition effects, in a few clicks without writing a single line of code.
// Generated by WOW Slider 7.7
//
//***********************************************
// Obfuscated by Javascript Obfuscator
// http://javascript-source.com
//***********************************************
jQuery("#wowslider-container1").wowSlider({effect:"cube_over",prev:"",next:"",duration:20*100,delay:20*100,width:640,height:360,autoPlay:true,autoPlayVideo:false,playPause:true,stopOnHover:false,loop:false,bullets:1,caption:true,captionEffect:"parallax",controls:true,responsive:1,fullScreen:false,gestures:2,onBeforeStep:0,images:0});