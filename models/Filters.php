<?php
class Filters extends model{

    public function getFilters(){
        $products = new Products();
        $brands = new Brands();

        $array = array(
            'brands' => array(),
            'maxslider' => 1000,
            'stars' => array(),
            'sale' => false,
            'options' => array()
        );

      
        $array['brands'] = $brands->getList();
        $brand_products = $products->getListOfBrands();

        foreach($array['brands'] as $brand_key => $brand_item){

            $array['brands'][$brand_key]['count'] = 0;

            foreach($brand_products as $brand_product){
                if($brand_product['id_brand'] == $brand_item['id']){

                    $array['brands'][$brand_key]['count'] = $brand_product['c'];

                }
            }
        }

        return $array;
    }

}