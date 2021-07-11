$(document).ready(function () {
    $('.typeahead').on('change',function () {
        var crmCustId = $('#crm_customer_id').val();
        var tagType = $(this).data('tag_type');
        var refDiv = $(this).data('ref_div');
        var tagList = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('office_name'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: {
                url: "/get-tags?tag_name=%QUERY&tag_type="+tagType,
                filter: function(x) {
                    return $.map(x, function (data) {
                        return {
                            id: data.id,
                            name: data.name
                        };
                    });
                },
                wildcard: "%QUERY"
            }
        });
        tagList.initialize();
        $(this).typeahead(null, {
            displayKey: 'name',
            engine: Handlebars,
            source: tagList.ttAdapter(),
            limit: 30,
            templates: {
                empty: [
                    '<div class="empty-message">',
                    'Unable to find any Result that match the current query',
                    '</div>'
                ].join('\n'),
                suggestion: Handlebars.compile('<div style="text-transform: capitalize;"><strong>{{name}}</strong></div>')
            }
        }).on('typeahead:selected', function (obj, datum) {
            POData = $.parseJSON(JSON.stringify(datum));
            POData.name = POData.name.replace(/\&/g,'%26');
            str = '<button id="tag'+POData.id+crmCustId+'" class="lable" style="display: inline;font-size: 90%;margin-left: 2px;margin-top:3px;margin-bottom:3px;padding-bottom: 2px;padding-top: 2px">'+POData.name+'<span style="color: red;" onclick="removeCustTag('+POData.id+','+crmCustId+')"> ×</span></button>&nbsp;&nbsp;&nbsp';
            $('#'+refDiv).append(str);
            if(crmCustId != 'null'){
                $.ajax({
                    url: '/tag/customer-tag',
                    type: 'POST',
                    dataType: 'array',
                    data: {
                        "_token": $('meta[name="csrf-token"]').attr('content'),
                        'crm_cust_id' : crmCustId,
                        'tag_id' : POData.id,
                        'tag_type' : tagType
                    },
                    success: function (responce) {
                    },
                    error: function (responce) {
                    }
                })
            }
        }).on('typeahead:open', function (obj, datum) {

        });
        $(this).keypress(function (e) {
            var key = e.which;
            if(key == '13'){
                var singleQuote = "'";
                var tagName = singleQuote+$('#tag_name').val()+singleQuote;
                var tag = $(this).val().replace(/ /g,"_");
                var tagStr = '<button id="tag'+tag+crmCustId+'" class="lable" style="display: inline;font-size: 90%;margin-left: 2px;margin-top:3px;margin-bottom:3px;padding-bottom: 2px;padding-top: 2px">'+tag+'<span style="color: red;" onclick="removeCustTag('+tagName+','+crmCustId+')"> ×</span></button>&nbsp;&nbsp;&nbsp';
                $('#'+refDiv).append(tagStr);
                $.ajax({
                    url: '/customer/create-assign-tag',
                    type: 'POST',
                    dataType: 'array',
                    data: {
                        "_token": $('meta[name="csrf-token"]').attr('content'),
                        'tag_name' : $(this).val(),
                        'customer_id' : crmCustId,
                        'tag_type' : tagType
                    },
                    success: function (status) {
                    },
                    error: function (status) {
                    }
                })
            }
        });
    });

});

function removeCustTag(tagId,crmCustId){
    event.preventDefault();
    var tag = 'tag'+tagId+crmCustId;
    tag = tag.replace(/ /g,"_");
    $('#'+tag).remove();
    $.ajax({
        url: '/customer/remove-tag/'+tagId+'/'+crmCustId,
        type: 'GET',
        async: true,
        success: function(data,textStatus,xhr){

        },
        error:function(errorData){
        }
    });
}