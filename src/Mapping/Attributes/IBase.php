<?php

namespace Varhall\Utilino\Mapping\Attributes;

interface IBase
{
    public function schema(): \Nette\Schema\Schema;
}