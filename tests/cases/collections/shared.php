<?php

namespace Tests\Utilino\Collections;

use Tester\Assert;
use Varhall\Utilino\Collections\ArrayCollection;
use Varhall\Utilino\Utils\Path;

require __DIR__ . '/../../bootstrap.php';

function create() {
    return ArrayCollection::range(1, 10);
}

