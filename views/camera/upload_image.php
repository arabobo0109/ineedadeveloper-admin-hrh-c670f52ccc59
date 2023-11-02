{{ view("includes/head", $data); }}
<body>
@if (isset($user))
{{ view("includes/header", $data); }}
{{ view("includes/sidebar", $data); }}
@endif
<div class="content">
    @if (isset($user))
    {{ view("customer/profile_header", $data); }}
    @endif
    <script src="/webcam/assets/js/jquery-1.12.4.min.js"></script>
    <div class="table-responsive p-b-3em" style="margin-right: 250px">
        <?php include "profile_picture.php"?>
    </div>
</div>
{{ view("includes/footer_camera",$data); }}
</body>
</html>
