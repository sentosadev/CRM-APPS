<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Performance_dashboard extends Crm_Controller
{
  var $title  = "Performance Dashboard";
  var $db2 = '';
  public function __construct()
  {
    parent::__construct();
    if (!logged_in()) redirect('auth/login');
    $this->load->model('leads_model', 'ld_m');
    $this->load->model('dealer_model', 'dealer_m');
    $this->load->model('performance_dashboard_model', 'pdm');
    $this->db_live = $this->load->database('sinsen_live', true);
  }

  public function index()
  {
    $data['title'] = $this->title;
    $data['file']  = 'view';
    $data['filter_header'] = [
      'id_platform_data' => ['text' => 'Platform Data', 'type' => 'select2'],
      'id_source_leads' => ['text' => 'Source Data', 'type' => 'select2'],
      'deskripsiEvent' => ['text' => 'Event Description', 'type' => 'select2'],
      'idKabupatenPengajuan' => ['text' => 'Kabupaten / Kota', 'type' => 'select2'],
      'searchAssignedDealer' => ['text' => 'Dealer', 'type' => 'select2'],
      'periode_created_leads' => ['text' => 'Periode', 'type' => 'daterange'],
      'id_series' => ['text' => 'Series', 'type' => 'select2'],
      'id_tipe' => ['text' => 'Tipe Motor', 'type' => 'select2'],
    ];
    $this->template_portal($data);
  }

  function _header_filter()
  {
    $periode = explode('-', $this->input->post('periode_created_leads'));
    $periodeCreatedLeads = NULL;
    if (count($periode) > 1) {
      foreach ($periode as $val) {
        $periodeCreatedLeads[] = convert_date(str_replace(' ', '', $val));
      }
    }

    $filter = [
      'platformDataIn' => $this->input->post('id_platform_data'),
      'sourceLeadsIn' => $this->input->post('id_source_leads'),
      'deskripsiEventIn' => $this->input->post('deskripsiEvent'),
      'periodeCreatedLeads' => $periodeCreatedLeads,
      'kabupatenIn' => $this->input->post('idKabupatenPengajuan'),
      'assignedDealerIn' => $this->input->post('searchAssignedDealer'),
      'kodeTypeUnitIn' => $this->input->post('id_tipe'),
      'seriesMotorIn' => $this->input->post('id_series'),
    ];
    return $filter;
  }

  function loadCardViewMD()
  {
    $filter = $this->_header_filter();
    $status = 1;
    $data_source = $this->pdm->data_source($filter);

    $f_pre = $filter;
    $f_pre['data_source']     = $data_source;
    $get_invited_pre_event    = $this->pdm->invited_pre_event($f_pre);
    $invited_pre_event        = $get_invited_pre_event['invited_pre_event'];
    $invited_pre_event_persen = $get_invited_pre_event['invited_pre_event_persen'];

    $get_leads = $this->pdm->leads_invited_non_invited($f_pre);
    $leads_invited_non_invited =  $get_leads['leads_invited_non_invited'];
    $tot_leads = $get_leads['tot_leads'];
    $leads_invited_non_invited_persen = $get_leads['leads_invited_non_invited_persen'];

    $fls = $filter;
    $fls['tot_leads']     = $tot_leads;
    $get_contact_leads    = $this->pdm->contact_leads($fls);
    $contact_leads        = $get_contact_leads['contact_leads'];
    $contact_leads_persen = $get_contact_leads['contact_leads_persen'];

    $fps = $filter;
    $fps['contact_leads'] = $contact_leads;
    $get_prospects    = $this->pdm->prospects($fps);
    $prospects        = $get_prospects['prospects'];
    $prospects_persen = $get_prospects['prospects_persen'];

    $fds = $filter;
    $fds['prospects'] = $prospects;
    $get_contacted_prospects    = $this->pdm->contacted_prospects($fds);
    $contacted_prospects        = $get_contacted_prospects['contacted_prospects'];
    $contacted_prospects_persen = $get_contacted_prospects['contacted_prospects_persen'];

    $fds = $filter;
    $fds['contacted_prospects'] = $contacted_prospects;
    $get_deal    = $this->pdm->deal($fds);
    $deal        = $get_deal['deal'];
    $deal_persen = $get_deal['deal_persen'];

    $fds = $filter;
    $fds['deal'] = $deal;
    $get_sales    = $this->pdm->sales($fds);
    $sales        = $get_sales['sales'];
    $sales_persen = $get_sales['sales_persen'];

    $data = [
      'data_source' => $data_source,
      'data_source_persen' => "100%",
      'invited_pre_event_persen' => $invited_pre_event_persen . '%',
      'invited_pre_event' => $invited_pre_event,
      'leads_invited_non_invited_persen' => $leads_invited_non_invited_persen . '%',
      'leads_invited_non_invited' => $leads_invited_non_invited,
      'contact_leads_persen' => $contact_leads_persen . '%',
      'contact_leads' => $contact_leads,
      'prospects_persen' => $prospects_persen . '%',
      'prospects' => $prospects,
      'prospects_persen' => $prospects_persen . '%',
      'contacted_prospects' => $contacted_prospects,
      'contacted_prospects_persen' => $contacted_prospects_persen . '%',
      'deal' => $deal,
      'deal_persen' => $deal_persen . '%',
      'sales' => $sales,
      'sales_persen' => $sales_persen . '%',
    ];

    $response = [
      'status' => $status,
      'data' => $data
    ];
    send_json($response);
  }
  function loadLeadsFunneling()
  {

    $filter = $this->_header_filter();
    $fds = $filter;
    $fds['data_source'] = 0;
    $get_leads = $this->pdm->leads_invited_non_invited($fds);

    $fds = $filter;
    $fds['tot_leads'] = 0;
    $fds['group_by'] = " stl.customerType";
    $get_contact_leads    = $this->pdm->contact_leads($fds);
    $contact_leads        = $get_contact_leads;

    $fds = $filter;
    $get_workload_md_leads    = $this->pdm->workload_md_leads($fds);
    $workload_md_leads        = $get_workload_md_leads;

    $get_unreachable_md_leads    = $this->pdm->unreachable_md_leads($fds);
    $unreachable_md_leads        = $get_unreachable_md_leads;

    $get_rejected_md_leads    = $this->pdm->rejected_md_leads($fds);
    $rejected_md_leads        = $get_rejected_md_leads;

    $get_failed_md_leads    = $this->pdm->failed_md_leads($fds);
    $failed_md_leads        = $get_failed_md_leads;

    $get_contacted_prospetcs    = $this->pdm->contacted_prospetcs($fds);
    $contacted_prospetcs        = $get_contacted_prospetcs;

    $get_workload_prospetcs    = $this->pdm->workload_prospetcs($fds);
    $workload_prospetcs        = $get_workload_prospetcs;

    $get_unreachable_prospetcs    = $this->pdm->unreachable_prospetcs($fds);
    $unreachable_prospetcs        = $get_unreachable_prospetcs;

    $get_rejected_prospetcs    = $this->pdm->rejected_prospetcs($fds);
    $rejected_prospetcs        = $get_rejected_prospetcs;

    $get_failed_prospetcs    = $this->pdm->failed_prospetcs($fds);
    $failed_prospetcs        = $get_failed_prospetcs;

    $result = [
      'status' => 1,
      'data' => [
        'lf_tot_leads' => $get_leads['tot_leads'],
        'lf_invited' => $get_leads['invited'],
        'lf_non_invited' => $get_leads['non_invited'],
        "lf_contacted_leads" => $contact_leads['contact_leads'],
        "lf_contacted_leads_invited" => $contact_leads['contact_leads_invited'],
        "lf_contacted_leads_non_invited" => $contact_leads['contact_leads_non_invited'],
        "lf_workload_leads" => $workload_md_leads['workload'],
        "lf_workload_leads_invited" => $workload_md_leads['workload_invited'],
        "lf_workload_leads_non_invited" => $workload_md_leads['workload_non_invited'],
        "lf_unreachable_leads" => $unreachable_md_leads['unreachable'],
        "lf_unreachable_leads_invited" => $unreachable_md_leads['unreachable_invited'],
        "lf_unreachable_leads_non_invited" => $unreachable_md_leads['unreachable_non_invited'],
        "lf_rejected_leads" => $rejected_md_leads['rejected'],
        "lf_rejected_leads_invited" => $rejected_md_leads['rejected_invited'],
        "lf_rejected_leads_non_invited" => $rejected_md_leads['rejected_non_invited'],
        "lf_failed_leads" => $failed_md_leads['failed'],
        "lf_failed_leads_invited" => $failed_md_leads['failed_invited'],
        "lf_failed_leads_non_invited" => $failed_md_leads['failed_non_invited'],
        "lf_contacted_prospects" => $contacted_prospetcs['contacted'],
        "lf_contacted_prospects_invited" => $contacted_prospetcs['contacted_invited'],
        "lf_contacted_prospects_non_invited" => $contacted_prospetcs['contacted_non_invited'],
        "lf_workload_prospects" => $workload_prospetcs['workload'],
        "lf_workload_prospects_invited" => $workload_prospetcs['workload_invited'],
        "lf_workload_prospects_non_invited" => $workload_prospetcs['workload_non_invited'],
        "lf_unreachable_prospects" => $unreachable_prospetcs['unreachable'],
        "lf_unreachable_prospects_invited" => $unreachable_prospetcs['unreachable_invited'],
        "lf_unreachable_prospects_non_invited" => $unreachable_prospetcs['unreachable_non_invited'],
        "lf_rejected_prospects" => $rejected_prospetcs['rejected'],
        "lf_rejected_prospects_invited" => $rejected_prospetcs['rejected_invited'],
        "lf_rejected_prospects_non_invited" => $rejected_prospetcs['rejected_non_invited'],
        "lf_failed_prospects" => $failed_prospetcs['failed'],
        "lf_failed_prospects_invited" => $failed_prospetcs['failed_invited'],
        "lf_failed_prospects_non_invited" => $failed_prospetcs['failed_non_invited'],
        "fl_hot" => 0,
        "fl_hot_invited" => 0,
        "fl_hot_non_invited" => 0,
        "fl_med" => 0,
        "fl_med_invited" => 0,
        "fl_med_non_invited" => 0,
        "fl_low" => 0,
        "fl_low_invited" => 0,
        "fl_low_non_invited" => 0,
        "fl_deal" => 0,
        "fl_deal_invited" => 0,
        "fl_deal_non_invited" => 0,
        "fl_need_fu" => 0,
        "fl_need_fu_invited" => 0,
        "fl_need_fu_non_invited" => 0,
        "fl_not_deal" => 0,
        "fl_not_deal_invited" => 0,
        "fl_not_deal_non_invited" => 0,
        "fl_sales" => 0,
        "fl_sales_invited" => 0,
        "fl_sales_non_invited" => 0,
        "fl_indent" => 0,
        "fl_indent_invited" => 0,
        "fl_indent_non_invited" => 0,
        "lf_conv_sales_all_leads" => 0,
        "lf_conv_sales_of_contacted" => 0,
        "lf_conv_sales_invited" => 0,
        "lf_conv_sales_non_invited" => 0,
      ]
    ];
    send_json($result);
  }
}
