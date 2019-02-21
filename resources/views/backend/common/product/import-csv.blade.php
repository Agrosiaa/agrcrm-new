@extends('backend.seller.layouts.master')
@section('css')
<!-- BEGIN PAGE LEVEL PLUGINS -->
<link href="/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css" xmlns="http://www.w3.org/1999/html"/>
<link href="/assets/global/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet" type="text/css" />
<link href="/assets/global/plugins/jquery-file-upload/blueimp-gallery/blueimp-gallery.min.css" rel="stylesheet" type="text/css" />
<link href="/assets/global/plugins/jquery-file-upload/css/jquery.fileupload.css" rel="stylesheet" type="text/css" />
<link href="/assets/global/plugins/jquery-file-upload/css/jquery.fileupload-ui.css" rel="stylesheet" type="text/css" />
<!-- END PAGE LEVEL PLUGINS -->
@endsection
@section('content')
@include('backend.partials.common.nav')
<!-- BEGIN PAGE CONTENT BODY -->
<div class="page-content">
<div class="container">
<!-- BEGIN PAGE BREADCRUMBS -->
<!-- END PAGE BREADCRUMBS -->
<!-- BEGIN PAGE CONTENT INNER -->
  <div class="page-content-inner">
    <div class="row">
      <div class="col-md-12">
        <div class="portlet light ">
          <div class="portlet-title">
            <div class="caption" style="color: #78A539">
              <span class="caption-subject sbold uppercase" >Import Products</span>
            </div>
          </div>
          <div class="tabbable-custom nav-justified">
            <ul class="nav nav-tabs nav-justified">
                <li class="active">
                    <a href="#tab_1" data-toggle="tab"> Upload Products </a>
                </li>
                <li>
                    <a href="#tab_2" data-toggle="tab"> Upload Images </a>
                </li>
                <li>
                    <a href="#tab_3" data-toggle="tab" class="tab-hide"> Category Products </a>
                </li>
                <li>
                    <a href="#tab_4" data-toggle="tab" class="tab-hide"> Category Products </a>
                </li>
            </ul>
            <div class="tab-content">
                @include('backend.partials.error-messages')
              <div class="tab-pane active" id="tab_1">
                <div class="portlet-body form">
                  <div class="loading-img" style="display:none;">
                    <img src="/assets/global/img/loading-spinner-grey.gif"/>
                  </div>
                  <!-- BEGIN FORM-->
                  <form action="/administration/import" method="POST" class="form-horizontal form-bordered" enctype="multipart/form-data">
                      {!! csrf_field() !!}
                      <div class="panel panel-success">
                          <div class="panel-heading">
                              <h3 class="panel-title">Important Notes</h3>
                          </div>
                          <div class="panel-body">
                              <ul>
                                  <li>Maximum<strong> 100</strong> products can be uploaded for one item head for a single import instance.</li>
                                  <li>Products will fail to upload in case name on the tabs in the excel sheet are altered.</li>
                                  <li>Strictly use dropdown values <strong>(Brand, Pickup Address etc)</strong> for related fields in the excel sheet.</li>
                                  <li>For successful validation, following fields should not be left blank: <strong>vendor sku, product name, base price, quantity</strong></li>
                                  <li>Search keywords should be , separated only.</li>
                              </ul>
                          </div>
                      </div>
                      <div class="form-body">
                          <div class="row">
                            <div class="form-group">
                                <label class="control-label col-md-3">Select Category</label>
                                <div class="col-md-4">
                                  <select class="form-control" name="category">
                                      @foreach($rootCategories as $rootCategory)
                                          <option value="{{$rootCategory['slug']}}">{{$rootCategory['name']}}</option>
                                      @endforeach
                                  </select>
                                </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="form-group">
                              <label class="control-label col-md-3">Choose File</label>
                              <div class="col-md-6">
                                  <div class="fileinput fileinput-new import" data-provides="fileinput">
                                      <div class="input-group input-large">
                                          <div class="form-control uneditable-input input-fixed input-medium input-csv" data-trigger="fileinput">
                                              <i class="fa fa-file fileinput-exists"></i>&nbsp;
                                              <span class="fileinput-filename"> </span>
                                          </div>
                                          <span class="input-group-addon btn default btn-file">
                                              <span class="fileinput-new"> Browse </span>
                                              <span class="fileinput-exists"> Change </span>
                                              <input type="file" name="excel_file"> </span>
                                          <a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                          {{--<a href="javascript:;" class="input-group-addon btn green fileinput-exists" id="btn-upload"> Upload </a>--}}
                                      </div>
                                  </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      @if($rootCategories)
                      <div class="row">
                          <div class="form-group">
                              <label class="control-label col-md-3"></label>
                              <div class="col-md-3">
                                <button type="submit" class="btn base-color fileinput-exists"/>Upload</button>
                              </div>
                          </div>
                      </div>
                      @endif
                      </form>
                      </div>
                  <!-- END FORM-->
                </div>
                <div class="tab-pane" id="tab_2">
                  <div class="portlet-body form">
                    <!-- BEGIN Image Upload-->
                      <div class="row">
                          <div class="col-md-12">
                              <div class="panel panel-success">
                                  <div class="panel-heading">
                                      <h3 class="panel-title">Important Notes</h3>
                                  </div>
                                  <div class="panel-body">
                                      <ul>
                                          <li> The maximum file size for uploads in is
                                              <strong>2 MB</strong></li>
                                          <li> Only image files (
                                              <strong>JPG, JPEG, PNG</strong>) are allowed </li>
                                          <li> After upload you can click on image to
                                              <strong>view preview</strong> of that image. </li>
                                      </ul>
                                  </div>
                              </div>
                              <form id="fileupload" action="/administration/image" method="POST" enctype="multipart/form-data">
                                  {!! csrf_field() !!}
                                  <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
                                  <div class="row fileupload-buttonbar">
                                      <div class="col-lg-7">
                                          <!-- The fileinput-button span is used to style the file input field as button -->
                                                <span class="btn base-color fileinput-button">
                                                    <i class="fa fa-plus"></i>
                                                    <span> Add files... </span>
                                                    <input type="file" name="files" multiple=""> </span>
                                          <button type="submit" class="btn blue start">
                                              <i class="fa fa-upload"></i>
                                              <span> Start upload </span>
                                          </button>
                                          <button type="reset" class="btn warning cancel">
                                              <i class="fa fa-ban-circle"></i>
                                              <span> Cancel upload </span>
                                          </button>
                                          <!--<button type="button" class="btn red delete">
                                              <i class="fa fa-trash"></i>
                                              <span> Delete </span>
                                          </button>
                                          <input type="checkbox" class="toggle">-->
                                          <!-- The global file processing state -->
                                          <span class="fileupload-process"> </span>
                                      </div>
                                      <!-- The global progress information -->
                                      <div class="col-lg-5 fileupload-progress fade">
                                          <!-- The global progress bar -->
                                          <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                                              <div class="progress-bar progress-bar-success" style="width:0%;"> </div>
                                          </div>
                                          <!-- The extended global progress information -->
                                          <div class="progress-extended"> &nbsp; </div>
                                      </div>
                                  </div>
                                  <!-- The table listing the files available for upload/download -->
                                  <table role="presentation" class="table table-striped clearfix">
                                      <tbody class="files"> </tbody>
                                  </table>
                              </form>
                          </div>
                      </div>
                      <!-- The blueimp Gallery widget -->
                      <div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls" data-filter=":even">
                          <div class="slides"> </div>
                          <h3 class="title"></h3>
                          <a class="prev"> ‹ </a>
                          <a class="next"> › </a>
                          <a class="close white"> </a>
                          <a class="play-pause"> </a>
                          <ol class="indicator"> </ol>
                      </div>
                      <!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
                      <script id="template-upload" type="text/x-tmpl"> {% for (var i=0, file; file=o.files[i]; i++) { %}
                                <tr class="template-upload fade">
                                    <td>
                                        <span class="preview"></span>
                                    </td>
                                    <td>
                                        <p class="name">{%=file.name%}</p>
                                        <strong class="error text-warning label label-danger"></strong>
                                    </td>
                                    <td>
                                        <p class="size">Processing...</p>
                                        <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                                            <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                                        </div>
                                    </td>
                                    <td> {% if (!i && !o.options.autoUpload) { %}
                                        <button class="btn blue start" disabled>
                                            <i class="fa fa-upload"></i>
                                            <span>Start</span>
                                        </button> {% } %} {% if (!i) { %}
                                        <button class="btn red cancel">
                                            <i class="fa fa-ban"></i>
                                            <span>Cancel</span>
                                        </button> {% } %} </td>
                                </tr> {% } %} </script>
                      <!-- The template to display files available for download -->
                      <script id="template-download" type="text/x-tmpl"> {% for (var i=0, file; file=o.files[i]; i++) { %}
                                <tr class="template-download fade">
                                    <td>
                                        <span class="preview"> {% if (file.thumbnailUrl) { %}
                                            <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery>
                                                <img height="80" width="80" src="{%=file.thumbnailUrl%}">
                                            </a> {% } %} </span>
                                    </td>
                                    <td>
                                        <p class="name"> {% if (file.url) { %}
                                            <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl? 'data-gallery': ''%}>{%=file.name%}</a> {% } else { %}
                                            <span>{%=file.name%}</span> {% } %} </p> {% if (file.error) { %}
                                        <div>
                                            <span class="label label-danger">Error</span> {%=file.error%}</div> {% } %} </td>
                                    <td>
                                        <span class="size">{%=o.formatFileSize(file.size)%}</span>
                                    </td>
                                    <td> {% if (file.deleteUrl) { %}
                                        <!--<button class="btn red delete btn-sm" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}" {% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}' {%
                                        } %}>
                                            <i class="fa fa-trash-o"></i>
                                            <span>Delete</span>
                                        </button>
                                        <input type="checkbox" name="delete" value="1" class="toggle"> {% } else { %}
                                        <button class="btn yellow cancel btn-sm">
                                            <i class="fa fa-ban"></i>
                                            <span>Cancel</span>
                                        </button> {% } %} </td>-->
                                </tr> {% } %} </script>
                    <!-- END Image Upload-->
                  </div>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- END PAGE CONTENT INNER -->
</div>
</div>
<!-- END PAGE CONTENT BODY -->
@endsection
@section('javascript')
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="/assets/global/plugins/fancybox/source/jquery.fancybox.pack.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jquery-file-upload/js/vendor/jquery.ui.widget.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jquery-file-upload/js/vendor/tmpl.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jquery-file-upload/js/vendor/load-image.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jquery-file-upload/js/vendor/canvas-to-blob.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jquery-file-upload/blueimp-gallery/jquery.blueimp-gallery.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jquery-file-upload/js/jquery.iframe-transport.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jquery-file-upload/js/jquery.fileupload.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jquery-file-upload/js/jquery.fileupload-process.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jquery-file-upload/js/jquery.fileupload-image.js" type="text/javascript"></script>
{{--<script src="/assets/global/plugins/jquery-file-upload/js/jquery.fileupload-audio.js" type="text/javascript"></script>--}}
{{--<script src="/assets/global/plugins/jquery-file-upload/js/jquery.fileupload-video.js" type="text/javascript"></script>--}}
<script src="/assets/global/plugins/jquery-file-upload/js/jquery.fileupload-validate.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jquery-file-upload/js/jquery.fileupload-ui.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
<script src="/assets/pages/scripts/form-fileupload.js" type="text/javascript"></script>
{{--<script src="/assets/layouts/layout3/scripts/demo.min.js" type="text/javascript"></script>--}}
<!-- BEGIN THEME LAYOUT SCRIPTS -->
@endsection
