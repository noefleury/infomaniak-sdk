<?php

namespace NoeFleury\InfomaniakSdk\Helper\RequestBuilderPlugin;

use NoeFleury\InfomaniakSdk\Enum\RequestBuilder\OrderDirection;

trait SortingPlugin
{

    protected string $orderBy;
    protected OrderDirection $orderDirection;

    public function orderBy(string $attribute, ?OrderDirection $direction = OrderDirection::Ascendant): self
    {
        $this->orderBy = $attribute;
        if (!is_null($direction)) {
            $this->orderDirection($direction);
        }
        return $this;
    }

    public function orderDirection(OrderDirection $direction): self
    {
        $this->orderDirection = $direction;
        return $this;
    }

    public function ascendant(): self
    {
        $this->orderDirection(OrderDirection::Ascendant);
        return $this;
    }

    public function descendant(): self
    {
        $this->orderDirection(OrderDirection::Descendant);
        return $this;
    }

    public function handleSortingPayload()
    {
        if (!empty($this->orderBy)) {
            $this->payload['order_by'] = $this->orderBy;
            if (!empty($this->orderDirection)) {
                $this->payload['order'] = $this->orderDirection->value;
            }
        }
    }


}
