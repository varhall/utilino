<?php

namespace Varhall\Utilino\Collections;


interface IPaginable
{
    public function limit($limit, $offset = null);
}