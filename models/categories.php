<?php
class Categories extends model{

    public function getList(){
        $array = array();

        $sql = "SELECT * FROM categories ORDER BY  subcategories DESC";
        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0){
            foreach($sql->fetchAll() as $item){
                $item['subs'] = array();
                $array[$item['id']] = $item;
            }
            
            while($this->stillNeed($array)){
                $this->organizeCategory($array);
            }
        }
        // echo '<pre>';
        // print_r($array);
        // exit;

        return $array;
    }

    private function organizeCategory(&$array){
        foreach($array as $id => $item){
            if(isset($array[$item['subcategories']])){
                $array[$item['subcategories']]['subs'][$item['id']] = $item;
                unset($array[$id]);
                break;
            }
        }
    }
    //Função auxiliar para ver se há subcategories a serem organizadas
    private function stillNeed($array){
        foreach($array as $item){
            if(!empty($item['subcategories'])){
                return true;
            }
        }

        return false;
    }
}