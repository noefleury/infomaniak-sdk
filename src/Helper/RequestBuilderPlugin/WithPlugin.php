<?php

namespace NoeFleury\InfomaniakSdk\Helper\RequestBuilderPlugin;

trait WithPlugin
{

    protected array $with = [];

    public function with(string $attribute): self
    {
        if (!in_array($attribute, $this->with)) {
            $this->with[] = $attribute;
        }
        return $this;
    }

    public function handleWithPayload()
    {
        if (!empty($this->with)) {
            $this->payload['with'] = implode(',', $this->with);
        }
    }


}
