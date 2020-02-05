var x =getMobileOperatingSystem()
function getMobileOperatingSystem() {
  var userAgent = navigator.userAgent || navigator.vendor || window.opera;

      // Windows Phone must come first because its UA also contains "Android"
    if (/windows phone/i.test(userAgent)) {
        return "Windows Phone";
    }

    if (/android/i.test(userAgent)) {
        return "Android";
    }

    // iOS detection from: http://stackoverflow.com/a/9039885/177710
    if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
        return "iOS";
    }

    return "unknown";
}
//document.getElementById("demo").innerHTML = getMobileOperatingSystem();
if (x == "Android") {
   window.location.assign("https://play.google.com/store/apps/details?id=..................");
} 
else if (x == "iOS") {
	window.location.assign("https://itunes.apple.com/....................");       
}