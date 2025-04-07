<div>
    @error($inputName)
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message ?? '' }}</strong>
        </span>
    @enderror
</div>