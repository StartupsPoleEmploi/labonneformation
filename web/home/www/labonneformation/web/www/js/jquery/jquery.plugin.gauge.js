(function($)
{
	$.fn.gauge=function(options)
	{
		options=typeof options!='undefined'?options:{};
		var startAngle=typeof options.startAngle!='undefined'?options.startAngle:-90;
		var stopAngle=typeof options.stopAngle!='undefined'?options.stopAngle:startAngle+360;
		var type=typeof options.type!='undefined'?options.type:"";
		var fontSizeFact=typeof options.fontSizeFact!='undefined'?options.fontSizeFact:"1";
		var effect=typeof options.effect!='undefined'?options.effect:"shadow";
		var backColor=typeof options.backColor!='undefined'?options.backColor:"#fff";
		var startColor=typeof options.startColor!='undefined'?options.startColor:"#f00";
		var stopColor=typeof options.stopColor!='undefined'?options.stopColor:"#0f0";
		var borderFact=typeof options.borderFact!='undefined'?options.borderFact:"1";
		var showText=typeof options.showTx!='undefined'?options.showTx:true;
		var lag=typeof options.lag!='undefined'?options.lag:0;
		var section=typeof options.section!='undefined'?options.section:{nb:0,color:"#003173"};
		var anim=typeof options.animate!='undefined'?options.animate:'fast';
		if(anim=='fast') anim=500;
		else
		if(anim=='slow') anim=1000;
		else
		if(anim==false) anim=0;
		
		if(type=="cam") {startAngle=-180; stopAngle=0;}
		startAngle=startAngle*Math.PI/180;
		stopAngle=stopAngle*Math.PI/180;
		var deltaAngle=Math.abs(stopAngle-startAngle);

		return this.each(function()
		{
			var id=$(this);
			var w=$(id).width(),h=$(id).height();
			var canv=document.createElement('canvas');//$('<canvas width="'+w+'px" height="'+h+'px" style="border:1px solid red; position:absolute;"></canvas>');

			$(canv).css({position:'absolute',left:0,top:0})
			       .attr('width',w+'px')
			       .attr('height',h+'px');
			id.css('position','relative');
			id.append(canv);
			var ctx=canv.getContext('2d');

			/* Quelques options data-xxx */
			var tx=id.data('tx');
			var showTx=typeof tx==="undefined" || tx=='' || showText==false || id.data('showtx')==0?false:true;

			if(tx<0 || tx>100) return this;

			var dec2hex=function(dec)
			{
				return dec<16?'0'+dec.toString(16):dec.toString(16);
			}
			var spread=function(c1,c2,tx)
			{
				var c=c1.replace('#','');
				if(c.length==3) c=c.replace(/(\w)(\w)(\w)/,"$1$1$2$2$3$3");
				c1=[parseInt(c.charAt(0)+c.charAt(1),16),parseInt(c.charAt(2)+c.charAt(3),16),parseInt(c.charAt(4)+c.charAt(5),16)];
				c=c2.replace('#','');
				if(c.length==3) c=c.replace(/(\w)(\w)(\w)/,"$1$1$2$2$3$3");
				c2=[parseInt(c.charAt(0)+c.charAt(1),16),parseInt(c.charAt(2)+c.charAt(3),16),parseInt(c.charAt(4)+c.charAt(5),16)];
				var dr=Math.ceil((c2[0]-c1[0])*tx/100);
				var dg=Math.ceil((c2[1]-c1[1])*tx/100);
				var db=Math.ceil((c2[2]-c1[2])*tx/100);
				return '#'+dec2hex(c1[0]+dr)+dec2hex(c1[1]+dg)+dec2hex(c1[2]+db);
			}
			var draw=function(ctx,tx,showTx)
			{
				var w=ctx.canvas.width,h=ctx.canvas.height;
				var min=(type=='cam')?(w/2>h?h:w/2):(w>h?h:w);
				var x=Math.ceil(w/2),y=Math.ceil(type=='cam'?h:h/2);
				var b=Math.ceil(min*borderFact/10);
				var r=Math.ceil((type=='cam'?min:min/2)-b-1);
				var fontSize=min/4*fontSizeFact;
				
				/* Effacement du cadre */
				ctx.beginPath();
				ctx.clearRect(0,0,w,h);

				/* Affichage de la bande avec ombres */
				ctx.beginPath();
				if(effect=='shadow')
				{
					ctx.shadowColor='rgba(0,0,0,0.5)';
					ctx.shadowBlur=(min/300*8);
					ctx.shadowOffsetX=(min/300*1.5);
				}
				ctx.lineWidth=b;
				ctx.strokeStyle=backColor;
				ctx.arc(x,y,r,startAngle,stopAngle);
				ctx.stroke();
				ctx.closePath();

				/* Affichage de l'arc couleur */
				ctx.beginPath();
				ctx.lineWidth=b;
				ctx.strokeStyle=spread(startColor,stopColor,tx);
				ctx.arc(x,y,r,startAngle,startAngle+tx/100*deltaAngle);
				ctx.stroke();
				ctx.closePath();

				var nb=section.nb;
				if(nb) {
					var inc=deltaAngle/nb;
					for(var i=startAngle+inc;i<=stopAngle;i+=inc)
					{
						ctx.beginPath();
						ctx.strokeStyle=section.color;
						ctx.arc(x,y,r,i,i+.01);
						ctx.stroke();
						ctx.closePath();
					}
				}

				/* Affichage du pourcentage */
				if(showTx) {
					ctx.beginPath();
					ctx.fillStyle="#000000";
					ctx.font='italic '+fontSize+'px Calibri';
					var txt=Math.ceil(tx)+" %";
					var posX=(w-ctx.measureText(txt).width)/2;
					var posY=type=='cam'?Math.ceil(h):Math.ceil((h-fontSize)/2+fontSize/1.3);
					ctx.fillText(txt,posX,posY);
					ctx.stroke();
					ctx.closePath();
				}
			}
			var animate=function(tx,showTx)
			{
				if(!anim) return draw(ctx,tx,showTx);
				draw(ctx,0,showTx);
				var firstDate=new Date().getTime();
				var t=setInterval(function() {
					var tx1=tx*(new Date().getTime()-firstDate)/anim;
					if(tx1>=tx)
					{
						clearInterval(t);
						tx1=tx;
					}
					draw(ctx,tx1,showTx);
				},20);
			}
			$(window).resize(function()
			{
				var w=id.width(),h=$(id).height();
				$(canv).attr('width',w+'px');
				$(canv).attr('height',h+'px');
				draw(ctx,tx,showTx);
			});
			if(lag) animate(0,false);
			setTimeout(function() {animate(tx,showTx);},lag);
			return this;
		});
	}
})(jQuery);