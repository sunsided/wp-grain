/*
	reflection.js for mootools v1.4
	(c) 2006-2008 Christophe Beyls <http://www.digitalia.be>
	MIT-style license.
*/
Element.implement({reflect:function(B){var A=this;if(A.get("tag")!="img"){return }B=$extend({height:0.33,opacity:0.5},B);function C(){A.unreflect();var G,E=Math.floor(A.height*B.height),H,D,F;if(Browser.Engine.trident){G=new Element("img",{src:A.src,styles:{width:A.width,height:A.height,marginBottom:-A.height+E,filter:"flipv progid:DXImageTransform.Microsoft.Alpha(opacity="+(B.opacity*100)+", style=1, finishOpacity=0, startx=0, starty=0, finishx=0, finishy="+(B.height*100)+")"}})}else{G=new Element("canvas");if(!G.getContext){return }}G.setStyles({display:"block",border:0});H=new Element(($(A.parentNode).get("tag")=="a")?"span":"div").injectAfter(A).adopt(A,G);H.className=A.className;A.store("reflected",H.style.cssText=A.style.cssText);H.setStyles({width:A.width,height:A.height+E,overflow:"hidden"});A.style.cssText="display: block; border: 0px";A.className="";if(!Browser.Engine.trident){D=G.setProperties({width:A.width,height:E}).getContext("2d");D.save();D.translate(0,A.height-1);D.scale(1,-1);D.drawImage(A,0,0,A.width,A.height);D.restore();D.globalCompositeOperation="destination-out";F=D.createLinearGradient(0,0,0,E);F.addColorStop(0,"rgba(255, 255, 255, "+(1-B.opacity)+")");F.addColorStop(1,"rgba(255, 255, 255, 1.0)");D.fillStyle=F;D.rect(0,0,A.width,E);D.fill()}}if(A.complete){A.unreflect();C()}else{A.onload=C}return A},unreflect:function(){var B=this,A=this.retrieve("reflected"),C;B.onload=$empty;if(A!==null){C=B.parentNode;B.className=C.className;B.style.cssText=A;B.store("reflected",null);C.parentNode.replaceChild(B,C)}return B}});

// AUTOLOAD CODE BLOCK (MAY BE CHANGED OR REMOVED)
var Reflection = {
	scanPage: function() {
		$$("img").filter(function(img) { return img.hasClass("reflect"); }).reflect({/* Put custom options here */});
	}
};
window.addEvent("domready", Reflection.scanPage);
