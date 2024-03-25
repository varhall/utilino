<?php

namespace Varhall\Utilino;

/**
 * Objects which can be serialized to array or JSON
 *
 * @author Ondrej Sibrava <sibrava@varhall.cz>
 */
interface ISerializable
{
    /**
     * Converts to object to deep array (each sub-item must be array)
     *
     * @return array
     */
    public function toArray();
}
