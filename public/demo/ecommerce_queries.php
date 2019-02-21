<?php
  /*
   * Paging
   */

  //var_dump($_POST["selected"]);


  $iTotalRecords = 24;
  $iDisplayLength = intval($_REQUEST['length']);
  $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
  $iDisplayStart = intval($_REQUEST['start']);
  $sEcho = intval($_REQUEST['draw']);

  $records = array();
  $records["data"] = array();

  $end = $iDisplayStart + $iDisplayLength;
  $end = $end > $iTotalRecords ? $iTotalRecords : $end;

  $status_list = array(
    array("default" => "Enabled"),
    array("default" => "Disabled")
  );
  $status1_list = array(
    array("default" => "Query Raised"),
    array("default" => "Pending")
  );

  for($i = $iDisplayStart; $i < $end; $i++) {
    $status = $status_list[rand(0, 2)];
    $status1 = $status1_list[rand(0, 2)];
    $id = ($i + 1);
    $records["data"][] = array(
      '<input type="checkbox" name="id[]" value="'.$id.'" Disabled="Disabled">',

      'Test Product',
      'Seller123',
      'category1',
      '<a data-toggle="modal" data-target="#query-raised" class="btn btn-sm btn-circle btn-default btn-editable">View Query</a>',
    );
  }

  if (isset($_REQUEST["customActionType"]) && $_REQUEST["customActionType"] == "group_action") {
    $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
    $records["customActionMessage"] = "Group action successfully has been completed. Well done!"; // pass custom message(useful for getting status of group actions)
  }

  $records["draw"] = $sEcho;
  $records["recordsTotal"] = $iTotalRecords;
  $records["recordsFiltered"] = $iTotalRecords;

  echo json_encode($records);



?>
