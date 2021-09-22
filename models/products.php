<?php

class Products extends model
{

    public function getAvailableOptions($filters = array())
    {

        $groups = array();
        $ids = array();

        $where = $this->buildWhere($filters);

        $sql = "SELECT id, options
        FROM products
        WHERE " . implode(' AND ', $where);

        $sql = $this->db->prepare($sql);

        $this->bindWhere($filters, $sql);

        $sql->execute();

        if ($sql->rowCount() > 0) {
            foreach ($sql->fetchAll() as $product) {

                //Retira a vírgula do campo options
                $options = explode(",", $product['options']);
                $ids[] = $product['id'];
                foreach ($options as $option) {
                    if (!in_array($option, $groups)) {
                        $groups[] = $option;
                    }
                }
            }
        }

        $options = $this->getAvailableValuesFromOptions($groups, $ids);

        return $options;
    }

    public function getAvailableValuesFromOptions($groups, $ids)
    {
        $array = array();
        $options = new Options();

        foreach ($groups as $option_group) {
            $array[$option_group] = array(
                'name' => $options->getName($option_group),
                'options' => array()
            );
        }

        $sql = "SELECT product_value, id_option,
        COUNT(id_option) as c
        FROM products_options
        WHERE id_option IN ('" . implode("','", $groups) . "') AND
        id_product IN ('" . implode("','", $ids) . "')
        GROUP BY product_value ORDER BY id_option";

        $sql = $this->db->query($sql);
        if ($sql->rowCount() > 0) {
            foreach ($sql->fetchAll() as $options) {
                $array[$options['id_option']]['options'][] = array(
                    'id' => $options['id_option'],
                    'value' => $options['product_value'],
                    'count' => $options['c']
                );
            }
        }

        return $array;
    }

    public function getSaleCount($filters = array())
    {
        $where = $this->buildWhere($filters);

        $where[] = 'sale = "1"';

        $sql = "SELECT COUNT(*) AS c
        FROM products
        WHERE " . implode(' AND ', $where);
        $sql = $this->db->prepare($sql);

        $this->bindWhere($filters, $sql);

        $sql->execute();

        if ($sql->rowCount() > 0) {
            $sql = $sql->fetch();

            return $sql['c'];
        } else {
            return '0';
        }
    }

    public function getMaxPrice($filters = array())
    {

        $where = $this->buildWhere($filters);

        $sql = "SELECT price FROM products 
        WHERE " . implode(' AND ', $where) . " 
        ORDER BY price 
        DESC LIMIT 1";
        $sql = $this->db->prepare($sql);

        $this->bindWhere($filters, $sql);

        $sql->execute();

        if ($sql->rowCount() > 0) {
            $sql = $sql->fetch();

            return $sql['price'];
        } else {
            return '0';
        }
    }

    public function getListOfStars($filters = array())
    {
        $array = array();

        $where = $this->buildWhere($filters);

        $sql = "SELECT rating, name, COUNT(id) c 
        FROM products 
        WHERE " . implode(' AND ', $where) . " 
        GROUP BY rating";
        $sql = $this->db->prepare($sql);

        $this->bindWhere($filters, $sql);

        $sql->execute();

        if ($sql->rowCount() > 0) {
            $array = $sql->fetchAll();
        }

        return $array;
    }

    public function getListOfBrands($filters = array())
    {
        $array = array();

        $where = $this->buildWhere($filters);

        $sql = "SELECT id_brand, name, COUNT(id) c FROM products WHERE " . implode(' AND ', $where) . " GROUP BY id_brand";
        $sql = $this->db->prepare($sql);

        $this->bindWhere($filters, $sql);

        $sql->execute();

        if ($sql->rowCount() > 0) {
            $array = $sql->fetchAll();
        }

        return $array;
    }
    public function getList($offset = 0, $limit = 3, $filters = array())
    {
        $array = array();

        $where = $this->buildWhere($filters);

        $sql = "SELECT * FROM products 
        WHERE " . implode(' AND ', $where) . "
        LIMIT $offset, $limit";
        $sql = $this->db->prepare($sql);

        $this->bindWhere($filters, $sql);

        $sql->execute();

        if ($sql->rowCount() > 0) {
            $array = $sql->fetchAll();

            $brands = new Brands();

            foreach ($array as $key => $item) {
                $array[$key]['brand_name'] = $brands->getNameById(
                    $item['id_brand']
                );
            }

            foreach ($array as $key => $item) {

                $array[$key]['images'] = $this->getImagesByProductId($item['id']);
            }
        }
        return $array;
    }

    public function getTotal($filters = array())
    {

        $where = $this->buildWhere($filters);

        $sql = "SELECT COUNT(*) AS c FROM products
        WHERE " . implode(' AND ', $where);
        $sql = $this->db->prepare($sql);

        $this->bindWhere($filters, $sql);

        $sql->execute();
        $sql = $sql->fetch();

        return $sql['c'];
    }

    public function getImagesByProductId($id)
    {
        $array = array();

        $sql = "SELECT url FROM  products_images  WHERE id_product = :id";
        $sql = $this->db->prepare($sql);
        $sql->bindValue(":id", $id);
        $sql->execute();

        if ($sql->rowCount() > 0) {
            $array = $sql->fetchAll();
        }

        return $array;
    }

    private function buildWhere($filters)
    {
        $where = array(
            '1 = 1'
        );

        if (!empty($filters['category'])) {
            $where[] = "id_category = :id_category";
        }

        if(!empty($filters['brand'])){
            $where[] = "id_brand IN('".implode("','",$filters['brand'])."')";
        }

        if(!empty($filters['star'])){
            $where[] = "rating IN('".implode("','",$filters['star'])."')";
        }

        if(!empty($filters['sale'])){
            $where[] = "sale = '1'";
        }

        if(!empty($filters['options'])){
            $where[] = "id IN (SELECT id_product FROM products_options 
            WHERE products_options.product_value  IN('".implode("','",$filters['options'])."'))";
        }

        return $where;
    }

    private function bindWhere($filters, &$sql)
    {
        if (!empty($filters['category'])) {
            $where[] = "id_category = :id_category";
        }
    }
}
