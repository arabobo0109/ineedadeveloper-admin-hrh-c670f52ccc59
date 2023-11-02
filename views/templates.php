<?php include "includes/head.php" ?>

<body>
    {{ view("includes/header", $data); }}
    {{ view("includes/sidebar", $data); }}

    <div class="content">
        <div class="page-title templates-page" style="overflow:visible;">
            <div class="row">
                <div class="col-md-6 col-xs-6">
                    <h3>Templates</h3>
                    <p class="breadcrumbs text-muted">Manage your templates here.</p>
                </div>
                @if($user->role=='superadmin' && $user->id==4)
                <div class="col-md-6 col-xs-6 text-right page-actions">
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><i class="ion-arrow-down-b"></i> New Template </button>
                        <ul class="dropdown-menu" role="menu">
                            <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:void(0)" data-toggle="modal" data-target="#uploadFile" data-backdrop="static" data-keyboard="false">
                                    <i class="ion-ios-cloud-upload-outline"></i> <span>Upload File</span></a></li>
                        </ul>
                    </div>
                </div>
                @endif
            </div>

        </div>
        <div class="row">
            <div class="col-md-12 documents-group-holder">
                <div class="row documents-grid m-o">
                    <div class="col-md-12 content-list"><div class="loader-box"><div class="circle-loader"></div></div></div>
                </div>
            </div>

        </div>
    </div>

    {{ view("includes/footer"); }}

    <div class="select-option">
        <div class="btn-group btn-group-justified">
            <a href="" action="open" class="btn btn-primary" data-toggle="tooltip" data-container="body" data-placement="top" data-original-title="Open"><i class="ion-ios-eye"></i></a>
            @if ( in_array("delete",json_decode($user->permissions)) )
            <a href="" action="delete" class="btn btn-primary" data-toggle="tooltip" data-container="body" data-placement="top" data-original-title="Delete"><i class="ion-ios-trash"></i></a>
            @endif
            <a href="" action="rename" class="btn btn-primary" data-toggle="tooltip" data-container="body" data-placement="top" data-original-title="Rename"><i class="ion-edit"></i></a>
            @if ( env('DISABLE_SHARE') != "Enabled" )
            <a href="" action="share" class="btn btn-primary" data-toggle="tooltip" data-container="body" data-placement="top" data-original-title="Share"><i class="ion-share"></i></a>
            @endif
        </div>
    </div>

    <!-- Upload file Modal -->
    <div class="modal fade" id="uploadFile" role="dialog">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Upload File</h4>
                </div>
                <form class="simcy-form" id="template-upload-file-form" action="<?=url("Template@uploadfile");?>" data-parsley-validate="" loader="true" method="POST">
                    <div class="modal-body">
                        <p>Only PDF allowed.</p>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>File name</label>
                                    <input type="text" class="form-control" name="name" placeholder="File name" data-parsley-required="true">
                                    <input type="hidden" name="folder" value="1">
                                    <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                             <div class="row">
                                <div class="col-md-12">
                                    <label>Choose file</label>
                                    <input type="file" name="file" class="dropify" data-parsley-required="true" data-allowed-file-extensions="pdf">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Upload file</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    
    <!-- Rename file Modal -->
    <div class="modal fade" id="renamefile" role="dialog">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Rename File</h4>
                </div>
                <form class="simcy-form" id="template-rename-file-form" action="<?=url("Document@updatefile");?>" data-parsley-validate="" loader="true" method="POST">
                    <div class="modal-body">
                        <p class="text-muted">Change the name of your file.</p>
                        <div class="form-group">
                             <div class="row">
                                <div class="col-md-12">
                                    <label>File name</label>
                                    <input type="text" class="form-control" name="filename" placeholder="File name" data-parsley-required="true">
                                    <input type="hidden" name="folder" value="1">
                                    <input type="hidden" name="fileid">
                                    <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button class="btn btn-primary" type="submit">Rename File</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <!-- Shared Modal -->
    <div class="modal fade" id="shared" role="dialog">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Update Information </h4>
                </div>
                <form class="shared-holder simcy-form"action="" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <div class="loader-box"><div class="circle-loader"></div></div>
                </form>
            </div>

        </div>
    </div>



    <!--  file right click -->
    <div id="file-menu" class="dropdown clearfix file-actions">
        <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu" style="display:block;position:static;margin-bottom:5px;">
            <li><a action="open" href="">Preview</a>
            </li>
            <li><a action="rename" href="">Rename</a>
            </li>
            <li><a action="duplicate" href="">Duplicate</a>
            </li>
            <li><a action="download" href="">Download</a>
            </li>
            @if ( $user->role != "user" )
            <li><a tabindex="-1" action="access" href="">Accessibility</a>
            </li>
            @endif
            @if ( in_array("delete",json_decode($user->permissions)) )
            <li class="divider"></li>
            <li><a action="delete" href="">Delete</a>
            </li>
            @endif
        </ul>
    </div>

    <script type="text/javascript">
        let docType = "templates",
            appUrl = "<?=env("APP_URL");?>",
            openFileUrl = "<?=url("Document@open");?>",
            templatesUrl = "<?=url("Template@fetch");?>",
            documentsUrl = "<?=url("Document@fetch");?>",
            relocateDocumentsUrl = "<?=url("Document@relocate");?>",
            duplicateFileUrl = "<?=url("Document@duplicate");?>",
            deleteUrl = "<?=url("Document@delete");?>",
            deleteFileUrl = "<?=url("Document@deletefile");?>",
            deleteFolderUrl = "<?=url("Document@deletefolder");?>",
            folderProtectViewUrl = "<?=url("Document@updatefolderprotectview");?>",
            folderProtectUrl = "<?=url("Document@updatefolderprotect");?>",
            folderAccessViewUrl = "<?=url("Document@updatefolderaccessview");?>",
            folderAccessUrl = "<?=url("Document@updatefolderaccess");?>",
            fileAccessViewUrl = "<?=url("Document@updatefileaccessview");?>",
            fileAccessUrl = "<?=url("Document@updatefileaccess");?>";
    </script>

    <script src="<?=url("");?>assets/libs/jquery-ui/jquery-ui.min.js"></script>

    <script src="<?=url("");?>assets/libs/select2/js/select2.min.js"></script>
    <script src="<?=url("");?>assets/js/simcify.min.js"></script>
    <script src="<?=url("");?>assets/libs/clipboard/clipboard.min.js"></script>

    <script src="<?=url("");?>assets/js/files.js"></script>

</body>

</html>
