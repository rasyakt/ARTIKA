@php
$currentLanguage = App::getLocale();
$languages = config('app.supported_languages', ['id' => 'Bahasa Indonesia', 'en' => 'English']);
@endphp

<div class="language-selector">
    <div class="btn-group" role="group" aria-label="Language Selection">
        @foreach($languages as $code => $name)
            @if($code === $currentLanguage)
                <a href="{{ route('language.change', ['lang' => $code]) }}" 
                   class="btn btn-sm btn-primary active" 
                   role="button">
                    {{ $name }}
                </a>
            @else
                <a href="{{ route('language.change', ['lang' => $code]) }}" 
                   class="btn btn-sm btn-outline-primary" 
                   role="button">
                    {{ $name }}
                </a>
            @endif
        @endforeach
    </div>
</div>

<style>
.language-selector {
    display: flex;
    gap: 0.5rem;
}

.language-selector .btn {
    font-size: 0.875rem;
    padding: 0.375rem 0.75rem;
    text-decoration: none;
    border: 1px solid currentColor;
    border-radius: 0.25rem;
    cursor: pointer;
    transition: all 0.2s ease;
}

.language-selector .btn.active {
    background-color: #007bff;
    color: white;
    border-color: #007bff;
}

.language-selector .btn-outline-primary:hover {
    background-color: #007bff;
    color: white;
    border-color: #007bff;
}
</style>
