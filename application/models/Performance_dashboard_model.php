<?php
class Performance_dashboard_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  function data_source($filter)
  {
    $filter['select'] = 'count';
    return $this->ld_m->getStagingLeads($filter)->row()->count;
  }

  function invited_pre_event($params)
  {
    $fds = $params;
    $fds['select'] = 'count';
    $fds['eventCodeInvitation_not_null'] = true;
    $invited_pre_event = $this->ld_m->getLeads($fds)->row()->count;
    $invited_pre_event_persen = number_format((@($invited_pre_event / $params['data_source']) * 100), 2);
    return [
      'invited_pre_event' => $invited_pre_event,
      'invited_pre_event_persen' => $invited_pre_event_persen,
    ];
  }
  function leads_invited_non_invited($params)
  {
    if (!isset($params['sourceLeadsIn'])) {
      $params['sourceLeadsIn'] = [28, 29];
    }
    $cust_type = $this->ld_m->getLeadsGroupByCustomerType($params)->result_array();
    $cust_v = isset($cust_type[0]['count_cust_type']) ? $cust_type[0]['count_cust_type'] : 0;
    $cust_r = isset($cust_type[1]['count_cust_type']) ? $cust_type[1]['count_cust_type'] : 0;
    $leads_invited_non_invited =  $cust_v . '/' . $cust_r;
    $tot_leads = $cust_v + $cust_r;
    $leads_invited_non_invited_persen = number_format((@($tot_leads / $params['data_source']) * 100), 2);
    return [
      'leads_invited_non_invited' => $leads_invited_non_invited,
      'invited' => $cust_v,
      'non_invited' => $cust_r,
      'tot_leads' => $tot_leads,
      'leads_invited_non_invited_persen' => $leads_invited_non_invited_persen,
    ];
  }

  function contact_leads($params)
  {
    $fds                    = $params;
    $fds['is_contacted'] = true;

    if (isset($params['group_by'])) {
      $fds['group_by'] = $params['group_by'];
    }
    if (!isset($fds['sourceLeadsIn'])) {
      $fds['sourceLeadsIn'] = [28, 29];
    }
    $get_contact_leads = $this->ld_m->getCountLeadsVsFollowUp($fds)->result();
    $contact_leads = 0;
    foreach ($get_contact_leads as $gt) {
      if (isset($gt->sourceData)) {
        if ($gt->sourceData == 28) {
          $contact_leads_invited = $gt->count;
        } elseif ($gt->sourceData == 29) {
          $contact_leads_non_invited = $gt->count;
        }
      }
      $contact_leads += $gt->count;
    }

    $contact_leads_persen = number_format((@($contact_leads / $params['tot_leads']) * 100), 2);
    return [
      'contact_leads' => $contact_leads,
      'contact_leads_persen' => $contact_leads_persen,
      'contact_leads_invited' => isset($contact_leads_invited) ? $contact_leads_invited : 0,
      'contact_leads_non_invited' => isset($contact_leads_non_invited) ? $contact_leads_non_invited : 0,
    ];
  }

  function prospects($params)
  {
    $fds                              = $params;
    $fds['kodeHasilStatusFollowUp']   = 1;
    if (!isset($fds['sourceLeadsIn'])) {
      $fds['sourceLeadsIn'] = [28, 29];
    }
    $fds['is_md'] = 1;
    $fds['reset_md_d'] = true;
    $res_prospects = $this->ld_m->getCountLeadsVsFollowUp($fds)->result();
    $prospects = 0;
    foreach ($res_prospects as $rs) {
      $prospects += $rs->count;
      if ($rs->sourceData == 28) {
        $prospects_invited = $rs->count;
      } elseif ($rs->sourceData == 29) {
        $prospects_non_invited = $rs->count;
      }
    }
    $prospects_persen = number_format((@($prospects / $params['contact_leads']) * 100), 2);
    return [
      'prospects_persen' => $prospects_persen,
      'prospects' => $prospects,
    ];
  }

  function contacted_prospects($params)
  {
    $fds                                    = $params;
    $fds['select']                          = 'count_distinct_leads_id';
    $fds['id_kategori_status_komunikasi']   = 4;
    $fds['assignedDealerIsNotNULL']         = true;
    if (!isset($fds['sourceLeadsIn'])) {
      $fds['sourceLeadsIn'] = [28, 29];
    }
    $contacted_prospects = $this->ld_m->getLeadsFollowUp($fds)->row()->count;
    $contacted_prospects_persen = number_format((@($contacted_prospects / $params['prospects']) * 100), 2);
    return [
      'contacted_prospects' => $contacted_prospects,
      'contacted_prospects_persen' => $contacted_prospects_persen,
    ];
  }
  function deal($params)
  {
    $fds = $params;
    $fds['select']                          = 'count_distinct_leads_id';
    $fds['kodeHasilStatusFollowUp']         = 3;
    $fds['id_kategori_status_komunikasi']   = 4;
    $fds['idSPK_not_null']                  = true;
    if (!isset($fds['sourceLeadsIn'])) {
      $fds['sourceLeadsIn'] = [28, 29];
    }
    $deal = $this->ld_m->getLeadsFollowUp($fds)->row()->count;
    $deal_persen = number_format((@($deal / $params['contacted_prospects']) * 100), 2);
    return [
      'deal' => $deal,
      'deal_persen' => $deal_persen,
    ];
  }
  function sales($params)
  {
    $fds                                    = $params;
    $fds['select']                          = 'count_distinct_leads_id';
    $fds['kodeHasilStatusFollowUp']         = 3;
    $fds['id_kategori_status_komunikasi']   = 4;
    $fds['frameNo_not_null']                = true;
    if (!isset($fds['sourceLeadsIn'])) {
      $fds['sourceLeadsIn'] = [28, 29];
    }
    $sales = $this->ld_m->getLeadsFollowUp($fds)->row()->count;
    $sales_persen = number_format((@($sales / $params['deal']) * 100), 2);
    return [
      'sales' => $sales,
      'sales_persen' => $sales_persen,
    ];
  }
  function workload_md_leads($params)
  {
    $fds = $params;
    $fds['is_workload'] = true;
    $fds['is_md'] = true;
    $fds['group_by'] = "ld.sourceData";
    if (!isset($fds['sourceLeadsIn'])) {
      $fds['sourceLeadsIn'] = [28, 29];
    }
    $res_workload = $this->ld_m->getCountLeadsVsFollowUp($fds)->result();
    $workload = 0;
    foreach ($res_workload as $rs) {
      $workload += $rs->count;
      if ($rs->sourceData == 28) {
        $workload_invited = $rs->count;
      } elseif ($rs->sourceData == 29) {
        $workload_non_invited = $rs->count;
      }
    }

    return [
      'workload' => $workload,
      'workload_invited' => isset($workload_invited) ? $workload_invited : 0,
      'workload_non_invited' => isset($workload_non_invited) ? $workload_non_invited : 0,
    ];
  }

  function unreachable_md_leads($params)
  {
    $fds = $params;
    $fds['is_unreachable'] = true;
    $fds['is_md'] = true;
    $fds['group_by'] = "ld.sourceData";
    // $fds['select']='';
    $res_unreachable = $this->ld_m->getCountLeadsVsFollowUp($fds)->result();
    // send_json($res_unreachable);
    $unreachable = 0;
    foreach ($res_unreachable as $rs) {
      $unreachable += $rs->count;
      if ($rs->sourceData == 28) {
        $unreachable_invited = $rs->count;
      } elseif ($rs->sourceData == 29) {
        $unreachable_non_invited = $rs->count;
      }
    }

    return [
      'unreachable' => $unreachable,
      'unreachable_invited' => isset($unreachable_invited) ? $unreachable_invited : 0,
      'unreachable_non_invited' => isset($unreachable_non_invited) ? $unreachable_non_invited : 0,
    ];
  }

  function rejected_md_leads($params)
  {
    $fds = $params;
    $fds['is_rejected'] = true;
    $fds['is_md'] = true;
    $fds['group_by'] = "ld.customerType";

    $res_rejected = $this->ld_m->getCountLeadsVsFollowUp($fds)->result();
    $rejected = 0;
    foreach ($res_rejected as $rs) {
      $rejected += $rs->count;
      if ($rs->customerType == 'V') {
        $rejected_invited = $rs->count;
      } elseif ($rs->customerType == 'R') {
        $rejected_non_invited = $rs->count;
      }
    }

    return [
      'rejected' => $rejected,
      'rejected_invited' => isset($rejected_invited) ? $rejected_invited : 0,
      'rejected_non_invited' => isset($rejected_non_invited) ? $rejected_non_invited : 0,
    ];
  }

  function failed_md_leads($params)
  {
    $fds = $params;
    $fds['is_failed'] = true;
    $fds['is_md'] = true;
    $fds['group_by'] = "ld.customerType";

    $res_failed = $this->ld_m->getCountLeadsVsFollowUp($fds)->result();
    $failed = 0;
    foreach ($res_failed as $rs) {
      $failed += $rs->count;
      if ($rs->customerType == 'V') {
        $failed_invited = $rs->count;
      } elseif ($rs->customerType == 'R') {
        $failed_non_invited = $rs->count;
      }
    }

    return [
      'failed' => $failed,
      'failed_invited' => isset($failed_invited) ? $failed_invited : 0,
      'failed_non_invited' => isset($failed_non_invited) ? $failed_non_invited : 0,
    ];
  }
  function contacted_prospetcs($params)
  {
    $fds                                    = $params;
    $fds['id_kategori_status_komunikasi']   = 4;
    $fds['is_dealer']                       = true;
    $fds['group_by']                        = "ld.sourceData";
    if (!isset($fds['sourceLeadsIn'])) {
      $fds['sourceLeadsIn'] = [28, 29];
    }

    $res_contacted = $this->ld_m->getCountLeadsVsFollowUp($fds)->result();
    $contacted = 0;
    foreach ($res_contacted as $rs) {
      $contacted += $rs->count;
      if ($rs->sourceData == 28) {
        $contacted_invited = $rs->count;
      } elseif ($rs->sourceData == 29) {
        $contacted_non_invited = $rs->count;
      }
    }

    return [
      'contacted' => $contacted,
      'contacted_invited' => isset($contacted_invited) ? $contacted_invited : 0,
      'contacted_non_invited' => isset($contacted_non_invited) ? $contacted_non_invited : 0,
    ];
  }
  function workload_prospetcs($params)
  {
    $fds                  = $params;
    $fds['is_workload']   = true;
    $fds['is_dealer']     = true;
    $fds['group_by']      = "ld.sourceData";
    if (!isset($fds['sourceLeadsIn'])) {
      $fds['sourceLeadsIn'] = [28, 29];
    }

    $res_workload = $this->ld_m->getCountLeadsVsFollowUp($fds)->result();
    $workload = 0;
    foreach ($res_workload as $rs) {
      $workload += $rs->count;
      if ($rs->sourceData == 28) {
        $workload_invited = $rs->count;
      } elseif ($rs->sourceData == 29) {
        $workload_non_invited = $rs->count;
      }
    }

    return [
      'workload' => $workload,
      'workload_invited' => isset($workload_invited) ? $workload_invited : 0,
      'workload_non_invited' => isset($workload_non_invited) ? $workload_non_invited : 0,
    ];
  }
  function unreachable_prospetcs($params)
  {
    $fds                      = $params;
    $fds['is_unreachable']    = true;
    $fds['is_dealer']         = true;
    $fds['group_by']          = "ld.sourceData";
    if (!isset($fds['sourceLeadsIn'])) {
      $fds['sourceLeadsIn'] = [28, 29];
    }

    $res_unreachable = $this->ld_m->getCountLeadsVsFollowUp($fds)->result();
    $unreachable = 0;
    foreach ($res_unreachable as $rs) {
      $unreachable += $rs->count;
      if ($rs->sourceData == 28) {
        $unreachable_invited = $rs->count;
      } elseif ($rs->sourceData == 29) {
        $unreachable_non_invited = $rs->count;
      }
    }

    return [
      'unreachable' => $unreachable,
      'unreachable_invited' => isset($unreachable_invited) ? $unreachable_invited : 0,
      'unreachable_non_invited' => isset($unreachable_non_invited) ? $unreachable_non_invited : 0,
    ];
  }
  function rejected_prospetcs($params)
  {
    $fds = $params;
    $fds['is_rejected']   = true;
    $fds['is_dealer']     = true;
    $fds['group_by']      = "ld.sourceData";
    if (!isset($fds['sourceLeadsIn'])) {
      $fds['sourceLeadsIn'] = [28, 29];
    }

    $res_rejected = $this->ld_m->getCountLeadsVsFollowUp($fds)->result();
    $rejected = 0;
    foreach ($res_rejected as $rs) {
      $rejected += $rs->count;
      if ($rs->sourceData == 28) {
        $rejected_invited = $rs->count;
      } elseif ($rs->sourceData == 29) {
        $rejected_non_invited = $rs->count;
      }
    }

    return [
      'rejected' => $rejected,
      'rejected_invited' => isset($rejected_invited) ? $rejected_invited : 0,
      'rejected_non_invited' => isset($rejected_non_invited) ? $rejected_non_invited : 0,
    ];
  }
  function failed_prospetcs($params)
  {
    $fds = $params;
    $fds['is_failed'] = true;
    $fds['is_dealer'] = true;
    $fds['group_by']      = "ld.sourceData";
    if (!isset($fds['sourceLeadsIn'])) {
      $fds['sourceLeadsIn'] = [28, 29];
    }

    $res_failed = $this->ld_m->getCountLeadsVsFollowUp($fds)->result();
    $failed = 0;
    foreach ($res_failed as $rs) {
      $failed += $rs->count;
      if ($rs->sourceData == 28) {
        $failed_invited = $rs->count;
      } elseif ($rs->sourceData == 29) {
        $failed_non_invited = $rs->count;
      }
    }

    return [
      'failed' => $failed,
      'failed_invited' => isset($failed_invited) ? $failed_invited : 0,
      'failed_non_invited' => isset($failed_non_invited) ? $failed_non_invited : 0,
    ];
  }

  function fl_contact_leads($params)
  {
    $fds = $params;
    $fds['is_contacted'] = true;
    // $fds['is_md'] = true;
    $fds['group_by'] = "ld.sourceData";
    if (!isset($fds['sourceLeadsIn'])) {
      $fds['sourceLeadsIn'] = [28, 29];
    }
    $res_contacted = $this->ld_m->getCountLeadsVsFollowUp($fds)->result();
    $contacted = 0;
    foreach ($res_contacted as $rs) {
      $contacted += $rs->count;
      if ($rs->sourceData == 28) {
        $contacted_invited = $rs->count;
      } elseif ($rs->sourceData == 29) {
        $contacted_non_invited = $rs->count;
      }
    }

    return [
      'contacted' => $contacted,
      'contacted_invited' => isset($contacted_invited) ? $contacted_invited : 0,
      'contacted_non_invited' => isset($contacted_non_invited) ? $contacted_non_invited : 0,
    ];
  }
  function hot($params)
  {
    $fds = $params;
    $fds['is_contacted'] = true;
    $fds['is_dealer'] = true;
    $fds['selisih_next_lebih_kecil_dari'] = 14;
    $fds['group_by']      = "ld.sourceData";
    if (!isset($fds['sourceLeadsIn'])) {
      $fds['sourceLeadsIn'] = [28, 29];
    }

    $res_hot = $this->ld_m->getCountLeadsVsFollowUp($fds)->result();
    $hot = 0;
    foreach ($res_hot as $rs) {
      $hot += $rs->count;
      if ($rs->sourceData == 28) {
        $hot_invited = $rs->count;
      } elseif ($rs->sourceData == 29) {
        $hot_non_invited = $rs->count;
      }
    }

    return [
      'hot' => $hot,
      'hot_invited' => isset($hot_invited) ? $hot_invited : 0,
      'hot_non_invited' => isset($hot_non_invited) ? $hot_non_invited : 0,
    ];
  }

  function medium($params)
  {
    $fds = $params;
    $fds['is_contacted'] = true;
    $fds['is_dealer'] = true;
    $fds['selisih_next_between'] = [14, 28];
    $fds['group_by']      = "ld.sourceData";
    if (!isset($fds['sourceLeadsIn'])) {
      $fds['sourceLeadsIn'] = [28, 29];
    }

    $res_medium = $this->ld_m->getCountLeadsVsFollowUp($fds)->result();
    $medium = 0;
    foreach ($res_medium as $rs) {
      $medium += $rs->count;
      if ($rs->sourceData == 28) {
        $medium_invited = $rs->count;
      } elseif ($rs->sourceData == 29) {
        $medium_non_invited = $rs->count;
      }
    }

    return [
      'medium' => $medium,
      'medium_invited' => isset($medium_invited) ? $medium_invited : 0,
      'medium_non_invited' => isset($medium_non_invited) ? $medium_non_invited : 0,
    ];
  }

  function low($params)
  {
    $fds = $params;
    $fds['is_contacted'] = true;
    $fds['is_dealer'] = true;
    $fds['selisih_next_lebih_besar_dari'] = 30;
    $fds['group_by']      = "ld.sourceData";
    if (!isset($fds['sourceLeadsIn'])) {
      $fds['sourceLeadsIn'] = [28, 29];
    }

    $res_low = $this->ld_m->getCountLeadsVsFollowUp($fds)->result();
    $low = 0;
    foreach ($res_low as $rs) {
      $low += $rs->count;
      if ($rs->sourceData == 28) {
        $low_invited = $rs->count;
      } elseif ($rs->sourceData == 29) {
        $low_non_invited = $rs->count;
      }
    }

    return [
      'low' => $low,
      'low_invited' => isset($low_invited) ? $low_invited : 0,
      'low_non_invited' => isset($low_non_invited) ? $low_non_invited : 0,
    ];
  }

  function fl_deal($params)
  {
    $fds                            = $params;
    $fds['is_contacted']            = true;
    $fds['is_dealer']               = true;
    $fds['group_by']      = "ld.sourceData";
    if (!isset($fds['sourceLeadsIn'])) {
      $fds['sourceLeadsIn'] = [28, 29];
    }
    $fds['kodeHasilStatusFollowUp'] = 3;
    $fds['idSPK_not_null']          = true;

    $res_deal = $this->ld_m->getCountLeadsVsFollowUp($fds)->result();
    $deal = 0;
    foreach ($res_deal as $rs) {
      $deal += $rs->count;
      if ($rs->sourceData == 28) {
        $deal_invited = $rs->count;
      } elseif ($rs->sourceData == 29) {
        $deal_non_invited = $rs->count;
      }
    }

    return [
      'deal' => $deal,
      'deal_invited' => isset($deal_invited) ? $deal_invited : 0,
      'deal_non_invited' => isset($deal_non_invited) ? $deal_non_invited : 0,
    ];
  }

  function fl_need_fu($params)
  {
    $fds                            = $params;
    $fds['not_contacted']            = true;
    $fds['is_dealer']               = true;
    $fds['group_by']      = "ld.sourceData";
    if (!isset($fds['sourceLeadsIn'])) {
      $fds['sourceLeadsIn'] = [28, 29];
    }
    $fds['kodeHasilStatusFollowUpNotIn'] = "3, 4";

    $res_need_fu = $this->ld_m->getCountLeadsVsFollowUp($fds)->result();
    $need_fu = 0;
    foreach ($res_need_fu as $rs) {
      $need_fu += $rs->count;
      if ($rs->sourceData == 28) {
        $need_fu_invited = $rs->count;
      } elseif ($rs->sourceData == 29) {
        $need_fu_non_invited = $rs->count;
      }
    }

    return [
      'need_fu' => $need_fu,
      'need_fu_invited' => isset($need_fu_invited) ? $need_fu_invited : 0,
      'need_fu_non_invited' => isset($need_fu_non_invited) ? $need_fu_non_invited : 0,
    ];
  }


  function fl_not_deal($params)
  {
    $fds                            = $params;
    $fds['is_contacted']            = true;
    $fds['is_dealer']               = true;
    $fds['group_by']      = "ld.sourceData";
    if (!isset($fds['sourceLeadsIn'])) {
      $fds['sourceLeadsIn'] = [28, 29];
    }
    $fds['kodeHasilStatusFollowUp'] = 4;

    $res_not_deal = $this->ld_m->getCountLeadsVsFollowUp($fds)->result();
    $not_deal = 0;
    foreach ($res_not_deal as $rs) {
      $not_deal += $rs->count;
      if ($rs->sourceData == 28) {
        $not_deal_invited = $rs->count;
      } elseif ($rs->sourceData == 29) {
        $not_deal_non_invited = $rs->count;
      }
    }

    return [
      'not_deal' => $not_deal,
      'not_deal_invited' => isset($not_deal_invited) ? $not_deal_invited : 0,
      'not_deal_non_invited' => isset($not_deal_non_invited) ? $not_deal_non_invited : 0,
    ];
  }

  function fl_sales($params)
  {
    $fds                            = $params;
    $fds['is_contacted']            = true;
    $fds['is_dealer']               = true;
    $fds['group_by']      = "ld.sourceData";
    if (!isset($fds['sourceLeadsIn'])) {
      $fds['sourceLeadsIn'] = [28, 29];
    }
    $fds['kodeHasilStatusFollowUp'] = 3;
    $fds['frameNo_not_null']        = true;
    $fds['idSPK_not_null']          = true;

    $res_sales = $this->ld_m->getCountLeadsVsFollowUp($fds)->result();
    $sales = 0;
    foreach ($res_sales as $rs) {
      $sales += $rs->count;
      if ($rs->sourceData == 28) {
        $sales_invited = $rs->count;
      } elseif ($rs->sourceData == 29) {
        $sales_non_invited = $rs->count;
      }
    }

    return [
      'sales' => $sales,
      'sales_invited' => isset($sales_invited) ? $sales_invited : 0,
      'sales_non_invited' => isset($sales_non_invited) ? $sales_non_invited : 0,
    ];
  }
  function fl_indent($params)
  {
    $fds                              = $params;
    $fds['is_contacted']              = true;
    $fds['is_dealer']                 = true;
    $fds['group_by']                  = "ld.sourceData";
    if (!isset($fds['sourceLeadsIn'])) {
      $fds['sourceLeadsIn'] = [28, 29];
    }
    $fds['kodeHasilStatusFollowUp']   = 3;
    $fds['kodeIndent_not_null']       = true;
    $fds['idSPK_not_null']            = true;
    $fds['frameNo_null']              = true;

    $res_indent = $this->ld_m->getCountLeadsVsFollowUp($fds)->result();
    $indent = 0;
    foreach ($res_indent as $rs) {
      $indent += $rs->count;
      if ($rs->sourceData == 28) {
        $indent_invited = $rs->count;
      } elseif ($rs->sourceData == 29) {
        $indent_non_invited = $rs->count;
      }
    }

    return [
      'indent' => $indent,
      'indent_invited' => isset($indent_invited) ? $indent_invited : 0,
      'indent_non_invited' => isset($indent_non_invited) ? $indent_non_invited : 0,
    ];
  }
}
