<?php

namespace Bernskiold\LaravelEuromonitor\Api\Resources;

use Bernskiold\LaravelEuromonitor\Api\EuromonitorApiClient;
use Bernskiold\LaravelEuromonitor\Exceptions\InvalidEuromonitorQueryException;
use Illuminate\Support\Facades\Validator;

use function class_uses;
use function method_exists;

class EuromonitorResource
{
    public function __construct(
        protected EuromonitorApiClient $client,
    ) {}

    /**
     * @throws InvalidEuromonitorQueryException
     */
    public function buildQuery(): array
    {
        $query = [];
        $traits = class_uses($this);

        foreach ($traits as $trait) {
            try {
                $traitShortName = (new \ReflectionClass($trait))->getShortName();
                $traitMethod = 'buildQuery'.$traitShortName;

                if (method_exists($this, $traitMethod)) {
                    $query = $this->$traitMethod($query);
                }
            } catch (\ReflectionException) {
                // Do nothing
            }
        }

        $this->validateQuery($query);

        return $query;
    }

    protected function validateQuery(array $query): void
    {
        $rules = array_merge($this->getRulesForSupports(), $this->getRules());
        $messages = array_merge($this->getMessagesForSupports(), $this->getMessages());

        $validator = Validator::make($query, $rules, $messages);

        if ($validator->fails()) {
            throw new InvalidEuromonitorQueryException('Invalid query', $validator->errors()->all());
        }
    }

    protected function getRulesForSupports(): array
    {
        $query = [];
        $traits = class_uses($this);

        foreach ($traits as $trait) {
            try {
                $traitShortName = (new \ReflectionClass($trait))->getShortName();
                $traitMethod = 'getRules'.$traitShortName;

                if (method_exists($this, $traitMethod)) {
                    $query = $this->$traitMethod($query);
                }
            } catch (\ReflectionException) {
                // Do nothing
            }
        }

        return $query;
    }

    protected function getMessagesForSupports(): array
    {
        $query = [];
        $traits = class_uses($this);

        foreach ($traits as $trait) {
            try {
                $traitShortName = (new \ReflectionClass($trait))->getShortName();
                $traitMethod = 'getMessages'.$traitShortName;

                if (method_exists($this, $traitMethod)) {
                    $query = $this->$traitMethod($query);
                }
            } catch (\ReflectionException) {
                // Do nothing
            }
        }

        return $query;
    }

    protected function getRules(): array
    {
        return [];
    }

    protected function getMessages(): array
    {
        return [];
    }
}
