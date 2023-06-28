<html>
<head></head>
<body>
<script>
    class AdTracker {
        constructor() {
            this.conversionType = null;
            this.trackingUrl = "http://localhost:3000/track?";
        }

        conversion(type) {
            this.conversionType = type === "post_impression" ? "imp" : type === "post_click" ? "clk" : null;
            return this;  // for method chaining
        }

        track(trackingConfig) {
            const queryParameters = {
                cid: trackingConfig.campaignId,
                crid: trackingConfig.creativeId,
                bid: trackingConfig.browserId,
                did: trackingConfig.deviceId,
                cip: trackingConfig.clientIp,
                conv: this.conversionType
            };

            const fullURL = this.trackingUrl + new URLSearchParams(queryParameters).toString();
            const img = new Image();
            img.src = fullURL;
            img.width = 0;
            img.height = 0;
            document.body.prepend(img);
            return this;
        }
    }

    window.App = window.App || new AdTracker();
</script>
<!-- do not edit below this line -->
<script>
    App.conversion("post_impression"); // should support more potential conversion types e.g.post_impression, post_click
</script>
<!--
config of track (trackingConfigObj) method should be mapped to url query parameters e.g.
campaignId -> cid
creativeId -> crid
browserId -> bid
deviceId -> did
clientIp -> cip
conv -> ??? (comes from App.conversion type param, where “post_impression” -> “imp”, ...)
-->
<script>
    App.track({
        campaignId : 4235,
        creativeId: 23423,
        browserId: 5,
        deviceId: 8,
        clientIp: '78.60.201.201'
    });
</script>
</body>
</html>
