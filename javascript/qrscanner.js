let scanner = new Instascan.Scanner({ video: document.getElementById('preview'), mirror: false });
scanner.addListener('scan', function (content) {
    // QR Code scanned
    // post to scansucess.php
    let eventCode = content;
    // alert("QR Code Scanned: " + eventCode);
    window.location.href = "scansuccess.php?eventCode=" + eventCode;
    // header("Location: scansuccess.php?eventCode=" + eventCode);
    console.log(content);
});
Instascan.Camera.getCameras().then(function (cameras) {
    if (cameras.length > 0) {
        for (let i = 0; i < cameras.length; i++) {
            document.getElementById("video-card").innerHTML += 
            "<a href='#' class='camera-switches btn btn-primary p-1 m-1' data-cam='"+i+"'>Camera " + (i+1) + "</a>";
        }
        // when clicked start scanner of that camera
        $(".camera-switches").click(function() {
            let cam = $(this).attr("data-cam");
            scanner.start(cameras[cam]);
            $("#video-text").html("<p class='card-text'>Camera " + (parseInt(cam)+1) + "</p>");
        });
        scanner.start(cameras[1]);
    } else {
        console.error('No cameras found.');
    }
}).catch(function (e) {
    console.error(e);
});