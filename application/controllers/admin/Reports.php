<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Reports extends Admin_controller
{
    /**
     * Codeigniter Instance
     * Expenses detailed report filters use $ci
     * @var object
     */
    private $ci;

    public function __construct()
    {
        parent::__construct();
        /*if (!has_permission('reports', '', 'view')) {
            access_denied('reports');
        }*/
        $this->ci = &get_instance();
        $this->load->model('reports_model');
        $this->load->model('leads_model');
        $this->load->model('payroll_model');
        $this->load->model('teams_model');
        
    }

    /* No access on this url */
    public function index()
    {
        redirect(admin_url());
    }

    /* See knowledge base article reports*/
    public function knowledge_base_articles()
    {
        $this->load->model('knowledge_base_model');
        $data['groups'] = $this->knowledge_base_model->get_kbg();
        $data['title'] = _l('kb_reports');
        $this->load->view('admin/reports/knowledge_base_articles', $data);
    }

    /*
        public function tax_summary(){
           $this->load->model('taxes_model');
           $this->load->model('payments_model');
           $this->load->model('invoices_model');
           $data['taxes'] = $this->db->query("SELECT DISTINCT taxname,taxrate FROM tblitemstax WHERE rel_type='invoice'")->result_array();
            $this->load->view('admin/reports/tax_summary',$data);
        }*/
    /* Repoert leads conversions */
    public function leads()
    {
        $type = 'leads';
        if ($this->input->get('type')) {
            $type = $type . '_' . $this->input->get('type');
            $data['leads_staff_report'] = json_encode($this->reports_model->leads_staff_report());
        }
        $this->load->model('leads_model');
        $data['statuses'] = $this->leads_model->get_status();
        $data['leads_this_week_report'] = json_encode($this->reports_model->leads_this_week_report());
        $data['leads_sources_report'] = json_encode($this->reports_model->leads_sources_report());
        $this->load->view('admin/reports/' . $type, $data);
    }

    /* Sales reportts */
    public function sales()
    {
        $data['mysqlVersion'] = $this->db->query('SELECT VERSION() as version')->row();
        $data['sqlMode'] = $this->db->query('SELECT @@sql_mode as mode')->row();

        if (is_using_multiple_currencies() || is_using_multiple_currencies('tblcreditnotes') || is_using_multiple_currencies('tblestimates') || is_using_multiple_currencies('tblproposals')) {
            $this->load->model('currencies_model');
            $data['currencies'] = $this->currencies_model->get();
        }
        $this->load->model('invoices_model');
        $this->load->model('estimates_model');
        $this->load->model('proposals_model');
        $this->load->model('credit_notes_model');

        $data['credit_notes_statuses'] = $this->credit_notes_model->get_statuses();
        $data['invoice_statuses'] = $this->invoices_model->get_statuses();
        $data['estimate_statuses'] = $this->estimates_model->get_statuses();
        $data['payments_years'] = $this->reports_model->get_distinct_payments_years();
        $data['estimates_sale_agents'] = $this->estimates_model->get_sale_agents();

        $data['invoices_sale_agents'] = $this->invoices_model->get_sale_agents();

        $data['proposals_sale_agents'] = $this->proposals_model->get_sale_agents();
        $data['proposals_statuses'] = $this->proposals_model->get_statuses();

        $data['invoice_taxes'] = $this->distinct_taxes('invoice');
        $data['estimate_taxes'] = $this->distinct_taxes('estimate');
        $data['proposal_taxes'] = $this->distinct_taxes('proposal');
        $data['credit_note_taxes'] = $this->distinct_taxes('credit_note');


        $data['title'] = _l('sales_reports');
        $this->load->view('admin/reports/sales', $data);
    }

    /* Customer report */
    public function customers_report()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('currencies_model');
            $select = [
                get_sql_select_client_company(),
                '(SELECT COUNT(clientid) FROM tblinvoices WHERE tblinvoices.clientid = tblclients.userid AND status != 5)',
                '(SELECT SUM(subtotal) - SUM(discount_total) FROM tblinvoices WHERE tblinvoices.clientid = tblclients.userid AND status != 5)',
                '(SELECT SUM(total) FROM tblinvoices WHERE tblinvoices.clientid = tblclients.userid AND status != 5)',
            ];

            $custom_date_select = $this->get_where_report_period();
            if ($custom_date_select != '') {
                $i = 0;
                foreach ($select as $_select) {
                    if ($i !== 0) {
                        $_temp = substr($_select, 0, -1);
                        $_temp .= ' ' . $custom_date_select . ')';
                        $select[$i] = $_temp;
                    }
                    $i++;
                }
            }
            $by_currency = $this->input->post('report_currency');
            $currency = $this->currencies_model->get_base_currency();
            $currency_symbol = $this->currencies_model->get_currency_symbol($currency->id);
            if ($by_currency) {
                $i = 0;
                foreach ($select as $_select) {
                    if ($i !== 0) {
                        $_temp = substr($_select, 0, -1);
                        $_temp .= ' AND currency =' . $by_currency . ')';
                        $select[$i] = $_temp;
                    }
                    $i++;
                }
                $currency = $this->currencies_model->get($by_currency);
                $currency_symbol = $this->currencies_model->get_currency_symbol($currency->id);
            }
            $aColumns = $select;
            $sIndexColumn = 'userid';
            $sTable = 'tblclients';
            $where = [];

            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, [], $where, [
                'userid',
            ]);
            $output = $result['output'];
            $rResult = $result['rResult'];
            $x = 0;
            foreach ($rResult as $aRow) {
                $row = [];
                for ($i = 0; $i < count($aColumns); $i++) {
                    if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
                        $_data = $aRow[strafter($aColumns[$i], 'as ')];
                    } else {
                        $_data = $aRow[$aColumns[$i]];
                    }
                    if ($i == 0) {
                        $_data = '<a href="' . admin_url('clients/client/' . $aRow['userid']) . '" target="_blank">' . $aRow['company'] . '</a>';
                    } elseif ($aColumns[$i] == $select[2] || $aColumns[$i] == $select[3]) {
                        if ($_data == null) {
                            $_data = 0;
                        }
                        $_data = format_money($_data, $currency_symbol);
                    }
                    $row[] = $_data;
                }
                $output['aaData'][] = $row;
                $x++;
            }
            echo json_encode($output);
            die();
        }
    }

    public function payments_received()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('currencies_model');
            $this->load->model('payment_modes_model');
            $online_modes = $this->payment_modes_model->get_online_payment_modes(true);
            $select = [
                'tblinvoicepaymentrecords.id',
                'tblinvoicepaymentrecords.date',
                'invoiceid',
                get_sql_select_client_company(),
                'paymentmode',
                'transactionid',
                'note',
                'amount',
            ];
            $where = [
                'AND status != 5',
            ];

            $custom_date_select = $this->get_where_report_period('tblinvoicepaymentrecords.date');
            if ($custom_date_select != '') {
                array_push($where, $custom_date_select);
            }

            $by_currency = $this->input->post('report_currency');
            if ($by_currency) {
                $currency = $this->currencies_model->get($by_currency);
                array_push($where, 'AND currency=' . $by_currency);
            } else {
                $currency = $this->currencies_model->get_base_currency();
            }

            $currency_symbol = $this->currencies_model->get_currency_symbol($currency->id);

            $aColumns = $select;
            $sIndexColumn = 'id';
            $sTable = 'tblinvoicepaymentrecords';
            $join = [
                'JOIN tblinvoices ON tblinvoices.id = tblinvoicepaymentrecords.invoiceid',
                'LEFT JOIN tblclients ON tblclients.userid = tblinvoices.clientid',
                'LEFT JOIN tblinvoicepaymentsmodes ON tblinvoicepaymentsmodes.id = tblinvoicepaymentrecords.paymentmode',
            ];

            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
                'number',
                'clientid',
                'tblinvoicepaymentsmodes.name',
                'tblinvoicepaymentsmodes.id as paymentmodeid',
                'paymentmethod',
                'deleted_customer_name',
            ]);

            $output = $result['output'];
            $rResult = $result['rResult'];

            $footer_data['total_amount'] = 0;
            foreach ($rResult as $aRow) {
                $row = [];
                for ($i = 0; $i < count($aColumns); $i++) {
                    if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
                        $_data = $aRow[strafter($aColumns[$i], 'as ')];
                    } else {
                        $_data = $aRow[$aColumns[$i]];
                    }
                    if ($aColumns[$i] == 'paymentmode') {
                        $_data = $aRow['name'];
                        if (is_null($aRow['paymentmodeid'])) {
                            foreach ($online_modes as $online_mode) {
                                if ($aRow['paymentmode'] == $online_mode['id']) {
                                    $_data = $online_mode['name'];
                                }
                            }
                        }
                        if (!empty($aRow['paymentmethod'])) {
                            $_data .= ' - ' . $aRow['paymentmethod'];
                        }
                    } elseif ($aColumns[$i] == 'tblinvoicepaymentrecords.id') {
                        $_data = '<a href="' . admin_url('payments/payment/' . $_data) . '" target="_blank">' . $_data . '</a>';
                    } elseif ($aColumns[$i] == 'tblinvoicepaymentrecords.date') {
                        $_data = _d($_data);
                    } elseif ($aColumns[$i] == 'invoiceid') {
                        $_data = '<a href="' . admin_url('invoices/list_invoices/' . $aRow[$aColumns[$i]]) . '" target="_blank">' . format_invoice_number($aRow['invoiceid']) . '</a>';
                    } elseif ($i == 3) {
                        if (empty($aRow['deleted_customer_name'])) {
                            $_data = '<a href="' . admin_url('clients/client/' . $aRow['clientid']) . '" target="_blank">' . $aRow['company'] . '</a>';
                        } else {
                            $row[] = $aRow['deleted_customer_name'];
                        }

                    } elseif ($aColumns[$i] == 'amount') {
                        $footer_data['total_amount'] += $_data;
                        $_data = format_money($_data, $currency_symbol);
                    }

                    $row[] = $_data;
                }
                $output['aaData'][] = $row;
            }

            $footer_data['total_amount'] = format_money($footer_data['total_amount'], $currency_symbol);
            $output['sums'] = $footer_data;
            echo json_encode($output);
            die();
        }
    }

    public function proposals_report()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('currencies_model');
            $this->load->model('proposals_model');

            $proposalsTaxes = $this->distinct_taxes('proposal');
            $totalTaxesColumns = count($proposalsTaxes);

            $select = [
                'id',
                'subject',
                'proposal_to',
                'date',
                'open_till',
                'subtotal',
                'total',
                'total_tax',
                'discount_total',
                'adjustment',
                'status',
            ];

            $proposalsTaxesSelect = array_reverse($proposalsTaxes);

            foreach ($proposalsTaxesSelect as $key => $tax) {
                array_splice($select, 8, 0, '(
                    SELECT CASE
                    WHEN discount_percent != 0 AND discount_type = "before_tax" THEN ROUND(SUM((qty*rate/100*tblitemstax.taxrate) - (qty*rate/100*tblitemstax.taxrate * discount_percent/100)),' . get_decimal_places() . ')
                    WHEN discount_total != 0 AND discount_type = "before_tax" THEN ROUND(SUM((qty*rate/100*tblitemstax.taxrate) - (qty*rate/100*tblitemstax.taxrate * (discount_total/subtotal*100) / 100)),' . get_decimal_places() . ')
                    ELSE ROUND(SUM(qty*rate/100*tblitemstax.taxrate),' . get_decimal_places() . ')
                    END
                    FROM tblitems_in
                    INNER JOIN tblitemstax ON tblitemstax.itemid=tblitems_in.id
                    WHERE tblitems_in.rel_type="proposal" AND taxname="' . $tax['taxname'] . '" AND taxrate="' . $tax['taxrate'] . '" AND tblitems_in.rel_id=tblproposals.id) as total_tax_single_' . $key);
            }

            $where = [];
            $custom_date_select = $this->get_where_report_period();
            if ($custom_date_select != '') {
                array_push($where, $custom_date_select);
            }

            if ($this->input->post('proposal_status')) {
                $statuses = $this->input->post('proposal_status');
                $_statuses = [];
                if (is_array($statuses)) {
                    foreach ($statuses as $status) {
                        if ($status != '') {
                            array_push($_statuses, $status);
                        }
                    }
                }
                if (count($_statuses) > 0) {
                    array_push($where, 'AND status IN (' . implode(', ', $_statuses) . ')');
                }
            }

            if ($this->input->post('proposals_sale_agents')) {
                $agents = $this->input->post('proposals_sale_agents');
                $_agents = [];
                if (is_array($agents)) {
                    foreach ($agents as $agent) {
                        if ($agent != '') {
                            array_push($_agents, $agent);
                        }
                    }
                }
                if (count($_agents) > 0) {
                    array_push($where, 'AND assigned IN (' . implode(', ', $_agents) . ')');
                }
            }


            $by_currency = $this->input->post('report_currency');
            if ($by_currency) {
                $currency = $this->currencies_model->get($by_currency);
                array_push($where, 'AND currency=' . $by_currency);
            } else {
                $currency = $this->currencies_model->get_base_currency();
            }

            $currency_symbol = $this->currencies_model->get_currency_symbol($currency->id);

            $aColumns = $select;
            $sIndexColumn = 'id';
            $sTable = 'tblproposals';
            $join = [];

            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
                'rel_id',
                'rel_type',
                'discount_percent',
            ]);

            $output = $result['output'];
            $rResult = $result['rResult'];

            $footer_data = [
                'total' => 0,
                'subtotal' => 0,
                'total_tax' => 0,
                'discount_total' => 0,
                'adjustment' => 0,
            ];

            foreach ($proposalsTaxes as $key => $tax) {
                $footer_data['total_tax_single_' . $key] = 0;
            }

            foreach ($rResult as $aRow) {
                $row = [];

                $row[] = '<a href="' . admin_url('proposals/list_proposals/' . $aRow['id']) . '" target="_blank">' . format_proposal_number($aRow['id']) . '</a>';

                $row[] = '<a href="' . admin_url('proposals/list_proposals/' . $aRow['id']) . '" target="_blank">' . $aRow['subject'] . '</a>';

                if ($aRow['rel_type'] == 'lead') {
                    $row[] = '<a href="#" onclick="init_lead(' . $aRow['rel_id'] . ');return false;" target="_blank" data-toggle="tooltip" data-title="' . _l('lead') . '">' . $aRow['proposal_to'] . '</a>' . '<span class="hide">' . _l('lead') . '</span>';
                } elseif ($aRow['rel_type'] == 'customer') {
                    $row[] = '<a href="' . admin_url('clients/client/' . $aRow['rel_id']) . '" target="_blank" data-toggle="tooltip" data-title="' . _l('client') . '">' . $aRow['proposal_to'] . '</a>' . '<span class="hide">' . _l('client') . '</span>';
                } else {
                    $row[] = '';
                }

                $row[] = _d($aRow['date']);

                $row[] = _d($aRow['open_till']);

                $row[] = format_money($aRow['subtotal'], $currency_symbol);
                $footer_data['subtotal'] += $aRow['subtotal'];

                $row[] = format_money($aRow['total'], $currency_symbol);
                $footer_data['total'] += $aRow['total'];

                $row[] = format_money($aRow['total_tax'], $currency_symbol);
                $footer_data['total_tax'] += $aRow['total_tax'];

                $t = $totalTaxesColumns - 1;
                $i = 0;
                foreach ($proposalsTaxes as $tax) {
                    $row[] = format_money(($aRow['total_tax_single_' . $t] == null ? 0 : $aRow['total_tax_single_' . $t]), $currency_symbol);
                    $footer_data['total_tax_single_' . $i] += ($aRow['total_tax_single_' . $t] == null ? 0 : $aRow['total_tax_single_' . $t]);
                    $t--;
                    $i++;
                }

                $row[] = format_money($aRow['discount_total'], $currency_symbol);
                $footer_data['discount_total'] += $aRow['discount_total'];

                $row[] = format_money($aRow['adjustment'], $currency_symbol);
                $footer_data['adjustment'] += $aRow['adjustment'];

                $row[] = format_proposal_status($aRow['status']);
                $output['aaData'][] = $row;
            }

            foreach ($footer_data as $key => $total) {
                $footer_data[$key] = format_money($total, $currency_symbol);
            }

            $output['sums'] = $footer_data;
            echo json_encode($output);
            die();
        }
    }

    public function estimates_report()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('currencies_model');
            $this->load->model('estimates_model');

            $estimateTaxes = $this->distinct_taxes('estimate');
            $totalTaxesColumns = count($estimateTaxes);

            $select = [
                'number',
                get_sql_select_client_company(),
                'invoiceid',
                'YEAR(date) as year',
                'date',
                'expirydate',
                'subtotal',
                'total',
                'total_tax',
                'discount_total',
                'adjustment',
                'reference_no',
                'status',
            ];

            $estimatesTaxesSelect = array_reverse($estimateTaxes);

            foreach ($estimatesTaxesSelect as $key => $tax) {
                array_splice($select, 9, 0, '(
                    SELECT CASE
                    WHEN discount_percent != 0 AND discount_type = "before_tax" THEN ROUND(SUM((qty*rate/100*tblitemstax.taxrate) - (qty*rate/100*tblitemstax.taxrate * discount_percent/100)),' . get_decimal_places() . ')
                    WHEN discount_total != 0 AND discount_type = "before_tax" THEN ROUND(SUM((qty*rate/100*tblitemstax.taxrate) - (qty*rate/100*tblitemstax.taxrate * (discount_total/subtotal*100) / 100)),' . get_decimal_places() . ')
                    ELSE ROUND(SUM(qty*rate/100*tblitemstax.taxrate),' . get_decimal_places() . ')
                    END
                    FROM tblitems_in
                    INNER JOIN tblitemstax ON tblitemstax.itemid=tblitems_in.id
                    WHERE tblitems_in.rel_type="estimate" AND taxname="' . $tax['taxname'] . '" AND taxrate="' . $tax['taxrate'] . '" AND tblitems_in.rel_id=tblestimates.id) as total_tax_single_' . $key);
            }

            $where = [];
            $custom_date_select = $this->get_where_report_period();
            if ($custom_date_select != '') {
                array_push($where, $custom_date_select);
            }

            if ($this->input->post('estimate_status')) {
                $statuses = $this->input->post('estimate_status');
                $_statuses = [];
                if (is_array($statuses)) {
                    foreach ($statuses as $status) {
                        if ($status != '') {
                            array_push($_statuses, $status);
                        }
                    }
                }
                if (count($_statuses) > 0) {
                    array_push($where, 'AND status IN (' . implode(', ', $_statuses) . ')');
                }
            }

            if ($this->input->post('sale_agent_estimates')) {
                $agents = $this->input->post('sale_agent_estimates');
                $_agents = [];
                if (is_array($agents)) {
                    foreach ($agents as $agent) {
                        if ($agent != '') {
                            array_push($_agents, $agent);
                        }
                    }
                }
                if (count($_agents) > 0) {
                    array_push($where, 'AND sale_agent IN (' . implode(', ', $_agents) . ')');
                }
            }

            $by_currency = $this->input->post('report_currency');
            if ($by_currency) {
                $currency = $this->currencies_model->get($by_currency);
                array_push($where, 'AND currency=' . $by_currency);
            } else {
                $currency = $this->currencies_model->get_base_currency();
            }
            $currency_symbol = $this->currencies_model->get_currency_symbol($currency->id);

            $aColumns = $select;
            $sIndexColumn = 'id';
            $sTable = 'tblestimates';
            $join = [
                'LEFT JOIN tblclients ON tblclients.userid = tblestimates.clientid',
            ];

            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
                'userid',
                'clientid',
                'tblestimates.id',
                'discount_percent',
                'deleted_customer_name',
            ]);

            $output = $result['output'];
            $rResult = $result['rResult'];

            $footer_data = [
                'total' => 0,
                'subtotal' => 0,
                'total_tax' => 0,
                'discount_total' => 0,
                'adjustment' => 0,
            ];

            foreach ($estimateTaxes as $key => $tax) {
                $footer_data['total_tax_single_' . $key] = 0;
            }

            foreach ($rResult as $aRow) {
                $row = [];

                $row[] = '<a href="' . admin_url('estimates/list_estimates/' . $aRow['id']) . '" target="_blank">' . format_estimate_number($aRow['id']) . '</a>';

                if (empty($aRow['deleted_customer_name'])) {
                    $row[] = '<a href="' . admin_url('clients/client/' . $aRow['userid']) . '" target="_blank">' . $aRow['company'] . '</a>';
                } else {
                    $row[] = $aRow['deleted_customer_name'];
                }

                if ($aRow['invoiceid'] === null) {
                    $row[] = '';
                } else {
                    $row[] = '<a href="' . admin_url('invoices/list_invoices/' . $aRow['invoiceid']) . '" target="_blank">' . format_invoice_number($aRow['invoiceid']) . '</a>';
                }

                $row[] = $aRow['year'];

                $row[] = _d($aRow['date']);

                $row[] = _d($aRow['expirydate']);

                $row[] = format_money($aRow['subtotal'], $currency_symbol);
                $footer_data['subtotal'] += $aRow['subtotal'];

                $row[] = format_money($aRow['total'], $currency_symbol);
                $footer_data['total'] += $aRow['total'];

                $row[] = format_money($aRow['total_tax'], $currency_symbol);
                $footer_data['total_tax'] += $aRow['total_tax'];

                $t = $totalTaxesColumns - 1;
                $i = 0;
                foreach ($estimateTaxes as $tax) {
                    $row[] = format_money(($aRow['total_tax_single_' . $t] == null ? 0 : $aRow['total_tax_single_' . $t]), $currency_symbol);
                    $footer_data['total_tax_single_' . $i] += ($aRow['total_tax_single_' . $t] == null ? 0 : $aRow['total_tax_single_' . $t]);
                    $t--;
                    $i++;
                }

                $row[] = format_money($aRow['discount_total'], $currency_symbol);
                $footer_data['discount_total'] += $aRow['discount_total'];

                $row[] = format_money($aRow['adjustment'], $currency_symbol);
                $footer_data['adjustment'] += $aRow['adjustment'];


                $row[] = $aRow['reference_no'];

                $row[] = format_estimate_status($aRow['status']);

                $output['aaData'][] = $row;
            }
            foreach ($footer_data as $key => $total) {
                $footer_data[$key] = format_money($total, $currency_symbol);
            }
            $output['sums'] = $footer_data;
            echo json_encode($output);
            die();
        }
    }

    private function get_where_report_period($field = 'date')
    {
        $months_report = $this->input->post('report_months');
        $custom_date_select = '';
        if ($months_report != '') {
            if (is_numeric($months_report)) {
                // Last month
                if ($months_report == '1') {
                    $beginMonth = date('Y-m-01', strtotime('first day of last month'));
                    $endMonth = date('Y-m-t', strtotime('last day of last month'));
                } else {
                    $months_report = (int)$months_report;
                    $months_report--;
                    $beginMonth = date('Y-m-01', strtotime("-$months_report MONTH"));
                    $endMonth = date('Y-m-t');
                }

                $custom_date_select = 'AND (' . $field . ' BETWEEN "' . $beginMonth . '" AND "' . $endMonth . '")';
            } elseif ($months_report == 'this_month') {
                $custom_date_select = 'AND (' . $field . ' BETWEEN "' . date('Y-m-01') . '" AND "' . date('Y-m-t') . '")';
            } elseif ($months_report == 'this_year') {
                $custom_date_select = 'AND (' . $field . ' BETWEEN "' .
                    date('Y-m-d', strtotime(date('Y-01-01'))) .
                    '" AND "' .
                    date('Y-m-d', strtotime(date('Y-12-31'))) . '")';
            } elseif ($months_report == 'last_year') {
                $custom_date_select = 'AND (' . $field . ' BETWEEN "' .
                    date('Y-m-d', strtotime(date(date('Y', strtotime('last year')) . '-01-01'))) .
                    '" AND "' .
                    date('Y-m-d', strtotime(date(date('Y', strtotime('last year')) . '-12-31'))) . '")';
            } elseif ($months_report == 'custom') {
                $from_date = to_sql_date($this->input->post('report_from'));
                $to_date = to_sql_date($this->input->post('report_to'));
                if ($from_date == $to_date) {
                    $custom_date_select = 'AND ' . $field . ' = "' . $from_date . '"';
                } else {
                    $custom_date_select = 'AND (' . $field . ' BETWEEN "' . $from_date . '" AND "' . $to_date . '")';
                }
            }
        }

        return $custom_date_select;
    }

    public function items()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('currencies_model');
            $v = $this->db->query('SELECT VERSION() as version')->row();
            // 5.6 mysql version don't have the ANY_VALUE function implemented.

            if ($v && strpos($v->version, '5.7') !== false) {
                $aColumns = [
                    'ANY_VALUE(description) as description',
                    'ANY_VALUE((SUM(tblitems_in.qty))) as quantity_sold',
                    'ANY_VALUE(SUM(rate*qty)) as rate',
                    'ANY_VALUE(AVG(rate*qty)) as avg_price',
                ];
            } else {
                $aColumns = [
                    'description as description',
                    '(SUM(tblitems_in.qty)) as quantity_sold',
                    'SUM(rate*qty) as rate',
                    'AVG(rate*qty) as avg_price',
                ];
            }

            $sIndexColumn = 'id';
            $sTable = 'tblitems_in';
            $join = ['JOIN tblinvoices ON tblinvoices.id = tblitems_in.rel_id'];

            $where = ['AND rel_type="invoice"', 'AND status != 5', 'AND status=2'];

            $custom_date_select = $this->get_where_report_period();
            if ($custom_date_select != '') {
                array_push($where, $custom_date_select);
            }
            $by_currency = $this->input->post('report_currency');
            if ($by_currency) {
                $currency = $this->currencies_model->get($by_currency);
                array_push($where, 'AND currency=' . $by_currency);
            } else {
                $currency = $this->currencies_model->get_base_currency();
            }

            if ($this->input->post('sale_agent_items')) {
                $agents = $this->input->post('sale_agent_items');
                $_agents = [];
                if (is_array($agents)) {
                    foreach ($agents as $agent) {
                        if ($agent != '') {
                            array_push($_agents, $agent);
                        }
                    }
                }
                if (count($_agents) > 0) {
                    array_push($where, 'AND sale_agent IN (' . implode(', ', $_agents) . ')');
                }
            }

            $currency_symbol = $this->currencies_model->get_currency_symbol($currency->id);
            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [], 'GROUP by description');

            $output = $result['output'];
            $rResult = $result['rResult'];

            $footer_data = [
                'total_amount' => 0,
                'total_qty' => 0,
            ];

            foreach ($rResult as $aRow) {
                $row = [];

                $row[] = $aRow['description'];
                $row[] = $aRow['quantity_sold'];
                $row[] = format_money($aRow['rate'], $currency_symbol);
                $row[] = format_money($aRow['avg_price'], $currency_symbol);
                $footer_data['total_amount'] += $aRow['rate'];
                $footer_data['total_qty'] += $aRow['quantity_sold'];
                $output['aaData'][] = $row;
            }

            $footer_data['total_amount'] = format_money($footer_data['total_amount'], $currency_symbol);

            $output['sums'] = $footer_data;
            echo json_encode($output);
            die();
        }
    }

    public function credit_notes()
    {
        if ($this->input->is_ajax_request()) {
            $credit_note_taxes = $this->distinct_taxes('credit_note');
            $totalTaxesColumns = count($credit_note_taxes);

            $this->load->model('currencies_model');

            $select = [
                'number',
                'date',
                get_sql_select_client_company(),
                'reference_no',
                'subtotal',
                'total',
                'total_tax',
                'discount_total',
                'adjustment',
                '(SELECT tblcreditnotes.total - (SELECT COALESCE(SUM(amount),0) FROM tblcredits WHERE tblcredits.credit_id=tblcreditnotes.id)) as remaining_amount',
                'status',
            ];

            $where = [];

            $credit_note_taxes_select = array_reverse($credit_note_taxes);

            foreach ($credit_note_taxes_select as $key => $tax) {
                array_splice($select, 5, 0, '(
                    SELECT CASE
                    WHEN discount_percent != 0 AND discount_type = "before_tax" THEN ROUND(SUM((qty*rate/100*tblitemstax.taxrate) - (qty*rate/100*tblitemstax.taxrate * discount_percent/100)),' . get_decimal_places() . ')
                    WHEN discount_total != 0 AND discount_type = "before_tax" THEN ROUND(SUM((qty*rate/100*tblitemstax.taxrate) - (qty*rate/100*tblitemstax.taxrate * (discount_total/subtotal*100) / 100)),' . get_decimal_places() . ')
                    ELSE ROUND(SUM(qty*rate/100*tblitemstax.taxrate),' . get_decimal_places() . ')
                    END
                    FROM tblitems_in
                    INNER JOIN tblitemstax ON tblitemstax.itemid=tblitems_in.id
                    WHERE tblitems_in.rel_type="credit_note" AND taxname="' . $tax['taxname'] . '" AND taxrate="' . $tax['taxrate'] . '" AND tblitems_in.rel_id=tblcreditnotes.id) as total_tax_single_' . $key);
            }

            $custom_date_select = $this->get_where_report_period();

            if ($custom_date_select != '') {
                array_push($where, $custom_date_select);
            }

            $by_currency = $this->input->post('report_currency');

            if ($by_currency) {
                $currency = $this->currencies_model->get($by_currency);
                array_push($where, 'AND currency=' . $by_currency);
            } else {
                $currency = $this->currencies_model->get_base_currency();
            }

            if ($this->input->post('credit_note_status')) {
                $statuses = $this->input->post('credit_note_status');
                $_statuses = [];
                if (is_array($statuses)) {
                    foreach ($statuses as $status) {
                        if ($status != '') {
                            array_push($_statuses, $status);
                        }
                    }
                }
                if (count($_statuses) > 0) {
                    array_push($where, 'AND status IN (' . implode(', ', $_statuses) . ')');
                }
            }

            $aColumns = $select;
            $sIndexColumn = 'id';
            $sTable = 'tblcreditnotes';
            $join = [
                'LEFT JOIN tblclients ON tblclients.userid = tblcreditnotes.clientid',
            ];

            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
                'userid',
                'clientid',
                'tblcreditnotes.id',
                'discount_percent',
                'deleted_customer_name',
            ]);

            $output = $result['output'];
            $rResult = $result['rResult'];

            $footer_data = [
                'total' => 0,
                'subtotal' => 0,
                'total_tax' => 0,
                'discount_total' => 0,
                'adjustment' => 0,
                'remaining_amount' => 0,
            ];

            foreach ($credit_note_taxes as $key => $tax) {
                $footer_data['total_tax_single_' . $key] = 0;
            }

            foreach ($rResult as $aRow) {
                $row = [];

                $row[] = '<a href="' . admin_url('credit_notes/list_credit_notes/' . $aRow['id']) . '" target="_blank">' . format_credit_note_number($aRow['id']) . '</a>';

                $row[] = _d($aRow['date']);

                if (empty($aRow['deleted_customer_name'])) {
                    $row[] = '<a href="' . admin_url('clients/client/' . $aRow['clientid']) . '">' . $aRow['company'] . '</a>';
                } else {
                    $row[] = $aRow['deleted_customer_name'];
                }

                $row[] = '<a href="' . admin_url('clients/client/' . $aRow['userid']) . '" target="_blank">' . $aRow['company'] . '</a>';

                $row[] = $aRow['reference_no'];

                $row[] = format_money($aRow['subtotal'], $currency->symbol);
                $footer_data['subtotal'] += $aRow['subtotal'];

                $row[] = format_money($aRow['total'], $currency->symbol);
                $footer_data['total'] += $aRow['total'];

                $row[] = format_money($aRow['total_tax'], $currency->symbol);
                $footer_data['total_tax'] += $aRow['total_tax'];

                $t = $totalTaxesColumns - 1;
                $i = 0;
                foreach ($credit_note_taxes as $tax) {
                    $row[] = format_money(($aRow['total_tax_single_' . $t] == null ? 0 : $aRow['total_tax_single_' . $t]), $currency->symbol);
                    $footer_data['total_tax_single_' . $i] += ($aRow['total_tax_single_' . $t] == null ? 0 : $aRow['total_tax_single_' . $t]);
                    $t--;
                    $i++;
                }

                $row[] = format_money($aRow['discount_total'], $currency->symbol);
                $footer_data['discount_total'] += $aRow['discount_total'];

                $row[] = format_money($aRow['adjustment'], $currency->symbol);
                $footer_data['adjustment'] += $aRow['adjustment'];

                $row[] = format_money($aRow['remaining_amount'], $currency->symbol);
                $footer_data['remaining_amount'] += $aRow['remaining_amount'];

                $row[] = format_credit_note_status($aRow['status']);

                $output['aaData'][] = $row;
            }

            foreach ($footer_data as $key => $total) {
                $footer_data[$key] = format_money($total, $currency->symbol);
            }

            $output['sums'] = $footer_data;
            echo json_encode($output);
            die();
        }
    }

    public function invoices_report()
    {
        if ($this->input->is_ajax_request()) {
            $invoice_taxes = $this->distinct_taxes('invoice');
            $totalTaxesColumns = count($invoice_taxes);

            $this->load->model('currencies_model');
            $this->load->model('invoices_model');

            $select = [
                'number',
                get_sql_select_client_company(),
                'YEAR(date) as year',
                'date',
                'duedate',
                'subtotal',
                'total',
                'total_tax',
                'discount_total',
                'adjustment',
                '(SELECT COALESCE(SUM(amount),0) FROM tblcredits WHERE tblcredits.invoice_id=tblinvoices.id) as credits_applied',
                '(SELECT total - (SELECT COALESCE(SUM(amount),0) FROM tblinvoicepaymentrecords WHERE invoiceid = tblinvoices.id) - (SELECT COALESCE(SUM(amount),0) FROM tblcredits WHERE tblcredits.invoice_id=tblinvoices.id))',
                'status',
            ];

            $where = [
                'AND status != 5',
            ];

            $invoiceTaxesSelect = array_reverse($invoice_taxes);

            foreach ($invoiceTaxesSelect as $key => $tax) {
                array_splice($select, 8, 0, '(
                    SELECT CASE
                    WHEN discount_percent != 0 AND discount_type = "before_tax" THEN ROUND(SUM((qty*rate/100*tblitemstax.taxrate) - (qty*rate/100*tblitemstax.taxrate * discount_percent/100)),' . get_decimal_places() . ')
                    WHEN discount_total != 0 AND discount_type = "before_tax" THEN ROUND(SUM((qty*rate/100*tblitemstax.taxrate) - (qty*rate/100*tblitemstax.taxrate * (discount_total/subtotal*100) / 100)),' . get_decimal_places() . ')
                    ELSE ROUND(SUM(qty*rate/100*tblitemstax.taxrate),' . get_decimal_places() . ')
                    END
                    FROM tblitems_in
                    INNER JOIN tblitemstax ON tblitemstax.itemid=tblitems_in.id
                    WHERE tblitems_in.rel_type="invoice" AND taxname="' . $tax['taxname'] . '" AND taxrate="' . $tax['taxrate'] . '" AND tblitems_in.rel_id=tblinvoices.id) as total_tax_single_' . $key);
            }

            $custom_date_select = $this->get_where_report_period();
            if ($custom_date_select != '') {
                array_push($where, $custom_date_select);
            }

            if ($this->input->post('sale_agent_invoices')) {
                $agents = $this->input->post('sale_agent_invoices');
                $_agents = [];
                if (is_array($agents)) {
                    foreach ($agents as $agent) {
                        if ($agent != '') {
                            array_push($_agents, $agent);
                        }
                    }
                }
                if (count($_agents) > 0) {
                    array_push($where, 'AND sale_agent IN (' . implode(', ', $_agents) . ')');
                }
            }

            $by_currency = $this->input->post('report_currency');
            $totalPaymentsColumnIndex = (12 + $totalTaxesColumns - 1);

            if ($by_currency) {
                $_temp = substr($select[$totalPaymentsColumnIndex], 0, -2);
                $_temp .= ' AND currency =' . $by_currency . ')) as amount_open';
                $select[$totalPaymentsColumnIndex] = $_temp;

                $currency = $this->currencies_model->get($by_currency);
                array_push($where, 'AND currency=' . $by_currency);
            } else {
                $currency = $this->currencies_model->get_base_currency();
                $select[$totalPaymentsColumnIndex] = $select[$totalPaymentsColumnIndex] .= ' as amount_open';
            }

            $currency_symbol = $currency->symbol;

            if ($this->input->post('invoice_status')) {
                $statuses = $this->input->post('invoice_status');
                $_statuses = [];
                if (is_array($statuses)) {
                    foreach ($statuses as $status) {
                        if ($status != '') {
                            array_push($_statuses, $status);
                        }
                    }
                }
                if (count($_statuses) > 0) {
                    array_push($where, 'AND status IN (' . implode(', ', $_statuses) . ')');
                }
            }

            $aColumns = $select;
            $sIndexColumn = 'id';
            $sTable = 'tblinvoices';
            $join = [
                'LEFT JOIN tblclients ON tblclients.userid = tblinvoices.clientid',
            ];

            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
                'userid',
                'clientid',
                'tblinvoices.id',
                'discount_percent',
                'deleted_customer_name',
            ]);

            $output = $result['output'];
            $rResult = $result['rResult'];

            $footer_data = [
                'total' => 0,
                'subtotal' => 0,
                'total_tax' => 0,
                'discount_total' => 0,
                'adjustment' => 0,
                'applied_credits' => 0,
                'amount_open' => 0,
            ];

            foreach ($invoice_taxes as $key => $tax) {
                $footer_data['total_tax_single_' . $key] = 0;
            }

            foreach ($rResult as $aRow) {
                $row = [];

                $row[] = '<a href="' . admin_url('invoices/list_invoices/' . $aRow['id']) . '" target="_blank">' . format_invoice_number($aRow['id']) . '</a>';

                if (empty($aRow['deleted_customer_name'])) {
                    $row[] = '<a href="' . admin_url('clients/client/' . $aRow['userid']) . '" target="_blank">' . $aRow['company'] . '</a>';
                } else {
                    $row[] = $aRow['deleted_customer_name'];
                }

                $row[] = $aRow['year'];

                $row[] = _d($aRow['date']);

                $row[] = _d($aRow['duedate']);

                $row[] = format_money($aRow['subtotal'], $currency_symbol);
                $footer_data['subtotal'] += $aRow['subtotal'];

                $row[] = format_money($aRow['total'], $currency_symbol);
                $footer_data['total'] += $aRow['total'];

                $row[] = format_money($aRow['total_tax'], $currency_symbol);
                $footer_data['total_tax'] += $aRow['total_tax'];

                $t = $totalTaxesColumns - 1;
                $i = 0;
                foreach ($invoice_taxes as $tax) {
                    $row[] = format_money(($aRow['total_tax_single_' . $t] == null ? 0 : $aRow['total_tax_single_' . $t]), $currency_symbol);
                    $footer_data['total_tax_single_' . $i] += ($aRow['total_tax_single_' . $t] == null ? 0 : $aRow['total_tax_single_' . $t]);
                    $t--;
                    $i++;
                }

                $row[] = format_money($aRow['discount_total'], $currency_symbol);
                $footer_data['discount_total'] += $aRow['discount_total'];

                $row[] = format_money($aRow['adjustment'], $currency_symbol);
                $footer_data['adjustment'] += $aRow['adjustment'];

                $row[] = format_money($aRow['credits_applied'], $currency_symbol);
                $footer_data['applied_credits'] += $aRow['credits_applied'];

                $amountOpen = $aRow['amount_open'];
                $row[] = format_money($amountOpen, $currency_symbol);
                $footer_data['amount_open'] += $amountOpen;

                $row[] = format_invoice_status($aRow['status']);

                $output['aaData'][] = $row;
            }

            foreach ($footer_data as $key => $total) {
                $footer_data[$key] = format_money($total, $currency_symbol);
            }

            $output['sums'] = $footer_data;
            echo json_encode($output);
            die();
        }
    }

    public function expenses($type = 'simple_report')
    {
        $this->load->model('currencies_model');
        $data['base_currency'] = $this->currencies_model->get_base_currency();
        $data['currencies'] = $this->currencies_model->get();

        $data['title'] = _l('expenses_report');
        if ($type != 'simple_report') {
            $this->load->model('expenses_model');
            $data['categories'] = $this->expenses_model->get_category();
            $data['years'] = $this->expenses_model->get_expenses_years();

            if ($this->input->is_ajax_request()) {
                $aColumns = [
                    'category',
                    'amount',
                    'expense_name',
                    'tax',
                    'tax2',
                    '(SELECT taxrate FROM tbltaxes WHERE id=tblexpenses.tax)',
                    'amount as amount_with_tax',
                    'billable',
                    'date',
                    get_sql_select_client_company(),
                    'invoiceid',
                    'reference_no',
                    'paymentmode',
                ];
                $join = [
                    'LEFT JOIN tblclients ON tblclients.userid = tblexpenses.clientid',
                    'LEFT JOIN tblexpensescategories ON tblexpensescategories.id = tblexpenses.category',
                ];
                $where = [];
                $filter = [];
                include_once(APPPATH . 'views/admin/tables/includes/expenses_filter.php');
                if (count($filter) > 0) {
                    array_push($where, 'AND (' . prepare_dt_filter($filter) . ')');
                }

                $by_currency = $this->input->post('currency');
                if ($by_currency) {
                    $currency = $this->currencies_model->get($by_currency);
                    array_push($where, 'AND currency=' . $by_currency);
                } else {
                    $currency = $this->currencies_model->get_base_currency();
                }
                $currency_symbol = $this->currencies_model->get_currency_symbol($currency->id);

                $sIndexColumn = 'id';
                $sTable = 'tblexpenses';
                $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
                    'tblexpensescategories.name as category_name',
                    'tblexpenses.id',
                    'tblexpenses.clientid',
                    'currency',
                ]);
                $output = $result['output'];
                $rResult = $result['rResult'];
                $this->load->model('currencies_model');
                $this->load->model('payment_modes_model');

                $footer_data = [
                    'amount' => 0,
                    'total_tax' => 0,
                    'amount_with_tax' => 0,
                ];

                foreach ($rResult as $aRow) {
                    $row = [];
                    for ($i = 0; $i < count($aColumns); $i++) {
                        if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
                            $_data = $aRow[strafter($aColumns[$i], 'as ')];
                        } else {
                            $_data = $aRow[$aColumns[$i]];
                        }
                        if ($aRow['tax'] != 0) {
                            $_tax = get_tax_by_id($aRow['tax']);
                        }
                        if ($aRow['tax2'] != 0) {
                            $_tax2 = get_tax_by_id($aRow['tax2']);
                        }
                        if ($aColumns[$i] == 'category') {
                            $_data = '<a href="' . admin_url('expenses/list_expenses/' . $aRow['id']) . '" target="_blank">' . $aRow['category_name'] . '</a>';
                        } elseif ($aColumns[$i] == 'expense_name') {
                            $_data = '<a href="' . admin_url('expenses/list_expenses/' . $aRow['id']) . '" target="_blank">' . $aRow['expense_name'] . '</a>';
                        } elseif ($aColumns[$i] == 'amount' || $i == 6) {
                            $total = $_data;
                            if ($i != 6) {
                                $footer_data['amount'] += $total;
                            } else {
                                if ($aRow['tax'] != 0 && $i == 6) {
                                    $total += ($total / 100 * $_tax->taxrate);
                                }
                                if ($aRow['tax2'] != 0 && $i == 6) {
                                    $total += ($aRow['amount'] / 100 * $_tax2->taxrate);
                                }
                                $footer_data['amount_with_tax'] += $total;
                            }

                            $_data = format_money($total, $currency_symbol);
                        } elseif ($i == 9) {
                            $_data = '<a href="' . admin_url('clients/client/' . $aRow['clientid']) . '">' . $aRow['company'] . '</a>';
                        } elseif ($aColumns[$i] == 'paymentmode') {
                            $_data = '';
                            if ($aRow['paymentmode'] != '0' && !empty($aRow['paymentmode'])) {
                                $payment_mode = $this->payment_modes_model->get($aRow['paymentmode'], [], false, true);
                                if ($payment_mode) {
                                    $_data = $payment_mode->name;
                                }
                            }
                        } elseif ($aColumns[$i] == 'date') {
                            $_data = _d($_data);
                        } elseif ($aColumns[$i] == 'tax') {
                            if ($aRow['tax'] != 0) {
                                $_data = $_tax->name . ' - ' . _format_number($_tax->taxrate) . '%';
                            } else {
                                $_data = '';
                            }
                        } elseif ($aColumns[$i] == 'tax2') {
                            if ($aRow['tax2'] != 0) {
                                $_data = $_tax2->name . ' - ' . _format_number($_tax2->taxrate) . '%';
                            } else {
                                $_data = '';
                            }
                        } elseif ($i == 5) {
                            if ($aRow['tax'] != 0 || $aRow['tax2'] != 0) {
                                if ($aRow['tax'] != 0) {
                                    $total = ($total / 100 * $_tax->taxrate);
                                }
                                if ($aRow['tax2'] != 0) {
                                    $total += ($aRow['amount'] / 100 * $_tax2->taxrate);
                                }
                                $_data = format_money($total, $currency_symbol);
                                $footer_data['total_tax'] += $total;
                            } else {
                                $_data = _format_number(0);
                            }
                        } elseif ($aColumns[$i] == 'billable') {
                            if ($aRow['billable'] == 1) {
                                $_data = _l('expenses_list_billable');
                            } else {
                                $_data = _l('expense_not_billable');
                            }
                        } elseif ($aColumns[$i] == 'invoiceid') {
                            if ($_data) {
                                $_data = '<a href="' . admin_url('invoices/list_invoices/' . $_data) . '">' . format_invoice_number($_data) . '</a>';
                            } else {
                                $_data = '';
                            }
                        }
                        $row[] = $_data;
                    }
                    $output['aaData'][] = $row;
                }

                foreach ($footer_data as $key => $total) {
                    $footer_data[$key] = format_money($total, $currency_symbol);
                }

                $output['sums'] = $footer_data;
                echo json_encode($output);
                die;
            }
            $this->load->view('admin/reports/expenses_detailed', $data);
        } else {
            if (!$this->input->get('year')) {
                $data['current_year'] = date('Y');
            } else {
                $data['current_year'] = $this->input->get('year');
            }


            $data['export_not_supported'] = ($this->agent->browser() == 'Internet Explorer' || $this->agent->browser() == 'Spartan');

            $this->load->model('expenses_model');

            $data['chart_not_billable'] = json_encode($this->reports_model->get_stats_chart_data(_l('not_billable_expenses_by_categories'), [
                'billable' => 0,
            ], [
                'backgroundColor' => 'rgba(252,45,66,0.4)',
                'borderColor' => '#fc2d42',
            ], $data['current_year']));

            $data['chart_billable'] = json_encode($this->reports_model->get_stats_chart_data(_l('billable_expenses_by_categories'), [
                'billable' => 1,
            ], [
                'backgroundColor' => 'rgba(37,155,35,0.2)',
                'borderColor' => '#84c529',
            ], $data['current_year']));

            $data['expense_years'] = $this->expenses_model->get_expenses_years();

            if (count($data['expense_years']) > 0) {
                // Perhaps no expenses in new year?
                if (!in_array_multidimensional($data['expense_years'], 'year', date('Y'))) {
                    array_unshift($data['expense_years'], ['year' => date('Y')]);
                }
            }

            $data['categories'] = $this->expenses_model->get_category();

            $this->load->view('admin/reports/expenses', $data);
        }
    }

    public function expenses_vs_income($year = '')
    {
        $_expenses_years = [];
        $_years = [];
        $this->load->model('expenses_model');
        $expenses_years = $this->expenses_model->get_expenses_years();
        $payments_years = $this->reports_model->get_distinct_payments_years();

        foreach ($expenses_years as $y) {
            array_push($_years, $y['year']);
        }
        foreach ($payments_years as $y) {
            array_push($_years, $y['year']);
        }

        $_years = array_map('unserialize', array_unique(array_map('serialize', $_years)));

        if (!in_array(date('Y'), $_years)) {
            $_years[] = date('Y');
        }

        rsort($_years, SORT_NUMERIC);
        $data['report_year'] = $year == '' ? date('Y') : $year;

        $data['years'] = $_years;
        $data['chart_expenses_vs_income_values'] = json_encode($this->reports_model->get_expenses_vs_income_report($year));
        $data['title'] = _l('als_expenses_vs_income');
        $this->load->view('admin/reports/expenses_vs_income', $data);
    }

    /* Total income report / ajax chart*/
    public function total_income_report()
    {
        echo json_encode($this->reports_model->total_income_report());
    }

    public function report_by_payment_modes()
    {
        echo json_encode($this->reports_model->report_by_payment_modes());
    }

    public function report_by_customer_groups()
    {
        echo json_encode($this->reports_model->report_by_customer_groups());
    }

    /* Leads conversion monthly report / ajax chart*/
    public function leads_monthly_report($month)
    {
        echo json_encode($this->reports_model->leads_monthly_report($month));
    }

    private function distinct_taxes($rel_type)
    {
        return $this->db->query("SELECT DISTINCT taxname,taxrate FROM tblitemstax WHERE rel_type='" . $rel_type . "' ORDER BY taxname ASC")->result_array();
    }

    function daily_work_report()
    {
        $data['title'] = 'Daily Work Report';
        $this->load->view('admin/reports/daily_work_report', $data);
    }

    function task_type()
    {
        $task_type = $this->input->post('task_type');
        //  echo $catid;
        if ($this->input->post('task_type')) {
            echo $this->reports_model->fetch_task_category($task_type);
        }
    }

    function task_category()
    {
        $task_category = $this->input->post('task_category');
        //  echo $catid;
        if ($this->input->post('task_category')) {
            echo $this->reports_model->task_category_fiels($task_category);
        }
    }

    function save_daily_report()
    {
        // print_r($this->input->post());
        $task_type = $this->input->post('task_type');
        switch ($task_type) {
            case "Assignment":
                {
                    $staff_id = $this->input->post('staff_id');
                    $date = $this->input->post('date');
                    $duration = $this->input->post('ass_duration');
                    $ass_desc = $this->input->post('ass_desc');
                    $remark = $this->input->post('remark');
                    $data = array('task_type' => $task_type, 'staff_id' => $staff_id, 'date' => $date, 'ass_desc' => $ass_desc, 'duration' => $duration, 'remark' => $remark);
                    
                    $this->db->insert('tblworkreport', $data);
                    //echo $this->db->last_query();exit;
                    set_alert('success', "Submitted  Successfully");
                    redirect($_SERVER['HTTP_REFERER']);
                }

            case "Personal_Meeting":
                {
                    $staff_id = $this->input->post('staff_id');
                    $date = $this->input->post('date');
                    $task_category = $this->input->post('task_category');
                    $company_name = $this->input->post('company_name');
                    $person_name = $this->input->post('person_name');
                    $address = $this->input->post('address');
                    $designation = $this->input->post('designation');
                    $mobile_number = $this->input->post('mobile_number');
                    $duration = $this->input->post('duration');
                    $remark = $this->input->post('remark');
                    $refernece_taken = $this->input->post('refernece_taken');
                    $next_fudate_date = $this->input->post('next_fudate');
                    $categorisation = $this->input->post('categorisation');

                    if ($refernece_taken == null) {
                        $refernece_taken = "No";
                    } 
                    else 
                    {
                        $ref_name = $this->input->post('reference_name');
                        $ref_phone = $this->input->post('reference_number');
                        $count = count($ref_name);
                        $refernece_taken = $count;
                        for($i = 0; $i<$count; $i++){
                        $entries[] = array(
                        'name'=>$ref_name[$i],
                        'number'=>$ref_phone[$i],
                        'taken_by'=>$staff_id,
                        );
                        }
                        
                        //print_r($entries);
                        $this->db->insert_batch('tblreference', $entries);
                    }
                    
      
                    if ($task_category == "Lead") {
                        $this->db->select('id');
                        $this->db->where('phonenumber', $mobile_number);
                        $data = $this->db->get('tblleads')->row();
                        $lead_id = $data->id;

                        /*print_r($data);
                        
                        exit;*/
                        if ($categorisation == "Converted") {
                            $categorisation = "3";
                        }

                        $data1 = array('status' => $categorisation);
                        $this->db->where('lead_id', $lead_id);
                        $this->db->update('tblmeeting_scheduled', $data1);

                        $data2 = array('meet_lead_id' => $lead_id, 'converted_by' => $staff_id);
                        $this->db->insert('tblbusiness', $data2);
                    }

                    if ($categorisation == "3") {
                            $categorisation = "Converted";
                        }
                    $this->db->select('id');
                    $this->db->where('mobile_number', $mobile_number);
                    $query = $this->db->get('tblworkreport');
                    $count = $query->num_rows();
                    
                    $data = array('staff_id' => $staff_id, 'date' => $date, 'task_type' => $task_type, 'task_category' => $task_category, 'company_name' => $company_name, 'person_name' => $person_name, 'designation' => $designation, 'mobile_number' => $mobile_number, 'address' => $address, 'duration' => $duration, 'remark' => $remark, 'refernece_taken' => $refernece_taken, 'next_fu_date' => $next_fudate_date, 'categorisation' => $categorisation);
                        // echo "insert";
                        // print_r($data);
                        // exit;

                        $this->db->insert('tblworkreport', $data);
                        set_alert('success', "Submitted  Successfully");
                        redirect($_SERVER['HTTP_REFERER']);

                }

            case "Calling":
                {
                    $staff_id = $this->input->post('staff_id');
                    $date = $this->input->post('date');
                    $task_category = $this->input->post('task_category');
                    $company_name = $this->input->post('company_name');
                    $person_name = $this->input->post('person_name');
                    $designation = $this->input->post('designation');
                    $address = $this->input->post('address');
                    $mobile_number = $this->input->post('mobile_number');
                    $duration = $this->input->post('duration');
                    $remark = $this->input->post('remark');
                    $refernece_taken = $this->input->post('refernece_taken');
                    $next_fudate_date = $this->input->post('next_fudate');
                    $categorisation = $this->input->post('categorisation');

                    if ($refernece_taken == null) {
                        $refernece_taken = "No";
                    } 
                    else 
                    {
                        $ref_name = $this->input->post('reference_name');
                        $ref_phone = $this->input->post('reference_number');
                        $count = count($ref_name);
                        $refernece_taken = $count;
                        for($i = 0; $i<$count; $i++){
                        $entries[] = array(
                        'name'=>$ref_name[$i],
                        'number'=>$ref_phone[$i],
                        'taken_by'=>$staff_id,
                        );
                        }
                        $this->db->insert_batch('tblreference', $entries);
                    }

                    $this->db->select('id');
                    $this->db->where('mobile_number', $mobile_number);
                    $query = $this->db->get('tblworkreport');
                    $count = $query->num_rows();
                    
                    $data = array('staff_id' => $staff_id, 'date' => $date, 'task_type' => $task_type, 'task_category' => $task_category, 'company_name' => $company_name, 'person_name' => $person_name, 'designation' => $designation, 'mobile_number' => $mobile_number, 'address' => $address , 'duration' => $duration, 'remark' => $remark, 'refernece_taken' => $refernece_taken, 'next_fu_date' => $next_fudate_date, 'categorisation' => $categorisation);
                        $this->db->insert('tblworkreport', $data);
                        set_alert('success', "Submitted  Successfully");
                        redirect($_SERVER['HTTP_REFERER']);
                        
                    /*if ($count > 0) {
                        $data = array('staff_id' => $staff_id, 'date' => $date, 'task_type' => $task_type, 'task_category' => $task_category, 'company_name' => $company_name, 'person_name' => $person_name, 'designation' => $designation, 'mobile_number' => $mobile_number, 'duration' => $duration, 'remark' => $remark, 'refernece_taken' => $refernece_taken, 'next_fu_date' => $next_fudate_date, 'categorisation' => $categorisation);
                        $this->db->update('tblworkreport', $data);
                        set_alert('success', "Submitted  Successfully");
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $data = array('staff_id' => $staff_id, 'date' => $date, 'task_type' => $task_type, 'task_category' => $task_category, 'company_name' => $company_name, 'person_name' => $person_name, 'designation' => $designation, 'mobile_number' => $mobile_number, 'duration' => $duration, 'remark' => $remark, 'refernece_taken' => $refernece_taken, 'next_fu_date' => $next_fudate_date);
                        $this->db->insert('tblworkreport', $data);
                        set_alert('success', "Submitted  Successfully");
                        redirect($_SERVER['HTTP_REFERER']);

                    }*/

                }
        }
    //print_r($query);
    }
    
     function view_work_report2()
    {
        $this->load->helper('url');
          $this->load->library("pagination");
        
        $configx = array();
        $configx["base_url"] = base_url()."admin/leads/business_report";
        $configx["total_rows"] = 2000;
        $configx["per_page"] = 30;
        //echo $configx["uri_segment"] = 3;
        //echo $this->uri->segment(4);
        //echo "----------------";
        
         $limit_per_page=30;
        $start_index = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $pagination = $this->pagination->create_links();
        
        $this->pagination->initialize($configx);
         $start = $this->input->get('start');
        $end = $this->input->get('end');
        $data['start'] = $start;
        $data['end'] = $end;
        $data['show_date'] = (!empty($start) && !empty($end)) ? date('d, M, Y', strtotime($start)) . ' To ' . date('d, M, Y', strtotime($end)) : "Today";
        $data['title'] = "Work Report";
        $data['bodyclass'] = 'hide-sidebar';
        
        $data['work_report'] = "";
      
       $data['get_staff'] = $this->reports_model->get_staff();
        $data['get_task_type'] = $this->reports_model->get_task_type();
        //print_r($data['get_staff']);


        $this->load->view('admin/reports/view_work_report', $data);
    }
    
    function view_work_report()
    {
        $this->load->helper('url');
        //   $this->load->library("pagination");
        
        // $configx = array();
        // $configx["base_url"] = base_url()."admin/leads/business_report";
        // $configx["total_rows"] = 2000;
        // $configx["per_page"] = 30;
        //echo $configx["uri_segment"] = 3;
        //echo $this->uri->segment(4);
        //echo "----------------";
        
        //  $limit_per_page=30;
        // $start_index = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        // $pagination = $this->pagination->create_links();
        
        // $this->pagination->initialize($configx);
        //  $start = $this->input->get('start');
        // $end = $this->input->get('end');
        // $data['start'] = $start;
        // $data['end'] = $end;
        
        $leads = $this->reports_model->filter_by_task_type();
        //print_r($leads);die;
        
        $data['show_date'] = (!empty($start) && !empty($end)) ? date('d, M, Y', strtotime($start)) . ' To ' . date('d, M, Y', strtotime($end)) : "Today";
        $data['title'] = "Work Report";
        $data['bodyclass'] = 'hide-sidebar';
        
        $data['work_report'] = "";
      
       $data['get_staff'] = $this->reports_model->get_staff();
        $data['get_task_type'] = $this->reports_model->get_task_type();
        //print_r($data['get_staff']);


        $this->load->view('admin/reports/view_work_report1', $data);
    }


    function view_work_report_old()
    {
        
    
    
        $start = $this->input->get('start');
        $end = $this->input->get('end');
        $data['start'] = $start;
        $data['end'] = $end;
        $data['show_date'] = (!empty($start) && !empty($end)) ? date('d, M, Y', strtotime($start)) . ' To ' . date('d, M, Y', strtotime($end)) : "Today";
        $data['title'] = "Work Report";
        $data['bodyclass'] = 'hide-sidebar';
        
        
        

        
        if(is_admin() || is_sub_admin())
        {
        $this->db->select('tblworkreport.*, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  staffname');
            $this->db->join('tblstaff', 'tblworkreport.staff_id= tblstaff.staffid');
        $data['work_report'] = $this->db->get('tblworkreport')->result();
        }
        
        elseif(herapermission())
        {
            $arr=herapermission();
            $useraid = $this->session->userdata('staff_user_id');
             
            
            
            $this->db->select('tblworkreport.*, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  staffname');
            $staffids = array($arr);
            //$this->db->where_in('staff_id', $staffids);
            $this->db->where("staff_id IN (".$arr.")",NULL, false);
            $this->db->or_where_in('staff_id', $useraid);
            $this->db->join('tblstaff', 'tblworkreport.staff_id= tblstaff.staffid');
            
            
            $data['work_report'] = $this->db->get('tblworkreport')->result();
            
             
            
        
        }
       
       
       
        $data['get_staff'] = $this->reports_model->get_staff();
        $data['get_task_type'] = $this->reports_model->get_task_type();
        //print_r($data['get_staff']);


        $this->load->view('admin/reports/view_work_report', $data);
    }

    private function printLeadData($leads)
    {
          if (!empty($leads)) { ?>
                        <table>
                               <thead>
                               <tr>
                                   <th><?php echo _l('id'); ?></th>
                                   <th class="bold"> Submitted By</th>
                                   <th class="bold"> Task Type</th>
                                   <th class="bold"> Category</th>
                                   <th class="bold">Company Name</th>
                                   <th class="bold">NAME OF THE PERSON</th>
                                   <th class="bold">Designation</th>
                                   <th class="bold">Mobile No.</th>
                                   <th class="bold">Duration</th>
                                   <th class="bold">Remarks</th>
                                   <th class="bold">Working date</th>
                                   <th class="bold">Next FU date</th>
                                   <th class="bold">Reference taken</th>
                                   <th class="bold">Catagorisation</th>
                               </tr>
                               </thead>
                               <tbody class="">
                                   <?php  foreach ($leads as $work_rep) { ?>
                                   <tr>
                                       <td><?= @++$i; ?></td>
                                       <td><?= $work_rep->staffname; ?></td>
                                       <td><?if($work_rep->task_type == "Personal_Meeting") { echo "Meeting"; }
                                       else { echo $work_rep->task_type; }
                                       ?></td>
                                       <td><?php if($work_rep->task_type == "Assignment") { echo $work_rep->ass_desc; }  else { echo $work_rep->task_category; } ?></td>
                                       <td><?= $work_rep->company_name; ?></td>
                                       <td><?= $work_rep->person_name; ?></td>
                                       <td><?= $work_rep->designation; ?></td>
                                       <td><?= $work_rep->mobile_number; ?></td>
                                       <td><?= $work_rep->duration; ?></td>
                                       <td><?= $work_rep->remark; ?></td>
                                       <td> <?php if($work_rep->task_type == "Assignment") { echo $work_rep->date; }  else { echo $work_rep->date; } ?></td>
                                       <td><?= $work_rep->next_fu_date; ?></td>
                                       <td><?= $work_rep->refernece_taken; ?></td>
                                       <td>
                                           <?php if($work_rep->categorisation == "3") { echo "Converted"; }  else { echo $work_rep->categorisation; } ?></td>
                                       
                                   </tr>
                                <script>   $(document).ready( function () {
    $('#mytable').DataTable();
} );</script>
              
                               <?php } ?>
                               </tbody>
                        </table>
                        <?php
                        } else {
                            echo "No Work Report Found";
                        } 
    }
//

    public function filter_by_task_type1($id = '')
    {
        $leads = $this->reports_model->filter_by_task_type1();
        // print_r($leads);die;
        $totalCount = $this->reports_model->work_report_get_total();
        // print_r($totalCount);die;
        $json_data = array(
            "draw"            => intval( $_POST['draw'] ),   
            "recordsTotal"    => intval( $totalCount ),  
            "recordsFiltered" => intval($totalCount),
            "aaData"            => $leads   // total data array
            );
            echo json_encode($json_data);
        // $this->printLeadData($leads);
    }
    
    public function filter_by_task_type($id = '')
    {

        $leads = $this->reports_model->filter_by_task_type();
        //print_r($this->db->last_query());
        $this->printLeadData($leads);
    }
    
    public function filter_reference_data($id = '')
    {

        $leads = $this->reports_model->filter_reference_data();
       // print_r($this->db->last_query());
        $this->printrefernceData($leads);
    }

    function task_type_lead()
    {
        $task_type = $this->input->post('task_category');
        //  echo $catid;
        if ($this->input->post('task_category')) {
            echo $this->reports_model->task_type_lead($task_type);
        }
    }

    function member_tracker()
    {
        $data['title'] = "Member Tracker";
        $data['bodyclass'] = 'hide-sidebar';
        
        
        $data['rmconverted'] = $this->reports_model->rmconverted();
                
        $data['work_report'] = $this->reports_model->business_report();
        
        //$qqq = $this->db->last_query(); 
        $this->load->view('admin/reports/member_tracker', $data);
    }
    
    function fd_tracker()
    {
        $data['title'] = "FD Tracker";
        $data['bodyclass'] = 'hide-sidebar';
        
        
        
        $data['work_report'] = $this->reports_model->business_report_fd();
        //echo $this->db->last_query();exit;
        //print_r($data['work_report']);exit;
        /* $userid = $this->session->userdata('staff_user_id');
        if($userid == 31){
            $data['rmconverted'] = $this->reports_model->working_person($userid, $return = "result");
        }else{
            $data['rmconverted'] = $this->reports_model->rmconverted_fd();
        } */
        $data['rmconverted'] = $this->reports_model->rmconverted_fd();
        //echo $this->db->last_query();exit;
        //print_r($data['rmconverted']);exit;
        //$qqq = $this->db->last_query();
       // print_r($qqq);
        $this->load->view('admin/reports/fd_tracker', $data);
    }
    
    function reference_taken()
    {
        $data['title'] = "Reference Taken";
        $data['rmconverted'] = $this->reports_model->rmrefconverted();
        $data['reference_report'] = $this->reports_model->reference_report();

        $this->load->view('admin/reports/reference_taken', $data);
    }
    
    function update_membership_papers()
    {
        $checkid = $this->input->get('checkid');
        $member_id = $this->input->get('member_id');
        $value = $this->input->get('value');
        
        switch($checkid)
        {
            case "working_paper":
                $data= array('working_paper' => $value);
                $this->db->where('id', $member_id);
                $this->db->update('tblbusiness', $data);
               
                break;
            
            case "goal_sheet":
                $data= array('goal_sheet' => $value);
                $this->db->where('id', $member_id);
                $this->db->update('tblbusiness', $data);
                break;
                
            case "existing_port":
                $data= array('existing_port' => $value);
                $this->db->where('id', $member_id);
                $this->db->update('tblbusiness', $data);
                break;
            case "investment":
                $data= array('investment' => $value);
                $this->db->where('id', $member_id);
                $this->db->update('tblbusiness', $data);
                break;
        }
        
        
    }
    
    function update_membership_remark()
    {
        $member_id = $this->input->get('member_id');
        $value = $this->input->get('value');
        $data= array('remark' => $value);
        $this->db->where('id', $member_id);
        $this->db->update('tblbusiness', $data);
        
    }
    
     function update_membership_exp_investment()
    {
        $member_id = $this->input->get('member_id');
        $value = $this->input->get('value');
        $data= array('exp_investment' => $value);
        $this->db->where('id', $member_id);
        $this->db->update('tblbusiness', $data);
    }
    
    function update_fd_tracker()
    {
        $checkfield = $this->input->get('checkid');
        $member_id = $this->input->get('member_id');
        $value = $this->input->get('value');
        
        switch ($checkfield)
        {
            case "maturity_amount":
                $data= array('maturity_amount' => $value);
                break;
            
            case "date_maturity":
                $data= array('date_maturity' => $value);
                break;
                
            case "transaction_date":
                $data= array('real_transaction_date' => $value);
                break;
                
            case "tenure":
                $data= array('tenure' => $value);
                break;
        }
        
        
        $this->db->where('id', $member_id);
        $this->db->update('tblbusiness', $data);
    }
    
   
    
    function custom_member_filter()
    {
        $leads = $this->reports_model->get_membership_filter();
        //$qq = $this->db->last_query();
      //  print_r($qq);
        $this->printBussinessData($leads); 
    }
    
    function custom_fd_filter()
    {
        $leads = $this->reports_model->get_fd_filter();
        $qq = $this->db->last_query();
      //  print_r($qq);
        $this->printBussinessDatafd($leads); 
    }
    
    
    
    function printBussinessDatafd($leads)
    {
         if (!empty($leads)) { ?>
         <table class="table dt-table scroll-responsive">
                            <thead>
                            <tr>
                                <th><?php echo _l('id'); ?></th>
                                <th class="bold"> Investor name</th>
                                <th class="bold"> Transaction Date</th>
                                <th class="bold">Category</th>
                                <th class="bold">Company</th>
                                <th class="bold">Tenure</th>
                                <th class="bold">Transaction Amount</th>
                                <th class="bold">Date of Maturity</th>
                                <th class="bold">Maturity Amount</th>
                            </tr>
                            </thead>
                            <tbody >
                            <?php foreach ($leads as $work_rep) { ?>
                                <tr>
                                    <td><?= @++$i; ?></td>
                                    <td><?= $work_rep->investor_name; ?></td>
                                    
                                     <td><span style="display:none"><?php if($work_rep->real_transaction_date == null){ echo $work_rep->transaction_date; } else { echo $work_rep->real_transaction_date; }  ?> </span><input <?php if (($GLOBALS['current_user']->admin == "1") || ($GLOBALS['current_user']->admin == "2")) {
                                                    } else {
                                                        echo "disabled";
                                                    } ?> type="text"  data-member_id="<?= $work_rep->id; ?>" id="transaction_date" name="transaction_date" class="form-control datepicker" value="<?php if($work_rep->real_transaction_date == null){ echo $work_rep->transaction_date; } else { echo $work_rep->real_transaction_date; }  ?>"></td>
                                    
                                    
                                    <td><?= $work_rep->product_name; ?></td>
                                    <td><?= $work_rep->company_name; ?></td>
                                    <td>
                                        <span style="display:none"> <?= $work_rep->tenure; ?> </span>
                                    
                                    <input <?php if (($GLOBALS['current_user']->admin == "1") || ($GLOBALS['current_user']->admin == "2")) {
                                                    } else {
                                                        echo "disabled";
                                                    } ?> type="text"  data-member_id="<?= $work_rep->id; ?>" id="tenure" name="tenure" class="form-control" value="<?= $work_rep->tenure; ?>">
                                    
                                    
                                    </td>
                                    <td><?= $work_rep->transaction_amount; ?></td>
                                     <td>
                                     <span style="display:none"> <?= $work_rep->date_maturity; ?> </span>
                                     <input <?php if (($GLOBALS['current_user']->admin == "1") || ($GLOBALS['current_user']->admin == "2")) {
                                                    } else {
                                                        echo "disabled";
                                                    } ?> type="text"  data-member_id="<?= $work_rep->id; ?>" id="date_maturity" name="date_maturity" class="form-control datepicker" value="<?= $work_rep->date_maturity; ?>"></td>
                                    <td>
                                    
                                     <span style="display:none"> <?= $work_rep->maturity_amount; ?> </span>
                                    <input <?php if (($GLOBALS['current_user']->admin == "1") || ($GLOBALS['current_user']->admin == "2")) {
                                                    } else {
                                                        echo "disabled";
                                                    } ?> type="text"  data-member_id="<?= $work_rep->id; ?>" id="maturity_amount" name="maturity_amount" class="form-control" value="<?= $work_rep->maturity_amount; ?>">
                                        
                                        </td>
                                   

                                </tr>
                                <script src="https://bfccapital.com/crm/assets/js/main.js?v=2.1.1"></script>
                                 
                                <script>
  $(document).ready(function(){
        $("input").change(function(){
                const  checkid = $(this).attr("id");
                member_id = $(this).data('member_id');
                value = $(this).val(); 
                (checkid);
                
                
                url = "<?= base_url('admin/reports/update_fd_tracker') ?>";
        $.get(url, {
                checkid: checkid,
                value: value,
                member_id:member_id
            } ,
            
           
            function (res) {
                $('.lightboxOverlay').html(res);
            })
            
            });
    });  
</script>
                            <?php } ?>
                            </tbody>
                        </table>
                       

                        <?php
                        } else {
                            echo "No FD Report Found";
                        } 
    }
    
    
    function printBussinessData($leads)
    { 
         if (!empty($leads)) { ?>
         <table class="table dt-table scroll-responsive">
                            <thead>
                            <tr>
                                <th><?php echo _l('id'); ?></th>
                                <th class="bold"> Members's Name</th>
                                <th class="bold"> Membership Acquisition Date</th>
                                <th class="bold">Category</th>
                                <th class="bold">Working Papers</th>
                                <th class="bold">Goal Sheet</th>
                                <th class="bold">Exsisting Portfolio Review</th>
                                <th class="bold">Investment</th>
                                <th class="bold">Remarks</th>
                                <th class="bold">Expected Date of investments</th>
                                <th class="bold">Converted By</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $converted_by = $this->session->userdata('staff_user_id');
                            foreach ($leads as $work_rep) { ?>
                                <tr>
                                    <td><?= @++$i; ?></td>
                                    <td><?= $work_rep->investor_name; ?></td>
                                    <td><?= $work_rep->transaction_date; ?></td>
                                    <td><?= $work_rep->product_name; ?></td>
                                    <td><p style="display:none"><? if ($work_rep->working_paper == 1) {
                                            echo "Yes";
                                        } else { echo "No"; } ?> </p> <input <? if ($work_rep->converted_by != $converted_by) {
                                            echo "disabled";
                                        } ?> type="checkbox"  data-member_id="<?= $work_rep->id; ?>" class="form-control" name="working_paper"
                                               id="working_paper" value="" <? if ($work_rep->working_paper == 1) {
                                            echo "checked";
                                        } ?> /></td>
                                    <td>
                                    <p style="display:none"><? if ($work_rep->goal_sheet == 1) {
                                            echo "Yes";
                                        } else { echo "No"; } ?> </p>
                                    <input <? if ($work_rep->converted_by != $converted_by) {
                                            echo "disabled";
                                        } ?> type="checkbox" data-member_id="<?= $work_rep->id; ?>" class="form-control" name="goal_sheet" id="goal_sheet"
                                               value="" <? if ($work_rep->goal_sheet == 1) {
                                            echo "checked";
                                        } ?>/></td>
                                    <td>
                                    <p style="display:none"><? if ($work_rep->existing_port == 1) {
                                            echo "Yes";
                                        } else { echo "No"; } ?> </p>
                                    <input <? if ($work_rep->converted_by != $converted_by) {
                                            echo "disabled";
                                        } ?> type="checkbox" data-member_id="<?= $work_rep->id; ?>" class="form-control" name="existing_port"
                                               id="existing_port" value="" <? if ($work_rep->existing_port == 1) {
                                            echo "checked";
                                        } ?>/></td>
                                    <td>
                                    <p style="display:none"><? if ($work_rep->investment == 1) {
                                            echo "Yes";
                                        } else { echo "No"; } ?> </p>
                                        <input <? if ($work_rep->converted_by != $converted_by) {
                                            echo "disabled";
                                        } ?> type="checkbox" data-member_id="<?= $work_rep->id; ?>" class="form-control" value=""  name="investment" id="investment"
                                               <? if ($work_rep->investment == 1) {
                                            echo "checked";
                                        } ?>/></td>
                                    <td>
                                        <? if ($work_rep->converted_by != $converted_by) {
                                             $condition = "disabled";
                                        } ?>
                                        <? $general4 = $work_rep->remark; ?>
                                        <?= form_textarea('remark', $general4, "class = 'form_control'  id='remark' data-member_id='$work_rep->id' $condition") ?>
                                    </td>
                                    <td>
                                         <input <? if ($work_rep->converted_by != $converted_by) {
                                            echo "disabled";
                                        } ?> type="text"  data-member_id="<?= $work_rep->id; ?>" id="exp_investment" name="exp_investment" class="form-control datepicker" value="<?= $work_rep->exp_investment; ?>"></td>

                                     <td><?= $work_rep->staff_res; ?></td>
                                </tr>
                                <script src="https://bfccapital.com/crm/assets/js/main.js?v=2.1.1"></script>
                                 <script type="text/javascript">
    $(document).ready(function(){
        $("input[type='checkbox']").click(function(){
            if($(this).prop("checked") == true){
                const checkid = $(this).attr("id");
                member_id = $(this).data('member_id');
                value = "1";
                //alert("Checkbox is checked.");
                url = "<?= base_url('admin/reports/update_membership_papers') ?>";
        $.get(url, {
                checkid: checkid,
                value: value,
                member_id:member_id
            },
            function (res) {
                $('.lightboxOverlay').html(res);
            })
            }
            else if($(this).prop("checked") == false){
                const checkid = $(this).attr("id");
                member_id = $(this).data('member_id');
                value = "0";
               // alert("Checkbox is unchecked.");
                url = "<?= base_url('admin/reports/update_membership_papers') ?>";
        $.get(url, {
                checkid: checkid,
                value: value,
                member_id:member_id
            },
            function (res) {
                $('.lightboxOverlay').html(res);
            })
            
            }
        });
    });
</script>


<script>
  $(document).ready(function(){
        $("textarea").blur(function(){
                const  checkid = $(this).attr("id");
                member_id = $(this).data('member_id');
                value = $(this).val(); 
                //alert(value);
                
                url = "<?= base_url('admin/reports/update_membership_remark') ?>";
        $.get(url, {
                value: value,
                member_id:member_id
            },
            function (res) {
                $('.lightboxOverlay').html(res);
            })
            
            });
    });  
</script>
<script>
  $(document).ready(function(){
        $("input[type='text']").blur(function(){
                const checkid = $(this).attr("id");
                member_id = $(this).data('member_id');
                value = $(this).val(); 
                
                url = "<?= base_url('admin/reports/update_membership_exp_investment') ?>";
        $.get(url, {
            
                value: value,
                member_id:member_id
            },
            function (res) {
                $('.lightboxOverlay').html(res);
            })
            
             });
    });  
</script>
                            <?php } ?>
                            </tbody>
                        </table>
                       

                        <?php
                        } else {
                            echo "No Membership Report Found";
                        } 
}
    
    
    function printBussinessDatainsurance($leads)
    {
         if (!empty($leads)) { ?>
         <table class="table dt-table scroll-responsive">
                            <thead>
                            <tr>
                                <th><?php echo _l('id'); ?></th>
                                <th class="bold">Investor name</th>
                                <th class="bold">Transaction Date</th>
                                <th class="bold">Product Type</th>
                                <th class="bold">Company</th>
                                <th class="bold">Scheme</th>
                                <th class="bold">Transaction Type</th>
                                <th class="bold">Policy Number</th>
                                <th class="bold">Sum Assured</th>
                                <th class="bold">Transaction Amount</th>
                                <th class="bold">Renewal Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($leads as $work_rep) { ?>
                                <tr>
                                    <td><?= @++$i; ?></td>
                                    <td><?= $work_rep->investor_name; ?></td>
                                    <td>
                                        <p style="display:none"><? if ($work_rep->real_transaction_date != null) {
                                            echo $work_rep->real_transaction_date;
                                        }
                                        else 
                                        {
                                            echo $work_rep->transaction_date;
                                        }
                                        
                                        ?></p>
                                    <input <? if (!is_sub_admin()) {
                                            echo "disabled";
                                        } ?> type="text"  data-member_id="<?= $work_rep->id; ?>" id="real_transaction_date" name="real_transaction_date" class="form-control datepicker" value=" <? if ($work_rep->real_transaction_date != null) {
                                            echo $work_rep->real_transaction_date;
                                        }
                                        else 
                                        {
                                            echo $work_rep->transaction_date;
                                        }
                                        
                                        ?>">
                                    
                                    <td><?= $work_rep->pro_type; ?></td>
                                    <td><?= $work_rep->company_name; ?></td>
                                    <td><?= $work_rep->product_name; ?></td>
                                    <td><?= $work_rep->transaction_type; ?></td>
                                    <td>
                                     <p style="display:none"><?= $work_rep->policy_number; ?></p>
                                    <input <? if (!is_sub_admin()) {
                                            echo "disabled";
                                        } ?> type="text"  data-member_id="<?= $work_rep->id; ?>" id="policy_number" name="policy_number" class="form-control " value="<?= $work_rep->policy_number; ?>"></td>
                                    
                                    <td>
                                         <p style="display:none"><?= $work_rep->sum_assured; ?></p>
                                        <input <? if (!is_sub_admin()) {
                                            echo "disabled";
                                        } ?> type="text"  data-member_id="<?= $work_rep->id; ?>" id="sum_assured" name="sum_assured" class="form-control" value="<?= $work_rep->sum_assured; ?>">
                                    </td>
                                    <td>
                                         <?= $work_rep->transaction_amount; ?></td>
                                        
                                        <td>
                                            <p style="display:none"><?= $work_rep->renewal_date; ?></p>
                                         <input <? if (!is_sub_admin()) {
                                            echo "disabled";
                                        } ?> type="text"  data-member_id="<?= $work_rep->id; ?>" id="renewal_date" name="renewal_date" class="form-control datepicker" value="<?= $work_rep->renewal_date; ?>"></td>

                                </tr>
                                <script src="https://bfccapital.com/crm/assets/js/main.js?v=2.1.1"></script>
                                 <script>
  $(document).ready(function(){
        $("input[type='text']").blur(function(){
                const  checkid = $(this).attr("id");
                member_id = $(this).data('member_id');
                value = $(this).val(); 
                //alert(value);
                
                url = "<?= base_url('admin/reports/update_insurance_data') ?>";
        $.get(url, {
                checkid: checkid,
                value: value,
                member_id:member_id
            },
            function (res) {
                $('.lightboxOverlay').html(res);
            })
            
            });
    });  
</script>


<script>
     $(document).on('change', '.custom_lead_filter', function () {
      const filterrm = $('#filterrm').val(),
            transctiondatestart = $('#transctiondatestart').val();
            transctiondateend = $('#transctiondateend').val();
          //  filterstatus = $('#filterstatus').val();
          
          
        url = "<?= base_url('admin/reports/custom_insurance_filter') ?>";
        
        $.get(url, {
                filterrm: filterrm,
                transctiondatestart: transctiondatestart,
                transctiondateend:transctiondateend
            },
            function (res) {
                $('.ajax-data').html(res);
            })
    });
</script>
                            <?php } ?>
                            </tbody>
                        </table>
                       

                        <?php
                        } else {
                            echo "No Insurance Report Found";
                        } 
    }
    
    public function dwr_summary()
    {
        $start = $this->input->get('start');
        $end = $this->input->get('end');
        $data['start'] = $start;
        $data['end'] = $end;
        //$data['show_date'] = (!empty($start) && !empty($end)) ? date('d, M, Y', strtotime($start)) . ' To ' . date('d, M, Y', strtotime($end)) : "Today";
        
        if($start)
        {
           $data['summary'] = get_leads_summary_date_wise($start, $end);
        }
        else
        {
            $month = date('m');
            $year = date('Y');
            if($month <= '3')
            {
                
                $fi_end_year = $year;
                $fi_start_year = $year-1;
            }
            else
            {
               $fi_end_year = $year+1;
               $fi_start_year = $year; 
            }
            
            $data['start'] = $fi_start_year.'-04-01';
            $data['end'] = $fi_end_year.'-03-31';
            $data['summary'] = get_leads_summary_date_wise($start, $end);
            
        }
        
        $data['staffList'] = $this->staff_model->get_hera_staff('', ['active' => 1, 'department_id' => 12]);
        $data['show_date'] = (!empty($start) && !empty($end)) ? date('d, M, Y', strtotime($start)) . ' To ' . date('d, M, Y', strtotime($end)) : date('d, M, Y', strtotime($data['start'])) . ' To ' . date('d, M, Y', strtotime($data['end'])) ;
           
       
         
        
        $data['statuses'] = $this->leads_model->get_dwrstatus();
        $data['sources'] = $this->leads_model->get_source();
        $data['title'] = "DWR Summary";
        
        $data['bodyclass'] = 'hide-sidebar';
        $this->load->view('admin/reports/dwr_summary', $data);
    }
    
    public function business_summary()
    {
        $start = $this->input->get('start');
        $end = $this->input->get('end');
        $data['start'] = $start;
        $data['end'] = $end;
        //$data['show_date'] = (!empty($start) && !empty($end)) ? date('d, M, Y', strtotime($start)) . ' To ' . date('d, M, Y', strtotime($end)) : "Today";
        
        
        
        if($start)
        {
           $data['summary'] = get_leads_summary_date_wise($start, $end);
        }
        else
        {
            $month = date('m');
            $year = date('Y');
            if($month <= '3')
            {
                
                $fi_end_year = $year;
                $fi_start_year = $year-1;
            }
            else
            {
               $fi_end_year = $year+1;
               $fi_start_year = $year; 
            }
            
            $data['start'] = $fi_start_year.'-04-01';
            $data['end'] = $fi_end_year.'-03-31';
            $data['summary'] = get_leads_summary_date_wise($start, $end);
            
        }
        
        $data['staffList'] = $this->staff_model->get_hera_staff('', ['active' => 1, 'department_id' => 12]);
       
         
        $data['show_date'] = (!empty($start) && !empty($end)) ? date('d, M, Y', strtotime($start)) . ' To ' . date('d, M, Y', strtotime($end)) : date('d, M, Y', strtotime($data['start'])) . ' To ' . date('d, M, Y', strtotime($data['end'])) ;
           
        $data['statuses'] = $this->leads_model->get_dwrstatus();
        $data['sources'] = $this->leads_model->get_source();
        $data['title'] = "Credit Report Summary";
        $this->load->view('admin/reports/business_summary', $data);
    }
    
    public function importcallingsheet()
    {
        if($this->input->post())
        {
            $this->reports_model->upload_calling_dwr();
        }
        $data['title'] = "DWR - Upload Calling Sheet";
        $this->load->view('admin/reports/importcallingsheet', $data);
    }
    
    
    function dwrdatepermission()
    {
        $data['title'] = 'DWR Date Permission';

        if ($this->input->post()) {
            $chdate1 = $this->input->post('date');
            $chdate = $chdate[0];
            $this->db->select('date');
            $this->db->where(array('tt.date' => $chdate));
            $query = $this->db->get('tbldwrdatepermsn tt');
            $checkdate = $query->num_rows();
            if ($checkdate > 0) {
                set_alert('warning', "DWR Date Permission Exist For $chdate ");
                redirect(admin_url('reports/dwrdatepermission'));
            } else {
                //$insert_data['date'] = $this->input->post('date');
                $insdate = $this->input->post('date');
                $wpids = $this->input->post('wpids');
                $total_count = count($wpids);
                
                for ($i = 0; $i < $total_count; $i++) {
                    $inserdata['date'] = $insdate[$i];
                    $inserdata['wp'] = $wpids[$i];
                    $this->db->insert('tbldwrdatepermsn', $inserdata);
                }
                set_alert('success', _l('added_successfully', "DWR Date Permission"));

            }
            return redirect(admin_url('reports/dwrdatepermission'));
        }


        /*$data['bodyclass'] = 'hide-sidebar';*/
        
        $data['availwp'] = $this->leads_model->get_wealthperson('', 'staffid, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  firstname', 'result_array');
        $data['assignedwp'] = $this->leads_model->getdatepermwp();
        //print_r($data['assignedwp']);


        $this->load->view('admin/reports/dwrdatepermission', $data);
    }
    
    function deletedatewp($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('tbldwrdatepermsn');

        set_alert('success', "Assigned Permission For DWR Deleted ");
        redirect(admin_url('reports/dwrdatepermission'));
    }
    
    function leave_report()
    {
        $start = $this->input->get('start');
        $end = $this->input->get('end');
        $data['start'] = $start;
        $data['end'] = $end;
        //$data['show_date'] = (!empty($start) && !empty($end)) ? date('d, M, Y', strtotime($start)) . ' To ' . date('d, M, Y', strtotime($end)) : "Today";
        if($start)
        {
           $data['summary'] = get_leave_summary_satffwise($start, $end);
        }
        else
        {
            $month = date('m');
            $year = date('Y');
            if($month <= '3')
            {
                
                $fi_end_year = $year;
                $fi_start_year = $year-1;
            }
            else
            {
               $fi_end_year = $year+1;
               $fi_start_year = $year; 
            }
            
            $data['start'] = $fi_start_year.'-04-01';
            $data['end'] = $fi_end_year.'-03-31';
            $data['summary'] = get_leave_summary_satffwise($start, $end);
            
        }
        //$data['summary'] = get_leave_summary_satffwise($start, $end);
        
        //print_r($GLOBALS['current_user']->department_id);    
        $department_hr = explode(",",$GLOBALS['current_user']->department_id);  
        // echo $department_hr[0];exit;
        
        if(is_admin() || $GLOBALS['current_user']->department_id == '5' )
        {
           $data['staffList'] = $this->staff_model->get_hera_staff('', ['active' => 1]); 
        }
        /* elseif( is_sub_admin()  && $GLOBALS['current_user']->department_id == '3') */
        elseif( is_sub_admin()  && $department_hr[0] == '3')
        {
            $data['staffList'] = $this->staff_model->get_hera_staff('', ['active' => 1]); 
        }
        else
        {
           $data['staffList'] = $this->staff_model->get_hera_staff('', ['active' => 1]); 
        }
        
        // echo "<pre>";
        // print_r($data['staffList']);exit;
       
       
        $data['title'] = 'Leave Report';
        $data['show_date'] = (!empty($start) && !empty($end)) ? date('d, M, Y', strtotime($start)) . ' To ' . date('d, M, Y', strtotime($end)) : date('d, M, Y', strtotime($data['start'])) . ' To ' . date('d, M, Y', strtotime($data['end'])) ;
        
        $this->load->view('admin/reports/leave_report', $data);
    }
    
     public function incentive_report()
    {
        $data['title'] = "Incentive Report ";
        $data['bodyclass'] = 'hide-sidebar';
        $data['rmconverted'] = $this->payroll_model->rmconverted();
        $data['incentive_payment_info'] = $this->payroll_model->get_incen();
        //print_r($data['incentive_payment_info']);exit;
        $this->load->view('admin/reports/incentive_report', $data);
    }
    
    function insurance_tracker()
    {
        $data['title'] = "Insurance Tracker";
        $data['bodyclass'] = 'hide-sidebar';
        
        
        $data['rmconverted'] = $this->reports_model->rmconverted_insurance();
        $data['work_report'] = $this->reports_model->insurance_tracker_data();
        //$qqq = $this->db->last_query();
       // print_r($qqq);
        $this->load->view('admin/reports/insurance_tracker', $data);
    }
    
    function update_insurance_data()
    {
        //print_r($this->input->get());exit;
        $checkid = $this->input->get('checkid');
        $member_id = $this->input->get('member_id');
        $value = $this->input->get('value');
        
        switch($checkid)
        {
            case "real_transaction_date":
                $data= array('real_transaction_date' => $value);
                $this->db->where('id', $member_id);
                $this->db->update('tblbusiness', $data);
               
                break;
            
            case "policy_number":
                $data= array('policy_number' => $value);
                $this->db->where('id', $member_id);
                $this->db->update('tblbusiness', $data);
                break;
                
            case "sum_assured":
                $data= array('sum_assured' => $value);
                $this->db->where('id', $member_id);
                $this->db->update('tblbusiness', $data);
                break;
            case "renewal_date":
                $data= array('renewal_date' => $value);
                $this->db->where('id', $member_id);
                $this->db->update('tblbusiness', $data);
                break;
        }
        
        
    }
    
    function custom_insurance_filter()
    {
        $leads = $this->reports_model->get_insurance_filter();
        $qq = $this->db->last_query();
      //  print_r($qq);
        $this->printBussinessDatainsurance($leads); 
    }
    
    function leave_report_detailed()
    {   
        $month = date('m');
        $year = date('Y');
        if($month <= '3')
        {
            $fi_end_year = $year;
            $fi_start_year = $year-1;
        }
        else
        {
            $fi_end_year = $year+1;
            $fi_start_year = $year; 
        }

        $start = $fi_start_year.'-04-01';
        $end = $fi_end_year.'-03-31';
       
        $data['title'] = "Leave Report Detailed";
        $data['rmconverted'] = $this->payroll_model->rmconverted();
        $this->db->select('tblleaveapplication.*, tblleavecategory.leave_category AS leavename');
        $this->db->where('tblleaveapplication.application_status', '2');
        $this->db->order_by('tblleaveapplication.leave_application_id', 'desc');
        $this->db->where('tblleaveapplication.leave_start_date >=', $start);
        $this->db->where('tblleaveapplication.leave_end_date <=', $end);
        $this->db->join('tblleavecategory', 'tblleaveapplication.leave_category_id = tblleavecategory.leave_category_id');
        $data['leaveAppplication']=$this->db->get('tblleaveapplication')->result();

        /*$data['leaveAppplication'] = $this->db->query("SELECT * from tblleaveapplication  order by leave_application_id")->result();
        */
        // echo $this->db->last_query();
        // exit();

        $this->load->view('admin/reports/leave_report_detailed', $data); 
    }
    
    function bussiness_updates()
    {
        
        if($this->input->post())
        {
            
            $transaction_date = $this->input->post('transaction_date');
            $working_person = $this->input->post('working_person');
            $amount = $this->input->post('amount');
            $category = $this->input->post('category');
            $remark = $this->input->post('remark');
            if($category == "deduct")
            {
                $amount = -$amount;
            }
            
            $update_arr = array('converted_by' => $working_person, 'transaction_date' => $transaction_date, 'net_credit' => $amount, 'remark' => $remark,'status' =>  'New');
            $upda = $this->db->insert('tblbusiness', $update_arr);
            if($upda)
            {
                 set_alert('success', "Submitted  Successfully");
                redirect($_SERVER['HTTP_REFERER']);
            }
            else
            {
                set_alert('warning', "Error While Submission");
                redirect($_SERVER['HTTP_REFERER']);
            }
        }

        $data['title'] = "Submit Business Updates";
        $this->db->select('staffid,  CONCAT(firstname, " ", lastname) AS  staffname');
        $this->db->where('active', 1);
        // $this->db->where('department_id','12');
        $this->db->like('department_id', 12);
        
        $data['rmconverted'] = $this->db->get('tblstaff')->result();
        $this->load->view('admin/reports/bussiness_updates', $data); 
    }
    
    public function special_leave(){
        $data['title'] = "Special Leave Report";
        
        $this->db->select('staffid,  CONCAT(firstname, " ", lastname) AS  staffname, sp_leave');
        $this->db->where('sp_leave !=','');
        $this->db->where('sp_leave !=','0');
        $this->db->where('admin !=','1');
        $data['specialLeaveData'] = $this->db->get('tblstaff')->result();
        /* echo "<pre>";
        print_r($data['specialLeaveData']);
        exit; */
        $this->load->view('admin/reports/special_leave', $data);
    }
}
