<?php

namespace NoeFleury\InfomaniakSdk\Helper\RequestBuilderPlugin;

trait OffsetPlugin
{

    protected int $limit;
    protected int $skip;

    public function limit(int $count): self
    {
        $this->limit = $count;
        return $this;
    }

    public function skip(int $count): self
    {
        $this->skip = $count;
        return $this;
    }

    public function handleOffsetPayload()
    {
        if (isset($this->limit)) {
            $this->payload['limit'] = $this->limit;
        }
        if (isset($this->skip)) {
            $this->payload['skip'] = $this->skip;
        }
    }

}
