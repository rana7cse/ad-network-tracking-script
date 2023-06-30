<html>
<head>
    <title>Ad campaign tracking script is running</title>
</head>
<body>
<h1>Ad campaign tracking script is running</h1>
<script>
    class AdTracker {
        constructor() {
            this.conversionType = null;
            this.trackingUrl = "http://localhost:3000/track";
        }

        conversion(type) {
            this.conversionType = type === "post_impression" ? "imp" : type === "post_click" ? "clk" : null;
        }

        track(trackingConfig) {
            const configAsParam = {
                cid: trackingConfig.campaignId,
                crid: trackingConfig.creativeId,
                bid: trackingConfig.browserId,
                did: trackingConfig.deviceId,
                cip: trackingConfig.clientIp,
                conv: this.conversionType
            };

            // check if params are not valid then stop here
            if (!this.checkParams(configAsParam)) {
                return false;
            }

            // Append image to body
            this.loadImageUrl(
                this.buildUrl(configAsParam)
            );

            return this;
        }

        buildUrl(queryParamsObject) {
            const urlQueryParams = new URLSearchParams(queryParamsObject).toString()
            return `${this.trackingUrl}?${urlQueryParams}`;
        }

        loadImageUrl(url) {
            const img = new Image();
            img.src = url;
            img.width = 0;
            img.height = 0;
            document.body.prepend(img);
        }

        checkParams(params) {
            for (let key in params) {
                if (!params[key]) return false
            }

            return true;
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
