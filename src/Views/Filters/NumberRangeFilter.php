<?php

namespace Rappasoft\LaravelLivewireTables\Views\Filters;

use Rappasoft\LaravelLivewireTables\Views\Filter;

class NumberRangeFilter extends Filter
{
    public array $options = [];

    public array $config = [];

    public function options(array $options = []): NumberRangeFilter
    {
        $this->options = [...config('livewire-tables.numberRange.defaultOptions'), ...$options];

        return $this;
    }

    public function getOptions(): array
    {
        return ! empty($this->options) ? $this->options : $this->options = config('livewire-tables.numberRange.defaultOptions');
    }

    public function config(array $config = []): NumberRangeFilter
    {
        $this->config = [...config('livewire-tables.numberRange.defaultConfig'), ...$config];

        return $this;
    }

    public function getConfigs(): array
    {
        return ! empty($this->config) ? $this->config : $this->config = config('livewire-tables.numberRange.defaultConfig');
    }

    public function validate(array $values): array|bool
    {
        if (empty($this->config)) {
            $this->getConfigs();
        }

        $values['min'] = isset($values['min']) ? intval($values['min']) : null;
        $values['max'] = isset($values['max']) ? intval($values['max']) : null;
        if ($values['min'] == 0 && $values['max'] == 0) {
            return false;
        }
        if ($values['max'] < $values['min']) {
            $tmpMin = $values['min'];
            $values['min'] = $values['max'];
            $values['max'] = $tmpMin;
        }

        if (! isset($values['min']) || ! is_numeric($values['min']) || $values['min'] < intval($this->getConfig('minRange')) || $values['min'] > intval($this->getConfig('maxRange'))) {
            return false;
        }
        if (! isset($values['max']) || ! is_numeric($values['max']) || $values['max'] > intval($this->getConfig('maxRange')) || $values['max'] < intval($this->getConfig('minRange'))) {
            return false;
        }

        return ['min' => $values['min'], 'max' => $values['max']];
    }

    public function isEmpty(array|string $value): bool
    {
        if (! is_array($value)) {
            return true;
        } else {
            if (! isset($value['min']) || ! isset($value['max']) || $value['min'] == '' || $value['max'] == '') {
                return true;
            }

            if (intval($value['min']) == intval($this->getConfig('minRange')) && intval($value['max']) == intval($this->getConfig('maxRange'))) {
                return true;
            }
        }

        return false;
    }

    public function getDefaultValue(): array|string
    {
        return [];
    }

    public function getFilterPillValue($values): ?string
    {
        if ($this->validate($values)) {
            return __('Min:').$values['min'].', '.__('Max:').$values['max'];
        }

        return '';
    }

    public function render(string $filterLayout, string $tableName, bool $isTailwind, bool $isBootstrap4, bool $isBootstrap5): string|\Illuminate\Contracts\Foundation\Application|\Illuminate\View\View|\Illuminate\View\Factory
    {

        return view('livewire-tables::components.tools.filters.number-range', [
            'filterLayout' => $filterLayout,
            'tableName' => $tableName,
            'isTailwind' => $isTailwind,
            'isBootstrap' => ($isBootstrap4 || $isBootstrap5),
            'isBootstrap4' => $isBootstrap4,
            'isBootstrap5' => $isBootstrap5,
            'filter' => $this,
        ]);
    }
}