<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Robot extends CI_Controller {

    public $url = "";
    
    public function __construct()
    {
        set_time_limit(0);
        parent::__construct();
        $this->load->helper('simple_html_dom');
        $this->load->model('shengfuping');
    }

    public function index()
    {
        $startDate  = mktime(0,0,0,9,3,2010);
        // $startDate  = mktime(0,0,0,5,13,2012);
        $endDate    = time();
        // $endDate    = mktime(0,0,0,5,13,2012);
        while($startDate <= $endDate){
            echo date('Y-m-d', $startDate). PHP_EOL;
            $this->url = "http://www.okooo.com/jingcai/". date('Y-m-d', $startDate);
            $this->_getData();
            $startDate += 86400;
            // sleep(rand(1,10));
        }
    }

    public function _getData()
    {
        // 1. 初始化
        $ch = curl_init();
        // 2. 设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        // 3. 执行并获取HTML文档内容
        $output  = curl_exec($ch);

        $output = mb_convert_encoding($output, "utf-8", "gb2312");
        $html   = str_get_html($output);

        if(!is_object($html)){
            log_message('error', '该URL不能解析:'.$this->url);
            return; 
        }

        $data = array();
        $matchs = $html->find('div.touzhu_1');
        if(empty($matchs)){
            echo 'No Match...'. PHP_EOL;
            return;
        }
        foreach($matchs as $match){
            $data['number']      = trim($match->find('.liansai .xulie', 0)->plaintext);
            $data['league']      = $match->find('.liansai .saiming', 0)->plaintext;
            $data['time']        = mb_substr($match->find('.liansai .shijian', 0)->title, 5);

            $data['home_team']   = $match->find('.shenpf .zhu .zhud .zhum', 0)->plaintext;
            $data['visit_team']  = $match->find('.shenpf .fu .ked .zhum', 0)->plaintext;
            
            $data['odds_3']      = trim($match->find('.shenpf .zhu .peilv', 0)->plaintext);
            $data['odds_1']      = trim($match->find('.shenpf .ping .peilv', 0)->plaintext);
            $data['odds_0']      = trim($match->find('.shenpf .fu .peilv', 0)->plaintext);
            
            $rangqiu3 = $match->find('.rangqiuspf .zhu .peilv', 0);
            $rangqiu1 = $match->find('.rangqiuspf .ping .peilv', 0);
            $rangqiu0 = $match->find('.rangqiuspf .fu .peilv', 0);
            $data['odds_rang_3'] = empty($rangqiu3) ? 0 : $rangqiu3->plaintext;
            $data['odds_rang_1'] = empty($rangqiu3) ? 0 : $rangqiu1->plaintext;
            $data['odds_rang_0'] = empty($rangqiu3) ? 0 : $rangqiu0->plaintext;
            
            $rangqiu     = $match->find('.rangqiuspf .rangqiu', 0);
            $rangqiuzhen = $match->find('.rangqiuspf .rangqiuzhen', 0);
            if($rangqiu){
                $data['rang'] = intval($rangqiu->plaintext);
            }else if($rangqiuzhen){
                $data['rang'] = intval($rangqiuzhen->plaintext);
            }else{
                $data['rang'] = 0;
            }

            $score = trim($match->find('.more .more_bg .p1', 0)->plaintext);
            $scoreArr = explode(':', $score);
            if(isset($scoreArr[0]) && isset($scoreArr[1])){
                $data['home_score']  = intval($scoreArr[0]);
                $data['visit_score'] = intval($scoreArr[1]);
            }else{
                $data['home_score'] = 0;
                $data['visit_score']= 0; 
            }

            if('延期' == $score){
                $data['status'] = 0;
            }else{
                $data['status'] = 1;
            }

            if($data['home_score'] > $data['visit_score']){
                $data['result'] = 3;
            }else if($data['home_score'] == $data['visit_score']){
                $data['result'] = 1;
            }else{
                $data['result'] = 0;
            }
            
            if(($data['home_score']+$data['rang']) > $data['visit_score']){
                $data['result_rang'] = 3;
            }else if(($data['home_score']+$data['rang']) == $data['visit_score']){
                $data['result_rang'] = 1;
            }else{
                $data['result_rang'] = 0;
            }

            $rs = 0;
            if(1 == $data['status']){
                $rs = $this->shengfuping->saveData($data);
            }
            
            if(!$rs){
                log_message('error', $data['time']. "  ". $data['home_team']. " vs ". $data['home_team']."  数据存入数据库失败");
                return ; 
            }

            echo substr($data['time'],0,10). " --- ". $data['number']. "  --- Done!". PHP_EOL;
        }

        $html->clear();
        
        // 4. 释放curl句柄
        curl_close($ch);
    }

}
