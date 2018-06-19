<?php

namespace Varhall\Utilino\Collections;

/**
 * Objects which can be serialized to array or JSON
 *
 * @author Ondrej Sibrava <sibrava@varhall.cz>
 */
interface ISerializable
{
    public function toArray();
    
    public function toJson();
}
