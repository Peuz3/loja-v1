<?php
class homeController extends controller {

	private $user;

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $dados = array();

        $filters = array();
        if(!empty($_GET['filter']) && is_array($_GET['filter'])){
            $filters = $_GET['filter'];
        }

        $products = new Products();
        $categories = new Categories();
        $f = new Filters();

        $currentPage = 1;
        $offset = 0;
        $limit = 3;
       
        if(!empty($_GET['p'])){
            $currentPage = $_GET['p'];
        }

        $offset = ($currentPage * $limit) - $limit;

        $dados['list'] = $products->getList($offset,$limit,$filters);
        $dados['totalItems'] = $products->getTotal($filters);
        $dados['numberOfPages'] = ceil($dados['totalItems'] / $limit);
        $dados['currentPage'] = $currentPage;

        $dados['categories'] = $categories->getList();

        $dados['filters'] = $f->getFilters($filters);
        $dados['filters_selected'] = $filters;


        $this->loadTemplate('home', $dados);
    }

}