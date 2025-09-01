{{-- resources/views/programs/_form.blade.php --}}
@php
    $program = $program ?? null;
@endphp

<div class="form-group">
    <label for="name">Name</label>
    <input id="name" name="name" value="{{ old('name', $program->name ?? '') }}" class="form-control" required>
    @error('name') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label for="description">Description</label>
    <textarea id="description" name="description" class="form-control">{{ old('description', $program->description ?? '') }}</textarea>
    @error('description') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label for="national_alignment">National alignment (link or short text)</label>
    <input id="national_alignment" name="national_alignment" value="{{ old('national_alignment', $program->national_alignment ?? '') }}" class="form-control">
    @error('national_alignment') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label>Focus areas</label>
    <select name="focus_areas[]" multiple class="form-control">
        @foreach($focusOptions as $opt)
            <option value="{{ $opt }}"
                {{ in_array($opt, old('focus_areas', $program->focus_areas ?? [])) ? 'selected' : '' }}>
                {{ $opt }}
            </option>
        @endforeach
    </select>
    <small class="form-text text-muted">Hold Ctrl/Cmd to select multiple. You can also add custom ones via comma-separated input (if you want to extend).</small>
    @error('focus_areas') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label>Phases</label>
    @foreach($phaseOptions as $phase)
        <div>
            <label>
                <input type="checkbox" name="phases[]" value="{{ $phase }}"
                    {{ in_array($phase, old('phases', $program->phases ?? [])) ? 'checked' : '' }}>
                {{ $phase }}
            </label>
        </div>
    @endforeach
    @error('phases') <div class="text-danger">{{ $message }}</div> @enderror
</div>
