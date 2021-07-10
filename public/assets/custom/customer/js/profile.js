$(document).ready(function(){
    var crops = $('#crops-select').html();
    var pesticides = $('#pesticides_selection').html();

    $('.add-crop-sowed').on('click', function(){
        var str = '<br><div class="row">'+
            '<div class="col-md-2">'+
            '<select class="form-control" name="crops[]">';
            str += crops;
            str +='</select>'+
            '</div>'+
            '<div class="col-md-4">'+
                '<div class="row">'+
                    '<div class="col-md-3">'+
                        '<label class="control-label">Sowing:</label>'+
                    '</div>'+
                    '<div class="col-md-6">'+
                        '<input type="date" name="sowed_date[]" style="margin-top: 4px;" placeholder="Sowing date">'+
                        '</div>'+
                    '</div>'+
                '</div>'+
                '<div class="col-md-4">'+
                    '<div class="row">'+
                        '<div class="col-md-3">'+
                            '<label class="control-label">Pattern:</label>'+
                        '</div>'+
                        '<div class="col-md-8">'+
                            '<select class="form-control" name="cropping_pattern[]">'+
                                '<option value="">Select pattern</option>'+
                                '<option value="Intercropping">Intercropping</option>'+
                                '<option value="Monocropping">Monocropping</option>'+
                            '</select>'+
                        '</div>'+
                    '</div>'+
                '</div>'+
            '</div>';

        $(this).parent().parent().parent().append(str);
    });


    $('.crops_sowed_selection').on('click', function(){
        var cropDate = $(this).find(':selected').data('crop_date');
        var cropSowedId = $(this).find(':selected').data('crop_sowed_id');
       var str = '<div class="col-md-12 border border-dark">'+
           '<div class="portlet light " id="blockui_sample_1_portlet_body">'+
               '<div class="portlet-title">'+
                   '<div class="caption">'+
                       '<i class="icon-crop font-green-sharp"></i>'+
                       '<span class="caption-subject font-green-sharp sbold">';
                    str += cropDate;
               str +='</span>'+
                   '</div>'+
               '</div>'+
               '<div class="portlet-body">'+
                   '<div class="form-body" data-spraying_done="'+1+'">'+
                       '<div class="form-group">'+
                               '<label class="col-md-3 control-label">Spraying 1:'+
                               '</label>'+
                               '<div class="col-md-3">'+
                                '<select class="form-control" name="pesticides['+cropSowedId+'][]">';
                                str += pesticides;
                                str +='</select>'+
                               '</div>'+
                               '<div class="col-md-3">'+
                                   '<input type="date" class="form-control" name="spraying_date['+cropSowedId+'][]" >'+
                                   '</div>'+
                                   '<div class="col-md-1">'+
                                       '<a href="javascript:;" class="btn btn-sm green add-spray-row"> Add'+
                                           '<i class="fa fa-plus"></i>'+
                                       '</a>'+
                                   '</div>'+
                               '</div>'+
                       '</div>'+
                   '</div>'+
               '</div>'+
           '</div>';
        $('#spraying-form').append(str);

        $('.add-spray-row').on('click', function(){
            var sprayNum = $(this).parent().parent().parent().data('spraying_done') + 1;
            var str = '<div class="form-body">'+
                '<div class="form-group">'+
                '<label class="col-md-3 control-label">Spraying ';
            str += sprayNum+ ':';
            str += '</label>'+
                '<div class="col-md-3">'+
                '<select class="form-control" name="pesticides['+cropSowedId+'][]">';
            str += pesticides;
            str += '</select>'+
                '</div>'+
                '<div class="col-md-3">'+
                '<input type="date" class="form-control" name="spraying_date['+cropSowedId+'][]" >'+
                '</div>'+
                '</div>'+
                '</div>';
            $(this).parent().parent().parent().append(str);
            $(this).parent().parent().parent().data('spraying_done',sprayNum);
        });
    });

    $('.add-spray-row').on('click', function(){
        var cropSowedId = $(this).data('crop_spray_id');
        var sprayNum = $(this).parent().parent().parent().data('spraying_done') + 1;
        var str = '<div class="form-body">'+
            '<div class="form-group">'+
            '<label class="col-md-3 control-label">Spraying ';
            str += sprayNum+ ':';
        str += '</label>'+
            '<div class="col-md-3">'+
            '<select class="form-control" name="pesticides['+cropSowedId+'][]">';
        str += pesticides;
        str += '</select>'+
            '</div>'+
            '<div class="col-md-3">'+
            '<input type="date" class="form-control" name="spraying_date['+cropSowedId+'][]" >'+
            '</div>'+
            '</div>'+
            '</div>';
        $(this).parent().parent().parent().append(str);
        $(this).parent().parent().parent().data('spraying_done',sprayNum);
    });
});