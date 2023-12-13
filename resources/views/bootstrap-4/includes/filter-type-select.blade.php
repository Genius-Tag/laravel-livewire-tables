<select
    onclick="event.stopPropagation();"
    wire:model.live="filters.{{ $key }}"
    id="filter-{{ $key }}"
    class="form-control"
>
    @foreach($filter->options() as $key => $value)
        <option value="{{ $key }}">{{ $value }}</option>
    @endforeach
</select>
