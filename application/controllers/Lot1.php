<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lot1 extends CI_Controller {

    public $total_pay = 0;
    public $total_get = 0;
    public $total_li  = 0;
    public $num_3     = 0;
    public $num_1     = 0;
    public $num_0     = 0;

    public function __construct()
    {
        set_time_limit(0);
        parent::__construct();
        $this->load->model('shengfuping');
    }

    public function index()
    {
        $query  = $this->_getData();
        // var_dump($this->db->last_query());die;
        $no_wins = array();
        $no_win  = 0;
        foreach($query->result() as $row){
            $per_pay = 0;
            $per_get = 0;
            $odds_rs = 0;

            $result = $row->result;
            $odds_3 = $row->odds_3;
            $odds_1 = $row->odds_1;
            $odds_0 = $row->odds_0;

            $odds_3_pay = 10000/$odds_3;
            $odds_1_pay = 10000/$odds_1;

            $per_pay = $odds_3_pay+ $odds_1_pay;
            
            if(3 == $result){
                $this->num_3++;
                $per_get = $odds_3_pay*$odds_3;
                $odds_rs = $odds_3;

                if($no_win) $no_wins[] = $no_win;
                $no_win = 0;
            }else if(1 == $result){
                $this->num_1++;
                $per_get = $odds_1_pay*$odds_1;
                $odds_rs = $odds_1;

                if($no_win) $no_wins[] = $no_win;
                $no_win = 0;
            }else{
                $no_win++;
                $this->num_0++;
                $per_get = 0;
                $odds_rs = $odds_0;
            }

            $this->total_pay += $per_pay;
            $this->total_get += $per_get;
            $this->total_li  = $this->total_get - $this->total_pay;
            
            echo 'Result:'.$result.' --- ';
            echo 'Odds:'.$odds_rs.' --- ';
            echo 'Per Pay:'.$per_pay.' --- ';
            echo 'Per Get:'.$per_get.' --- ';
            echo 'Total Pay:'.$this->total_pay.' --- ';
            echo 'Total Get:'.$this->total_li;
            echo '<br>';
        }

        sort($no_wins);

        echo '<p><b>Total</b><br>';
        echo 'Num 3: '. $this->num_3.'<br>';
        echo 'Num 1: '. $this->num_1.'<br>';
        echo 'Num 0: '. $this->num_0.'<br>';
        echo 'Total Pay: '. $this->total_pay.'<br>';
        echo 'Total Get: '. $this->total_get.'<br>';
        echo 'No Win Max: '. end($no_wins).'<br>';
        echo '</p>';
    }

    private function _getData()
    {
        $this->db->where('odds_3 >', 1.7);
        // $this->db->where('odds_3 <=', 2.5);
        $this->db->where('odds_1 !=', 0.00);
        $this->db->where('odds_0 !=', 0.00);
        $where = "odds_3 < odds_0";
        $this->db->where($where); 
        return $this->db->get('shengfuping');
    }
}
