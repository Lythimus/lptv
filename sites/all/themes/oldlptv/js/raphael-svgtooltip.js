Raphael.el.tooltip = function (tp) {
 this.tp = tp;
 this.tp.ox = 0;
 this.tp.oy = 0;
 this.tp.hide();
 this.mousemove(function(event){
 this.tp.translate(event.clientX -
 this.tp.ox,event.clientY – this.tp.oy);
 this.tp.ox = event.clientX;
 this.tp.oy = event.clientY;
 });
 this.hover(
 function(event){
 this.tp.show().toFront();
 },
 function(event){
 this.tp.hide();
 this.unmousemove();
 });
 return this;
 };
