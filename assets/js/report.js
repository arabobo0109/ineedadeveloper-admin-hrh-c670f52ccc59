var filters_array = JSON.parse(JSON.stringify(filters));
var current_filter_rules_array = [];
var current_filter_total_array = [];
if(current_filter_rules != "")
    current_filter_rules_array = JSON.parse(current_filter_rules);
if(current_filter_total != "")
    current_filter_total_array = JSON.parse(current_filter_total);

var operator = {
    "string": ["=", "like","not like","!="],
    "integer": ["=", ">", "<","!="],
    "double": ["=", ">", "<","!="],
    "datetime": ["=", "<=", ">=", "<", ">","Today","Yesterday","This month","Previous month","Between"],
    "boolean": ["1", "0"],
    "date": ["=", "<=", ">=","<", ">","Today","Yesterday","This month","Previous month","Between"]
};
var condition = {
    "period": ["This month", "All"]
};

let filter_area=document.getElementsByClassName('filter-area')[0];

init_rows();

$('#add_row').on('click', function() {
    var selected_column = $("#column_select").val();
    var selected_column_array = get_row_from_array(selected_column, filters_array);

    mk_html(selected_column_array['id'], selected_column_array['label'], selected_column_array['type'], "", "");
});

$('#add_total_row').on('click', function() {
    var selected_column = $("#total_column_select").val();
    var selected_column_array = get_row_from_array(selected_column, filters_array);

    mk_total_html(selected_column_array['id'], selected_column_array['label'], selected_column_array['type']);
});

function init_rows(){
    console.log(current_filter_rules_array);
    for(i = 0 ; i < current_filter_rules_array.length ; i++){
        mk_html(current_filter_rules_array[i]['column_id'], current_filter_rules_array[i]['column_name'], current_filter_rules_array[i]['column_type'], current_filter_rules_array[i]['operater'], current_filter_rules_array[i]['row_val']);
    }

    for(i = 0 ; i < current_filter_total_array.length ; i++){
        var column_meta = JSON.parse(current_filter_total_array[i]['column_meta']);
        mk_total_html(column_meta['column_id'], column_meta['column_name'], column_meta['column_type'], current_filter_total_array[i]);
    }
}

function mk_html(column_id, column_name, column_type, column_operator, row_val){
    var operator_arr = operator[column_type];
    var column_operator_str = "";
    let rand_id = Math.floor(Math.random() * 10000);
    let selectList=document.createElement('select');
    selectList.id=rand_id;
    selectList.className="form-control float-left";
    selectList.style.width='142px';
    selectList.style.marginRight='10px';
    selectList.name="operater[]";
    selectList.setAttribute('onchange','onchange_operator(this)');

    for(j = 0 ; j < operator_arr.length ; j++ ){
        column_operator_str += '<option value="'+ encodeURIComponent(operator_arr[j]) + '"';
        if(operator_arr[j] == column_operator)
            column_operator_str += "selected";

        column_operator_str += '>'+ operator_arr[j] +'</option>';
    }
    selectList.innerHTML=column_operator_str;

    var required_val = "required";
    var datepicker = "";
    let to_val='';
    if(isDateColumn(column_type)){
        required_val = "";
        datepicker = "datepicker";
        let val_list=row_val.split(',');
        row_val=val_list[0];
        to_val=val_list[1]?val_list[1]:'';
    }

    let each_row=document.createElement('div');
    each_row.className="each-row";
    each_row.innerHTML+=
        '            <input type="hidden" name="column_id[]" value="'+ column_id +'">\n' +
        '            <input type="hidden" name="column_name[]" value="'+ column_name +'">\n' +
        '            <input type="hidden" name="column_type[]" value="'+ column_type +'">\n' +
        '            <label class="float-left">' + column_name + ' : </label>\n';
    each_row.appendChild(selectList);

    let input_html='<input type="text" class="form-control float-left '+datepicker+'" ' + required_val + ' style="width: 100px;" name="row_val[]" value="';
    each_row.innerHTML+=input_html+row_val +'">';

    if(isDateColumn(column_type)){
        let input_div=document.createElement('div');
        input_div.className='toDate';
        input_div.innerHTML='<span class="float-left" style="padding: 5px">to</span>'+input_html+to_val +'">';
        each_row.appendChild(input_div);
    }

    each_row.innerHTML+='<input type="button" class="btn btn-success float-right" value="Remove" onclick="remove_row(this)">\n';
    filter_area.appendChild(each_row);

    onchange_operator(document.getElementById(rand_id));

    if(isDateColumn(column_type)){
        $(".datepicker").datepicker({
            todayHighlight: !0,
            format: 'yyyy-mm-dd'
        });
    }
}

function onchange_operator(obj){
    let rowVal=obj.parentNode.querySelector('input[name="row_val[]"]');
    if (isSpecificDate(obj.value))
        rowVal.style.display='none';
    else{
        rowVal.style.display='block';
    }
    let toDate=obj.parentNode.querySelector('div.toDate');
    if (toDate){
        if (obj.value=='Between'){
            toDate.style.display='block';
        }
        else
            toDate.style.display='none';
    }
}

function mk_total_html(column_id, column_name, column_type, row_val = []){
    var row_title = ""
    if(row_val.length != 0)
        row_title = row_val['title'];

    var condition_period = "";
    for(j = 0 ; j < condition['period'].length ; j++ ){
        condition_period += '<option value="'+ condition['period'][j] + '"';
        if(condition['period'][j] == row_val['period'])
            condition_period += " selected";

        condition_period += '>'+ condition['period'][j] +'</option>';
    }

    var html_cont = '<div class="each-row modal-body">\n' +
        '            <input type="hidden" name="column_id[]" value="'+ column_id +'">\n' +
        '            <input type="hidden" name="column_name[]" value="'+ column_name +'">\n' +
        '            <input type="hidden" name="column_type[]" value="'+ column_type +'">\n' +
        '            <label>- ' + column_name + ' </label><br/>\n' +
        '<div class="row" style="margin-top: 10px;"><div class="col-md-2">' +
        '            <label>Title : </label>\n' +
        '</div><div class="col-md-8">' +
        '            <input type="text" class="form-control" required name="title[]" value="'+ row_title +'">' +
        '</div></div><div class="row" style="margin-top: 10px;"><div class="col-md-2"><label> Period : </label>\n' +
        '</div><div class="col-md-4">' +
        '            <select class="form-control" name="period[]">\n' + condition_period +
        '            </select>\n' +
        '</div></div>' +
        '            <input type="button" class="btn btn-success float-right" value="Remove" onclick="remove_row(this)">\n' +
        '</div>';


    $(".total-area").append(html_cont);
}

function remove_row(row){
    $(row.parentNode).remove();
}
function get_row_from_array(tstr, tarray){
    var i = 0;
    for(i = 0 ; i < tarray.length ; i++){
        if(tarray[i]['id'] == tstr){
            return tarray[i];
        }
    }
    return 0;
}

function isSpecificDate(column_operator){
    column_operator=decodeURIComponent(column_operator);
    if((column_operator == "Today") || (column_operator == "Yesterday") || (column_operator == "This month") || (column_operator == "Previous month"))
       return  true;
    return  false;
}

function isDateColumn(column_type){
    if((column_type == "date") || (column_type == "datetime"))
        return true;
    return false;
}
