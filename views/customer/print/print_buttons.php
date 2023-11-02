<div class="row" style="top: 300px; float: left; right:12%; width: 300px; position: absolute;">
    <label>After click "Window Print" button, Please check Paper size in More settings</label>

    <br /><br />
    <button class="btn btn-success" onclick="printDiv('card')">Window Print</button>
    <hr>
    <div style="margin-left: 20px; display: none">
        <label class="checkbox">
            <input type="checkbox" id="useDefaultPrinter" checked/> <strong>Print to Default printer</strong> or...
        </label>
    </div>

    <div id="loadPrinters">
        Click to load and select one of the installed printers!
        <br /><br />
        <input type="button" onclick="javascript:jsWebClientPrint.getPrinters();" value="Load installed Printers" class="btn btn-primary"/>

    </div>

    <div id="installedPrinters" style="visibility:hidden">
        <label for="installedPrinterName">Select an installed Printer:</label>
        <select name="installedPrinterName" id="installedPrinterName"></select>
    </div>

    <input id="printBtn" type="button" class="btn btn-success" value="Direct Print" />
</div>

<script type="text/javascript">

    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
    }

    var wcppGetPrintersTimeout_ms = 10000; //10 sec
    var wcppGetPrintersTimeoutStep_ms = 500; //0.5 sec

    function wcpGetPrintersOnSuccess() {
        // Display client installed printers
        if (arguments[0].length > 0) {
            var p = arguments[0].split("|");
            var options = '';
            for (var i = 0; i < p.length; i++) {
                options += '<option>' + p[i] + '</option>';
            }
            $('#installedPrinters').css('visibility', 'visible');
            $('#installedPrinterName').html(options);
            $('#installedPrinterName').focus();
            $('#loadPrinters').hide();
        } else {
            alert("No printers are installed in your system.");
        }
    }
    function wcpGetPrintersOnFailure() {
        // Do something if printers cannot be got from the client
        alert("No printers are installed in your system.");
    }
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js" type="text/javascript"></script>

<script src="<?=url("");?>assets/js/html2canvas.js"></script>