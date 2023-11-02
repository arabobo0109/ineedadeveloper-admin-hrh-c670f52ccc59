<!-- The Make Selection Modal -->
<div class="modal" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Make a selection</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div id="cropimage">
                    <img id="imageprev" src="/webcam/assets/img/bg.png"/>
                </div>

                <div class="progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                </div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <div class="btngroup">
                    <button type="button" class="btn upload1 float-left" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btnsmall" id="rotateL" title="Rotate Left"><i class="fa fa-undo"></i></button>
                    <button type="button" class="btn btnsmall" id="rotateR" title="Rotate Right"><i class="fa fa-repeat"></i></button>
                    <button type="button" class="btn btnsmall" id="scaleX" title="Flip Horizontal"><i class="fa fa-arrows-h"></i></button>
                    <button type="button" class="btn btnsmall" id="scaleY" title="Flip Vertical"><i class="fa fa-arrows-v"></i></button>
                    <button type="button" class="btn btnsmall" id="reset" title="Reset"><i class="fa fa-refresh"></i></button>
                    <button type="button" class="btn camera1 float-right" id="saveAvatar">Save</button>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- The Camera Modal -->
<div class="modal" id="cameraModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Take a picture</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body"  style="width: 100%;height: 100%">
                <div id="my_camera"></div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn upload" data-dismiss="modal">Close</button>
                <button type="button" class="btn camera" onclick="take_snapshot()">Take a picture</button>
            </div>
        </div>
    </div>
</div>
