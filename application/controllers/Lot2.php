<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lot2 extends CI_Controller {

    public $total_pay = 0;
    public $total_get = 0;
    public $total_li  = 0;

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

        $array = array();
        $i     = 0;
        foreach($query->result() as $row){
            if(isset($array[$i]) && 2 == count($array[$i])){
                $i++;
            }

            $array[$i][] = $row;
        }

        $new    = array();
        $total  = 0;
        $no_win = 0;
        $no_wins= array();
        foreach ($array as $key => $value) {
            if(2 !== count($value)) continue;

            $per_pay = 0;
            $per_get = 0;

            $result = '';
            $result = $value[0]->result.$value[1]->result;

            $odds   = 0;
            $odds1  = $this->_get_odds($value[0]);
            $odds2  = $this->_get_odds($value[1]);
            $odds   = $odds1 * $odds2;

            $odds_33_pay = 10000/($value[0]->odds_3 * $value[1]->odds_3);
            $odds_31_pay = 10000/($value[0]->odds_3 * $value[1]->odds_1);
            $odds_13_pay = 10000/($value[0]->odds_1 * $value[1]->odds_3);
            $odds_11_pay = 10000/($value[0]->odds_1 * $value[1]->odds_1);

            $per_pay = $odds_33_pay + $odds_31_pay + $odds_13_pay + $odds_11_pay;

            if(!isset($new[$result])){
                $new[$result]['num']  = 1;
                $new[$result]['odds'] = $odds;
            }else{
                $new[$result]['num']++;
                $new[$result]['odds'] += $odds;
            }

            if('33' != $result && '31' != $result && '13' != $result && '11' != $result){
                $no_win++;
                $per_get = 0;
            }else{
                if(0 != $no_win){
                    $no_wins[] = $no_win;
                    $no_win = 0;
                }

                $per_get = 10000;
            }

            $this->total_pay += $per_pay;
            $this->total_get += $per_get;
            $this->total_li  = $this->total_get - $this->total_pay;

            $total++;

            echo ($key+1). '&nbsp;&nbsp;&nbsp;&nbsp;';
            echo 'Result: '.$result. '&nbsp;&nbsp;&nbsp;&nbsp;';
            echo 'Odds: '.$odds. '&nbsp;&nbsp;&nbsp;&nbsp;';
            echo 'Per Pay:'.$per_pay.'&nbsp;&nbsp;&nbsp;&nbsp;';
            echo 'Per Get:'.$per_get.'&nbsp;&nbsp;&nbsp;&nbsp;';
            echo 'Total Pay:'.$this->total_pay.'&nbsp;&nbsp;&nbsp;&nbsp;';
            echo 'Total Get:'.$this->total_li;
            echo '<br>';
        }

        echo '<p><b>Total</b><br>';
        echo '2c1: '.$total.'<br>';
        foreach($new as $key => $value){
            echo $key. " Num: ". $value['num']. '&nbsp;&nbsp;&nbsp;&nbsp;';
            echo "Odds: ". $value['odds'].'<br>';
        }

        echo '<pre>';
        print_r($no_wins);
        sort($no_wins);
        echo '11 No Win Max: '. end($no_wins);
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

    private function _get_odds($data)
    {
        if(3 == $data->result){
            return $data->odds_3;
        }else if(1 == $data->result){
            return $data->odds_1;
        }else{
            return $data->odds_0;
        }
    }
}
