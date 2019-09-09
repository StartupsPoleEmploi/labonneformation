(function($)
{
	$.fn.datePicker=function(options)
	{
		return this.each(function()
			{
				var o=$(this);
				if(0)
				o.on('keydown',function(key)
				{
					var key=key.which;
					alert(key);
					return true;
				});
				if(0)
				o.on('keydown',function(key)
				{
					var val=o.val();
					var keyCode=key.which;

					console.log(keyCode);

					if(keyCode==8 || keyCode==37 || keyCode==39 || keyCode==46) return true;
					if(keyCode!=111 && keyCode!=191 && (keyCode<96 || keyCode>105)) return false;

					if(keyCode==111 || keyCode==191) val+='/';
					else val+=String.fromCharCode(keyCode-96+48);
					if(val.match(/^\d{1,2}\/?$/g))
					{
						if(val.match(/^\d{2}$/g)) val+='/';
						o.val(val);
						return false;
					} else
					if(val.match(/^\d{1,2}\/\d{1,2}\/?$/g))
					{
						if(val.match(/^\d{1,2}\/\d{2}$/g)) val+='/';
						o.val(val);
						return false;
					} else
					if(val.match(/^\d{1,2}\/\d{1,2}\/\d{1,4}$/g))
					{
						o.val(val);
						return false;
					}
					console.log('eh non');
					return false;
				});
				if(1)
				o.keypress(function(key)
				{
					var val=o.val()+String.fromCharCode(key.which);

					if(key.which==8) return true;
					if(val.match(/^\d{1,2}\/\d{1,2}\/\d{0,4}$/g))
						return true;
					else
					if(val.match(/^\d{1,2}\/\d{0,2}$/g))
					{
						if(val.match(/^\d{1,2}\/\d\d$/g)) val+="/";
					}
					else
					if(val.match(/^\d{0,2}$/g))
					{
						if(val.match(/^\d\d$/g)) val+="/";
					}
					else
					{
						return false;
					}
					o.val(val);
					return false;
				});
				return o;
			});
	}
})(jQuery);
