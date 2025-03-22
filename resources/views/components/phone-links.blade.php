@if ($phone)
    <a href="tel:{{ $phone }}" class="text-primary-600 cursor-pointer">
        📞 {{ $phone }}
    </a>
    |
    <a href="https://wa.me/+2{{ $phone }}" class="text-primary-600 cursor-pointer" target="_blank">
        💬 WhatsApp
    </a>
@endif
