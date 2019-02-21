//.add-product .typeahead

    $(document).on("click",".add-to-compare input",function(value,index){
    var categoryID = $('#category').val();
    var searchIDs = $(".add-to-compare input:checkbox:checked").map(function(){
        return $(this).val();
    }).get();
    var citiList = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('products'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: "/products/get-list?name=%QUERY"+"&id="+categoryID+"&preiwIDs="+searchIDs,
            type:'GET',
            filter: function(x) {
                return $.map(x, function (data) {
                    return {
                        name: data.product_name,
                        id: data.id
                    };
                });
            },
            wildcard: "%QUERY"
        }
    });

    citiList.initialize();
    $('.add-product .typeahead').typeahead(null, {
        display: 'name',
        displayKey:'id',
        source: citiList.ttAdapter(),
        templates: {
            empty: [
                '<div class="empty-message">',
                'Unable to find any product that match the current query',
                '</div>'
            ].join('\n'),

            suggestion: Handlebars.compile('<div> <strong>{{name}}</strong></div>')
        }
    }).on('typeahead:selected', function (obj, datum) {
        var url = window.location.hostname;
        var POData = new Array();
        POData = $.parseJSON(JSON.stringify(datum));
        var ids = readCookie('products');
        var count = readCookie('count');
        var pids = ids.split(',');
        pids.push(POData['id']);
        ids = pids.join(',');
        count = pids.length;
        document.cookie = "products ="+ids+";domain="+url+";path=/";
        document.cookie = "count ="+count+";domain="+url+";path=/";
        var removeBtn = POData.id;
        $("#"+removeBtn).prop('checked', true);
        searchIDs.push(POData.id);
        if(count < 2) {
            $("#compare-btn").hide();
        } else{
            $("#compare-btn").show();
        }
        if(count >= 3){
            $(".add-to-compare input:checkbox:checked").show();
            $(".add-to-compare input:checkbox:checked + label").css("visibility","visible");
            $(".add-to-compare input:checkbox:not(:checked)").hide();
            $(".add-to-compare input:checkbox:not(:checked) + label").css("visibility","hidden");


        }else{
            $(".add-to-compare input:checkbox:not(:checked)").show();
            $(".add-to-compare input:checkbox:not(:checked) + label").css("visibility","visible");

        }
        $.ajax({
            url: "/products/detail",
            async:false,
            data:{'id':searchIDs,'count':count},
            type: 'GET',
            success: function(data, textStatus, xhr) {
                if(xhr.status==200){
                    $("#compareData").html(data);
                }else{
                    //alert(xhr.responseText);
                }
            }
        });
    })
        .on('typeahead:open', function (obj, datum) {

        });
});


$(document).on("click",".add-to-compare input",function(value,index){


});


