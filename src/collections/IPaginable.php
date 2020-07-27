<?php

namespace Varhall\Utilino\Collections;


interface IPaginable
{
    public function limit(?int $limit, ?int $offset = null);
}