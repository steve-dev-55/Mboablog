<?php

namespace App\Controllers;

use App\Models\TagModel;

class TagController extends BaseController
{
    private $tagModel;

    public function __construct()
    {
        parent::__construct();
        $this->tagModel = new TagModel();
    }

    public function search()
    {
        if ($this->isGet() && $this->getQueryData()['search']) {
            $searchTerm = $this->getQueryData()['search'];
            $tags = $this->tagModel->getWhere('name', $searchTerm);
            $this->json($tags);
            exit;
        }
    }
}
