@foreach ($blocks as $block)
    <option value="{{ $block->block }}">{{ $block->block }}</option>
@endforeach