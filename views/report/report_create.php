{{ view("includes/head", $data); }}
<body>
{{ view("includes/header", $data); }}
{{ view("includes/sidebar", $data); }}

<div class="content">
    <div class="page-title">
        <div class="pull-right page-actions lower">
            <button class="btn btn-primary" data-toggle="modal" data-target="#create_employer" data-backdrop="static" data-keyboard="false"><i class="ion-plus-round"></i> New Report</button>
        </div>
        <h3>Report</h3>
    </div>
    <div class="row" style="margin-top:20px;">
        <div class="light-card col-md-12 table-responsive" style="margin-right: 10px;">
            <table class="table display companies-list" id="data-table">
                <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th>Type</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Created by</th>
                    <th>Created at</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @if ( count($reports) > 0 )
                @foreach ( $reports as $index => $report )
                <tr class="c-p" >
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td onclick="redirect_report('{{$report["report"]->id}}')">{{$report["template"]->name}}</td>
                    <td onclick="redirect_report('{{$report["report"]->id}}')"><strong>{{$report["report"]->name}}</strong></td>
                    <td onclick="redirect_report('{{$report["report"]->id}}')" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 450px;">
                    {{$report["report"]->description}}</td>
                    <td onclick="redirect_report('{{$report["report"]->id}}')"><strong>{{$report["report"]->created_by}}</strong></td>
                    <td onclick="redirect_report('{{$report["report"]->id}}')" style="padding-right: 50px;">{{date('Y-m-d',strtotime($report["report"]->created_at))}}</td>
                    <td class="text-center">
                        <div class="dropdown">
                            <span class="company-action dropdown-toggle" data-toggle="dropdown"><i class="ion-ios-more"></i></span>
                            <ul class="dropdown-menu" role="menu">
                                <li role="presentation">
                                    <a class="fetch-display-click" data="reportid:{{ $report['report']->id }}|csrf-token:{{ csrf_token() }}" url="<?=url("Report@updateview");?>" holder=".update-holder" modal="#update" href="">Edit</a>
                                    <a class="send-to-server-click"  data="reportid:{{ $report['report']->id }}|csrf-token:{{ csrf_token() }}" url="<?=url("Report@delete");?>" warning-title="Are you sure?" warning-message="This Report will be deleted." warning-button="Continue" loader="true" href="">Delete</a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>

                @endforeach
                @else
                <tr>
                    <td colspan="9" class="text-center">It's empty here</td>
                </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="create_employer" role="dialog">
    <div class="close-modal" data-dismiss="modal">&times;</div>
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Create Report</h4>
            </div>
            <form class="simcy-form" id="create_report_form" action="<?= url("Report@createReport"); ?>"
                  data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <input type="hidden" name="csrf-token" value="{{ csrf_token() }}">
                            <div class="col-md-12">
                                <label>Template</label>
                                <select class="form-control valid col-md-6" name="template_id">
                                    @foreach ( $reports_template as $each_template )
                                    <option value="{{$each_template->id}}">{{$each_template->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Name</label>
                                <input type="text" class="form-control valid col-md-6" name="name" placeholder="Name"
                                       value="" required="" aria-invalid="false">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">

                            <div class="col-md-12">
                                <label>Description</label>
                                <textarea class="form-control valid" name="description" required="" rows="9"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Create Report</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Update Report Modal -->
<div class="modal fade" id="update" role="dialog">
    <div class="close-modal" data-dismiss="modal">&times;</div>
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Report </h4>
            </div>
            <form class="update-holder simcy-form" id="update-report-form" action="<?=url("Report@update");?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                <div class="loader-box"><div class="circle-loader"></div></div>
            </form>
        </div>
    </div>
</div>

{{ view("includes/footer"); }}

<script>
    function redirect_report(r_id){
        window.location.href="/report/browse/" + r_id;
    }

    $(document).ready(function() {
        $('#data-table').DataTable();
    });
</script>
</body>
</html>

