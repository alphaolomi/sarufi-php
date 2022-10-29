<?php

namespace Alphaolomi\Sarufi;

class Bot
{
    protected $id;
    protected $name;
    protected $industry;
    protected $description;
    protected $visible_on_community;
    protected $intents;
    protected $flows;
    protected $token;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->industry = $data['industry'];
        $this->description = $data['description'];
        $this->visible_on_community = $data['visible_on_community'];
        $this->intents = $data['intents'];
        $this->flow = $data['flow'];
        $this->token = $data['token'];
    }

    public function createFromArray(array $data)
    {
        return new self($data);
    }
}
