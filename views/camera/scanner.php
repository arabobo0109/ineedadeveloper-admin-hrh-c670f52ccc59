@if ((isset($user) && $user->role!='user') || empty($passports->path0) || $student->status=='created')
<div class="buttonarea" style="margin-left: 20px;">
    <label class="btn upload"> <i class="fa fa-upload"></i> &nbsp; Upload
        <input type="file" class="sr-only" id="input_front" name="image" accept="image/*">
    </label>

    <button class="btn camera" data-backdrop="static" data-toggle="modal"
            data-target="#cameraModal" onclick="set_front_back('front')"><i class="fa fa-camera"></i> &nbsp; Camera
    </button>

    @if (isset($user) && $user->role!='user')
        <button class="btn btn-primary" id="btn-scan" style="margin-left: 8px"> <i class="fa fa-print"></i> &nbsp;  Scanner </button>
        <p class="text-danger mt-1" id="download-app" style="display:none;padding: 20px;">No Scan app application found in your machine. Please download, install and open first then refresh the browser. <a href="Scan_App_SetUp.msi" download>Download app</a></p>
    @endif
</div>
@endif

<div class="container">
    <div class="row" id="th_container">
        <div class="col-md-12 mb-1 text-secondary" id="th_container_empty" style="display: none">No scanned images</div>
    </div>
</div>

@if (isset($user) && $user->role!='user')
<script type="text/javascript">
    window.URL = window.URL || window.webkitURL;

    document.addEventListener('DOMContentLoaded', function(){
            let th_container = document.getElementById("th_container"),
            btn_scan = document.getElementById("btn-scan"),
            download_app = document.getElementById("download-app"),
            btn_upload = document.getElementById("btn-upload");

        var i = 0;
        var wsImpl = window.WebSocket || window.MozWebSocket;
        window.ws = new wsImpl('ws://localhost:8181/');
        ws.onmessage = function(e){
            if (e.data instanceof Blob){
                i++;
                document.getElementById("th_container_empty").style.display = 'none';
                document.getElementById("btn-upload").style.display = '';
                var th_img_id = "upl_image" + i;
                createThumbnail(th_container, th_img_id, "Scan "+i);
                appendImage(e.data, th_img_id);
            }
        };
        ws.onopen = function(){
            btn_scan.removeAttribute('disabled');
            download_app.style.display = 'none';
        };
        ws.onerror = function(e){
            btn_scan.setAttribute('disabled', '');
            download_app.style.display = '';
        }

        btn_scan.addEventListener('click', function(){
            ws.send("1100");
        }, false);

        btn_upload.addEventListener('click', function(){
            btn_upload.style.display = 'none';
            Array.from(document.querySelectorAll("img.th_img")).forEach(e => { uploadImage(e); });
        }, false);
    });

    document.addEventListener('click',function(e){
        if (e.target && e.target.className == 'th_rotate') {
            var c = document.createElement('canvas');
            var ctx = c.getContext('2d');
            var src_img = e.target.previousElementSibling;
            var img = new Image();
            img.src = src_img.src;
            img.onload = function() {
                imgWidth = img.width;
                imgHeight = img.height;
                c.width = imgHeight;
                c.height = imgWidth;
                ctx.translate(c.width/2, c.height/2);
                ctx.rotate(90*Math.PI/180);
                ctx.translate(-c.height/2, -c.width/2);
                ctx.drawImage(img, 0, 0);
                c.toBlob(function(blob){
                    window.URL.revokeObjectURL(src_img.src);
                    src_img.src = window.URL.createObjectURL(blob);
                }, "image/jpeg", 0.9);
            }
        }
    });

    function createThumbnail(container, th_img_id, caption){
        var col = document.createElement("div");
        col.className = "col-2";
        container.appendChild(col);

        var th = document.createElement("div");
        th.className = "card mb-4 shadow-sm text-center";
        col.appendChild(th);

        var th_img = document.createElement("img");
        th_img.style.width='150px';
        th_img.className = "card-img-top th_img";
        th_img.id = th_img_id;
        th_img.width = 150;
        th.appendChild(th_img);

        var th_rotate = document.createElement("div");
        th_rotate.className = "th_rotate";
        th.appendChild(th_rotate);

        var info = document.createElement("div");
        info.className = "card-body";
        info.innerHTML = caption;
        th.appendChild(info);

        var prg = document.createElement("div");
        prg.className = "progress th_progress";
        prg.innerHTML = "<div class=\"progress-bar bg-warning\" role=\"progressbar\" aria-valuemin=\"0\" aria-valuemax=\"100\"></div>";
        th.appendChild(prg);

        var th_remove = document.createElement("button");
        th_remove.className = "close text-danger th_remove";
        th_remove.setAttribute("type", "button");
        th_remove.setAttribute("aria-label", "Remove");
        th_remove.innerHTML = "<span aria-hidden=\"true\">&times;</span>";
        th.appendChild(th_remove);

        th_remove.addEventListener("click", function(e){
            e.target.parentNode.parentNode.parentNode.remove();
        }, false);
    }

    function appendImage(file, img_id, maxSizeWH = 3000){
        function calculateAspectRatioFit(srcWidth, srcHeight, maxWidth, maxHeight) {
            var ratio = [maxWidth / srcWidth, maxHeight / srcHeight ];
            ratio = Math.min(ratio[0], ratio[1]);
            return { width:srcWidth*ratio, height:srcHeight*ratio };
        }
        var img = new Image();
        img.src = window.URL.createObjectURL(file);
        img.onload = function(){
            var c = document.createElement('canvas');
            if ((this.naturalWidth < maxSizeWH) && (this.naturalHeight < maxSizeWH)){
                var new_dimensions = {width:this.naturalWidth, height:this.naturalHeight};
            }else{
                var new_dimensions = calculateAspectRatioFit(this.naturalWidth, this.naturalHeight, maxSizeWH, maxSizeWH);
            }
            c.width = new_dimensions.width;
            c.height = new_dimensions.height;
            c.getContext('2d').drawImage(this, 0, 0, this.naturalWidth, this.naturalHeight, 0, 0, new_dimensions.width, new_dimensions.height);
            c.toBlob(function(blob){
                document.getElementById(img_id).src = window.URL.createObjectURL(blob);
            }, "image/jpeg", 0.9);
            c.remove();
        }
        img.remove();
    }

    function uploadImage(src_img){
        fetch(src_img.src).then(i=>i.blob()).then(function(imageBlob){
            var fd = new FormData();
            fd.append("type", "image_upload");
            fd.append("blob", imageBlob);
            var xhr = new XMLHttpRequest();
            xhr.addEventListener("loadstart", function(e){
                var siblings = [].slice.call(src_img.parentNode.children) // convert to array
                    .filter(function(v) { return v !== src_img }) // remove element itself
                    .forEach(function(el){
                        if (el.className == "th_rotate" || el.className == "close th_remove") el.remove();
                        if (el.className == "progress th_progress") el.style.display = "flex";
                    });
            }, false);
            xhr.upload.addEventListener("progress", function(e) {
                if (e.lengthComputable) {
                    var percentage = Math.round((e.loaded*100)/e.total);
                    src_img.nextSibling.nextSibling.firstChild.style.width = percentage+'%';
                    src_img.nextSibling.nextSibling.firstChild.setAttribute("aria-valuenow", percentage);
                }
            }, false);
            xhr.onreadystatechange = function(){
                if (xhr.readyState == 4 && xhr.status == 200){
                    var r = JSON.parse(xhr.responseText);
                    src_img.classList.remove("th_img")
                    var caption = document.getElementById(src_img.id).nextSibling;
                    caption.innerHTML = r.newname;
                    src_img.removeAttribute("id");
                }
            }
            xhr.open("POST", "upload.php");
            xhr.overrideMimeType('text/plain; charset=x-user-defined-binary');
            xhr.send(fd);
        });
    }
</script>

<style>
    .ex_caption {
        font-weight: 300;
    }
    .th_rotate {
        position: absolute;
        top: 40%;
        left: 50%;
        margin: -16px 0 0 -16px;
        width: 32px;
        height: 32px;
        cursor: pointer;
        background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAAABGdBTUEAALGPC/xhBQAAACBjSFJN AAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAC2VBMVEX////r7+oeHx4dHR0i IiIcHRwlJiUmJiYcHBwbHBsoKSgbGxsZGRkgICAZGhkYGBgfIB8YGRgXGBcfHx8aGhoXFxceHh4W FxYWFhYVFhUVFRUUFRQUFBQTExMTFBMSExISEhIREREQERAREhEQEBAgICAeHx4wMDAqKiogICBA QEAuLi4jJCMeHh5ERUQnKCcfHx9DREMrLCshISEdHh0gISAjIyMlJSUkJSQeHh4dHh0iIiIqKiog ICAfIB8oKSgiIiIgISCipKEfIB/U2NPr7+orKyvj5+Lr7+owMTDO0s3r7+rr7+o/QD/r7+rr7+oe Hh4tLi3o7Ofr7+o7PDvFycTr7+o8PTzZ3djr7+o6Ozrc4Nvr7+pAQT+EhoPr7+o5Ojno7Oc1NjWl p6RBQkHr7+rQ1M/R1dDr7+odHh2ZnJg2NzZXWFZAQUAWFxYaGhoZGhl1d3QrLSsXGBcnJydjZWO4 u7ciIyIYGBifoZ4YGRgTFBMWFhYjJCPj5+LFyMQaGhoUFRQTFBMXFxerrqrr7+qWmZYYGRgXFxcX FxcXGBfr7+rn6+br7+rr7+rr7+rJzcjr7+rr7+rr7+rr7+rr7+rr7+rr7+rr7+rr7+o4OThCQ0I8 PTwxMjFERUQ/QD82NzYlJiUtLS07PDtDRENBQUE5OjkvLy8oKSg0NTQ3ODdAQUBBQkEzMzMxMTE9 Pj0+Pz43NzcsLCwrKytFRUUrLCs/Pz9fYF+Pko/DxsLr7+pFR0V+gH4zNDNfYV+jpaI4ODhmZ2VN Tk06Ozo/QT9naWeOkY41NjW0t7MsLSwwMTAtLi0uLy45OjgyMzIbHBsvMC8qKyopKSkuLi4nJyci IyJhY2FJSkkwMC8gISAdHh0mJyYjIyMcHRwbGxt8fnuDhYIpKikkJSQiIiIjJCNqbGqgo6CHiode YF5RUlC8v7uFh4VKTEpERUN5fHm1uLTi5eHd4dz///81j6aBAAAAmnRSTlMAAAAAAAAAAAAAAAAA AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAigz44Dn+/YQG/tAn/vdyKn7D9robJKjyWlf3pXi+VO9I +c4eufeEOf6cCR7+yif++GaDwgP+4zPc+RL+u/um/YFD5UsDDyn7fhX85OHvD6H1maVFvocGSO1K v9M8GJnhJPznzLrSbJGHqGD6KrfJPNLbk0UMUbL0YwAAAAFiS0dEAIgFHUgAAAAHdElNRQfjBQMG IweiEX1iAAACVElEQVQ4y2NgYGBgRAZMqmrMMMAABSgKWNQ1NJlZIQCrAjatWdo6ungUsOvNnjNX 34CVAwiwm2A4b978BUbGzFgUmJiamS9ctHjJvCVLly23sOTkRFVgZb1i+cpVyxavXgIEa+astbFF NcHOft2s9fPXzIaCNRs2bnJAVuC4ecH61WuQwfwtTkgKnLeuWj8fBWzb7uKKUGDltmD9hg0bluzY uWvX7g0gsH6PuwdSSHruXbx+/fp9Xt4+jFy+QNb6Nfv9/Ll54AoCAg8sXrz4YFAw0DLeECDzUGgY Ex8/P1xB+Nxlhw8fiYgEuVYg6vDho9ExHIJCQkJwBbErly2bFRcP9q5wwqpjifEiQqJAAFeQdHzV qr3JkPASSzmRGskvLgECcAUnjy9YcCoNooAzPYNbVEICRUHm6ePHj5/JgigQ8uCRkIQCmILIs3v3 7j2ZDVEgJSUlDQNwK3Lmnjt3MheiQAYEpPPyz58/XwBXUHhh7tzTRcUIBVIlCy9evFQKV1C29cKF y1fKwQpkgUCu4urJCxdOVsIVBF87febM1qtV1UAF8vLysjW1K4AC1+sQcVF/4/TpkytuNjQyMirI NzXfur319OmtLZEIBa1tW0+e3Lrizt32js6uezdvg3j3XWWQ0kP3g4tbt259ePvR1cdPFq4Asi8+ 7ZFQQE5Rvc/OXASChw8vPQTTz/siFRRR0mT/hBcP4eDlq1QJBSUl1FQ9cdLO129Asm/fvZ88RVpZ CV0BI2OM99RdHz7umjZ9hqSCkgoQYMtZM+PFpeQg0ggFeAEAzmwM75REXZ4AAAAldEVYdGRhdGU6 Y3JlYXRlADIwMTktMDUtMDNUMTM6MzU6MDctMDc6MDASd+GQAAAAJXRFWHRkYXRlOm1vZGlmeQAy MDE5LTA1LTAzVDEzOjM1OjA3LTA3OjAwYypZLAAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VS ZWFkeXHJZTwAAAAASUVORK5CYII=");
    }
    .th_remove {
        position: absolute;
        top: .5rem;
        right: .5rem;
        font-size: 2em;
        opacity: 0.8;
    }
    .th_progress {
        position: absolute;
        top: 4rem;
        right: 1rem;
        left: 1rem;
        height: .4rem;
        display: none;
    }
    .th_progress .progress-bar {
        width: 0%;
    }
</style>

@endif





