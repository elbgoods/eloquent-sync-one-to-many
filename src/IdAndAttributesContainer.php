<?php

namespace Elbgoods\SyncOneToMany;

class IdAndAttributesContainer
{
    protected $id;
    protected array $additionalAttributes;

    /**
     * @param mixed $arrayKey
     * @param int|array $arrayValue
     */
    public function __construct($arrayKey, $arrayValue)
    {
        if ($this->hasAdditionalAttributes($arrayValue)) {
            $this->id = $arrayKey;
            $this->additionalAttributes = $arrayValue;
        } else {
            $this->id = $arrayValue;
            $this->additionalAttributes = [];
        }
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public function getAdditionalAttributes(): array
    {
        return $this->additionalAttributes;
    }

    /**
     * @param int|array $arrayValue
     */
    protected function hasAdditionalAttributes($arrayValue): bool
    {
        return is_array($arrayValue);
    }
}
