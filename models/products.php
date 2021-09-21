<?php

class Products extends model{
    public function getListOfBrands(){
        $array = array();

        $sql = "SELECT id_brand, name, COUNT(id) c FROM products GROUP BY id_brand";
        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0){
            $array = $sql->fetchAll();
        }

        return $array;
    }
    public function getList($offset = 0, $limit = 3, $filters= array()){
        $array = array();

        $where = array(
            '1 = 1'
        );

        if(!empty($filters['category'])){
            $where[] = "id_category = :id_category";
        }

        $sql = "SELECT * FROM products 
        WHERE ".implode(' AND ', $where) ."
        LIMIT $offset, $limit";
        $sql = $this->db->prepare($sql);

        if(!empty($filters['category'])){
           $sql->bindValue(':id_category',$filters['category'] );
        }

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
        $where = array(
            '1 = 1'
        );

        if(!empty($filters['category'])){
            $where[] = "id_category = :id_category";
        }

        $sql = "SELECT COUNT(*) AS c FROM products
        WHERE ".implode(' AND ', $where); 
        $sql = $this->db->prepare($sql);
        
        if(!empty($filters['category'])){
            $sql->bindValue(':id_category',$filters['category'] );
         }
         
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

}