/*
	JQuery Complete v1.5
	Copyright (c) 2014-2017 Sébastien Gréau
	Licensed under the MIT license

	This plugin needs jquery library (tested at least with 1.10.1).
	jQuery: http://code.jquery.com/jquery-1.10.1.min.js

	Option:
	call:       3 possibilities:
	            1. it can be an url string like "/call.php?q=%s" where '%s' is the token automaticaly replaced by the keyword.
	            2. it can be a javascript array like "obj", where the variable obj is initialized like:
	               var obj=[]; obj['key']=''; obj['list']=[{label:'a'},...]
	            3. it can be a callback function like function(keyword,response) {return <The result>}, where:
	               - keyword: the current keyword
	               - response: function name to call to send result: response(result) {}
	               -> The result can be true if you call the response function like above
	                  The result can be an Array like the 2nd possibilitie
	                  The result can be a string which is the final called URL by the plugin. Like: return '/call.php?q='+encodeURIComponent(keyword)
	onselect:   callback function(data) {}. Hook to a function when an item is selected:
	            - data: data of the selected item
	onchange:   callback function(keyword,listData) {}. Hook to a function when key is pressed:
	            - keyword: the current keyword
	            - listData: array of current items list
	onvalidate: callBack function(keyword,curIdx,listData) {return curIdx or -1}. To do something when Enter key is pressed,
	            and to set which item you want to select anyway:
	            - keyword: the current keyword
	            - curIdx: index of the current selected item (-1 if nothing)
	            - listData: array of current items list
	            Also, this option can be:
	            - 'auto' if you want auto select the first item when none is selected
	            - 'auto_submit': like auto, but with submit
	            - 'close': just close the list. No submit.
	            - 'close_submit': close the list and submit
	cachedurat: set max duration of the inner cache (default: 1 min). 0=no cache.
	setshow:    set the slide speed to open the list box (défault: 50 ms). The value can be a function like function(box) {box.show();}
	sethide:    set the slide speed to close the list box (default: 50 ms). The value can be a function like function(box) {box.hide();}
	lag:        set delay before the server call when changing text zone (default: 50 ms).
	charmin:    minimum number of characters before to send the request (default: 1)
	classover:  add a class name on a focused item.
	classlist:  add a class name on the global 'ul' created by the list. If this option is set, you must set all CSS needed to display
	            the list.
	height:     the height of the list when classlist option is not set. (default: 200px)
	width:      the width of the list when classlist option is not set. (default: like the input zone)
	            Note: if the classlist is defined, you must set 'width:default', if you want a list width like the
	            input width. Otherwise, you will set the width into the css file.

	Server side [PHP]: (using option call:'/getlist.php?q=%s')
	getlist.php code:
	$keywords=iconv("UTF-8","CP1252//TRANSLIT",$_GET['q']);
	header('Content-Type: application/json');
	exit(json_encode(array(
		'key'=>$keywords,
		'list'=>array(
			array('label'=>utf8_encode('a: '.$keywords)),
			array('label'=>utf8_encode('b: '.$keywords))
		)
	)));
*/
(function($)
{
	$.fn.complete=function(option)
	{
		var zone=this;
		if(zone.length==0) throw 'Exception jquery complete: target not exists';

		var data=new Array,cur=-1,listOver=false;
		var cache=new Array,cacheDuration=60*1000,lag=50,setHide=50,setShow=50,charMin=1;
		var list,color,bgColor,timeout=null,xhr=null,isDefaultWidth=false;

		var response=function(result)
		{
			if(!result['list'].length) hide();

			initList(result['list']);
			if(cacheDuration>0) setCache(result['key'],result);
			if(getCount()>0) show();
		};

		var select=function(idx)
		{
			if(typeof(data[idx])!='undefined')
			{
				cur=idx;
				if(typeof option.onselect=='function') option.onselect(data[idx]);
				else zone.val(data[idx].label);
				setItem(idx,true);
				hide();
			}
		};

		var initList=function(resultList)
		{
			var html='';

			data=resultList;
			for(idx in data) html+='<li name="'+idx+'">'+data[idx].label+'</li>';
			list.html(html);

			list.hover(function()
			{
				listOver=true;
			},function()
			{
				listOver=false;
			}).children('li').hover(function()
			{
				removeStyleOver();
				addStyleOver(this);
			},function()
			{
				removeStyleOver();
			}).click(function()
			{
				select($(this).attr('name'));
			});

			if(typeof option.onchange=='function') option.onchange(zone.val(),data);
		};

		var addStyleOver=function(item)
		{
			if(typeof option.classover!='undefined') $(item).addClass(option.classover);
			else $(item).css({'color':bgColor,'background-color':color});
			$(item).attr('over',true);
		};

		var removeStyleOver=function()
		{
			var item=list.find('li[over=true]');
			if(typeof option.classover!='undefined') $(item).removeClass(option.classover);
			else $(item).removeAttr('style')
			$(item).removeAttr('over');
		};

		var getCount=function()
		{
			return list.children('li').size();
		};

		var calcItemIdx=function(idx)
		{
			idx=parseInt(idx);
			if(idx<0) idx=0;
			else
			{
				var count=getCount();
				if(idx>=count) idx=count-1;
			}
			return idx;
		};

		var setItem=function(idx,isSetPos)
		{
			removeStyleOver();
			if(idx>=0)
			{
				var item=list.find('li[name='+idx+']');
				addStyleOver(item);

				if(isSetPos)
				{
					var itemL=item.offset().top;
					var limitL=list.offset().top;
					var limitH=list.offset().top+list.height()-item.height();
					if(itemL<limitL) list.scrollTop(itemL-limitL+list.scrollTop());
					else if(itemL>limitH) list.scrollTop(itemL-limitH+list.scrollTop());
				}
			}
		};

		var getCurItem=function()
		{
			var idx=-1;
			var itemOver=list.find('li[over=true]:first');
			if(itemOver.size()) idx=parseInt(itemOver.attr('name'));
			return idx;
		};

		var hide=function()
		{
			if(list.is(':visible'))
			{
				if(typeof setHide=='function') setHide(list); else list.slideUp(setHide);
			}
		};

		var clean=function()
		{
			hide();
			initList(new Array);
		};

		var show=function()
		{
			if(!list.is(':visible') && getCount()>0)
			{
				setPos();
				listOver=false;
				if(typeof setShow=='function') setShow(list); else list.slideDown(setShow);
				if(cur>=0) setItem(cur,false);
			}
		};

		var setPos=function()
		{
			var marginTop=parseInt(zone.css('margin-top')),marginLeft=parseInt(zone.css('margin-left'));
			if(isNaN(marginTop)) marginTop=0;
			if(isNaN(marginLeft)) marginLeft=0;
			var top=zone.position().top+zone.outerHeight()+marginTop;
			var left=zone.position().left+marginLeft;
			list.css({'left':left+'px','top':top+'px'});
			if(isDefaultWidth) list.css('width',zone.outerWidth()+'px');
		};

		var setCache=function(key,data)
		{
			cache[key]={timeout:(new Date()).getTime()+cacheDuration,data:data};
		};

		var getCache=function(key)
		{
			if(typeof(cache[key])!='undefined')
			{
				if(cache[key].timeout>=(new Date()).getTime()) return cache[key].data;
				else delete cache[key];
			}
			return null;
		};

		this.keydown(function(key)
		{
			if(list.is(':visible'))
			{
				var idx=getCurItem(),stop=true,curkey=key.which;

				switch(curkey)
				{
					case 38: //key up
						setItem(calcItemIdx(idx-1),true);
						break;

					case 40: //key down
						setItem(calcItemIdx(idx+1),true);
						break;

					case 9:
					case 13:
						if(curkey==9) stop=false;
						if(typeof(option.onvalidate)!='undefined')
						{
							if(option.onvalidate=='auto') {if(idx<0) idx=0;}
							else if(option.onvalidate=='auto_submit') {hide(); stop=false; if(idx<0) idx=0;}
							else if(option.onvalidate=='close') hide();
							else if(option.onvalidate=='close_submit') {hide(); stop=false;}
							else idx=option.onvalidate(zone.val(),idx,data);
						}
						select(idx);
						break;

					default:
						stop=false;
						break;
				}

				if(stop)
				{
					key.preventDefault();
					key.stopImmediatePropagation();
				}
			}
		});

		this.keyup(function(key)
		{
			var idx=getCurItem();

			switch(key.which)
			{
				case 38: //key up
				case 40: //key down
					show();
					break;

				case 13: //Enter
					break;

				case 27: //Esc
					hide();
					break;

				default:
					clearTimeout(timeout);
					if(xhr) {xhr.abort(); xhr=null;}
					var keyword=zone.val();
					if(keyword.length>=charMin)
					{
						var tmp=getCache(keyword);
						if(!tmp) timeout=setTimeout(function(){
							var link=option.call;
							if(typeof option.call=='function')
							{
								link=option.call(keyword,response);
								if(typeof link!='string') response(link);
							}
							else if(typeof option.call!='string') response(option.call);
							else link=link.replace(/%s/i,encodeURIComponent(keyword));
							if(typeof link=='string') xhr=$.ajax({url:link,dataType:'json',success:response});
						},keyword.length>=charMin+1?lag:0);
						else response(tmp);
					} else clean();
					break;
			}
		});

		this.click(function()
		{
			if(list.is(':visible')) hide(); else show();
		});

		this.blur(function(event)
		{
			if(!listOver) hide();
		});

		//Object init
		if(typeof option.cachedurat!='undefined') cacheDuration=option.cachedurat;
		if(typeof option.lag!='undefined') lag=option.lag;
		if(typeof option.sethide!='undefined') setHide=option.sethide;
		if(typeof option.setshow!='undefined') setShow=option.setshow;
		if(typeof option.charmin!='undefined') charMin=option.charmin;

		color=zone.css('color');
		bgColor=zone.css('background-color');
		list=$(document.createElement('ul'));
		list.css({
			'position':'absolute',
			'overflow':'auto',
			'cursor':'pointer',
			'display':'none'
		});

		$(window).resize(function(){
			setPos();
		});

		if(typeof option.classlist!='undefined')
		{
			if(typeof option.width!='undefined' && option.width=='default') isDefaultWidth=true;
			list.addClass(option.classlist);
		}
		else
		{
			if(typeof option.width=='undefined') isDefaultWidth=true;
			list.css({
				'list-style-type':'none',
				'padding':0,
				'margin':0,
				'text-align':'left',
				'font-weight':'normal',
				'border':'1px solid '+zone.css('border-color'),
				'background-color':bgColor,
				'width':(!isDefaultWidth?option.width:zone.outerWidth()+'px'),
				'max-height':(typeof option.height!='undefined'?option.height:'200px'),
				'z-index':1000
			});
		}

		$(this).attr('autocomplete','off').after(list);
		return $(this);
	}
})(jQuery);