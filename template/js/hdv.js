/**
 * @see https://developers.google.com/youtube/js_api_reference
 */
function onYouTubePlayerReady(player){
    // The player argument may be the player object or player ID.
    if (player && typeof player === "string") player = document.getElementById(player);
    if (!player) return;

    var YT_PLAYERSTATE_UNSTARTED = -1;
    var pixelsToQuality = {
        // Used in portrait mode.
        "width": [
            // The quality levels are based on video height so try to guess reasonable
            // width for each of them. (There is no simple way to get actual video
            // resolution across player versions and web pages.)
            // Expecting narrow aspect ratio is safe in terms of optimum quality,
            // but could lead to selecting higher than necessary quality, which
            // is prevented by expecting wide aspect ratio for high quality levels.
            [320, "small"],     // 4:3
            [480, "medium"],    // 4:3
            [640, "large"],     // 4:3
            [1280, "hd720"],    // 16:9
            [1920, "hd1080"],   // 16:9
            [Number.POSITIVE_INFINITY, "highres"]
        ],
        // Used in landscape mode.
        "height": [
            [240, "small"],
            [360, "medium"],
            [480, "large"],
            [720, "hd720"],
            [1080, "hd1080"],
            [Number.POSITIVE_INFINITY, "highres"]
        ]
    };

    /**
     * Returns optimum quality, which is the smallest video resolution that overflows
     * the screen resolution at least on one axis (the shorter axis here). This will
     * result in downscaling even in fullscreen mode and thus highest quality on given
     * screen.
     * Note: screen.height and screen.width properties return incorrect values for
     * zoomed and unzoomed pages.
     */
    var getOptimumQuality = function() {
        // Detect portrait/landscape mode.
        var axis = screen.height <= screen.width ? "height" : "width";
        for (var i = 0; i < pixelsToQuality[axis].length; i++) {
            if (screen[axis] <= pixelsToQuality[axis][i][0])
                return pixelsToQuality[axis][i][1];
        }
    };

    var setHighestQuality = function(player) {
        // We need to be little bit stubborn about setting playback quality, because:
        // a) half the time between onYouTubePlayerReady call and actual playback
        // the quality can't be changed. And there is no event that would guarantee
        // it to be ready before playback starts. Setting quality during playback
        // won't prevent player to buffer low quality video at the beginning.
        // b) video ads block the settings too.
        // c) changing video may result in video ad (see b).
        var intervalId = window.setInterval(function() {
            try {
                // get and cache optimum quality
                this._quality = this._quality || getOptimumQuality();
                player.setPlaybackQuality(this._quality);
                if (player.getPlaybackQuality() === this._quality) {
                    window.clearInterval(intervalId);
                }
            } catch(e) {
                // Make sure interval isn't lingering after player is destroyed.
                window.clearInterval(intervalId);
            }
        }, 100);
    };

    setHighestQuality(player);
    player.addEventListener("onStateChange", function(event) {
        if (event === YT_PLAYERSTATE_UNSTARTED) {
            // Player has changed the video (without page reload).
            setHighestQuality(player);
        }
    });
}
