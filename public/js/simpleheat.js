"use strict";function simpleheat(t){if(!(this instanceof simpleheat))return new simpleheat(t);this._canvas=t="string"==typeof t?document.getElementById(t):t,this._ctx=t.getContext("2d"),this._width=t.width,this._height=t.height,this._max=1,this._data=[]}"undefined"!=typeof module&&(module.exports=simpleheat),simpleheat.prototype={defaultRadius:25,defaultGradient:{.4:"blue",.6:"cyan",.7:"lime",.8:"yellow",1:"red"},data:function(t){return this._data=t,this},max:function(t){return this._max=t,this},add:function(t){return this._data.push(t),this},clear:function(){return this._data=[],this},radius:function(t,i){i=void 0===i?15:i;var a=this._circle=this._createCanvas(),e=a.getContext("2d"),h=this._r=t+i;return a.width=a.height=2*h,e.shadowOffsetX=e.shadowOffsetY=2*h,e.shadowBlur=i,e.shadowColor="black",e.beginPath(),e.arc(-h,-h,t,0,2*Math.PI,!0),e.closePath(),e.fill(),this},resize:function(){this._width=this._canvas.width,this._height=this._canvas.height},gradient:function(t){var i=this._createCanvas(),a=i.getContext("2d"),e=a.createLinearGradient(0,0,0,256);for(var h in i.width=1,i.height=256,t)e.addColorStop(+h,t[h]);return a.fillStyle=e,a.fillRect(0,0,1,256),this._grad=a.getImageData(0,0,1,256).data,this},draw:function(t){this._circle||this.radius(this.defaultRadius),this._grad||this.gradient(this.defaultGradient);var i=this._ctx;i.clearRect(0,0,this._width,this._height);for(var a,e=0,h=this._data.length;e<h;e++)a=this._data[e],i.globalAlpha=Math.min(Math.max(a[2]/this._max,void 0===t?.05:t),1),i.drawImage(this._circle,a[0]-this._r,a[1]-this._r);var s=i.getImageData(0,0,this._width,this._height);return this._colorize(s.data,this._grad),i.putImageData(s,0,0),this},_colorize:function(t,i){for(var a,e=0,h=t.length;e<h;e+=4)(a=4*t[e+3])&&(t[e]=i[a],t[e+1]=i[a+1],t[e+2]=i[a+2])},_createCanvas:function(){return"undefined"!=typeof document?document.createElement("canvas"):new this._canvas.constructor}};
