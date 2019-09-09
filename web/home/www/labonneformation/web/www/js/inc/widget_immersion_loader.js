(function() {

    var getLBFUrl = function(path, env) {
        var location = window.location.href;

        return 'https://labonneformation.pole-emploi.fr' + path;
	};
	

    var appendChild = function(name, element, attach) {
        element.appendChild(document.createComment('immersion-' + name + '-start'));
        element.appendChild(attach);
        element.appendChild(document.createComment('immersion-' + name + '-end'));
    };

    function getWidgetIframe(attributes) {
		var path = '/widget-immersion/';
		if(attributes.format!='') path += attributes.format;
		else path += 'horizontal';

		if(attributes.metier!='' && attributes.lieu !='')
		{
			path += '/' + attributes.metier +
            '/' + attributes.lieu;
		}
        var iframe = document.createElement('iframe');
        iframe.className = 			'immersion-widget-iframe';		
		iframe.style.width = 		'1px';
		iframe.style.height =		'600px';
			
		iframe.style.minWidth = 	'100%';
		iframe.style.minHeight =	'100%';
        iframe.scrolling = 			'no';
        iframe.frameBorder = 		'0';
		iframe.src = getLBFUrl(path, attributes.env);
		//alert(getLBFUrl(path, attributes.env));

        return iframe;
	}	

    document.addEventListener('DOMContentLoaded', function() {

        var elements = document.querySelectorAll('.immersion-widget');
        for (var i = 0; i < elements.length; i++) {
            var element = elements[i];
            var attributes = {
                lieu: element.getAttribute('data-lieu'),
                metier: element.getAttribute('data-metier'),
				env: element.getAttribute('data-env'),
				format: element.getAttribute('data-format')
			};

			appendChild('widget', element, getWidgetIframe(attributes));

		}
	});
	
})();
