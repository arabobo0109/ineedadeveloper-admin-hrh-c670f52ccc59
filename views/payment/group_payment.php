{{ view("includes/head", $data); }}
<body>

{{ view("includes/header", $data); }}
{{ view("includes/sidebar", $data); }}

<div class="content">
    <div class="page-title">
        <h3 style="font-weight: bold">Amount: <span style="color: red">${{$group_total}}</span>
        </h3>
    </div>
    <?php include "credit_card_form.php" ?>
</div>

{{ view("includes/footer"); }}

