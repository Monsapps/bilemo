<?php

namespace App\Model;

abstract class AbstractModel
{
    public $data;
    public $_links;
    public $meta;

    public function addMeta($name, $value) : void
    {
        if (isset($this->meta[$name])) {
            throw new \LogicException(sprintf('This meta already exists. You are trying to override this meta, use the setMeta method instead for the %s meta.', $name));
        }
        
        $this->setMeta($name, $value);
    }
    
    public function setMeta($name, $value) : void
    {
        $this->meta[$name] = $value;
    }

}
