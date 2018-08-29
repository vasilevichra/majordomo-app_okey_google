<?php
/*
* @version 0.1 (wizard)
*/
 global $session;
  if ($this->owner->name=='panel') {
   $out['CONTROLPANEL']=1;
  }
  $qry="1";
  // search filters
  // QUERY READY
  global $save_qry;
  if ($save_qry) {
   $qry=$session->data['okey_google_qry'];
  } else {
   $session->data['okey_google_qry']=$qry;
  }
  if (!$qry) $qry="1";
  $sortby_okey_google="ID DESC";
  $out['SORTBY']=$sortby_okey_google;
  // SEARCH RESULTS
  $res=SQLSelect("SELECT * FROM okey_google WHERE $qry ORDER BY ".$sortby_okey_google);
  if ($res[0]['ID']) {
   //paging($res, 100, $out); // search result paging
   $total=count($res);
   for($i=0;$i<$total;$i++) {
    // some action for every record if required
   }
   $out['RESULT']=$res;
  }
