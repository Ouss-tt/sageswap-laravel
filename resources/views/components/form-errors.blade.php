@if ($errors->any())
    <div class="form-error" role="alert">
        @foreach ($errors->all() as $message)
            <p class="form-error__item">{{ $message }}</p>
        @endforeach
    </div>
@endif
