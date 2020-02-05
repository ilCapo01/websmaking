<script src="https://static.codepen.io/assets/common/stopExecutionOnTimeout-de7e2ef6bfefd24b79a3f68b414b87b8db5b08439cac3f1012092b2290c719cd.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script id="rendered-js">
      // Adjusted version of https://codepen.io/bramus/pen/yikfd
// This version also reverses the animation when elements that got slide into view
// succesively slide out of view again.

// In case you"re wondering about that `.css("top", $animatable.css("top"))` part:
// -> it"s the magic part in this code as it triggers layout, so the browser will 
// render after having deleted the animate-in class and having added the animate-out
// class. That way the animation-play-state will actually change from running to 
// paused to running again, thus triggering the start of the animation

jQuery(function ($) {

  var doAnimations = function () {

    var offset = $(window).scrollTop() + $(window).height(),
    $animatables = $(".animatable");

    $animatables.each(function (i) {
      var $animatable = $(this);

      // Items that are "above the fold"
      if ($animatable.offset().top + $animatable.height() + 50 < offset) {

        // Item previously wasn"t marked as "above the fold": mark it now
        if (!$animatable.hasClass("animate-in")) {
          $animatable.removeClass("animate-out").css("top", $animatable.css("top")).addClass("animate-in");
        }

      }

      // Items that are "below the fold"
      else if ($animatable.offset().top + $animatable.height() + 50 > offset) {

          // Item previously wasn"t marked as "below the fold": mark it now
          if ($animatable.hasClass("animate-in")) {
            $animatable.removeClass("animate-in").css("top", $animatable.css("top")).addClass("animate-out");
          }

        }

    });

  };

  // Hook doAnimations on scroll, and trigger a scroll
  $(window).on("scroll", doAnimations);
  $(window).trigger("scroll");

});
      //# sourceURL=pen.js
    </script>