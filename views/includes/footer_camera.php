
<footer>
    <p class="text-right">&copy; <?=date("Y")?> <?=env("APP_NAME").' | '.env('Site_Name')?> </p>
</footer>

<script type="text/javascript">
    var countNotificationsUrl = '<?=url("Notification@count");?>';
</script>

<!-- camera scripts -->
<script src="/webcam/assets/js/jquery-1.12.4.min.js"></script>
<script src="<?=url("");?>assets/libs/bootstrap/js/bootstrap.min.js"></script>
<script src="/webcam/assets/js/dropzone.js"></script>
<!-- Script -->
<script src="/webcam/webcamjs/webcam.js"></script>
<script src="/webcam/cropper/cropper.js"></script>


<script src="<?=url("");?>assets/libs/jquery-ui/jquery-ui.min.js"></script>
<script src="<?=url("");?>assets/libs/jcanvas/jcanvas.min.js"></script>
<script src="<?=url("");?>assets/js/dom-to-image.min.js"></script>
<script src="<?=url("");?>assets/libs/jcanvas/signature.min.js"></script>
<script src="<?=url("");?>assets/js/simcify.min.js"></script>
<script src="<?=url("");?>assets/js/jquery-validate.min.js"></script>
<script src="<?=url("");?>assets/js/jquery-additional-methods.min.js"></script>

<!-- custom scripts -->
<script src="<?=url("");?>assets/js/custom.js"></script>
<script src="<?=url("");?>assets/js/signature-pad/signature_pad.umd.js"></script>
<script src="<?=url("");?>assets/js/signature-pad/app.js"></script>
<script src="<?=url("");?>assets/js/pushy.js"></script>

<script>
    var content_height = $(window).height() - 97;
    $(".content").css("min-height",content_height);

    // A button for taking snaps (from avatar and ID, passport camera)
    function take_snapshot() {
        // take snapshot and get image data
        Webcam.snap( function(data_uri) {
            // display results in page
            $("#cameraModal").modal('hide');
        @if (isset($passports))  //Avatar
            uploadImage(dataURItoBlob(data_uri));
        @else
            $("#myModal").modal({backdrop: "static"});
            $("#cropimage").html('<img id="imageprev" src="'+data_uri+'"/>');
            cropImage();
        @endif
            //document.getElementById('cropimage').innerHTML = ;
        } );

        Webcam.reset();
    }

    function saveSnap(){
        // Get base64 value from <img id='imageprev'> source
        var base64image =  document.getElementById("imageprev").src;

        Webcam.upload( base64image, 'upload.php', function(code, text) {
            console.log('Save successfully');
            //console.log(text);
        });

    }

    // camera script
    $('#cameraModal').on('show.bs.modal', function () {
        console.log("cameraModal show");
        configure();
    })
    $('#cameraModal').on('hide.bs.modal', function () {
        Webcam.reset();
        $("#cropimage").html("");
    })

    $('#myModal').on('hide.bs.modal', function () {
        $("#cropimage").html('<img id="imageprev" src="/webcam/assets/img/bg.png"/>');
    })
</script>
