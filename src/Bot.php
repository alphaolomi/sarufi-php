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
    protected $modelName;
    protected  $confidenceThreshold;
    protected $evaluationMetrics;
    protected  $language; // english or swahili
    protected  $webhookUrl;
    protected  $webhookTriggerIntents;

    public function __construct(
        string $name = "My Bot",
        null|string $description = null,
        string $industry = "general",
        $flows = [],
        $intents = [],
        bool $visibleOnCommunity = false,
        $modelName = null,
        int $confidence_threshold = null,
        $evaluationMetrics = [],
        $language = null, // english or swahili
        $webhookUrl = null,
        $webhookTriggerIntents = [],
    ) {
        $this->name = $name . " #" . rand(1, 1000);
        $this->industry = $industry;
        $this->description = $description;
        $this->visible_on_community = $visibleOnCommunity;
        $this->intents = $intents;
        $this->flows = $flows;
        $this->modelName = $modelName;
        $this->confidenceThreshold = $confidence_threshold;
        $this->evaluationMetrics = $evaluationMetrics;
        $this->language = $language;
        $this->webhookUrl = $webhookUrl;
        $this->webhookTriggerIntents = $webhookTriggerIntents;
    }

    public function create(array $data)
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->industry = $data['industry'];
        $this->description = $data['description'];
        $this->visible_on_community = $data['visible_on_community'];
        $this->intents = $data['intents'];
        $this->flows = $data['flow'];
        $this->modelName = $data['model_name'];
        $this->confidenceThreshold = $data['confidence_threshold'];
        $this->evaluationMetrics = $data['evaluation_metrics'];
        $this->language = $data['language'];
        $this->webhookUrl = $data['webhook_url'];
        $this->webhookTriggerIntents = $data['webhook_trigger_intents'];
    }

    public function createFromArray(array $data)
    {
        return new self($data);
    }
}
