function NoJQueryPostMessageMixin(postBinding,receiveBinding){var setMessageCallback,unsetMessageCallback,currentMsgCallback,intervalId,lastHash,cacheBust=1;return window.postMessage?(window.addEventListener?(setMessageCallback=function(callback){window.addEventListener("message",callback,!1)},unsetMessageCallback=function(callback){window.removeEventListener("message",callback,!1)}):(setMessageCallback=function(callback){window.attachEvent("onmessage",callback)},unsetMessageCallback=function(callback){window.detachEvent("onmessage",callback)}),this[postBinding]=function(message,targetUrl,target){targetUrl&&target.postMessage(message,targetUrl.replace(/([^:]+:\/\/[^\/]+).*/,"$1"))},this[receiveBinding]=function(callback,sourceOrigin,delay){return currentMsgCallback&&(unsetMessageCallback(currentMsgCallback),currentMsgCallback=null),!!callback&&void(currentMsgCallback=setMessageCallback(function(e){switch(Object.prototype.toString.call(sourceOrigin)){case"[object String]":if(sourceOrigin!==e.origin)return!1;break;case"[object Function]":if(sourceOrigin(e.origin))return!1}callback(e)}))}):(this[postBinding]=function(message,targetUrl,target){targetUrl&&(target.location=targetUrl.replace(/#.*$/,"")+"#"+ +new Date+cacheBust++ +"&"+message)},this[receiveBinding]=function(callback,sourceOrigin,delay){intervalId&&(clearInterval(intervalId),intervalId=null),callback&&(delay="number"==typeof sourceOrigin?sourceOrigin:"number"==typeof delay?delay:100,intervalId=setInterval(function(){var hash=document.location.hash,re=/^#?\d+&/;hash!==lastHash&&re.test(hash)&&(lastHash=hash,callback({data:hash.replace(re,"")}))},delay))}),this}