/**
 * Author: Colin Mollenhour
 * Description: Wraps Raphael primitives with a set consisting of a primitive and an invisible
 * clone used for handling mouse events.
 * Access the overlay element and reset its opacity with: overlay()
 * Access the set within overlay event handlers with: this.set
 */
Raphael.fn.mouseable = function(){
  var args = Array.prototype.slice.call(arguments);
  var type = args.shift();
  var set = this.set([this[type].apply(this,args)]);
  set.push(set.items[0].clone().attr({"opacity":0.0}));
  set.items[1].set = set;
  set.overlay = function(){
    return this.items[1].attr({"opacity":0.0});
  };
  return set;
};


Raphael.el.tooltip = function (tp) {
 this.tp = tp;
 this.tp.ox = 500;
 this.tp.oy = -80;
 this.tp.hide();
 this.mousemove(function(event){
	 this.tp.translate(event.clientX - this.tp.ox, event.clientY - this.tp.oy);
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

 function swapItems(){
	imagesArray = ['covers/image1.jpg', 'covers/image2.jpg', 'covers/image3.jpg', 'covers/image4.jpg', 'covers/image5.jpg', 'covers/image6.jpg'];
	$.each($("[src*='covers/image']"), function() {
		$(this).attr('src', imagesArray[Math.floor(Math.random()*imagesArray.length-1)]);
	});
}
 
Raphael.fn.pieChart = function (cx, cy, r, values, labels, stroke) {
    var paper = this,
        rad = Math.PI / 180,
        chart = this.set();
    function sector(cx, cy, r, startAngle, endAngle, params) {
        var x1 = cx + r * Math.cos(-startAngle * rad),
            x2 = cx + r * Math.cos(-endAngle * rad),
            y1 = cy + r * Math.sin(-startAngle * rad),
            y2 = cy + r * Math.sin(-endAngle * rad);
        return paper.path(["M", cx, cy, "L", x1, y1, "A", r, r, 0, +(endAngle - startAngle > 180), 0, x2, y2, "z"]).attr(params);
    }
    var angle = 0,
        total = 0,
		colorsFirst =Raphael.rgb(240,216,96);
		colorsSecond= Raphael.rgb(48,192,192);
        process = function (j) {
            var value = values[j],
                angleplus = 360/7,
                popangle = angle + (angleplus / 2),
                color = (j < 7) ? colorsFirst : colorsSecond,
                ms = 500,
                delta = 30,
                bcolor = color,
                p = sector(cx, cy, value, angle, angle + angleplus, {fill: "90-" + bcolor + "-" + color, stroke: stroke, "stroke-width": 3, cursor: "pointer"}),
                txt = paper.text(cx + (delta+120 ) * Math.cos(-popangle * rad), cy + (delta+120) * Math.sin(-popangle * rad), labels[j]).attr({fill: "#F0F0D8", stroke: "none", opacity: 1, "font-size": 30, "font-family": "SFAtarianSystem"}).rotate(-360/7*j+60);
				over = function () {
					p.stop().animate({transform: "s1.05 1.05 " + cx + " " + cy}, ms, "elastic");
//                txt.stop().animate({opacity: 1}, ms, "elastic");
				}
				out = function () {
					p.stop().animate({transform: ""}, ms, "elastic");
//                txt.stop().animate({opacity: 0}, ms);
				};
            p.mouseover(over).mouseout(out);
			
			p.click(swapItems);
			//txt.mouseover(over).mouseout(out);
			//var popularity = paper.text(-140, -135, "unpopular");
			//popularity.attr({"font-size": 18, "font-family": "SFAtarianSystem"});
			var offsetX = 10;
			var offsetY = 0;
			var popularity = (j < 7) ? "unpopular3.png" : "popular2.png";
			var screenshot = new Array("ff7screen.jpg", "aliensscreen.jpeg",
				"allodsscreen.jpeg","avatarscreen.jpeg", 
				"billscreen.jpeg", "civscreen.jpeg", 
				"dexterscreen.jpg","ghostbustersscreen.jpeg", 
				"sawscreen.jpeg","scissorsscreen.jpeg",
				"seriousscreen.jpeg", "skyrimscreen.jpeg",
				"synthscreen.jpeg", "terminatorscreen.jpeg");
			var gameTitle = new Array("Final Fantasy VII", "Alien Vs. Predator",
				"Allod the Great","Avatar: Blue Peeps", 
				"Bill Nye Science", "Civilization IV", 
				"Dexter","Ghost Busters", 
				"Saw XXIV","Edward Scissors",
				"Serious Sam", "Skyrim",
				"Amplitude", "Terminator");
			var lperTitle = new Array("SoapPotato", "Superrrr",
				"Something","TheWho", 
				"sleepsatnight", "AdamBarkis", 
				"KeysOfIRON","Mmmmmsyrup", 
				"Arkavious","Banananna",
				"11gt10", "ak48",
				"altf4", "WarAndStuff");
				var tip = paper.set()
				.push(paper.image(popularity, offsetX,offsetY,17,115))
				.push(paper.rect(17+offsetX, offsetY, 10, 115).attr({fill: "#FFFFFF", stroke: "none"}))
				.push(paper.rect(17+offsetX, offsetY, 117, 115, 10).attr({fill: "#FFFFFF", stroke: "none"}))
				.push(paper.rect(17+offsetX, 6+offsetY, 117, 103, 3).attr({fill: "#F0F0D8", stroke: "none"}))
				.push(paper.image(screenshot[j], 17+offsetX,38+offsetY,117,70))
				.push(paper.text(22+offsetX, 16+offsetY, gameTitle[j]).attr({"width": 30,"text-anchor": "start", color: "#000018", "font-size": 14, "font-family": "Georgia"}))
				.push(paper.text(22+offsetX, 28+offsetY, lperTitle[j]).attr({"text-anchor": "start", color: "#000018", "font-size": 12, "font-family": "Georgia"}));
			p.tooltip(tip);
//			txt.tooltip(tip);
            angle += angleplus;
            chart.push(p);
            chart.push(txt);
        };
    for (var i = 0, ii = values.length; i < ii; i++) {
        total += values[i];
    }
    for (i = 0; i < ii; i++) {
        process(i);
    }
	var centerc = paper.circle(cx, cy, 30).attr({fill: "90-" + "#C00060", stroke: stroke, "stroke-width": 3, cursor: "pointer"});
	var alltxt = paper.text(cx, cy, "All").attr({fill: "#F0F0D8", stroke: "none", opacity: 1, "font-size": 40, "font-family": "SFAtarianSystem", cursor: "pointer"});
	over = function () {centerc.stop().animate({transform: "s1.2 1.2 " + cx + " " + cy}, 500, "elastic");}
	out = function () {centerc.stop().animate({transform: ""},500, "elastic");};
	centerc.mouseover(over).mouseout(out).click(swapItems);
	alltxt.mouseover(over).mouseout(out).click(swapItems);
	chart.push(centerc);
	chart.push(alltxt);
    return chart;
};

$(function () {
    var values = [],
        labels = [];
    $("#graphix tr").each(function () {
        values.push(parseInt($("td", this).text(), 10));
        labels.push($("th", this).text());
    });
    $("#graphix").hide();
    Raphael("holder", 600, 600).pieChart(300, 300, 200, values, labels, "#fff");
});
