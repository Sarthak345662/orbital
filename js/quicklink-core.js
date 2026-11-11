!function(e,n){"object"==typeof exports&&"undefined"!=typeof module?module.exports=n():"function"==typeof define&&define.amd?define(n):e.quicklink=n()}(this,function(){var e={};function n(e){return new Promise(function(n,t){var r=new XMLHttpRequest;r.open("GET",e,r.withCredentials=!0),r.onload=function(){200===r.status?n():t()},r.send()})}var t,r,i=(t="prefetch",((r=document.createElement("link")).relList||{}).supports&&r.relList.supports(t)?function(e){return new Promise(function(n,t){var r=document.createElement("link");r.rel="prefetch",r.href=e,r.onload=n,r.onerror=t,document.head.appendChild(r)})}:n);function o(t,r,o){if(!(e[t]||(o=navigator.connection)&&((o.effectiveType||"").includes("2g")||o.saveData)))return(r?function(e){return null==self.fetch?n(e):fetch(e,{credentials:"include"})}:i)(t).then(function(){e[t]=!0})}var u=u||function(e){var n=Date.now();return setTimeout(function(){e({didTimeout:!1,timeRemaining:function(){return Math.max(0,50-(Date.now()-n))}})},1)},c=new Set,f=new IntersectionObserver(function(e){e.forEach(function(e){if(e.isIntersecting){var n=e.target.href;c.has(n)&&a(n)}})});function a(e){c.delete(e),o(new URL(e,location.href).toString(),f.priority)}return function(e){e=Object.assign({timeout:2e3,priority:!1,timeoutFn:u,el:document},e),f.priority=e.priority;var n=e.origins||[location.hostname],t=e.ignores||[];e.timeoutFn(function(){e.urls?e.urls.forEach(a):Array.from(e.el.querySelectorAll("a"),function(e){f.observe(e),n.length&&!n.includes(e.hostname)||function e(n,t){return Array.isArray(t)?t.some(function(t){return e(n,t)}):(t.test||t).call(t,n.href,n)}(e,t)||c.add(e.href)})},{timeout:e.timeout})}});

window.addEventListener( 'load', () => {
	const exportedOptions = window.quicklinkOptions || {};

	const options = {};
	// el: Convert selector into element reference.
	if ( 'string' === typeof exportedOptions.el && exportedOptions.el ) {
		options.el = document.querySelector( exportedOptions.el );
	}

	// urls: Verify we don't get an empty array, as that would turn off quicklink.
	if ( Array.isArray( exportedOptions.urls ) && 0 < exportedOptions.urls.length ) {
		options.urls = exportedOptions.urls;
	}

	// timeout: Verify we actually get an int for milliseconds.
	if ( 'number' === typeof exportedOptions.timeout ) {
		options.timeout = exportedOptions.timeout;
	}

	// timeoutFn: Obtain function reference as opposed to function string, if it is not the default.
	if ( 'string' === typeof exportedOptions.timeoutFn && 'requestIdleCallback' !== exportedOptions.timeoutFn ) {
		const timeoutFn = window[ exportedOptions.timeoutFn ];
		options.timeoutFn = function() {
			return timeoutFn.apply( window, arguments );
		};
	}

	// priority: Obtain priority.
	if ( 'boolean' === typeof exportedOptions.priority ) {
		options.priority = exportedOptions.priority;
	}

	// origins: Verify we don't get an empty array, as that would turn off quicklink.
	if ( Array.isArray( exportedOptions.origins ) && 0 < exportedOptions.origins.length ) {
		options.origins = exportedOptions.origins;
	}

	// ignores: Convert strings to regular expressions.
	if ( Array.isArray( exportedOptions.ignores ) && 0 < exportedOptions.ignores.length ) {
		options.ignores = exportedOptions.ignores.map( ( ignore ) => {
			return new RegExp( ignore );
		} );
	}

	quicklink( options );
} );