<?php // [MINOR-CHANGES-WANTED 13/7/23]
if (!isset($_POST['eventCode'])) {
    ?>
    <div class="container">
        <!-- TODO CHECK  -->
        <div 
            class="" 
            style="
             
                height: 85vh;"
            >
            <div 
                class="position-absolute" 
                style="
                    left: 50%; 
                    top: 45%; 
                    transform: translate(-50%, -50%);"
                >
                <div class="card borderRemoveOnMobile" style="width: 400px;">
                    <!-- Instacam Library -->
                    <script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
                    <video class="card-img-top" id="preview" style="max-height: 200px;"></video>
                    <div class="card-body" id="video-card">
                        <div 
                            class="
                                row 
                                row-cols-auto
                                g-3
                                "
                            >
                            <h5 
                                class="
                                    card-title 
                                    col-sm-8
                                    col-12
                                    text-center
                                    align-self-center"
                                >Scan Event QR
                            </h5>
                            <a 
                                class="
                                    col-sm-4
                                    col-12
                                    btn 
                                    btn-outline-dark 
                                    py-2
                                    mb-1
                                    "
                                id="startScan"
                                data-active='0'
                                >Begin scan</a>
                            <div class="card-text row mb-1" id="video-text"
                                style="
                                    width: 100%;
                                    text-align: center;
                                    margin: auto;
                                    "
                                ></div>    
                        </div>
                        
                        <hr>
                        <form 
                            class="
                                row 
                                row-cols-auto 
                                g-3 
                                align-items-center
                                mt-1" 
                            action="scansuccess.php" 
                            method="GET"
                            >
                            <div 
                                class="
                                    col-sm-8
                                    col-12
                                    form-group 
                                    mb-3"
                                >
                                <input 
                                    class="
                                        form-control
                                        text-center 
                                        py-2"
                                    id="input_event_code" 
                                    type="text" 
                                    name="input_event_code"
                                    placeholder="Enter event code"
                                    required
                                    >
                            </div>
                            <input 
                                class="
                                    col-sm-4
                                    col-12
                                    py-2
                                    mb-3
                                    btn 
                                    btn-outline-dark" 
                                type="submit" 
                                value="Join"
                                >
                        </form>
                    </div>

                    <script type="text/javascript" src="./javascript/qrscanner.js"></script> 
                </div>
            </div>
        </div>
    </div>
<?php
}
?>