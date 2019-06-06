/**
 * Created by Ameya Joshi on 9/4/18.
 */

$(document).ready(function(){
    var citiList1 = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('office_name'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: "http://agrcrm_api.com/get-pincode?_token="+$("input[name='_token']").val()+"&pincode=%QUERY",
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
    citiList1.initialize();
    $('.edit-pincode').typeahead(null, {
        display: 'pincode',
        source: citiList1.ttAdapter(),
        templates: {
            suggestion: Handlebars.compile('<div><input type="text" class="form-control pincode-class"  style=" border: solid 1px deepskyblue ;padding-top: 5px ; color: black;" value="{{pincode}}"></div>')
        }
    }).on('typeahead:selected', function (obj, datum) {
        var POData = new Array();
        POData = $.parseJSON(JSON.stringify(datum));
        $('.edit-pincode').val(POData["pincode"]);
        $('.edit-atPost').html(POData["at_post"]);
        $('.edit-stateName').val(POData["state"]);
        $('.edit-atPost').trigger('change');
    }).on('typeahead:open', function (obj, datum) {
        $('.edit-atPost').html('');
        $('.edit-taluka').val('');
        $('.edit-stateName').val('');
        $('.edit-district').val('');
    });

    $('.edit-atPost').on('change', function(){
        var postId = $(this).val();
        var pincode = $('.pincode-class').val();
        $.ajax({
            url:'http://agrcrm_api.com/get-post-office-info/'+postId+"?pincode="+pincode+"&_token="+$("input[name='_token']").val(),
            method: 'GET',
            async: false,
            success: function(data,textStatus,xhr){
                $('.edit-taluka').val(data.taluka);
                $('.edit-district').val(data.district);
            },
            error: function(data){

            }
        });
    });
});