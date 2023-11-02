<div class="avatararea" style="margin-top: 20px; padding: 20px 0;">
<!--    <div class="row" style="padding: 20px 30px;">-->
<!--        <div class="col-md-3">-->
<!--            <span class="color-red" style="font-size: 15px;line-height: 30px;">Please select document type :</span>-->
<!--        </div>-->
        <div class="col-md-3 hidden">
            <select class="form-control" id="document_type">
                <option
                        @if( $passports->type == "ID" )
                        selected
                    @endif
                    value="ID">ID Card</option>
                <option
                        @if( $passports->type == "Passport" )
                        selected
                    @endif
                        value="Passport">Passport</option>
            </select>
        </div>
<!--    </div>-->
    <div class="row">
        <div style="margin-left: 50px; margin-top: 30px;">Please upload a copy or take a picture of your Govt issued ID</div>
        <div class="imagearea col-lg-8 col-md-8 col-sm-12">
            <div class="avatarimage" id="drop-area">
                @if(!empty($passports->path0) && file_exists($_SERVER['DOCUMENT_ROOT']."/uploads/passport/".$passports->path0))
                    <img src="/uploads/passport/{{ $passports->path0 }}" class="img-responsive click-preview" id="front_image"/>
                @else
                    <img src="/webcam/assets/img/avatar.jpg" id="front_image"  class="img-responsive" />
                @endif
            </div>
            <?php include "scanner.php"?>
        </div>
    </div>

<!--    <div class="row">-->
<!--        <div style="margin-left: 50px; margin-top: 30px;">Back Image (Please upload and take a picture of back image)</div>-->
<!--        <div class="imagearea col-lg-8 col-md-5 col-sm-12">-->
<!--            <div class="avatarimage" id="drop-area">-->
<!--                @if(!empty($passports->path1) && file_exists($_SERVER['DOCUMENT_ROOT']."/uploads/passport/".$passports->path1))-->
<!--                    <img src="/uploads/passport/{{ $passports->path1 }}" alt="avatar"  id="back_image" />-->
<!--                @else-->
<!--                    <img src="/webcam/assets/img/avatar.jpg" alt="avatar"  id="back_image" />-->
<!--                @endif-->
<!--            </div>-->
<!--            <div class="buttonarea" style="margin-left: 20px;">-->
<!--                <label class="btn upload"> <i class="fa fa-upload"></i> &nbsp; Upload-->
<!--                    <input type="file" class="sr-only" id="input_back" name="image" accept="image/*">-->
<!--                </label>-->
<!---->
<!--                <button class="btn camera" data-backdrop="static" data-toggle="modal"-->
<!--                        data-target="#cameraModal" onclick="set_front_back('back')"><i class="fa fa-camera"></i> &nbsp; Camera-->
<!--                </button>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->

    <input type="hidden" id="upload_front_back" >
</div>

@if (!isset($user))
<div style="width: 70%; margin: 15px auto;">
<!--    <a class="btn" href="{{ url('Student@profilePicture')}}">Skip</a>-->
    <button class="btn btn-primary"  style="float: right;" onclick="window.location.replace (location.origin+'/profilePicture/avatar');">Next</button>
</div>
@endif

<script>
    function set_front_back(front_back){
        $("#upload_front_back").val(front_back);
    }

    /* INPUT Front UPLOAD FILE */
    input_front.addEventListener('change', function (e) {
        var image = document.querySelector('#imageprev');
        var files = e.target.files;
        var done = async function (url) {
            input_front.value = '';
            image.src = url;
            // $("#myModal").modal({backdrop: "static"});
            $("#upload_front_back").val("front");
            let upload_file = await fetch(url).then(r => r.blob());
            uploadImage(upload_file);
        };
        var reader;
        var file;
        var url;

        if (files && files.length > 0) {
            file = files[0];

            if (URL) {
                console.log("URL");
                done(URL.createObjectURL(file));
            } else if (FileReader) {
                console.log("FileReader");
                reader = new FileReader();
                reader.onload = function (e) {
                    done(reader.result);
                };
                reader.readAsDataURL(file);
            }
        }
    });

    /* INPUT Back UPLOAD FILE */
    // input_back.addEventListener('change', function (e) {
    //     var image = document.querySelector('#imageprev');
    //     var files = e.target.files;
    //     var done = function (url) {
    //         input_back.value = '';
    //         image.src = url;
    //         $("#myModal").modal({backdrop: "static"});
    //         $("#upload_front_back").val("back");
    //         cropImage();
    //
    //     };
    //     var reader;
    //     var file;
    //     var url;
    //
    //     if (files && files.length > 0) {
    //         file = files[0];
    //
    //         if (URL) {
    //             done(URL.createObjectURL(file));
    //         } else if (FileReader) {
    //             reader = new FileReader();
    //             reader.onload = function (e) {
    //                 done(reader.result);
    //             };
    //             reader.readAsDataURL(file);
    //         }
    //     }
    // });
</script>