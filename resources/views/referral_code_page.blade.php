<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SayHello</title>
</head>
<body>
    <input type="hidden" name="referral_code" id="referral_code" value="{{ $referral_code }}">
</body>
<script>
</script>
<script>
    var referral_code = document.getElementById('referral_code').value;

    console.log(referral_code);
    myfunction();
    function myfunction() {
        if (window.navigator.userAgentData) {
        const platform = window.navigator.userAgentData.platform;

        if (platform.includes('Android')) {
            // The device is running Android
            // alert('Android device');
            // window.location.href = `http://play.google.com/store/apps/details?id=${referral_code}`;
            
            
            window.location.href = "https://dev.codemeg.com/sayhello/Say_hello_26-06.apk";

            var apkUrl = "https://dev.codemeg.com/sayhello/Say_hello_26-06.apk";
            var link = document.createElement('a');
            link.href = apkUrl;
            link.download = 'app.apk';;
        } else if (platform.includes('iPhone') || platform.includes('iPad') || platform.includes('iPod')) {
            // The device is running iOS
            // window.location.href = `http://itunes.apple.com/lb/app/${referral_code}`;
            window.location.href = "https://dev.codemeg.com/sayhello/Say_hello_26-06.apk";

        } else {
            // The platform couldn't be determined
            // alert('Unknown platform');
        }
        } else {
        // alert('iOS device');
        // window.location.href =`http://itunes.apple.com/lb/app/${referral_code}`;
        window.location.href = "https://dev.codemeg.com/sayhello/Say_hello_26-06.apk";
        console.log('User Agent Client Hints API not supported');
        }


    }
</script>
</html>