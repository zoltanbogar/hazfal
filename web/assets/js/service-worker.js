"use strict";var precacheConfig=[["/assets/fonts/fontawesome-webfont.674f50d2.eot","674f50d287a8c48dc19ba404d20fe713"],["/assets/fonts/fontawesome-webfont.af7ae505.woff2","af7ae505a9eed503f8b8e6982036873e"],["/assets/fonts/fontawesome-webfont.b06871f2.ttf","b06871f281fee6b241d60582ae9369b9"],["/assets/fonts/fontawesome-webfont.fee66e71.woff","fee66e712a8a08eef5805a46892932ad"],["/assets/fonts/themify.2c454669.eot","2c454669bdf3aebf32a1bd8ac1e0d2d6"],["/assets/fonts/themify.a1ecc3b8.woff","a1ecc3b826d01251edddf29c3e4e1e97"],["/assets/fonts/themify.e23a7dca.ttf","e23a7dcaefbde4e74e263247aa42ecd7"],["/assets/images/404.f97802b0.png","f97802b0567b0469f22da92330d64ec6"],["/assets/images/500.6e177923.png","6e17792333c1f535f031067e34cea15b"],["/assets/images/bg.fd1a1dd6.jpg","fd1a1dd62728fd79e8870e42399fdc2d"],["/assets/images/fontawesome-webfont.912ec66d.svg","912ec66d7572ff821749319396470bde"],["/assets/images/sf.f10c2d76.png","f10c2d76500745c76d0e8a9c78081e93"],["/assets/images/sort_asc.9326ad44.png","9326ad44ae4bebdedd141e7a53c2a730"],["/assets/images/sort_asc_disabled.d7dc10c7.png","d7dc10c78f23615d328581aebcd805eb"],["/assets/images/sort_both.9a648608.png","9a6486086d09bb38cf66a57cc559ade3"],["/assets/images/sort_desc.1fc418e3.png","1fc418e33fd5a687290258b23fac4e98"],["/assets/images/sort_desc_disabled.bda51e15.png","bda51e15154a18257b4f955a222fd66f"],["/assets/images/themify.9c8e96ec.svg","9c8e96ecc7fa01e6ebcd196495ed2db5"],["/assets/manifest.json","61bbc0959ee4602c78560644c6105a93"]],cacheName="sw-precache-v3-Symfonator-"+(self.registration?self.registration.scope:""),ignoreUrlParametersMatching=[/^utm_/],addDirectoryIndex=function(e,t){var s=new URL(e);return"/"===s.pathname.slice(-1)&&(s.pathname+=t),s.toString()},cleanResponse=function(e){return e.redirected?("body"in e?Promise.resolve(e.body):e.blob()).then(function(t){return new Response(t,{headers:e.headers,status:e.status,statusText:e.statusText})}):Promise.resolve(e)},createCacheKey=function(e,t,s,a){var n=new URL(e);return a&&n.pathname.match(a)||(n.search+=(n.search?"&":"")+encodeURIComponent(t)+"="+encodeURIComponent(s)),n.toString()},isPathWhitelisted=function(e,t){if(0===e.length)return!0;var s=new URL(t).pathname;return e.some(function(e){return s.match(e)})},stripIgnoredUrlParameters=function(e,t){var s=new URL(e);return s.hash="",s.search=s.search.slice(1).split("&").map(function(e){return e.split("=")}).filter(function(e){return t.every(function(t){return!t.test(e[0])})}).map(function(e){return e.join("=")}).join("&"),s.toString()},hashParamName="_sw-precache",urlsToCacheKeys=new Map(precacheConfig.map(function(e){var t=e[0],s=e[1],a=new URL(t,self.location),n=createCacheKey(a,hashParamName,s,/\.\w{8}\./);return[a.toString(),n]}));function setOfCachedUrls(e){return e.keys().then(function(e){return e.map(function(e){return e.url})}).then(function(e){return new Set(e)})}self.addEventListener("install",function(e){e.waitUntil(caches.open(cacheName).then(function(e){return setOfCachedUrls(e).then(function(t){return Promise.all(Array.from(urlsToCacheKeys.values()).map(function(s){if(!t.has(s)){var a=new Request(s,{credentials:"same-origin"});return fetch(a).then(function(t){if(!t.ok)throw new Error("Request for "+s+" returned a response with status "+t.status);return cleanResponse(t).then(function(t){return e.put(s,t)})})}}))})}).then(function(){return self.skipWaiting()}))}),self.addEventListener("activate",function(e){var t=new Set(urlsToCacheKeys.values());e.waitUntil(caches.open(cacheName).then(function(e){return e.keys().then(function(s){return Promise.all(s.map(function(s){if(!t.has(s.url))return e.delete(s)}))})}).then(function(){return self.clients.claim()}))}),self.addEventListener("fetch",function(e){if("GET"===e.request.method){var t,s=stripIgnoredUrlParameters(e.request.url,ignoreUrlParametersMatching),a="index.html";(t=urlsToCacheKeys.has(s))||(s=addDirectoryIndex(s,a),t=urlsToCacheKeys.has(s));var n="index.html";!t&&"navigate"===e.request.mode&&isPathWhitelisted([],e.request.url)&&(s=new URL(n,self.location).toString(),t=urlsToCacheKeys.has(s)),t&&e.respondWith(caches.open(cacheName).then(function(e){return e.match(urlsToCacheKeys.get(s)).then(function(e){if(e)return e;throw Error("The cached response that was expected is missing.")})}).catch(function(t){return console.warn('Couldn\'t serve response for "%s" from cache: %O',e.request.url,t),fetch(e.request)}))}});