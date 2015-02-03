<?php
class Shengfuping extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    public function saveData($data)
    {
        $time   = isset($data['time']) ? $data['time'] : '';
        $number = isset($data['number']) ? $data['number'] : '';
        $home   = isset($data['home_team']) ? $data['home_team'] : '';
        $visit  = isset($data['visit_team']) ? $data['visit_team'] : '';

        $map = array();
        $map['time']        = $time;
        $map['number']      = $number;
        $map['home_team']   = $home;
        $map['visit_team']  = $visit;

        $array['time']          = isset($data['time']) ? $data['time'] : '';
        $array['number']        = isset($data['number']) ? $data['number'] : '';
        $array['league']        = isset($data['league']) ? $data['league'] : '';
        $array['home_team']     = isset($data['home_team']) ? $data['home_team'] : '';
        $array['visit_team']    = isset($data['visit_team']) ? $data['visit_team'] : '';
        $array['odds_3']        = isset($data['odds_3']) ? $data['odds_3'] : 0;
        $array['odds_1']        = isset($data['odds_1']) ? $data['odds_1'] : 0;
        $array['odds_0']        = isset($data['odds_0']) ? $data['odds_0'] : 0;
        $array['odds_rang_3']   = isset($data['odds_rang_3']) ? $data['odds_rang_3'] : 0;
        $array['odds_rang_1']   = isset($data['odds_rang_1']) ? $data['odds_rang_1'] : 0;
        $array['odds_rang_0']   = isset($data['odds_rang_0']) ? $data['odds_rang_0'] : 0;
        $array['home_score']    = isset($data['home_score']) ? $data['home_score'] : 0;
        $array['visit_score']   = isset($data['visit_score']) ? $data['visit_score'] : 0;
        $array['rang']          = isset($data['rang']) ? $data['rang'] : 0;
        $array['status']        = isset($data['status']) ? $data['status'] : 1;
        $array['result']        = isset($data['result']) ? $data['result'] : 0;
        $array['result_rang']   = isset($data['result_rang']) ? $data['result_rang'] : 0;

        $query = $this->db->get_where('shengfuping', $map, 1);
        if(0 == $query->num_rows()){
            $array['created_at'] = $array['updated_at'] = time();
            return $this->db->insert('shengfuping', $array);
        }else{
            $array['updated_at'] = time();
            return $this->db->update('shengfuping', $array, $map);
        }
    }
}