let scanner = new Instascan.Scanner({ 
    video: document.getElementById('preview'), 
    mirror: false, 
    backgroundScan: false,
    scanPeriod: 5
    });
scanner.addListener('scan', function (content) {
    // QR Code scanned
    // post to scansucess.php
    let eventCode = content;
    // header("Location: scansuccess.php?qridentifier=$event_code");

    window.location.href = "scansuccess.php?qridentifier=" + eventCode;
    // header("Location: scansuccess.php?eventCode=" + eventCode);
    console.log(content);
});
$("#startScan").click(function() {
    if($(this).attr("data-active") == 0) {
        $(this).attr("data-active", 1);
        document.getElementById("startScan").innerHTML = "Stop Scan"; 
        Instascan.Camera.getCameras().then(function (cameras) {
            if (cameras.length > 0) {
                let camActivated = false;
                for (let i = 0; i < cameras.length; i++) {
                    const cameraName = cameras[i]['name'];
                    if (cameraName.includes("back")) {
                        scanner.start(cameras[i]);
                        camActivated = true;
                        break;
                    }
                }
                if (camActivated == false) {
                    scanner.start(cameras[0]);
                }
            } else {
                console.error('No cameras found.');
            }
        }).catch(function (e) {
            console.error(e);
        });    
    } else {
        scanner.stop();
        $(this).attr("data-active", 0);
        document.getElementById("startScan").innerHTML = "Begin Scan";

        $("#video-text").html("");
    }
});
