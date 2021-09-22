<?php
class Filters extends model
{

    public function getFilters($filters)
    {
        $products = new Products();
        $brands = new Brands();

        $array = array(
            'brands' => array(),
            'slider0' => 0,
            'slider1' => 0,
            'maxslider' => 1000,
            'stars' => array(
                '0' => 0,
                '1' => 0,
                '2' => 0,
                '3' => 0,
                '4' => 0,
                '5' => 0
            ),
            'sale' => 0,
            'options' => array()
        );


        $array['brands'] = $brands->getList();
        $brand_products = $products->getListOfBrands($filters);

        //Criando um filtro de marcas
        foreach ($array['brands'] as $brand_key => $brand_item) {

            $array['brands'][$brand_key]['count'] = 0;

            foreach ($brand_products as $brand_product) {
                if ($brand_product['id_brand'] == $brand_item['id']) {

                    $array['brands'][$brand_key]['count'] = $brand_product['c'];
                }
            }
            if ($array['brands'][$brand_key]['count'] == '0') {
                unset($array['brands'][$brand_key]);
            }
        }

        //Criando filtro de Preço      
        if (isset($filters['slider0'])) {
            $array['slider0'] = $filters['slider0'];
        }

        if (isset($filters['slider1'])) {
            $array['slider1'] = $filters['slider1'];
        }

        $array['maxslider'] = $products->getMaxPrice($filters);
        if ($array['slider1'] == 0) {
            $array['slider1'] = $array['maxslider'];
        }

        //Criando o filtro das estrelas
        $star_products = $products->getListOfStars($filters);
        foreach ($array['stars'] as $star_key => $star_item) {
            foreach ($star_products as $star_product) {
                if ($star_product['rating'] == $star_key) {
                    $array['stars'][$star_key] = $star_product['c'];
                }
            }
        }
        //Criando o filtro das promoções
        $array['sale'] = $products->getSaleCount($filters);

        //Criando o filtro das opções
        $array['options'] = $products->getAvailableOptions($filters);

        return $array;
    }
}
