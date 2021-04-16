/**
 * Created by Ameya Joshi on 9/4/18.
 */

$(document).ready(function(){
    var baseUrl = $('#base_url').val();
    var citiList = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('office_name'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: baseUrl+"/get-pincode?_token="+$("input[name='_token']").val()+"&pincode=%QUERY",
            filter: function(x) {
                return $.map(x, function (data) {
                    return {
                        pincode: data.pincode,
                        at_post: data.post_offices,
                        state: data.state
                    };
                });
            },
            wildcard: "%QUERY"
        }
    });
    citiList.initialize();
    $('#pincode').typeahead(null, {
        display: 'pincode',
        source: citiList.ttAdapter(),
        templates: {
            suggestion: Handlebars.compile('<div><input type="text" class="form-control"  style=" border: solid 1px deepskyblue ;padding-top: 5px ; color: black;" value="{{pincode}}"></div>')
        }
    }).on('typeahead:selected', function (obj, datum) {
        var POData = new Array();
        POData = $.parseJSON(JSON.stringify(datum));
        $('#pincode').val(POData["pincode"]);
        $('#atPost').html(POData["at_post"]);
        $('#stateName').val(POData["state"]);
        $('#atPost').trigger('change');
    }).on('typeahead:open', function (obj, datum) {
        $('#atPost').html('');
        $('#taluka').val('');
        $('#stateName').val('');
        $('#district').val('');
    });

    $('#atPost').on('change', function(){
        var postId = $(this).val();
        var pincode = $("#pincode").val();
        $.ajax({
            url: baseUrl+"/get-post-office-info/"+postId+"?pincode="+pincode+"&_token="+$("input[name='_token']").val(),
            method: 'GET',
            async: false,
            success: function(data,textStatus,xhr){
                $('#taluka').val(data.taluka);
                $('#district').val(data.district);
            },
            error: function(data){

            }
        });
    });
});