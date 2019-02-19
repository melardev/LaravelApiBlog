<?php
namespace App\Dtos;
class CommentForm
{

    //@NotNull
    private $productId;


    private $description;

    public function getProductId() {
        return $this->productId;
    }

    public function setProductId(Long $productId) {
        $this->productId = $productId;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription(String $description) {
        $this->description = $description;
    }
}
