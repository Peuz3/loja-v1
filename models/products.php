<?php

class Products extends model{

    public function getSaleCount($filters = array()){
        $where = $this->buildWhere($filters);

        $where[] = 'sale = "1"';

        $sql = "SELECT COUNT(*) AS c
        FROM products
        WHERE ".implode(' AND ', $where);
        $sql = $this->db->prepare($sql);

        $this->bindWhere($filters, $sql);
 
         $sql->execute();

         if($sql->rowCount() > 0){
             $sql = $sql->fetch();

             return $sql['c'];
         }else {
             return '0';
         }
    }

    public function getMaxPrice($filters = array()){

        $where = $this->buildWhere($filters);

        $sql = "SELECT price FROM products WHERE ".implode(' AND ', $where) ." ORDER BY price DESC LIMIT 1";
        $sql = $this->db->prepare($sql);

        $this->bindWhere($filters, $sql);
 
         $sql->execute();

         if($sql->rowCount() > 0){
             $sql = $sql->fetch();

             return $sql['price'];
         }else {
             return '0';
         }
    }

    public function getListOfStars($filters = array()){
        $array = array();

        $where = $this->buildWhere($filters);

        $sql = "SELECT rating, name, COUNT(id) c 
        FROM products 
        WHERE ".implode(' AND ', $where) ." 
        GROUP BY rating";
        $sql = $this->db->prepare($sql);

        $this->bindWhere($filters, $sql);
 
         $sql->execute();

        if($sql->rowCount() > 0){
            $array = $sql->fetchAll();
        }

        return $array;
    }

    public function getListOfBrands($filters= array()){
        $array = array();

        $where = $this->buildWhere($filters);

        $sql = "SELECT id_brand, name, COUNT(id) c FROM products WHERE ".implode(' AND ', $where) ." GROUP BY id_brand";
        $sql = $this->db->prepare($sql);

        $this->bindWhere($filters, $sql);
 
         $sql->execute();

        if($sql->rowCount() > 0){
            $array = $sql->fetchAll();
        }

        return $array;
    }
    public function getList($offset = 0, $limit = 3, $filters= array()){
        $array = array();

        $where = $this->buildWhere($filters);

        $sql = "SELECT * FROM products 
        WHERE ".implode(' AND ', $where) ."
        LIMIT $offset, $limit";
        $sql = $this->db->prepare($sql);

        $this->bindWhere($filters, $sql);

        $sql->execute();

        if($sql->rowCount() > 0){
            $array = $sql->fetchAll();

            $brands = new Brands();

            foreach($array as $key => $item){
                $array[$key]['brand_name'] = $brands->getNameById(
                        $item['id_brand']);
            }

            foreach($array as $key => $item){

                $array[$key]['images'] = $this->getImagesByProductId($item['id']);
            }
        }
        return $array;
    }

    public function getTotal($filters = array()){
       
        $where = $this->buildWhere($filters);

        $sql = "SELECT COUNT(*) AS c FROM products
        WHERE ".implode(' AND ', $where); 
        $sql = $this->db->prepare($sql);
        
       $this->bindWhere($filters, $sql);
         
        $sql->execute();
        $sql = $sql->fetch();

        return $sql['c'];
    }

    public function getImagesByProductId($id){
        $array = array();

        $sql = "SELECT url FROM  products_images  WHERE id_product = :id";
        $sql = $this->db->prepare($sql);
        $sql->bindValue(":id" , $id);
        $sql->execute();

        if($sql->rowCount() > 0){
            $array = $sql->fetchAll();
        }

        return $array;
    }

    private function buildWhere($filters){
        $where = array(
            '1 = 1'
        );

        if(!empty($filters['category'])){
            $where[] = "id_category = :id_category";
        }

        return $where;

    }

    private function bindWhere($filters, &$sql){
        if(!empty($filters['category'])){
            $where[] = "id_category = :id_category";
        }
    }

}