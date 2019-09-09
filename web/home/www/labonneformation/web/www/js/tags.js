function tags(disable,tagSEO) {
	if(!disable) {
		tarteaucitron.user.analyticsUa = idAnalytics;
        tarteaucitron.user.analyticsMore = function () {
			window.GoogleAnalyticsObject = 'ga';
			window.ga = window.ga || function () {
				window.ga.q = window.ga.q || [];
				window.ga.q.push(arguments);
			};
			ga('require', idOptimize);
 		};
        (tarteaucitron.job = tarteaucitron.job || []).push('analytics');
	} else {
		console.log("Analytics disabled");
	}

	if(tagSEO!=="") {
		tarteaucitron.user.gtagUa = tagSEO;
        (tarteaucitron.job = tarteaucitron.job || []).push('gtag');
	} else {
		console.log("Tag SEO disabled");
	}
}
/* Si la variable globale disableAnalytics existe et est à true, alors on ne pose pas de tag */
tags(typeof disableAnalytics==="undefined"?false:disableAnalytics,typeof tagSEO==="undefined"?false:tagSEO);

function pageview(path) {
	var disable=typeof disableAnalytics==="undefined"?false:disableAnalytics;
	if(!disable)
	{
		tarteaucitron.user.analyticsUa = idAnalytics;
		tarteaucitron.user.analyticsPageView = path;
        (tarteaucitron.job = tarteaucitron.job || []).push('analytics');
	}
}

function track(label) {
	var disable=typeof disableAnalytics==="undefined"?false:disableAnalytics;
	if(!disable)
	{
		tarteaucitron.user.analyticsUa = idAnalytics;
		tarteaucitron.user.analyticsMore = function () {
			window.GoogleAnalyticsObject = 'ga';
			window.ga = window.ga || function () {
				window.ga.q = window.ga.q || [];
				window.ga.q.push(arguments);
			};
			
			ga('send',{
				hitType:'event',
				eventCategory:'outbound',
				eventAction:'click',
				eventLabel:label
			});
		};
        (tarteaucitron.job = tarteaucitron.job || []).push('analytics');
	}
}
/* Hotjar Tracking Code for http://labonneformation.pole-emploi.fr */
(tarteaucitron.job = tarteaucitron.job || []).push('hotjar');

function hotjar(id) {
    (function(h,o,t,j,a,r){
        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
        h._hjSettings={hjid:id,hjsv:5};
        a=o.getElementsByTagName('head')[0];
        r=o.createElement('script');r.async=1;
        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
        a.appendChild(r);
    })(window,document,'//static.hotjar.com/c/hotjar-','.js?sv=');
}
/* Si la variable globale _nohotjar existe et est à true, alors on ne pose pas de tag */
//if(typeof _nohotjar==="undefined") hotjar(idHotjar);
if(typeof _nohotjar==="undefined"){
	tarteaucitron.user.hotjarId = idHotjar;
	tarteaucitron.user.HotjarSv = 5;
}

/* Crisp chat*/
function crisp(id) {
	(function(){
		window.$crisp=[];
		window.CRISP_WEBSITE_ID=id;
		d=document;
		s=d.createElement("script");
		s.src="https://client.crisp.chat/l.js";
		s.async=1;
		d.getElementsByTagName("head")[0].appendChild(s);
	})();
}
/* Si la variable globale _nocrisp existe et est à true, alors on ne pose pas de tag */
if(typeof _nocrisp==="undefined") crisp(idCrisp);

