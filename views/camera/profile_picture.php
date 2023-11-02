<link rel="stylesheet" href="/webcam/assets/css/layout.css">
<link rel="stylesheet" href="/webcam/assets/css/font-awesome.min.css">
<link rel="stylesheet" href="/webcam/cropper/cropper.css">

<style>
.buttonarea {
    display: inline-block;
    margin: 20px 0 30px 75px;
}
@media only screen and (max-width:679px){
    #cropimage{
        overflow: hidden;
        height: 515px;
        width:100%;
    }
}
@media only screen and (max-width:479px){
    #cropimage{
        overflow: hidden;
        height: 400px;
        width:100%;
    }
}
</style>

@if (isset($passports))
<?php include "passports_area.php"?>
@else
    <div class="avatararea">
        <h3>Upload and Crop your Profile Picture</h3>
        <div class="row">
            <div class="imagearea col-md-8" style="float: none;margin: auto">
                <div class="avatarimage" id="drop-area">
                    @if(!empty($student->avatar) && file_exists($_SERVER['DOCUMENT_ROOT']."/uploads/avatar/".$student->avatar))
                        <img src="/uploads/avatar/{{ $student->avatar }}" id="front_image" class="img-responsive click-preview" />
                    @else
                        <img src="/webcam/assets/img/avatar.jpg" alt="avatar"  id="front_image" />
                        @if (isset($user) && $user->role!='user')
                        <p>Drop your Profile Image here</p>
                        @endif
                    @endif
                </div>
                @if ((isset($user) && $user->role!='user') || empty($student->avatar) || $student->status=='created')
                <div class="buttonarea">
                    @if (isset($user) && $user->role!='user')
                        <label class="btn upload"> <i class="fa fa-upload"></i> &nbsp; Upload<input type="file" class="sr-only" id="input" name="image" accept="image/*">  </label>
                    @endif
                    <button class="btn camera" data-backdrop="static" data-toggle="modal" data-target="#cameraModal">
                        <i class="fa fa-camera"></i> &nbsp;  Camera
                    </button>
                </div>
                @endif
                <div class="alert" role="alert"></div>
            </div>
        </div>
    </div>

    @if (!isset($user))
    <div style="width: 70%; margin: 15px auto;">
<!--        <button class="btn" id="skip_btn">Skip</button>-->
        <button class="btn btn-primary" style="float: right;" id="next_btn">Next</button>
    </div>
    @endif
@endif

<?php include "camera_modal.php"?>

<script>
    let is_uploaded=true;
    @if(empty($student->avatar))
        is_uploaded=false;
    @endif
    // Configure a few settings and attach camera
    function configure(){
        @if (isset($passports))
            // var cameras = new Array(); //create empty array to later insert available devices
            // navigator.mediaDevices.enumerateDevices() // get the available devices found in the machine
            //     .then(function(devices) {
            //         devices.forEach(function(device) {
            //             var i = 0;
            //             console.log(device);
            //             if(device.kind=== "videoinput"){ //filter video devices only
            //                 cameras[i]= device.deviceId; // save the camera id's in the camera array
            //                 i++;
            //             }
            //         });
            //     })
            Webcam.set({
                    width: 810,
                    height: 720,
                    jpeg_quality: 100
                // deviceId: { exact: cameras[1] }
            });
        @else
            Webcam.set({
                width: 540,
                height: 480,
                jpeg_quality: 100
            });
        @endif
        Webcam.attach('#my_camera');
    }

/* CROP IMAGE AFTER UPLOAD */	
    function cropImage() {
          var image = document.querySelector('#imageprev');
          var minAspectRatio = 0.5;
          var maxAspectRatio = 1.5;

          var cropper = new Cropper(image, {
            aspectRatio: 11 /12,
            minCropBoxWidth: 220,
            minCropBoxHeight: 240,

            ready: function () {
              var cropper = this.cropper;
              var containerData = cropper.getContainerData();
              var cropBoxData = cropper.getCropBoxData();
              var aspectRatio = cropBoxData.width / cropBoxData.height;
              //var aspectRatio = 4 / 3;
              var newCropBoxWidth;
              cropper.setDragMode("move");
              if (aspectRatio < minAspectRatio || aspectRatio > maxAspectRatio) {
                newCropBoxWidth = cropBoxData.height * ((minAspectRatio + maxAspectRatio) / 2);

                cropper.setCropBoxData({
                  left: (containerData.width - newCropBoxWidth) / 2,
                  width: newCropBoxWidth
                });
              }
            },

            cropmove: function () {
              var cropper = this.cropper;
              var cropBoxData = cropper.getCropBoxData();
              var aspectRatio = cropBoxData.width / cropBoxData.height;

              if (aspectRatio < minAspectRatio) {
                cropper.setCropBoxData({
                  width: cropBoxData.height * minAspectRatio
                });
              } else if (aspectRatio > maxAspectRatio) {
                cropper.setCropBoxData({
                  width: cropBoxData.height * maxAspectRatio
                });
              }
            },


          });

          $("#scaleY").click(function(){
            var Yscale = cropper.imageData.scaleY;
            if(Yscale==1){ cropper.scaleY(-1); } else {cropper.scaleY(1);};
          });

          $("#scaleX").click( function(){
            var Xscale = cropper.imageData.scaleX;
            if(Xscale==1){ cropper.scaleX(-1); } else {cropper.scaleX(1);};
          });

          $("#rotateR").click(function(){ cropper.rotate(45); });
          $("#rotateL").click(function(){ cropper.rotate(-45); });
          $("#reset").click(function(){ cropper.reset(); });
          $("#saveAvatar").click(function(){
              canvas = cropper.getCroppedCanvas({
                width: 220,
                height: 240,
              });
              canvas.toBlob(function (blob) {
                  uploadImage(blob);
              });

          });
    };

    function dataURItoBlob(dataurl) {
        var arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1],
            bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
        while(n--){
            u8arr[n] = bstr.charCodeAt(n);
        }
        return new Blob([u8arr], {type:mime});
    }

    function uploadImage(blob) {
        var $progress = $('.progress');
        var $progressBar = $('.progress-bar');
        $progress.show();

        let formData = new FormData();
        formData.append('avatar', blob, 'avatar.jpg');
        formData.append('user_id', '{{$student->id}}');
        formData.append('csrf-token', '{{csrf_token()}}');
        let preview_image=document.getElementById('front_image');
    @if (isset($passports))
        let front_back=$("#upload_front_back").val();
        if (front_back==='back') {
            preview_image=document.getElementById('back_image');
        }
        else
            preview_image=document.getElementById('front_image');

        formData.append('front_back', front_back);
        formData.append('document_type', $("#document_type").val());
    @endif
        $.ajax('/student/uploadPicture', {
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,

            xhr: function () {
                var xhr = new XMLHttpRequest();

                xhr.upload.onprogress = function (e) {
                    var percent = '0';
                    var percentage = '0%';

                    if (e.lengthComputable) {
                        percent = Math.round((e.loaded / e.total) * 100);
                        percentage = percent + '%';
                        $progressBar.width(percentage).attr('aria-valuenow', percent).text(percentage);
                    }
                };

                return xhr;
            },

            success: function () {
                console.log('student/uploadPicture Success');
                swal({
                    title: "Success!",
                    text: "Upload success",
                    type: "success"
                });
            },

            error: function () {
                swal("Error!","Upload error", "error");
            },

            complete: function () {
                console.log('student/uploadPicture complete');
                is_uploaded=true;
                $("#myModal").modal('hide');
                $progress.hide();
                blobToDataURL(blob, function(dataurl){
                    console.log(dataurl);
                    preview_image.src = dataurl;
                });
            },
        });
    }
    //**blob to dataURL**
    function blobToDataURL(blob, callback) {
        var a = new FileReader();
        a.onload = function(e) {callback(e.target.result);}
        a.readAsDataURL(blob);
    }

    $("#skip_btn").click(function () {
        // console.log(location);
        window.location.replace (location.origin+'/payment');
    });

    $("#next_btn").click(function () {
        if (is_uploaded)
            window.location.replace (location.origin+'/payment');
        else
            swal("You can't go to next!", "Please upload your profile picture.", "error");
    });

    /* UPLOAD Image */
    var input = document.getElementById('input');
    var $alert = $('.alert');


    /* DRAG and DROP File */
    $("#drop-area").on('dragenter', function (e) {
        e.preventDefault();
    });

    $("#drop-area").on('dragover', function (e) {
        e.preventDefault();
    });

    $("#drop-area").on('drop', function (e) {
        var image = document.querySelector('#imageprev');
        var files = e.originalEvent.dataTransfer.files;

        var done = function (url) {
            input.value = '';
            image.src = url;
            $alert.hide();
            $("#myModal").modal({backdrop: "static"});
            cropImage();
        };

        var reader;
        var file;
        var url;

        if (files && files.length > 0) {
            file = files[0];

            if (URL) {
                done(URL.createObjectURL(file));
            } else if (FileReader) {
                reader = new FileReader();
                reader.onload = function (e) {
                    done(reader.result);
                };
                reader.readAsDataURL(file);
            }
        }

        e.preventDefault();

    });

    /* INPUT UPLOAD FILE */
    input.addEventListener('change', function (e) {
        var image = document.querySelector('#imageprev');
        var files = e.target.files;
        var done = function (url) {
            input.value = '';
            image.src = url;
            $alert.hide();
            $("#myModal").modal({backdrop: "static"});
            cropImage();

        };
        var reader;
        var file;
        var url;

        if (files && files.length > 0) {
            file = files[0];

            if (URL) {
                done(URL.createObjectURL(file));
            } else if (FileReader) {
                reader = new FileReader();
                reader.onload = function (e) {
                    done(reader.result);
                };
                reader.readAsDataURL(file);
            }
        }
    });

</script>