<?php include "includes/head.php"; ?>
<body>
    <!-- header start -->
    {{ view("includes/header", $data); }}
    <!-- sidebar -->
    {{ view("includes/sidebar", $data); }}
    <div class="content">
        <div class="page-title">
            <h3>Checkout History</h3>
            <p>All checkout history lists.</p>
        </div>
    </div>

    <!-- footer -->
    {{ view("includes/footer"); }}
    @if ( count($checkout_history) > 0 )
    <script>
        $(document).ready(function() {
            $('#data-table').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'excelHtml5',
                    'csvHtml5'
                ]
            });
            
            
        });
    </script>
    @endif
</body>

</html>
