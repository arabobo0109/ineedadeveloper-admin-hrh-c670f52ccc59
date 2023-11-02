<?php include "includes/head.php" ?>
<body>
    <!-- header start -->
    {{ view("includes/header", $data); }}
    <!-- sidebar -->
    {{ view("includes/sidebar", $data); }}

    <div class="content">
        <div class="page-title">
            <h3>Import Students</h3>
        </div>
            <div class="row student bg-white">
                <div class="row" style="padding: 20px;margin-left: 10px">
                    <a class="btn btn-success" href="<?=url("")?>assets/files/student_demo.xlsx" download><i class="ion-code-download"></i>
                        Download Template Excel file
                    </a>
                </div>

                <div class="col-md-6" style="padding: 10px">
                    <form class="simcy-form page-actions" method="post" loader="true" action="<?= url("Import@Excel"); ?>" enctype="multipart/form-data" style="margin:20px;">
                        <input type="hidden" name="csrf-token" value="{{ csrf_token() }}">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Room Weekly Fee($)</label>
                                    <input type="number" class="form-control" name="weekly" placeholder="weekly fee" value="{{ $company->weekly}}" required id="weekly_input">
                                    <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />
                                </div>
                                <div class="col-md-6">
                                    <label>Security Deposit($)</label>
                                    <input type="text" class="form-control" name="security" placeholder="Security Deposit" value="{{ $company->security }}" data-parsley-required="true">
                                </div>
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom: 20px">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Administration Fee($)</label>
                                    <input type="text" class="form-control" name="administration" placeholder="administration fee" value="{{ $company->administration }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label>Laundry Fee($)</label>
                                    <input type="text" class="form-control" name="laundry" placeholder="Laundry Fee" value="{{ $company->laundry}}" data-parsley-required="true">
                                </div>
                            </div>
                        </div>

                        <input type="file" name="file" id="file" accept=".xlsx" style="display: initial">
                        <button type="submit" id="submit" name="action" value="import"
                                class="btn btn-primary"><i class="ion-email-unread"></i>Import
                        </button>
                    </form>
                </div>
                <div class="col-md-6">
                    <p>The lease_start and lease_end should be 'yyyy-mm-dd'.</p>
                    <p>If no brithday, Please input '1800-01-01'.</p>
                    <p>Gender should be 'Male', 'Female' or 'M', 'F'.</p>
                    <p>If no employer, Please input 'None'.</p>
                    <p>If no sponsor, Please input 'None'.</p>
                    <p>If no identifier, Please input 'None'.</p>
                    <hr>
                    <p>After you imported a excel file and downloaded, Already added row has 'yes' in 'Is Imported?'.
                    so, you can edit again only rows of which 'Is Imported?' isn't yes. and upload again.</p>
                </div>
            </div>
    </div>

    <!-- footer -->
    {{ view("includes/footer"); }}
</body>

</html>
