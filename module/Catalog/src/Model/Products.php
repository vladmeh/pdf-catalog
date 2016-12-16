<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Catalog\Model;


class Products
{
    public $id;
    public $categoryId;
    public $sku;
    public $name;
    public $image;
    public $uploadPath;
    public $draft;
    public $uploadPathDraft;
    public $note;
    public $fullPath;

    public function exchangeArray(array $data)
    {
        $this->id = !empty($data['id']) ? $data['id'] : null;
        $this->categoryId = !empty(['category_id']) ? $data['category_id'] : null;
        $this->sku = !empty(['sku']) ? $data['sku'] : null;
        $this->name = !empty(['name']) ? $data['name'] : null;
        $this->image = !empty(['image']) ? $data['image'] : null;
        $this->uploadPath = !empty(['upload_path']) ? $data['upload_path'] : null;
        $this->draft = !empty(['draft']) ? $data['draft'] : null;
        $this->uploadPathDraft = !empty(['upload_path_draft']) ? $data['upload_path_draft'] : null;
        $this->note = !empty(['note']) ? $data['note'] : null;
        $this->fullPath = !empty(['full_path']) ? $data['full_path'] : null;
    }
}