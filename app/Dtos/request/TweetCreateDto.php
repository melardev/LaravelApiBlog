<?php

namespace App\Dtos;
class TweetCreateDto
{


    private $content;

    private $parentId;

    public function getParentId() {
        return $this->parentId;
    }

    public function setParentId($parentId) {
        $this->parentId = parentId;
    }

    public function getContent() {
        return $this->content;
    }

    public function setContent($content) {
        $this->content = $content;
    }
}
