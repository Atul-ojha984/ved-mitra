@php
    $brand = config('brand');
    $legalPages = config('legal');
@endphp
@once
    <style>
        .brand-heading { font-family: 'Cinzel', 'Outfit', serif; letter-spacing: 0; }
        .temple-gradient { background: linear-gradient(135deg, #6b1111 0%, #a3280f 42%, #f97316 72%, #d6a83f 100%); }
        .divine-glow { box-shadow: 0 0 34px rgba(251, 191, 36, 0.28); }
        .form-input-error { border-color: #ef4444 !important; box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.14) !important; }
    </style>
@endonce
<footer id="contact" class="bg-[#2a0d09] text-orange-50 no-print">
    <div class="max-w-7xl mx-auto px-6 py-14">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-10">
            <div class="lg:col-span-2">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-3 brand-heading text-2xl font-bold text-white">
                    <span class="h-11 w-11 rounded-full temple-gradient divine-glow flex items-center justify-center"><i class="fa-solid fa-om"></i></span>
                    {{ config('app.name', 'Ved Mitra') }}
                </a>
                <p class="mt-4 text-orange-100 leading-relaxed">{{ $brand['tagline'] }}</p>
                <p class="mt-3 text-sm text-orange-200/80">Our mission is to make authentic Hindu rituals, Vedic guidance, festival wisdom, and devotional reading simple, respectful, and accessible for every family.</p>
            </div>

            <div>
                <h3 class="font-bold text-white mb-4">Quick Links</h3>
                <div class="grid gap-2 text-sm text-orange-100/85">
                    <a class="hover:text-white" href="{{ url('/') }}">Home</a>
                    <a class="hover:text-white" href="{{ route('pandit.search') }}">Book Pandit</a>
                    <a class="hover:text-white" href="{{ route('kundli.form') }}">Kundli</a>
                    <a class="hover:text-white" href="{{ route('festivals.index') }}">Festivals</a>
                    <a class="hover:text-white" href="{{ route('ebooks.index') }}">E-books</a>
                    <a class="hover:text-white" href="#contact">Contact</a>
                </div>
            </div>

            <div>
                <h3 class="font-bold text-white mb-4">Legal</h3>
                <div class="grid gap-2 text-sm text-orange-100/85">
                    @foreach(['privacy-policy', 'terms-and-conditions', 'refund-policy', 'cancellation-policy', 'trademark-notice', 'disclaimer'] as $slug)
                        <a class="hover:text-white" href="{{ route('legal.show', $slug) }}">{{ $legalPages[$slug]['title'] }}</a>
                    @endforeach
                </div>
            </div>

            <div>
                <h3 class="font-bold text-white mb-4">Support</h3>
                <div class="grid gap-2 text-sm text-orange-100/85">
                    @foreach(['help-center', 'faq', 'customer-support', 'email-support'] as $slug)
                        <a class="hover:text-white" href="{{ route('legal.show', $slug) }}">{{ $legalPages[$slug]['title'] }}</a>
                    @endforeach
                    <a class="hover:text-white" href="mailto:{{ $brand['support_email'] }}">{{ $brand['support_email'] }}</a>
                </div>
            </div>

            <div>
                <h3 class="font-bold text-white mb-4">Newsletter</h3>
                <form class="space-y-3" data-newsletter-form>
                    <input type="email" name="newsletter_email" required autocomplete="email" placeholder="Email address" class="w-full rounded-xl border border-white/10 bg-white/10 px-4 py-3 text-sm text-white placeholder-orange-100/60 outline-none focus:ring-2 focus:ring-amber-300">
                    <p class="hidden text-xs text-red-200" data-newsletter-error></p>
                    <button type="submit" class="w-full rounded-xl bg-amber-400 px-4 py-3 text-sm font-bold text-[#431407] hover:bg-amber-300 transition">Subscribe</button>
                </form>
                <div class="flex gap-3 mt-5 text-lg">
                    <a aria-label="Instagram" class="hover:text-white" href="{{ $brand['social']['instagram'] }}"><i class="fa-brands fa-instagram"></i></a>
                    <a aria-label="Facebook" class="hover:text-white" href="{{ $brand['social']['facebook'] }}"><i class="fa-brands fa-facebook"></i></a>
                    <a aria-label="YouTube" class="hover:text-white" href="{{ $brand['social']['youtube'] }}"><i class="fa-brands fa-youtube"></i></a>
                    <a aria-label="Twitter X" class="hover:text-white" href="{{ $brand['social']['twitter'] }}"><i class="fa-brands fa-x-twitter"></i></a>
                </div>
            </div>
        </div>
        <div class="mt-10 border-t border-white/10 pt-6 flex flex-col md:flex-row gap-3 justify-between text-sm text-orange-100/70">
            <p>© {{ date('Y') }} {{ config('app.name', 'Ved Mitra') }}. All rights reserved.</p>
            <p>Spiritual guidance is faith-based and should be used with personal discretion.</p>
        </div>
    </div>
</footer>
<script>
    document.querySelectorAll('[data-newsletter-form]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            event.preventDefault();
            const input = form.querySelector('input[type="email"]');
            const error = form.querySelector('[data-newsletter-error]');
            const button = form.querySelector('button[type="submit"]');

            if (!input.checkValidity()) {
                error.textContent = 'Enter a valid email address.';
                error.classList.remove('hidden');
                input.classList.add('form-input-error');
                return;
            }

            error.classList.add('hidden');
            input.classList.remove('form-input-error');
            button.disabled = true;
            button.classList.add('opacity-80', 'cursor-wait');
            button.textContent = 'Subscribing...';

            setTimeout(() => {
                form.reset();
                button.disabled = false;
                button.classList.remove('opacity-80', 'cursor-wait');
                button.textContent = 'Subscribe';
                window.vedToast?.('Newsletter subscription saved. Dhanyavaad.', 'success');
            }, 350);
        });
    });
</script>
