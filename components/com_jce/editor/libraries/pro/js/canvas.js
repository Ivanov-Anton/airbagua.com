/* jce - 2.7.7 | 2019-04-03 | https://www.joomlacontenteditor.net | Copyright (C) 2006 - 2019 Ryan Demmer. All rights reserved | GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html */
!function($,Wf){$.support.canvas=!!document.createElement("canvas").getContext,$.widget("ui.canvas",{stack:[],options:{onfilterstart:$.noop,onfilterprogress:$.noop},_create:function(){this.canvas=document.createElement("canvas"),this.context=this.canvas.getContext("2d"),this.draw()},getContext:function(){return this.context},getCanvas:function(){return this.canvas},setSize:function(w,h){$.extend(this.options,{width:w,height:h}),this.draw()},draw:function(el,w,h){el=el||$(this.element).get(0);var w=w||this.options.width||el.width,h=h||this.options.height||el.height;this.save(),$(this.canvas).attr({width:w,height:h}),this.context.drawImage(el,0,0,w,h)},free:function(n){n.getContext("2d").clearRect(0,0,0,0),$(n).remove()},clone:function(){return $(this.canvas).clone().get(0)},copy:function(){var copy=this.clone();return copy.getContext("2d").drawImage(this.canvas,0,0),copy},clear:function(){var ctx=this.context,w=$(this.element).width(),h=$(this.element).height();ctx&&ctx.clearRect(0,0,w,h)},resize:function(w,h,save){var ctx=this.context;w=parseInt(w),h=parseInt(h);this.canvas.width,this.canvas.height;if(ctx){save&&this.save(),ctx.imageSmoothingEnabled=ctx.mozImageSmoothingEnabled=ctx.webkitImageSmoothingEnabled=!0;var copy=this.copy();copy.getContext("2d").drawImage(this.canvas,0,0,w,h),$(this.canvas,copy).attr({width:w,height:h}),ctx.drawImage(copy,0,0),this.free(copy)}},crop:function(w,h,x,y,save){var ctx=this.context;if(w=parseInt(w),h=parseInt(h),x=parseInt(x),y=parseInt(y),ctx){save&&this.save(),x<0&&(x=0),x>this.canvas.width-1&&(x=this.canvas.width-1),y<0&&(y=0),y>this.canvas.height-1&&(y=this.canvas.height-1),w<1&&(w=1),x+w>this.canvas.width&&(w=this.canvas.width-x),h<1&&(h=1),y+h>this.canvas.height&&(h=this.canvas.height-y),ctx.imageSmoothingEnabled=ctx.mozImageSmoothingEnabled=ctx.webkitImageSmoothingEnabled=!0;var copy=this.copy();copy.getContext("2d").drawImage(this.canvas,0,0),$(this.canvas).attr({width:w,height:h}),ctx.drawImage(copy,x,y,w,h,0,0,w,h),this.free(copy)}},rotate:function(angle,save){var cw,ch,ctx=this.context,w=this.canvas.width,h=this.canvas.height;switch(angle<0&&(angle+=360),angle){case 90:case 270:cw=h,ch=w;break;case 180:cw=w,ch=h}if(ctx){save&&this.save();var copy=this.copy();$(this.canvas).attr({width:cw,height:ch}),ctx.translate(cw/2,ch/2),ctx.rotate(angle*Math.PI/180),ctx.drawImage(copy,-w/2,-h/2),this.free(copy)}},flip:function(axis,save){var ctx=this.context,w=this.canvas.width,h=this.canvas.height;if(ctx){save&&this.save();var copy=this.copy();copy.getContext("2d").drawImage(this.canvas,0,0,w,h,0,0,w,h),ctx.clearRect(0,0,w,h),$(this.canvas).attr({width:w,height:h}),"horizontal"==axis?(ctx.scale(-1,1),ctx.drawImage(copy,-w,0,w,h)):(ctx.scale(1,-1),ctx.drawImage(copy,0,-h,w,h)),this.free(copy)}},filter:function(filter,amount,save){save&&this.save(),new Filter(this.canvas).add(filter,amount).apply()},save:function(){var ctx=this.context,w=this.canvas.width,h=this.canvas.height;this.stack.push({width:w,height:h,data:ctx.getImageData(0,0,w,h)})},restore:function(){var ctx=this.context,img=$(this.element).get(0);ctx.restore(),ctx.drawImage(img,0,0)},undo:function(){var ctx=this.context,item=($(this.element).get(0),this.stack.pop());item&&($(this.canvas).attr({width:item.width,height:item.height}),item.data?ctx.putImageData(item.data,0,0):this.restore())},load:function(){var ctx=this.context,w=this.canvas.width,h=this.canvas.height,data=ctx.getImageData(0,0,w,h);ctx.clearRect(0,0,w,h),ctx.putImageData(data,0,0)},update:function(){this.load(),this.stack=[]},getMime:function(s){var mime="image/jpeg",ext=Wf.String.getExt(s);switch(ext){case"jpg":case"jpeg":mime="image/jpeg";break;case"png":mime="image/png";break;case"bmp":mime="image/bmp"}return mime},resample:function(callback,nw,nh){var self=this,ctx=this.context,w=this.canvas.width,h=this.canvas.height,data1=ctx.getImageData(0,0,w,h),tmp=this.copy(),data2=tmp.getContext("2d").getImageData(0,0,w,h),worker=new Worker(Wf.getPath()+"/js/worker-hermite.js");Date.now();worker.onmessage=function(event){var out=event.data.data;self.clear(),self.context.putImageData(out,0,0),"function"==typeof callback&&callback()},worker.postMessage([data1,w,h,nw||w,nh||h,data2])},output:function(mime,quality,blob){function dataURItoBlob(dataURI,mime){var byteString,i,arrayBuffer,intArray;for(byteString=dataURI.split(",")[0].indexOf("base64")>=0?atob(dataURI.split(",")[1]):decodeURIComponent(dataURI.split(",")[1]),arrayBuffer=new ArrayBuffer(byteString.length),intArray=new Uint8Array(arrayBuffer),i=0;i<byteString.length;i+=1)intArray[i]=byteString.charCodeAt(i);return new Blob([arrayBuffer],{type:mime})}var self=this;return mime=mime||this.getMime($(this.element).get(0).src),quality=parseInt(quality)||100,quality=Math.max(Math.min(quality,100),10),quality/=100,this.load(),blob?dataURItoBlob(self.canvas.toDataURL(mime,quality),mime):self.canvas.toDataURL(mime,quality)},remove:function(){$(this.canvas).remove(),this.destroy()}})}(jQuery,Wf);