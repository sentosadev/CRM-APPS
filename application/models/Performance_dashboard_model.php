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
    $cust_type = $this->ld_m->getLeadsGroupByCustomerType($params)->result_array();
    $cust_v = isset($cust_type[1]['count_cust_type']) ? $cust_type[1]['count_cust_type'] : 0;
    $cust_r = isset($cust_type[0]['count_cust_type']) ? $cust_type[0]['count_cust_type'] : 0;
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
    $fds['select']          = 'count';
    $fds['fu_md_contacted'] = true;
    if (isset($params['group_by'])) {
      $fds['group_by'] = $params['group_by'];
    }

    $get_contact_leads = $this->ld_m->getLeads($fds)->result();
    $contact_leads = 0;
    foreach ($get_contact_leads as $gt) {
      if (isset($gt->customerType)) {
        if ($gt->customerType == 'V') {
          $contact_leads_invited = $gt->count;
        } elseif ($gt->customerType == 'R') {
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
    $fds           = $params;
    $fds['select'] = 'count_distinct_leads_id';
    $fds['kodeHasilStatusFollowUp'] = 1;
    $prospects = $this->ld_m->getLeadsFollowUp($fds)->row()->count;
    $prospects_persen = number_format((@($prospects / $params['contact_leads']) * 100), 2);
    return [
      'prospects_persen' => $prospects_persen,
      'prospects' => $prospects,
    ];
  }

  function contacted_prospects($params)
  {
    $fds = $params;
    $fds['select']                        = 'count_distinct_leads_id';
    $fds['kodeHasilStatusFollowUp']       = 1;
    $fds['id_kategori_status_komunikasi'] = 4;
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
    $fds['select'] = 'count_distinct_leads_id';
    $fds['kodeHasilStatusFollowUp'] = 3;
    $fds['id_kategori_status_komunikasi'] = 4;
    $fds['idSPK_not_null'] = true;

    $deal = $this->ld_m->getLeadsFollowUp($fds)->row()->count;
    $deal_persen = number_format((@($deal / $params['contacted_prospects']) * 100), 2);
    return [
      'deal' => $deal,
      'deal_persen' => $deal_persen,
    ];
  }
  function sales($params)
  {
    $fds = $params;
    $fds['select'] = 'count_distinct_leads_id';
    $fds['kodeHasilStatusFollowUp'] = 3;
    $fds['id_kategori_status_komunikasi'] = 4;
    $fds['frameNo_not_null'] = true;
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
    $fds['group_by'] = "ld.customerType";

    $res_workload = $this->ld_m->getCountLeadsVsFollowUp($fds)->result();
    $workload = 0;
    foreach ($res_workload as $rs) {
      $workload += $rs->count;
      if ($rs->customerType == 'V') {
        $workload_invited = $rs->count;
      } elseif ($rs->customerType == 'R') {
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
    $fds['group_by'] = "ld.customerType";

    $res_unreachable = $this->ld_m->getCountLeadsVsFollowUp($fds)->result();
    $unreachable = 0;
    foreach ($res_unreachable as $rs) {
      $unreachable += $rs->count;
      if ($rs->customerType == 'V') {
        $unreachable_invited = $rs->count;
      } elseif ($rs->customerType == 'R') {
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
}
