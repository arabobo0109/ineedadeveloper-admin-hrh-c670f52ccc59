<?php
use Simcify\Models\CompanyModel;
?>
<?= view("includes/head"); ?>
<style type="text/css"> /* Styles of the 404 page of my website. */
    body {
        background: #C6ECFF;
        color: #d3d7de;
        font-family: "Courier new";
        font-size: 18px;
        line-height: 1.5em;
        cursor: default;
    }

    a {
        background: #3DA4FF;
        text-decoration: none;
        color: #fff;
        border: 1px solid #3DA4FF;
        padding: 6px 20px;
        border-radius: 16px;
        margin-top: 36px;
    }

    .code-area {
        position: absolute;
        min-width: 320px;
        top: 50%;
        left: 50%;
        -webkit-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
    }

    .code-area > span {
        display: block;
    }
    p{
        font-size: 15px;
        line-height: 20px;
        color: #0082FF;
        opacity: 0.8;
    }
    img{
        width: 250px;
    }

    @media screen and (max-width: 320px) {
        .code-area {
            font-size: 5vw;
            min-width: auto;
            width: 95%;
            margin: auto;
            padding: 5px;
            padding-left: 10px;
            line-height: 6.5vw;
        }
    } </style>
<body>
<div class="code-area">
    <img src="/uploads/app/logo.png" class="img-responsive">
    <span style="color: red;font-style: italic;font-size: 22px;opacity: 0.6;font-weight: bold;margin-top: 40px;">Sorry, we're down for maintenance. </span>
    <hr>
    <p>We'll be back shortly.</p>
    <p id="demo" style="font-size:30px;font-weight: bold"></p>
    <hr>
    <p>If you have a questions, Get in touch with the site owner</p>
    <p style="color: #030303;opacity: 1;font-size: 20px;"><?=env("DrawerReportMail1")?></p><br>
</div>

<?php
$company=CompanyModel::GetCompany();
$diff=strtotime($company->maintenance_time)-strtotime(date("Y-m-d H:i:s"));
?>

<script>
    let distance=<?=$diff?>;
    // Update the count down every 1 second
    var countdownfunction = setInterval(function() {
        distance --;
        var days = Math.floor(distance / (60 * 60 * 24));
        var hours = Math.floor((distance % (60 * 60 * 24)) / (60 * 60));
        var minutes = Math.floor((distance % (60 * 60)) / (60));
        var seconds = Math.floor((distance % (60)));
        document.getElementById("demo").innerHTML = days + "d " + hours + "h "
            + minutes + "m " + seconds + "s ";
        if (distance < 0) {
            clearInterval(countdownfunction);
            document.getElementById("demo").innerHTML = "Done.  Please reload the website.";
        }
    }, 1000);
</script>

<?php die; ?>
</body>
</html>