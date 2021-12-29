<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Reports_model extends CRM_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     *  Leads conversions monthly report
     * @param mixed $month which month / chart
     * @return  array          chart data
    */

    public function leads_monthly_report($month)
    {
        $result = $this->db->query('select last_status_change from tblleads where MONTH(last_status_change) = ' . $month . ' AND status = 1 and lost = 0')->result_array();
        $month_dates = [];
        $data = [];
        for ($d = 1; $d <= 31; $d++) {
            $time = mktime(12, 0, 0, $month, $d, date('Y'));
            if (date('m', $time) == $month) {
                $month_dates[] = _d(date('Y-m-d', $time));
                $data[] = 0;
            }
        }
        $chart = [
            'labels' => $month_dates,
            'datasets' => [
                [
                    'label' => _l('leads'),
                    'backgroundColor' => 'rgba(197, 61, 169, 0.5)',
                    'borderColor' => '#c53da9',
                    'borderWidth' => 1,
                    'tension' => false,
                    'data' => $data,
                ],
            ],
        ];
        foreach ($result as $lead) {
            $i = 0;
            foreach ($chart['labels'] as $date) {
                if (_d(date('Y-m-d', strtotime($lead['last_status_change']))) == $date) {
                    $chart['datasets'][0]['data'][$i]++;
                }
                $i++;
            }
        }

        return $chart;
    }

    public function get_stats_chart_data($label, $where, $dataset_options, $year)
    {
        $chart = [
            'labels' => [],
            'datasets' => [
                [
                    'label' => $label,
                    'borderWidth' => 1,
                    'tension' => false,
                    'data' => [],
                ],
            ],
        ];

        foreach ($dataset_options as $key => $val) {
            $chart['datasets'][0][$key] = $val;
        }
        $this->load->model('expenses_model');
        $categories = $this->expenses_model->get_category();
        foreach ($categories as $category) {
            $_where['category'] = $category['id'];
            $_where['YEAR(date)'] = $year;
            if (count($where) > 0) {
                foreach ($where as $key => $val) {
                    $_where[$key] = $val;
                }
            }
            array_push($chart['labels'], $category['name']);
            array_push($chart['datasets'][0]['data'], total_rows('tblexpenses', $_where));
        }

        return $chart;
    }

    public function get_expenses_vs_income_report($year = '')
    {
        $this->load->model('expenses_model');

        $months_labels = [];
        $total_expenses = [];
        $total_income = [];
        $i = 0;
        if (!is_numeric($year)) {
            $year = date('Y');
        }
        for ($m = 1; $m <= 12; $m++) {
            array_push($months_labels, _l(date('F', mktime(0, 0, 0, $m, 1))));
            $this->db->select('id')->from('tblexpenses')->where('MONTH(date)', $m)->where('YEAR(date)', $year);
            $expenses = $this->db->get()->result_array();
            if (!isset($total_expenses[$i])) {
                $total_expenses[$i] = [];
            }
            if (count($expenses) > 0) {
                foreach ($expenses as $expense) {
                    $expense = $this->expenses_model->get($expense['id']);
                    $total = $expense->amount;
                    // Check if tax is applied
                    if ($expense->tax != 0) {
                        $total += ($total / 100 * $expense->taxrate);
                    }
                    if ($expense->tax2 != 0) {
                        $total += ($expense->amount / 100 * $expense->taxrate2);
                    }
                    $total_expenses[$i][] = $total;
                }
            } else {
                $total_expenses[$i][] = 0;
            }
            $total_expenses[$i] = array_sum($total_expenses[$i]);
            // Calculate the income
            $this->db->select('amount');
            $this->db->from('tblinvoicepaymentrecords');
            $this->db->join('tblinvoices', 'tblinvoices.id = tblinvoicepaymentrecords.invoiceid');
            $this->db->where('MONTH(tblinvoicepaymentrecords.date)', $m);
            $this->db->where('YEAR(tblinvoicepaymentrecords.date)', $year);
            $payments = $this->db->get()->result_array();
            if (!isset($total_income[$m])) {
                $total_income[$i] = [];
            }
            if (count($payments) > 0) {
                foreach ($payments as $payment) {
                    $total_income[$i][] = $payment['amount'];
                }
            } else {
                $total_income[$i][] = 0;
            }
            $total_income[$i] = array_sum($total_income[$i]);
            $i++;
        }
        $chart = [
            'labels' => $months_labels,
            'datasets' => [
                [
                    'label' => _l('report_sales_type_income'),
                    'backgroundColor' => 'rgba(37,155,35,0.2)',
                    'borderColor' => '#84c529',
                    'borderWidth' => 1,
                    'tension' => false,
                    'data' => $total_income,
                ],
                [
                    'label' => _l('expenses'),
                    'backgroundColor' => 'rgba(252,45,66,0.4)',
                    'borderColor' => '#fc2d42',
                    'borderWidth' => 1,
                    'tension' => false,
                    'data' => $total_expenses,
                ],
            ],
        ];

        return $chart;
    }

    /**
     * Chart leads weeekly report
     * @return array  chart data
     */
    public function leads_this_week_report()
    {
        $this->db->where('CAST(last_status_change as DATE) >= "' . date('Y-m-d', strtotime('monday this week')) . '" AND CAST(last_status_change as DATE) <= "' . date('Y-m-d', strtotime('sunday this week')) . '" AND status = 1 and lost = 0');
        $weekly = $this->db->get('tblleads')->result_array();
        $colors = get_system_favourite_colors();
        $chart = [
            'labels' => [
                _l('wd_monday'),
                _l('wd_tuesday'),
                _l('wd_wednesday'),
                _l('wd_thursday'),
                _l('wd_friday'),
                _l('wd_saturday'),
                _l('wd_sunday'),
            ],
            'datasets' => [
                [
                    'data' => [
                        0,
                        0,
                        0,
                        0,
                        0,
                        0,
                        0,
                    ],
                    'backgroundColor' => [
                        $colors[0],
                        $colors[1],
                        $colors[2],
                        $colors[3],
                        $colors[4],
                        $colors[5],
                        $colors[6],
                    ],
                    'hoverBackgroundColor' => [
                        adjust_color_brightness($colors[0], -20),
                        adjust_color_brightness($colors[1], -20),
                        adjust_color_brightness($colors[2], -20),
                        adjust_color_brightness($colors[3], -20),
                        adjust_color_brightness($colors[4], -20),
                        adjust_color_brightness($colors[5], -20),
                        adjust_color_brightness($colors[6], -20),
                    ],
                ],
            ],
        ];
        foreach ($weekly as $weekly) {
            $lead_status_day = _l(mb_strtolower('wd_' . date('l', strtotime($weekly['last_status_change']))));
            $i = 0;
            foreach ($chart['labels'] as $dat) {
                if ($lead_status_day == $dat) {
                    $chart['datasets'][0]['data'][$i]++;
                }
                $i++;
            }
        }

        return $chart;
    }

    public function leads_staff_report()
    {
        $this->load->model('staff_model');
        $staff = $this->staff_model->get();
        if ($this->input->post()) {
            $from_date = to_sql_date($this->input->post('staff_report_from_date'));
            $to_date = to_sql_date($this->input->post('staff_report_to_date'));
        }
        $chart = [
            'labels' => [],
            'datasets' => [
                [
                    'label' => _l('leads_staff_report_created'),
                    'backgroundColor' => 'rgba(3,169,244,0.2)',
                    'borderColor' => '#03a9f4',
                    'borderWidth' => 1,
                    'tension' => false,
                    'data' => [],
                ],
                [
                    'label' => _l('leads_staff_report_lost'),
                    'backgroundColor' => 'rgba(252,45,66,0.4)',
                    'borderColor' => '#fc2d42',
                    'borderWidth' => 1,
                    'tension' => false,
                    'data' => [],
                ],
                [
                    'label' => _l('leads_staff_report_converted'),
                    'backgroundColor' => 'rgba(37,155,35,0.2)',
                    'borderColor' => '#84c529',
                    'borderWidth' => 1,
                    'tension' => false,
                    'data' => [],
                ],
            ],
        ];
        foreach ($staff as $member) {
            array_push($chart['labels'], $member['firstname'] . ' ' . $member['lastname']);
            if (!isset($to_date) && !isset($from_date)) {
                $this->db->where('CASE WHEN assigned=0 THEN addedfrom=' . $member['staffid'] . ' ELSE assigned=' . $member['staffid'] . ' END
                    AND status=1', '', false);
                $total_rows_converted = $this->db->count_all_results('tblleads');

                $total_rows_created = total_rows('tblleads', [
                    'addedfrom' => $member['staffid'],
                ]);

                $this->db->where('CASE WHEN assigned=0 THEN addedfrom=' . $member['staffid'] . ' ELSE assigned=' . get_staff_user_id() . ' END
                    AND lost=1', '', false);
                $total_rows_lost = $this->db->count_all_results('tblleads');
            } else {
                $sql = "SELECT COUNT(tblleads.id) as total FROM tblleads WHERE DATE(last_status_change) BETWEEN '" . $from_date . "' AND '" . $to_date . "' AND status = 1 AND CASE WHEN assigned=0 THEN addedfrom=" . $member['staffid'] . ' ELSE assigned=' . $member['staffid'] . ' END';
                $total_rows_converted = $this->db->query($sql)->row()->total;

                $sql = "SELECT COUNT(tblleads.id) as total FROM tblleads WHERE DATE(dateadded) BETWEEN '" . $from_date . "' AND '" . $to_date . "' AND addedfrom=" . $member['staffid'] . '';
                $total_rows_created = $this->db->query($sql)->row()->total;

                $sql = "SELECT COUNT(tblleads.id) as total FROM tblleads WHERE DATE(last_status_change) BETWEEN '" . $from_date . "' AND '" . $to_date . "' AND lost = 1 AND CASE WHEN assigned=0 THEN addedfrom=" . $member['staffid'] . ' ELSE assigned=' . $member['staffid'] . ' END';

                $total_rows_lost = $this->db->query($sql)->row()->total;
            }

            array_push($chart['datasets'][0]['data'], $total_rows_created);
            array_push($chart['datasets'][1]['data'], $total_rows_lost);
            array_push($chart['datasets'][2]['data'], $total_rows_converted);
        }

        return $chart;
    }

    /**
     * Lead conversion by sources report / chart
     * @return arrray chart data
     */
    public function leads_sources_report()
    {
        $this->load->model('leads_model');
        $sources = $this->leads_model->get_source();
        $chart = [
            'labels' => [],
            'datasets' => [
                [
                    'label' => _l('report_leads_sources_conversions'),
                    'backgroundColor' => 'rgba(124, 179, 66, 0.5)',
                    'borderColor' => '#7cb342',
                    'data' => [],
                ],
            ],
        ];
        foreach ($sources as $source) {
            array_push($chart['labels'], $source['name']);
            array_push($chart['datasets'][0]['data'], total_rows('tblleads', [
                'source' => $source['id'],
                'status' => 1,
                'lost' => 0,
            ]));
        }

        return $chart;
    }

    public function report_by_customer_groups()
    {
        $months_report = $this->input->post('months_report');
        $groups = $this->clients_model->get_groups();
        if ($months_report != '') {
            $custom_date_select = '';
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

                $custom_date_select = '(tblinvoicepaymentrecords.date BETWEEN "' . $beginMonth . '" AND "' . $endMonth . '")';
            } elseif ($months_report == 'this_month') {
                $custom_date_select = '(tblinvoicepaymentrecords.date BETWEEN "' . date('Y-m-01') . '" AND "' . date('Y-m-t') . '")';
            } elseif ($months_report == 'this_year') {
                $custom_date_select = '(tblinvoicepaymentrecords.date BETWEEN "' .
                    date('Y-m-d', strtotime(date('Y-01-01'))) .
                    '" AND "' .
                    date('Y-m-d', strtotime(date('Y-12-31'))) . '")';
            } elseif ($months_report == 'last_year') {
                $custom_date_select = '(tblinvoicepaymentrecords.date BETWEEN "' .
                    date('Y-m-d', strtotime(date(date('Y', strtotime('last year')) . '-01-01'))) .
                    '" AND "' .
                    date('Y-m-d', strtotime(date(date('Y', strtotime('last year')) . '-12-31'))) . '")';
            } elseif ($months_report == 'custom') {
                $from_date = to_sql_date($this->input->post('report_from'));
                $to_date = to_sql_date($this->input->post('report_to'));
                if ($from_date == $to_date) {
                    $custom_date_select = 'tblinvoicepaymentrecords.date ="' . $from_date . '"';
                } else {
                    $custom_date_select = '(tblinvoicepaymentrecords.date BETWEEN "' . $from_date . '" AND "' . $to_date . '")';
                }
            }
            $this->db->where($custom_date_select);
        }
        $this->db->select('amount,tblinvoicepaymentrecords.date,tblinvoices.clientid,(SELECT GROUP_CONCAT(name) FROM tblcustomersgroups LEFT JOIN tblcustomergroups_in ON tblcustomergroups_in.groupid = tblcustomersgroups.id WHERE customer_id = tblinvoices.clientid) as customerGroups');
        $this->db->from('tblinvoicepaymentrecords');
        $this->db->join('tblinvoices', 'tblinvoices.id = tblinvoicepaymentrecords.invoiceid');
        $this->db->where('tblinvoices.clientid IN (select customer_id FROM tblcustomergroups_in)');
        $this->db->where('tblinvoices.status !=', 5);
        $by_currency = $this->input->post('report_currency');
        if ($by_currency) {
            $this->db->where('currency', $by_currency);
        }
        $payments = $this->db->get()->result_array();
        $data = [];
        $data['temp'] = [];
        $data['total'] = [];
        $data['labels'] = [];
        foreach ($groups as $group) {
            if (!isset($data['groups'][$group['name']])) {
                $data['groups'][$group['name']] = $group['name'];
            }
        }
        // If any groups found
        if (isset($data['groups'])) {
            foreach ($data['groups'] as $group) {
                foreach ($payments as $payment) {
                    $p_groups = explode(',', $payment['customerGroups']);
                    foreach ($p_groups as $p_group) {
                        if ($p_group == $group) {
                            $data['temp'][$group][] = $payment['amount'];
                        }
                    }
                }
                array_push($data['labels'], $group);
                if (isset($data['temp'][$group])) {
                    $data['total'][] = array_sum($data['temp'][$group]);
                }
            }
        }
        $chart = [
            'labels' => $data['labels'],
            'datasets' => [
                [
                    'label' => _l('customer_groups'),
                    'backgroundColor' => 'rgba(197, 61, 169, 0.2)',
                    'borderColor' => '#c53da9',
                    'borderWidth' => 1,
                    'tension' => false,
                    'data' => $data['total'],
                ],
            ],
        ];

        return $chart;
    }

    public function report_by_payment_modes()
    {
        $this->load->model('payment_modes_model');
        $modes = $this->payment_modes_model->get('', [], true, true);
        $year = $this->input->post('year');
        $colors = get_system_favourite_colors();
        $this->db->select('amount,tblinvoicepaymentrecords.date');
        $this->db->from('tblinvoicepaymentrecords');
        $this->db->where('YEAR(tblinvoicepaymentrecords.date)', $year);
        $this->db->join('tblinvoices', 'tblinvoices.id = tblinvoicepaymentrecords.invoiceid');
        $by_currency = $this->input->post('report_currency');
        if ($by_currency) {
            $this->db->where('currency', $by_currency);
        }
        $all_payments = $this->db->get()->result_array();
        $chart = [
            'labels' => [],
            'datasets' => [],
        ];
        $data = [];
        $data['months'] = [];
        foreach ($all_payments as $payment) {
            $month = date('m', strtotime($payment['date']));
            $dateObj = DateTime::createFromFormat('!m', $month);
            $month = $dateObj->format('F');
            if (!isset($data['months'][$month])) {
                $data['months'][$month] = $month;
            }
        }
        usort($data['months'], function ($a, $b) {
            $month1 = date_parse($a);
            $month2 = date_parse($b);

            return $month1['month'] - $month2['month'];
        });

        foreach ($data['months'] as $month) {
            array_push($chart['labels'], _l($month) . ' - ' . $year);
        }
        $i = 0;
        foreach ($modes as $mode) {
            if (total_rows('tblinvoicepaymentrecords', [
                    'paymentmode' => $mode['id'],
                ]) == 0) {
                continue;
            }
            $color = '#4B5158';
            if (isset($colors[$i])) {
                $color = $colors[$i];
            }
            $this->db->select('amount,tblinvoicepaymentrecords.date');
            $this->db->from('tblinvoicepaymentrecords');
            $this->db->where('YEAR(tblinvoicepaymentrecords.date)', $year);
            $this->db->where('tblinvoicepaymentrecords.paymentmode', $mode['id']);
            $this->db->join('tblinvoices', 'tblinvoices.id = tblinvoicepaymentrecords.invoiceid');
            $by_currency = $this->input->post('report_currency');
            if ($by_currency) {
                $this->db->where('currency', $by_currency);
            }
            $payments = $this->db->get()->result_array();

            $datasets_data = [];
            $datasets_data['total'] = [];
            foreach ($data['months'] as $month) {
                $total_payments = [];
                if (!isset($datasets_data['temp'][$month])) {
                    $datasets_data['temp'][$month] = [];
                }
                foreach ($payments as $payment) {
                    $_month = date('m', strtotime($payment['date']));
                    $dateObj = DateTime::createFromFormat('!m', $_month);
                    $_month = $dateObj->format('F');
                    if ($month == $_month) {
                        $total_payments[] = $payment['amount'];
                    }
                }
                $datasets_data['total'][] = array_sum($total_payments);
            }
            $chart['datasets'][] = [
                'label' => $mode['name'],
                'backgroundColor' => $color,
                'borderColor' => adjust_color_brightness($color, -20),
                'tension' => false,
                'borderWidth' => 1,
                'data' => $datasets_data['total'],
            ];
            $i++;
        }

        return $chart;
    }

    /**
     * Total income report / chart
     * @return array chart data
     */
    public function total_income_report()
    {
        $year = $this->input->post('year');
        $this->db->select('amount,tblinvoicepaymentrecords.date');
        $this->db->from('tblinvoicepaymentrecords');
        $this->db->where('YEAR(tblinvoicepaymentrecords.date)', $year);
        $this->db->join('tblinvoices', 'tblinvoices.id = tblinvoicepaymentrecords.invoiceid');
        $by_currency = $this->input->post('report_currency');
        if ($by_currency) {
            $this->db->where('currency', $by_currency);
        }
        $payments = $this->db->get()->result_array();
        $data = [];
        $data['months'] = [];
        $data['temp'] = [];
        $data['total'] = [];
        $data['labels'] = [];
        foreach ($payments as $payment) {
            $month = date('m', strtotime($payment['date']));
            $dateObj = DateTime::createFromFormat('!m', $month);
            $month = $dateObj->format('F');
            if (!isset($data['months'][$month])) {
                $data['months'][$month] = $month;
            }
        }
        usort($data['months'], function ($a, $b) {
            $month1 = date_parse($a);
            $month2 = date_parse($b);

            return $month1['month'] - $month2['month'];
        });
        foreach ($data['months'] as $month) {
            foreach ($payments as $payment) {
                $_month = date('m', strtotime($payment['date']));
                $dateObj = DateTime::createFromFormat('!m', $_month);
                $_month = $dateObj->format('F');
                if ($month == $_month) {
                    $data['temp'][$month][] = $payment['amount'];
                }
            }
            array_push($data['labels'], _l($month) . ' - ' . $year);
            $data['total'][] = array_sum($data['temp'][$month]);
        }
        $chart = [
            'labels' => $data['labels'],
            'datasets' => [
                [
                    'label' => _l('report_sales_type_income'),
                    'backgroundColor' => 'rgba(37,155,35,0.2)',
                    'borderColor' => '#84c529',
                    'tension' => false,
                    'borderWidth' => 1,
                    'data' => $data['total'],
                ],
            ],
        ];

        return $chart;
    }

    public function get_distinct_payments_years()
    {
        return $this->db->query('SELECT DISTINCT(YEAR(date)) as year FROM tblinvoicepaymentrecords')->result_array();
    }

    public function get_distinct_customer_invoices_years()
    {
        return $this->db->query('SELECT DISTINCT(YEAR(date)) as year FROM tblinvoices WHERE clientid=' . get_client_user_id())->result_array();
    }

    function fetch_task_category($task_type)
    {
        switch ($task_type) {
            case "Personal_Meeting":
                {
                    $output = '
                    <div class="col-md-3">
                    <small>Meeting Category</small>
                    <select name="task_category" id="task_category" class="form-control">
                    <option value="">Select Category</option>
                    <option value="Member">Member</option>
                    <option value="Client">Client</option>
                    <option value="Prospect">Prospect</option>
                    <option value="Lead">Lead</option>
                    <option value="Reference">Reference</option>
                    <option value="Visit">Visit</option>
                    <option value="Others">Others</option>
                    </select>
                    </div>
                    <div id="task_lead">
                    </div>
                    <div class="col-md-3">
                    <small>Company Name</small>
                    <input class="form-control company" id="company_name" name="company_name" placeholder="Enter Company Name" />
                    </div>
                    <div class="col-md-3">
                    <small class="req text-danger">Name Of the Person</small>
                    <input class="form-control name" id="person_name" name="person_name" placeholder="Name Of the Person" required/>
                    </div>
					<div class="col-md-3">
                    <small >Address</small>
                    <input class="form-control address" id="address" name="address" placeholder="Address"/>
                    </div>
					<div class="col-md-3">
                    <small class="req text-danger">Mobile Number *</small>
                    <input class="form-control phonenumber" id="mobile_number" name="mobile_number" placeholder="Mobile Number" required />
                    </div>
                    <div class="col-md-3">
                    <small>Designation</small>
                    <input class="form-control designation" id="designation" name="designation" placeholder="Designation" />
                    </div>
                    <div class="col-md-3">
                    <small>Duration (Mins / hrs Consumed)</small>
                    <input class="form-control" id="duration" name="duration" placeholder="Duration (Mins / hrs Consumed)" />
                    </div>
                    
                    <div class="col-md-9">
                    <small>Brief Remark</small>
                    <textarea class="form-control" rows="4" cols="50" name="remark" id="remark" > </textarea>
                    </div>
                    <div class="col-md-3">
                    <small>Next FU Date</small>
                    <input type="text" name="next_fudate" id="next_fudate" class="form-control datepicker" value="" data-format="dd-mm-yyyy" placeholder="Next FU Date" />
                    </div>
                    <div class="col-md-3">
                    <small>Categorisation</small>
                    <select name="categorisation"  class="form-control">
                                              <option value="A">A</option>
                                              <option value="B">B</option>
                                              <option value="C">C</option>
                                              <option value="Scrap">Scrap</option>
                                              <option value="Converted">Converted</option> 
                                              <option value="N/A">N/A</option> 
                                              </select>
                    </div>
                    
                    <div class="col-md-3 pull-right">
                    <small style="font-size: 13px;">Refernece Taken</small>
                    <input type="checkbox" class="checkbox-inline" id="refernece_taken" name="refernece_taken" value="Yes" style="margin: 10px;"/>
                    </div>
                    <script src="https://bfccapital.com/crm/assets/js/main.js?v=2.1.1"></script>
                    <script>
                    $(document).ready(function() {
                    
                    $("#task_category").change(function() {
                    var task_category = $("#task_category").val();
                      
                      
                      if(task_category == "Lead")
                      {
                      
                      $.ajax({
                        url:"https://bfccapital.com/crm/admin/reports/task_type_lead",
                        method:"POST",
                        data:{task_category:task_category},
                        success:function(data)
                        {
                            $("#task_lead").html(data);
                        }
                      });
                      }
                      
                      else
                      {
                         $.ajax({
                        url:"https://bfccapital.com/crm/admin/reports/task_type_lead",
                        method:"POST",
                        data:{task_category:task_category},
                        success:function(data)
                        {
                            $("#task_lead").html(data);
                        }
                      });
                      }
                      
                    });
                    
                    $("#refernece_taken").change(function() {
                    $(".input_fields_container").toggle();
                    });
                    });
                    </script>
                     <script>


    $(function () {
	
        $("#next_fudate").datetimepicker({
            format: "Y-m-d",
            onShow: function (ct) {
                this.setOptions({
                    minDate: 0
                })
            },
            timepicker: false
        });
    });
</script>
                    ';
                    return $output;
                }
                break;

            case "Calling":
                {
                    $output = '
                    <a href="'.admin_url().'reports/importcallingsheet" target="_blank">Click here To Upload Calling Shhet </a>
                    <div class="col-md-3">
                    <small>Calling Category</small>
                    <select name="task_category" id="task_category" class="form-control">
                    <option value="">Select Category</option>
                    <option value="corporate-qc">Corporate QC FU</option>
                    <option value="corporate-qc">Non-Corporate QC</option>
                    <option value="non-qc">Non-QC</option>
                    <option value="Insti">Insti</option>
                    </select>
                    </div>
                     <div class="col-md-3">
                    <small>Company Name</small>
                    <input class="form-control" id="company_name" name="company_name" placeholder="Enter Company Name" />
                    </div>
                    <div class="col-md-3">
                    <small class="req text-danger">Name Of the Person</small>
                    <input class="form-control" id="person_name" name="person_name" placeholder="Name Of the Person" required/>
                    </div>
					<div class="col-md-3">
                    <small >Address</small>
                    <input class="form-control address" id="address" name="address" placeholder="Address"/>
                    </div>
                    <div class="col-md-3">
                    <small class="req text-danger">Mobile Number *</small>
                    <input class="form-control phonenumber" id="mobile_number" name="mobile_number" placeholder="Mobile Number" required />
                    </div>
					<div class="col-md-3">
                    <small>Designation</small>
                    <input class="form-control" id="designation" name="designation" placeholder="Designation" />
                    </div>
                    <div class="col-md-3">
                    <small>Duration (Mins / hrs Consumed)</small>
                    <input class="form-control" id="duration" name="duration" placeholder="Duration (Mins / hrs Consumed)" required/>
                    </div>
                    <div class="col-md-9">
                    <small>Brief Remark</small>
                    <textarea class="form-control" rows="4" cols="50" name="remark" id="remark" > </textarea>
                    </div>
                    
                    <div class="col-md-3">
                    <small>Next FU Date</small>
                    <input type="text" name="next_fudate" id="next_fudate" class="form-control datepicker" value="" data-format="dd-mm-yyyy" placeholder="Next FU Date" />
                    </div>
                    <div class="col-md-3">
                    <small>Categorisation</small>
                    <select name="categorisation"  class="form-control">
                                    
                                              
                                              <option value="A">A</option>
                                              <option value="B">B</option>
                                              <option value="C">C</option>
                                              <option value="Scrap">Scrap</option>
                                              <option value="Member">Member</option> 
                                              <option value="Client">Client</option>
                                              <option value="Prospect">Prospect</option>
                                              <option value="Lead">Lead</option>
                                              
                                              <option value="CNP">CNP</option></select>
                    </div>
                    <div class="col-md-3 pull-right">
                    <small style="font-size:13px">Refernece Taken</small>
                    <input type="checkbox" class="checkbox-inline" id="refernece_taken" name="refernece_taken" value="Yes" style="margin:10px"/>
                    </div>
                    <script src="https://bfccapital.com/crm/assets/js/main.js?v=2.1.1"></script>
                    <script>
                    $(document).ready(function() {
                    $("#refernece_taken").change(function() {
                    $(".input_fields_container").toggle();
                    });
                    });
                    </script>
                    <script>


    $(function () {
	
        $("#next_fudate").datetimepicker({
            format: "Y-m-d",
            onShow: function (ct) {
                this.setOptions({
                    minDate: 0
                })
            },
            timepicker: false
        });
    });
</script>
                    
                    
                    ';
                    return $output;
                }
                break;

            case "Assignment":
                {
                    $output = '
                    <div class="col-md-3">
                    <small>Duration of Assignment</small>
                    <input class="form-control" id="ass_duration" name="ass_duration" placeholder="Duration of Assignment" />
                    </div>
                    <div class="col-md-3">
                    <small>Assignment Description</small>
                    <input class="form-control" id="ass_desc" name="ass_desc" placeholder="Assignment Description" />
                    </div>
                    <div class="col-md-6">
                    <small>Detailed Remark On The Assignment</small>
                    <textarea class="form-control" rows="4" cols="50" name="remark" id="remark" > </textarea>
                    </div>
                    ';
                    return $output;
                }
                break;
        }

    }

    function task_category_fiels()
    {
        switch ($task_category) {
            case "":
                {

                }
        }
    }

    public function _checkRecords($query, $return)
    {
        if ($query->num_rows()) {
            return $query->$return();
        }
    }

    public function get_staff($return = "result")
    {
        if(is_admin() || is_sub_admin())
        {
            $this->db->select('staff_id, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  staffname');
            $this->db->group_by('staff_id');
            $this->db->join('tblstaff', 'tblworkreport.staff_id= tblstaff.staffid');
			$this->db->order_by('tblstaff.staffid', 'desc');
            return $this->_checkRecords($this->db->get('tblworkreport'), $return);
        }
        elseif(herapermission())
        {
            $arr=herapermission();
			$useraid = $this->session->userdata('staff_user_id');
            $leadsource = array($arr);
            $this->db->select('staff_id, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  staffname');
            $this->db->where("staff_id IN (".$arr.")",NULL, false);
			$this->db->or_where_in('staff_id', $useraid);
            $this->db->group_by('staff_id');
            $this->db->join('tblstaff', 'tblworkreport.staff_id= tblstaff.staffid');
			$this->db->order_by('tblstaff.staffid', 'desc');
            return $this->_checkRecords($this->db->get('tblworkreport'), $return);
        }
            

    }

    public function get_task_type($return = "result")
    {

        $this->db->select('task_type');
        $this->db->group_by('task_type');
        return $this->_checkRecords($this->db->get('tblworkreport'), $return);

    }

    public function filter_by_task_type($limit=30,$start=1)
    {
        $return = "result";
        $task_type = $this->input->get('tasktype');
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $staff_name = $this->input->get('staff_name');
        $stf = $this->input->get('staff_name');
       // echo $stf[0];

        
        if ($stf[0] != null) {

            $this->db->where_in('staff_id', $staff_name);
        }
        else  {
            
            if(is_admin())
            {
                
            }
            else
            {
              $arr=herapermission();
            
             $this->db->where("staff_id IN (".$arr.")",NULL, false);  
            }
            
           
        
        }
        if (isset($task_type)) {
            
            $this->db->where_in('task_type', $task_type);
        }
        
        if (isset($end_date) && $end_date != null  ) 
        {

            $this->db->where('date BETWEEN "'.$start_date.'" and "'. $end_date.'"');
        }
        
       

        
            $this->db->select('tblworkreport.*, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  staffname');
            $this->db->join('tblstaff', 'tblworkreport.staff_id= tblstaff.staffid');
            
            $this->db->limit($limit,$start);
            return $this->_checkRecords($this->db->get('tblworkreport'), $return);
        
        
    }
    
    
    public function filter_by_task_type1()
    {
        $limit=$_REQUEST['length'];//30;
        $start=$_REQUEST['start'];//1;
        $return = "result";
        $task_type = $_POST['tasktypefilter'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $staff_name = $_POST['staff_name'];
        $stf = $_POST['staff_name'];
       // echo $stf[0];

        
        if ($stf != null) {

            $this->db->where_in('staff_id', $staff_name);
        }
        else  {
            
            if(is_admin() || is_sub_admin())
            {
                
            }
            else
            {
              $arr=herapermission();
            
             $this->db->where("staff_id IN (".$arr.")",NULL, false);  
            }
            
           
        
        }
        if (isset($task_type)) {
            
            $this->db->where_in('task_type', $task_type);
        }
        
        if (isset($end_date) && $end_date != null  ) 
        {

            $this->db->where('date BETWEEN "'.$start_date.'" and "'. $end_date.'"');
        }
        
       

        
            $this->db->select('tblworkreport.*, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  staffname');
            $this->db->join('tblstaff', 'tblworkreport.staff_id= tblstaff.staffid');
            $this->db->order_by("id", "desc"); 
            $this->db->limit($limit,$start);
            return $this->_checkRecords($this->db->get('tblworkreport'), $return);
        
        
    }
    
    function work_report_get_total() 
    {
        // return $this->db->count_all('tblworkreport');
        //print_r($_POST);die;
        $return = "result";
        $task_type = $_POST['tasktypefilter'];
        $start_date = (isset($_POST['start_date']) && trim($_POST['start_date'])!='')?date("Y-m-d",strtotime(trim($_POST['start_date']))):'';//$_POST['start_date'];die;
        $end_date = (isset($_POST['end_date']) && trim($_POST['end_date'])!='')?date("Y-m-d",strtotime(trim($_POST['end_date']))):'';//$_POST['end_date'];
        $staff_name = $_POST['staff_name'];
        //$stf = $_POST['staff_name'];die;
       // echo $stf[0];

        
        if (count($staff_name) > 0) {

            $this->db->where_in('staff_id', $staff_name);
        }
        // else  {
            
        //     if(is_admin())
        //     {
                
        //     }
        //     else
        //     {
        //       $arr=herapermission();
            
        //      $this->db->where("staff_id IN (".$arr.")",NULL, false);  
        //     }
            
           
        
        // }
        if (count($task_type)>0) {
            
            $this->db->where_in('task_type', $task_type);
        }
        
        if (isset($start_date) && $start_date != '' && isset($end_date) && $end_date != ''  ) 
        {

            $this->db->where('date BETWEEN "'.$start_date.'" and "'. $end_date.'"');
        }
        
       //echo $limit."-----".$start;exit;
            $this->db->select('tblworkreport.id');
            $this->db->join('tblstaff', 'tblworkreport.staff_id= tblstaff.staffid');
			$this->db->order_by('tblstaff.staffid', 'desc');
            $this->db->limit($limit,$start);
            // $this->db->limit(500,0);
			
            $ss = $this->db->get('tblworkreport');
			
			/* $this->db->select('tblworkreport.*,tblstaff.*');
			$this->db->from('tblworkreport');
			//$this->db->join('credentials', 'tblanswers.answerid = credentials.cid', 'right outer'); 
			$this->db->join('tblstaff', 'tblworkreport.staff_id= tblstaff.staffid');
			$query = $this->db->get();
			$data = $query->result();

			
			print_r($this->db->last_query);die; */
			
            // $ss2 = $this->_checkRecords($this->db->get('tblworkreport'), $return);
            // print_r($ss);
            // echo $ss->num_rows();
            // print_r($ss->result);
            
            // print_r($this->db->last_query());
            return $ss->num_rows();
    }
    
    
    public function filter_reference_data($return = "result")
    { 
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $staff_name = $this->input->get('staff_name');

        if (isset($staff_name )) {

            $this->db->where_in('taken_by', $staff_name);
        }
        
        

        

        
            $this->db->select('tblreference.*, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  staffname');
            $this->db->join('tblstaff', 'tblreference.taken_by= tblstaff.staffid');
            return $this->_checkRecords($this->db->get('tblreference'), $return);
        
        
    }


    function task_type_lead($task_type)
    {

        //$this->db->where("tblmeeting_scheduled.meeting_cat", "Prospect");
        $useraid = $this->session->userdata('staff_user_id');
        
        $ignore = array(3, 10);
        $this->db->where_not_in("tblmeeting_scheduled.status", $ignore);
        $this->db->where('tblmeeting_scheduled.assigned', $useraid);
        $this->db->join('tblleads AS tbll', 'tblmeeting_scheduled.lead_id=tbll.id');
        $this->db->select('tbll.*');
        $data = $this->db->get('tblmeeting_scheduled');

        //print_r($data->result());


        switch ($task_type) {
            case "Lead":
                {
                    $output = '
                    <div class="col-md-4">
                    <small>Meeting Sch Leads</small>
                    
                    <select name="meeting_leads[]" id="meeting_leads" class="form-control meeting_leads"><option value="">Select Meeting Leads</option>';
                    '<option value="">Select Meeting Leads</option>';
                    foreach ($data->result() as $row) {
                        $output .= '
                    <option data-selected="' . $row->name . '" data-phone="' . $row->phonenumber . '" data-company="' . $row->company . '" data-name="' . $row->name . '" data-designation="' . $row->designation . '" data-address="' . $row->address . '" value="' . $row->name . '">' . $row->name . '</option>';
                    }
                    $output .= '</select></div>
                    <script>
                     $(document).ready(function() {
                        $("select.meeting_leads").change(function() {
                            var phone = $("select.meeting_leads").find(":selected").data("phone");
                            $(".phonenumber").val(phone);
                            var company = $("select.meeting_leads").find(":selected").data("company");
                            $(".company").val(company);
                            var designation = $("select.meeting_leads").find(":selected").data("designation");
                            $(".designation").val(designation);
                            var name = $("select.meeting_leads").find(":selected").data("name");
                            $(".name").val(name); 
                            var address = $("select.meeting_leads").find(":selected").data("address");
                            $(".address").val(address);
                        });
                    }); 
                    </script>
                    <script>
                            $(document).ready(function(){
                             
                                $("#task_lead").show();
                              
                              
                            });
                            </script>
                    ';
                    return $output;
                }
                break;
                
        default:
            {
                $output = '<script>
                            $(document).ready(function(){
                             
                                $("#task_lead").hide();
                              
                              
                            });
                            </script>
                ';
                    return $output;
            }
        }

    }

    function business_report($return = "result")
    {
        if(is_admin())
        {
            $this->db->select('CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  staff_res, tblproduct_categories.name as pro_type,tblproduct_companies.name as company_name,tblproducts.product_name as product_name, tblbusiness.*');
            $this->db->where('transaction_date !=', NULL);
            $this->db->where('product_type', "86");
            $this->db->where('transaction_type', "FRESH");
            $this->db->join('tblproducts', 'tblbusiness.scheme=tblproducts.id');
            $this->db->join('tblproduct_categories', 'tblbusiness.product_type=tblproduct_categories.id');
            $this->db->join('tblproduct_companies', 'tblbusiness.company=tblproduct_companies.id');
            $this->db->join('tblstaff', 'tblbusiness.converted_by=tblstaff.staffid');
			$this->db->order_by('tblbusiness.id','DESC');
			//echo $this->db->last_query();exit;
            return $this->_checkRecords($this->db->get('tblbusiness'), $return);
            }
        elseif(has_permission('member_tracker', '', 'view'))
        {
			$user_role = $this->session->userdata('admin_role');
			if($user_role == 2){
				$this->db->select('CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  staff_res,tblproduct_categories.name as pro_type,tblproduct_companies.name as company_name,tblproducts.product_name as product_name, tblbusiness.*');
				$this->db->where('transaction_date !=', NULL);
				$this->db->where('product_type', "86");
				$this->db->where('transaction_type', "FRESH");
				$this->db->join('tblproducts', 'tblbusiness.scheme=tblproducts.id');
				$this->db->join('tblproduct_categories', 'tblbusiness.product_type=tblproduct_categories.id');
				$this->db->join('tblproduct_companies', 'tblbusiness.company=tblproduct_companies.id');
				$this->db->join('tblstaff', 'tblbusiness.converted_by=tblstaff.staffid');
				$this->db->order_by('tblbusiness.id', 'DESC');
				//echo $this->db->last_query();exit;
				return $this->_checkRecords($this->db->get('tblbusiness'), $return);
			}else{
				$arr=herapermission();
				$useraid = $this->session->userdata('staff_user_id');
				$this->db->select('CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  staff_res,tblproduct_categories.name as pro_type,tblproduct_companies.name as company_name,tblproducts.product_name as product_name, tblbusiness.*');
				$this->db->where('transaction_date !=', NULL);
				$this->db->where('product_type', "86");
				$this->db->where('transaction_type', "FRESH");
				$this->db->where("converted_by IN (".$arr.")",NULL, false);
			//	$this->db->or_where_in('converted_by', $useraid);
				$this->db->join('tblproducts', 'tblbusiness.scheme=tblproducts.id');
				$this->db->join('tblproduct_categories', 'tblbusiness.product_type=tblproduct_categories.id');
				$this->db->join('tblproduct_companies', 'tblbusiness.company=tblproduct_companies.id');
				$this->db->join('tblstaff', 'tblbusiness.converted_by=tblstaff.staffid');
				
				return $this->_checkRecords($this->db->get('tblbusiness'), $return);
			}
			
			
         
        }
        elseif(herapermission())
        {
            $arr=herapermission();
			$useraid = $this->session->userdata('staff_user_id');
			$this->db->select('CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  staff_res,tblproduct_categories.name as pro_type,tblproduct_companies.name as company_name,tblproducts.product_name as product_name, tblbusiness.*');
            $this->db->where('transaction_date !=', NULL);
            $this->db->where('product_type', "86");
            $this->db->where('transaction_type', "FRESH");
            $this->db->where("converted_by IN (".$arr.")",NULL, false);
		//	$this->db->or_where_in('converted_by', $useraid);
            $this->db->join('tblproducts', 'tblbusiness.scheme=tblproducts.id');
            $this->db->join('tblproduct_categories', 'tblbusiness.product_type=tblproduct_categories.id');
            $this->db->join('tblproduct_companies', 'tblbusiness.company=tblproduct_companies.id');
            //$this->db->join('tblstaff', 'tblbusiness.converted_by=tblstaff.staffid');
			$this->db->order_by('tblbusiness.id', 'DESC');
            //echo $this->db->last_query();exit;
            return $this->_checkRecords($this->db->get('tblbusiness'), $return);
			
			
			
        }
    }
    
    
    
    function business_report_fd($return = "result")
    {
        if(is_admin())
        {
            $this->db->select('tblproduct_categories.name as pro_type,tblproduct_companies.name as company_name,tblproducts.product_name as product_name, tblbusiness.*');
            $this->db->where('transaction_date !=', NULL);
            $this->db->where('product_type', "81");
            $this->db->join('tblproducts', 'tblbusiness.scheme=tblproducts.id');
            $this->db->join('tblproduct_categories', 'tblbusiness.product_type=tblproduct_categories.id');
            $this->db->join('tblproduct_companies', 'tblbusiness.company=tblproduct_companies.id');
			$this->db->order_by('tblbusiness.id', 'DESC');
            return $this->_checkRecords($this->db->get('tblbusiness'), $return);
            }
        elseif(has_permission('fd_tracker', '', 'view'))
        {
			$admin_role = $this->session->userdata('admin_role');
			if($admin_role == 2){
				$this->db->select('tblproduct_categories.name as pro_type,tblproduct_companies.name as company_name,tblproducts.product_name as product_name, tblbusiness.*');
				$this->db->where('transaction_date !=', NULL);
				$this->db->where('product_type', "81");
				$this->db->join('tblproducts', 'tblbusiness.scheme=tblproducts.id');
				$this->db->join('tblproduct_categories', 'tblbusiness.product_type=tblproduct_categories.id');
				$this->db->join('tblproduct_companies', 'tblbusiness.company=tblproduct_companies.id');
				$this->db->order_by('tblbusiness.id', 'DESC');
				return $this->_checkRecords($this->db->get('tblbusiness'), $return);
				
			}else{
				$arr=herapermission();
				$useraid = $this->session->userdata('staff_user_id');
				$this->db->select('tblproduct_categories.name as pro_type,tblproduct_companies.name as company_name,tblproducts.product_name as product_name, tblbusiness.*');
				$this->db->where('transaction_date !=', NULL);
				$this->db->where('product_type', "81");
				$this->db->where("converted_by IN (".$arr.")",NULL, false);
			//	$this->db->or_where_in('converted_by', $useraid);
				$this->db->join('tblproducts', 'tblbusiness.scheme=tblproducts.id');
				$this->db->join('tblproduct_categories', 'tblbusiness.product_type=tblproduct_categories.id');
				$this->db->join('tblproduct_companies', 'tblbusiness.company=tblproduct_companies.id');
				$this->db->order_by('tblbusiness.id', 'DESC');
				
				return $this->_checkRecords($this->db->get('tblbusiness'), $return);
			}
         
        }
        elseif(herapermission())
        {
            $arr=herapermission();
			$useraid = $this->session->userdata('staff_user_id');
			$this->db->select('tblproduct_categories.name as pro_type,tblproduct_companies.name as company_name,tblproducts.product_name as product_name, tblbusiness.*');
            $this->db->where('transaction_date !=', NULL);
            $this->db->where('product_type', "81");
            $this->db->where("converted_by IN (".$arr.")",NULL, false);
		//	$this->db->or_where_in('converted_by', $useraid);
            $this->db->join('tblproducts', 'tblbusiness.scheme=tblproducts.id');
            $this->db->join('tblproduct_categories', 'tblbusiness.product_type=tblproduct_categories.id');
            $this->db->join('tblproduct_companies', 'tblbusiness.company=tblproduct_companies.id');
            $this->db->order_by('tblbusiness.id', 'DESC');
            
            return $this->_checkRecords($this->db->get('tblbusiness'), $return);
			
        }
    }
    
    function reference_report($return = "result")
    {
        $this->db->select(' CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  staffname, tblreference.*');
        $this->db->join('tblstaff', 'tblreference.taken_by=tblstaff.staffid');
        if(is_admin())
        {
           return $this->_checkRecords($this->db->get('tblreference'), $return);
        }
        elseif(herapermission())
        {
            $arr=herapermission();
            $this->db->where("taken_by IN (".$arr.")",NULL, false);
            return $this->_checkRecords($this->db->get('tblreference'), $return);
        }
        
    }
    
    
    function rmconverted($return = "result")
    {
        
        
        if(is_admin())
        {
			$this->db->select('tblstaff.staffid as staffid, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS   firstname');
			$this->db->group_by('converted_by');
			$this->db->where('transaction_date !=', NULL);
			$this->db->where('product_type', "86");
			$this->db->where('transaction_type', "FRESH");
			$this->db->join('tblstaff', 'tblbusiness.converted_by=tblstaff.staffid');
			return $this->_checkRecords($this->db->get('tblbusiness'), $return);
        }
        
        elseif(has_permission('member_tracker', '', 'view'))
        {
			$admin_role = $this->session->userdata('admin_role');
			if($admin_role == 2){
				$this->db->select('tblstaff.staffid as staffid, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS   firstname');
				$this->db->group_by('converted_by');
				$this->db->where('transaction_date !=', NULL);
				$this->db->where('product_type', "86");
				$this->db->where('transaction_type', "FRESH");
				$this->db->join('tblstaff', 'tblbusiness.converted_by=tblstaff.staffid');
				return $this->_checkRecords($this->db->get('tblbusiness'), $return);
			}else{
				$arr=herapermission();
				$useraid = $this->session->userdata('staff_user_id');$this->db->select('tblstaff.staffid as staffid, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS   firstname');
				//$this->db->where_in('converted_by',$arr );
				
				$this->db->where("converted_by IN (".$arr.")",NULL, false);
				$this->db->or_where_in('converted_by',$useraid );
				$this->db->group_by('converted_by');
				$this->db->where('transaction_date !=', NULL);
				$this->db->where('product_type', "86");
				$this->db->where('transaction_type', "FRESH");
				$this->db->join('tblstaff', 'tblbusiness.converted_by=tblstaff.staffid');
				return $this->_checkRecords($this->db->get('tblbusiness'), $return);
			}
        }
        elseif(herapermission())
        {
            $arr=herapermission();
            $useraid = $this->session->userdata('staff_user_id');$this->db->select('tblstaff.staffid as staffid, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS   firstname');
            //$this->db->where_in('converted_by',$arr );
            
            $this->db->where("converted_by IN (".$arr.")",NULL, false);
            $this->db->or_where_in('converted_by',$useraid );
            $this->db->group_by('converted_by');
            $this->db->where('transaction_date !=', NULL);
            $this->db->where('product_type', "86");
            $this->db->where('transaction_type', "FRESH");
            $this->db->join('tblstaff', 'tblbusiness.converted_by=tblstaff.staffid');
            return $this->_checkRecords($this->db->get('tblbusiness'), $return);
        }
        
    }
    
    
    function rmconverted_fd($return = "result")
    {
        
        
        if(is_admin())
        {
            //exit("admin");
        $this->db->select('tblstaff.staffid as staffid, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS   firstname');
        $this->db->group_by('converted_by');
            $this->db->where('transaction_date !=', NULL);
            $this->db->where('product_type', "81");
        $this->db->join('tblstaff', 'tblbusiness.converted_by=tblstaff.staffid');
        }
        
        elseif(has_permission('fd_tracker', '', 'view'))
        {
            $admin_role = $this->session->userdata('admin_role');
			if($admin_role == 2){
				
				$this->db->select('tblstaff.staffid as staffid, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS   firstname');
				$this->db->group_by('converted_by');
				$this->db->where('transaction_date !=', NULL);
				$this->db->where('product_type', "81");
				$this->db->join('tblstaff', 'tblbusiness.converted_by=tblstaff.staffid');
			}else{
				$arr=herapermission();
				$useraid = $this->session->userdata('staff_user_id');$this->db->select('tblstaff.staffid as staffid, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS   firstname');
				//$this->db->where_in('converted_by',$arr );

				$this->db->where("converted_by IN (".$arr.")",NULL, false);
				$this->db->or_where_in('converted_by',$useraid );
				$this->db->group_by('converted_by');
				$this->db->where('transaction_date !=', NULL);
				$this->db->where('product_type', "81");
				$this->db->join('tblstaff', 'tblbusiness.converted_by=tblstaff.staffid');
			}
        }
        elseif(herapermission())
        {
            $arr=herapermission();
            $useraid = $this->session->userdata('staff_user_id');$this->db->select('tblstaff.staffid as staffid, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS   firstname');
            //$this->db->where_in('converted_by',$arr );
            
            $this->db->where("converted_by IN (".$arr.")",NULL, false);
            $this->db->or_where_in('converted_by',$useraid );
            $this->db->group_by('converted_by');
        $this->db->where('transaction_date !=', NULL);
        $this->db->where('product_type', "81");
            $this->db->join('tblstaff', 'tblbusiness.converted_by=tblstaff.staffid');
            
        }
        return $this->_checkRecords($this->db->get('tblbusiness'), $return);
    }
    
    
    function rmrefconverted($return = "result")
    {
        
        
        if(is_admin())
        {
            
        $this->db->select('tblstaff.staffid as staffid, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS   staffname');
        $this->db->group_by('taken_by');
        $this->db->join('tblstaff', 'tblreference.taken_by=tblstaff.staffid');
        }
        
        
        elseif(herapermission())
        {
            $arr=herapermission();
            $useraid = $this->session->userdata('staff_user_id');$this->db->select('tblstaff.staffid as staffid, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS   staffname');
            //$this->db->where_in('converted_by',$arr );
            
            $this->db->where("taken_by IN (".$arr.")",NULL, false);
            $this->db->or_where_in('taken_by',$useraid );
            $this->db->group_by('taken_by');
            $this->db->join('tblstaff', 'tblreference.taken_by=tblstaff.staffid');
            
        }
        return $this->_checkRecords($this->db->get('tblreference'), $return);
    }
    
    function get_membership_filter($return = "result")
    {
        $filterrm = $_GET['filterrm'];
        
        $from_date =$_GET['transctiondatestart'];
        $to_date = $_GET['transctiondateend'];
        
        if ( $filterrm != null) {

            $this->db->where_in('converted_by', $filterrm);
        }
        if ( $filterrm == null ) {
            $arr=herapermission();
             $this->db->where("converted_by IN (".$arr.")",NULL, false);
           
        }
        
        if (isset($to_date) && $to_date != null  ) 
        {

            $this->db->where('transaction_date BETWEEN "'.$from_date.'" and "'. $to_date.'"');
        }
        
            $this->db->select('CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  staff_res, tblproduct_categories.name as pro_type,tblproduct_companies.name as company_name,tblproducts.product_name as product_name, tblbusiness.*');
            $this->db->where('transaction_date !=', NULL);
            $this->db->where('product_type', "86");
            $this->db->where('transaction_type', "FRESH");
            $this->db->join('tblproducts', 'tblbusiness.scheme=tblproducts.id');
            $this->db->join('tblproduct_categories', 'tblbusiness.product_type=tblproduct_categories.id');
            $this->db->join('tblproduct_companies', 'tblbusiness.company=tblproduct_companies.id');
            $this->db->join('tblstaff', 'tblbusiness.converted_by=tblstaff.staffid');
            return $this->_checkRecords($this->db->get('tblbusiness'), $return);
    }
    
    function get_fd_filter($return = "result")
    {
        $filterrm = $_GET['filterrm'];
        
        $from_date =$_GET['transctiondatestart'];
        $to_date = $_GET['transctiondateend'];
        
        if ( $filterrm != null) {

            $this->db->where_in('converted_by', $filterrm);
        }
        if ( $filterrm == null ) {
            $arr=herapermission();
             $this->db->where("converted_by IN (".$arr.")",NULL, false);
           
        }
        
        if (isset($to_date) && $to_date != null  ) 
        {

            $this->db->where('transaction_date BETWEEN "'.$from_date.'" and "'. $to_date.'"');
        }
        
            $this->db->select('tblproduct_categories.name as pro_type,tblproduct_companies.name as company_name,tblproducts.product_name as product_name, tblbusiness.*');
            $this->db->where('transaction_date !=', NULL);
            $this->db->where('product_type', "81");
            $this->db->join('tblproducts', 'tblbusiness.scheme=tblproducts.id');
            $this->db->join('tblproduct_categories', 'tblbusiness.product_type=tblproduct_categories.id');
            $this->db->join('tblproduct_companies', 'tblbusiness.company=tblproduct_companies.id');
            return $this->_checkRecords($this->db->get('tblbusiness'), $return);
    }
    
    
public function get_workingdaysdwr($id = '', $start_date = '', $end_date = '')
    {
       // $this->db->where($where);
        if (is_numeric($id)) {
            $this->db->where('staff_id', $id);
              $this->db->where('date BETWEEN "'.$start_date.'" and "'. $end_date.'"');
            $this->db->group_by('date');
          $query =  $this->db->get('tblworkreport');
            return $query->num_rows();
        }

      

        if (!$statuses) {
            $this->db->where('staff_id', $id);
              $this->db->where('date BETWEEN "'.$start_date.'" and "'. $end_date.'"');
            $this->db->group_by('date');
            $query = $this->db->get('tblworkreport');
            $statuses = $query->num_rows();
        }

        return $statuses;

    }
    
    public function get_reference_taken($id = '', $start_date = '', $end_date = '')
    {
       // $this->db->where($where);
        if (is_numeric($id)) {
            $this->db->select_sum('refernece_taken');
            $this->db->where('staff_id', $id);
            $this->db->where('date BETWEEN "'.$start_date.'" and "'. $end_date.'"');
          $query =  $this->db->get('tblworkreport')->row();
            return $query;
        }

      

        if (!$statuses) {
            $this->db->select_sum('refernece_taken');
            $this->db->where('staff_id', $id);
            $this->db->where('date BETWEEN "'.$start_date.'" and "'. $end_date.'"');
          $query =  $this->db->get('tblworkreport')->row();
        }

        return $query;

    }
    
    public function get_assignment_done($id = '', $start_date = '', $end_date = '')
    {
       // $this->db->where($where);
        if (is_numeric($id)) {
            $this->db->select('ass_desc');
            $this->db->where('staff_id', $id);
            $this->db->where('date BETWEEN "'.$start_date.'" and "'. $end_date.'"');
            $this->db->where('task_type', 'Assignment');
          $query =  $this->db->get('tblworkreport')->result();
            return $query;
        }

      

        if (!$statuses) {
            $this->db->select('ass_desc');
            $this->db->where('staff_id', $id);
            $this->db->where('date BETWEEN "'.$start_date.'" and "'. $end_date.'"');
            $this->db->where('task_type', 'Assignment');
          $query =  $this->db->get('tblworkreport')->result();
        }

        return $query;

    }
    
    function upload_calling_dwr()
    {
        $total_imported = 0;
        $staff_id = $_POST['staff_id'];
        $date = $_POST['date'];
        $task_type = "Calling";

        $count = 0;
        $fp = fopen($_FILES['userfile']['tmp_name'], 'r') or die("can't open file");
        while ($csv_line = fgetcsv($fp, 1024)) {
            $count++;
            if ($count == 1) {
                continue;
            }
            for ($i = 0, $j = count($csv_line); $i < $j; $i++) {
                if (!empty($csv_line[0])) {
                    $insert_csv = array();
                    $insert_csv['task_category'] = $csv_line[0];
                    $insert_csv['company_name'] = $csv_line[1];
                    $insert_csv['person_name'] = $csv_line[2];
                    $insert_csv['address'] = $csv_line[3];
                    $insert_csv['mobile_number'] = $csv_line[4];
                    $insert_csv['designation'] = $csv_line[5];
                    $insert_csv['duration'] = $csv_line[6];
                    $insert_csv['remark'] = $csv_line[7];
                    $insert_csv['next_fudate'] = $csv_line[8];
                    $insert_csv['categorisation'] = $csv_line[9];
                }
            }
            $i++;
            $total_imported++;
            $data = array(

                'task_category' => $insert_csv['task_category'],
                'company_name' => $insert_csv['company_name'],
                'person_name' => $insert_csv['person_name'],
                'designation' => $insert_csv['designation'],
                'mobile_number' => $insert_csv['mobile_number'],
                'address' => $insert_csv['address'],
                'duration' => $insert_csv['duration'],
                'remark' => $insert_csv['remark'],
                'next_fu_date' => $insert_csv['next_fudate'],
                'staff_id' => $staff_id,
                'date' => $date,
                'categorisation' => $insert_csv['categorisation'],
                'task_type' => $task_type
            );
            $data['crane_features'] = $this->db->insert('tblworkreport', $data);
            
            
        }
        fclose($fp) or die("can't close file");
        if (isset($data['crane_features'])) 

        $data['success'] = "success";
        set_alert('success', _l('import_total_imported', $total_imported));
        return $data;
    }
    
    function insurance_tracker_data()
    {
        $product_typedata = array('82','83','84');
        if(is_admin())
        {
            $this->db->select('tblproduct_categories.name as pro_type,tblproduct_companies.name as company_name,tblproducts.product_name as product_name, tblbusiness.*');
            $this->db->where('transaction_date !=', NULL);
             $this->db->where_in('product_type', $product_typedata);
            $this->db->join('tblproducts', 'tblbusiness.scheme=tblproducts.id');
            $this->db->join('tblproduct_categories', 'tblbusiness.product_type=tblproduct_categories.id');
            $this->db->join('tblproduct_companies', 'tblbusiness.company=tblproduct_companies.id');
            return $this->db->get('tblbusiness')->result();
            }
        elseif(is_sub_admin())
        {
            $this->db->select('tblproduct_categories.name as pro_type,tblproduct_companies.name as company_name,tblproducts.product_name as product_name, tblbusiness.*');
            $this->db->where('transaction_date !=', NULL);
             $this->db->where_in('product_type', $product_typedata);
            $this->db->join('tblproducts', 'tblbusiness.scheme=tblproducts.id');
            $this->db->join('tblproduct_categories', 'tblbusiness.product_type=tblproduct_categories.id');
            $this->db->join('tblproduct_companies', 'tblbusiness.company=tblproduct_companies.id');
            return $this->db->get('tblbusiness')->result();
         
        }
        elseif(herapermission())
        {
            $arr=herapermission();
			$useraid = $this->session->userdata('staff_user_id');
			$this->db->select('tblproduct_categories.name as pro_type,tblproduct_companies.name as company_name,tblproducts.product_name as product_name, tblbusiness.*');
            $this->db->where('transaction_date !=', NULL);
             $this->db->where_in('product_type', $product_typedata);
            $this->db->where("converted_by IN (".$arr.")",NULL, false);
		//	$this->db->or_where_in('converted_by', $useraid);
            $this->db->join('tblproducts', 'tblbusiness.scheme=tblproducts.id');
            $this->db->join('tblproduct_categories', 'tblbusiness.product_type=tblproduct_categories.id');
            $this->db->join('tblproduct_companies', 'tblbusiness.company=tblproduct_companies.id');
            
            
            return $this->db->get('tblbusiness')->result();
			
			
			
        }
       
    }
    
    function rmconverted_insurance($return = "result")
    {
        $product_typedata = array('82','83','84');
        
        if(is_admin())
        {
            
        $this->db->select('tblstaff.staffid as staffid, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS   firstname');
        $this->db->group_by('converted_by');
            $this->db->where('transaction_date !=', NULL);
             $this->db->where_in('product_type', $product_typedata);
        $this->db->join('tblstaff', 'tblbusiness.converted_by=tblstaff.staffid');
        }
        
        elseif(is_sub_admin())
        {
            
        $this->db->select('tblstaff.staffid as staffid, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS   firstname');
        $this->db->group_by('converted_by');
            $this->db->where('transaction_date !=', NULL);
             $this->db->where_in('product_type', $product_typedata);
        $this->db->join('tblstaff', 'tblbusiness.converted_by=tblstaff.staffid');
        }
        elseif(herapermission())
        {
            $arr=herapermission();
            $useraid = $this->session->userdata('staff_user_id');$this->db->select('tblstaff.staffid as staffid, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS   firstname');
            //$this->db->where_in('converted_by',$arr );
            
            $this->db->where("converted_by IN (".$arr.")",NULL, false);
            $this->db->or_where_in('converted_by',$useraid );
            $this->db->group_by('converted_by');
            $this->db->where('transaction_date !=', NULL);
             $this->db->where_in('product_type', $product_typedata);
            $this->db->join('tblstaff', 'tblbusiness.converted_by=tblstaff.staffid');
            
        }
        return $this->_checkRecords($this->db->get('tblbusiness'), $return);
    }
    
    function get_insurance_filter($return = "result")
    {
        $filterrm = $_GET['filterrm'];
        
        $from_date =$_GET['transctiondatestart'];
        $to_date = $_GET['transctiondateend'];
        $product_typedata = array('82','83','84');
        $this->db->select('tblproduct_categories.name as pro_type,tblproduct_companies.name as company_name,tblproducts.product_name as product_name, tblbusiness.*');
        
        if ( $filterrm != null) {

            $this->db->where_in('converted_by', $filterrm);
        }
        if ( $filterrm == null ) {
            $arr=herapermission();
             $this->db->where("converted_by IN (".$arr.")",NULL, false);
           
        }
        
        if (isset($to_date) && $to_date != null  ) 
        {

            $this->db->where('transaction_date BETWEEN "'.$from_date.'" and "'. $to_date.'"');
        }
        
        
            $this->db->where('transaction_date !=', NULL);
             $this->db->where_in('product_type', $product_typedata);
            $this->db->join('tblproducts', 'tblbusiness.scheme=tblproducts.id');
            $this->db->join('tblproduct_categories', 'tblbusiness.product_type=tblproduct_categories.id');
            $this->db->join('tblproduct_companies', 'tblbusiness.company=tblproduct_companies.id');
            return $this->_checkRecords($this->db->get('tblbusiness'), $return);
    }
	
	public function working_person($id, $return = "result"){
		
		$this->db->select('tblstaff.staffid as staffid, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS   firstname');
		//$this->db->group_by('team_id');
		//$this->db->where('transaction_date !=', NULL);
		$this->db->where('tblstaffdepartments.team_id', "20");
		//$this->db->where('transaction_type', "FRESH");
		$this->db->join('tblstaff', 'tblstaffdepartments.staffid=tblstaff.staffid');
		return $this->_checkRecords($this->db->get('tblstaffdepartments'), $return);
	}
	
	public function getwelthteam(){
		
		$this->db->select('tblstaff.*, tblteams.id as team_id');
		$this->db->from('tblteams');
		$this->db->join('tblstaff','tblstaff.staffid = tblteams.staffid');
        $this->db->where('tblteams.department_id', 12);
        $this->db->order_by('tblstaff.staffid',ASC);
		$query = $this->db->get();
		return $query->result();
		
	}

     public function getStaff($staffId){ //return $staffId;
        $this->db->select('*');
        $this->db->order_by('department_id');
        $this->db->where('active',1);
        $this->db->where('department_id', 12);
        $this->db->where_in('staffid', $staffId);
        return $this->db->get('tblstaff')->result_array();
    }

     public function getStaffSalary($staffId){

        $this->db->select('*');
        $this->db->from('tbl_salary_template');
        $this->db->where('tbl_salary_template.salary_grade',$staffId);
        $query = $this->db->get();

        return $query->result();
        
    }

    public function getStaffwef($staffId,$transctiondatestart,$transctiondateend){

        $this->db->where("timefrom>=", $transctiondatestart);
        $this->db->where("timeto<=", $transctiondateend);
        $this->db->where('sal_temp_id',$staffId);
        $this->db->order_by('id', ASC);
        return $this->db->get('tbl_salary_wef')->result();
    }

    public  function getTeamCredit($staffId,$start_date,$end_date)
    {

        if($start_date==''){
            $start_date = "2020-04-01";
        }else{

        }

        if($end_date==''){
            $end_date = "2021-03-31";
        }else{
           
        }

        $this->db->select_sum('net_credit');
        $this->db->where('converted_by', $staffId);
        $this->db->where('status', "Verified");
        $this->db->where('transaction_date >=', $start_date);
        $this->db->where('transaction_date <=', $end_date);
        $query = $this->db->get('tblbusiness');
       
        return $query->result();
    }

    public function getTeam($staffid){
        $this->db->where('staffid', $staffid);
        $this->db->where('department_id',12);
        return $this->db->get('tblteams')->result();
    }

    public function getdeptment($team_id){
        //echo $team_id; exit();
        $this->db->where('team_id',$team_id);
        $this->db->where('departmentid',12);
        return $this->db->get('tblstaffdepartments')->result();
    }
    
}
