<?php

namespace Varhall\Utilino\Collections;


interface ISearchable
{
    public function search($value, callable $func = NULL);
}