<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php
        $cover = $ebook->cover_image
            ? (\Illuminate\Support\Str::startsWith($ebook->cover_image, ['http://', 'https://']) ? $ebook->cover_image : asset('storage/'.$ebook->cover_image))
            : config('brand.images.ebooks');
    @endphp
    @include('partials.head-assets', [
        'title' => $ebook->title,
        'description' => $ebook->description,
        'image' => $cover,
        'type' => 'article',
    ])
    <style>
        .reader-night {
            background: #130806;
            color: #fef3c7;
        }
        .reader-night .reader-panel {
            background: #1f0f0b;
            border-color: rgba(251, 191, 36, 0.22);
            color: #fffbeb;
        }
        .reader-night .reader-muted {
            color: #fcd9a2;
        }
        .reader-night .reader-verse {
            background: #2a130d;
            border-color: rgba(251, 191, 36, 0.18);
        }
        .reader-hit mark {
            background: #fde68a;
            color: #431407;
            padding: 0 0.15rem;
            border-radius: 0.25rem;
        }
    </style>
</head>
<body class="font-sans text-slate-900 antialiased" data-reader-page data-book-id="{{ $ebook->id }}">
    @include('partials.navbar')
    @include('partials.toasts')

    <header class="relative overflow-hidden text-white no-print">
        <img src="{{ $cover }}" alt="{{ $ebook->title }} cover" class="absolute inset-0 h-full w-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-[#2a0d09] via-[#431407]/80 to-[#431407]/35"></div>
        <div class="max-w-7xl mx-auto px-6 py-16 relative z-10">
            <a href="{{ route('ebooks.index') }}" class="inline-flex items-center gap-2 text-amber-100 font-bold mb-6"><i class="fa-solid fa-arrow-left"></i> Back to Library</a>
            <p class="uppercase tracking-[0.25em] text-xs font-bold text-amber-100">{{ $ebook->category }}</p>
            <h1 class="brand-heading text-4xl md:text-6xl font-bold mt-3">{{ $ebook->title }}</h1>
            <p class="mt-4 max-w-2xl text-orange-50 leading-8">{{ $ebook->description }}</p>
            <div class="mt-5 flex flex-wrap gap-3 text-sm text-orange-100">
                <span><i class="fa-solid fa-eye mr-1"></i>{{ $ebook->views }} views</span>
                <span><i class="fa-solid fa-book-open mr-1"></i>{{ $chapters ? count($chapters) : 1 }} chapters</span>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 py-10">
        <div class="grid grid-cols-1 lg:grid-cols-[18rem_1fr] gap-8">
            <aside class="no-print space-y-4 lg:sticky lg:top-24 h-fit">
                <div class="reader-panel divine-card rounded-2xl p-5">
                    <h2 class="brand-heading font-bold text-[#431407] mb-4">Reader Tools</h2>
                    <div class="grid gap-3">
                        <input data-reader-search type="search" placeholder="Search within book" class="w-full rounded-xl border border-amber-200 px-4 py-3 outline-none focus:ring-2 focus:ring-orange-500">
                        <button type="button" data-night-toggle class="rounded-xl bg-[#431407] px-4 py-3 text-sm font-bold text-white"><i class="fa-solid fa-moon mr-2"></i>Night Mode</button>
                        <button type="button" data-bookmark-list-toggle class="rounded-xl border border-amber-200 bg-orange-50 px-4 py-3 text-sm font-bold text-[#431407]"><i class="fa-solid fa-bookmark mr-2"></i>Bookmarks</button>
                        <button type="button" onclick="window.print()" class="rounded-xl bg-orange-600 px-4 py-3 text-sm font-bold text-white"><i class="fa-solid fa-file-pdf mr-2"></i>Download PDF</button>
                    </div>
                    <div data-bookmark-list class="hidden mt-4 border-t border-amber-100 pt-4 text-sm"></div>
                </div>

                @if($chapters)
                    <div class="reader-panel divine-card rounded-2xl p-5">
                        <h2 class="brand-heading font-bold text-[#431407] mb-4">Chapters</h2>
                        <div class="grid gap-2 text-sm">
                            @foreach($chapters as $chapterIndex => $chapter)
                                <a href="#chapter-{{ $chapterIndex }}" class="rounded-lg px-3 py-2 hover:bg-orange-50 text-slate-700">{{ $chapter['title'] }}</a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </aside>

            <article class="reader-panel print-surface divine-card rounded-2xl p-5 md:p-9">
                @if($chapters)
                    <div class="space-y-10">
                        @foreach($chapters as $chapterIndex => $chapter)
                            <section id="chapter-{{ $chapterIndex }}" data-reader-chapter>
                                <h2 class="brand-heading text-3xl font-bold text-[#431407] mb-5">{{ $chapter['title'] }}</h2>
                                <div class="space-y-6">
                                    @foreach($chapter['sections'] as $sectionIndex => $section)
                                        <div class="reader-hit rounded-2xl border border-amber-100 p-4 md:p-6" data-reader-section data-bookmark-key="{{ $chapterIndex }}-{{ $sectionIndex }}" data-bookmark-title="{{ $chapter['title'] }} · {{ $section['heading'] }}">
                                            <div class="flex items-start justify-between gap-3 mb-3">
                                                <h3 class="font-bold text-lg text-orange-800">{{ $section['heading'] }}</h3>
                                                <button type="button" data-bookmark class="no-print h-9 w-9 rounded-full bg-orange-50 text-orange-700 hover:bg-orange-100" aria-label="Bookmark section"><i class="fa-regular fa-bookmark"></i></button>
                                            </div>
                                            <div class="reader-verse hindi-copy whitespace-pre-line rounded-2xl border border-amber-100 bg-orange-50/60 p-4 text-xl leading-10 text-[#431407]" data-verse>{{ $section['verse'] }}</div>
                                            <p class="reader-muted hindi-copy mt-4 text-slate-700 leading-8"><span class="font-bold text-orange-800">अर्थ:</span> <span data-meaning>{{ $section['meaning'] }}</span></p>
                                        </div>
                                    @endforeach
                                </div>
                            </section>
                        @endforeach
                    </div>
                @elseif($ebook->content)
                    <div class="prose max-w-none text-slate-700 leading-8 whitespace-pre-line">{!! nl2br(e($ebook->content)) !!}</div>
                @else
                    <p class="text-slate-400 text-center py-12">Content coming soon...</p>
                @endif
            </article>
        </div>

        @if($related->count() > 0)
            <section class="mt-12 no-print">
                <h2 class="brand-heading text-2xl font-bold text-[#431407] mb-5">Related Books</h2>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    @foreach($related as $r)
                        <a href="{{ route('ebooks.show', $r) }}" class="bg-white rounded-2xl shadow-sm border border-amber-100 p-4 hover:shadow-md transition"><h3 class="font-bold text-sm text-[#431407]">{{ $r->title }}</h3><p class="text-xs text-slate-500 mt-1">{{ $r->category }}</p></a>
                    @endforeach
                </div>
            </section>
        @endif
    </main>

    @include('partials.footer')
    <script>
        (() => {
            const body = document.querySelector('[data-reader-page]');
            if (!body) return;
            const bookId = body.dataset.bookId;
            const nightKey = `vedReaderNight:${bookId}`;
            const bookmarkKey = `vedReaderBookmarks:${bookId}`;
            const search = document.querySelector('[data-reader-search]');
            const bookmarkList = document.querySelector('[data-bookmark-list]');

            if (localStorage.getItem(nightKey) === '1') document.body.classList.add('reader-night');

            document.querySelector('[data-night-toggle]')?.addEventListener('click', () => {
                document.body.classList.toggle('reader-night');
                localStorage.setItem(nightKey, document.body.classList.contains('reader-night') ? '1' : '0');
            });

            const getBookmarks = () => JSON.parse(localStorage.getItem(bookmarkKey) || '[]');
            const setBookmarks = (items) => localStorage.setItem(bookmarkKey, JSON.stringify(items));
            const renderBookmarks = () => {
                if (!bookmarkList) return;
                const items = getBookmarks();
                bookmarkList.innerHTML = items.length
                    ? items.map((item) => `<a class="block py-2 border-b border-amber-100 last:border-0 hover:text-orange-700" href="#${item.id}">${item.title}</a>`).join('')
                    : '<p class="text-slate-400">No bookmarks yet.</p>';
            };

            document.querySelector('[data-bookmark-list-toggle]')?.addEventListener('click', () => {
                bookmarkList?.classList.toggle('hidden');
                renderBookmarks();
            });

            document.querySelectorAll('[data-reader-section]').forEach((section, index) => {
                section.id = section.id || `reader-section-${index}`;
                section.querySelector('[data-bookmark]')?.addEventListener('click', () => {
                    const items = getBookmarks();
                    const item = { id: section.id, title: section.dataset.bookmarkTitle };
                    const exists = items.some((saved) => saved.id === item.id);
                    setBookmarks(exists ? items.filter((saved) => saved.id !== item.id) : [...items, item]);
                    window.vedToast?.(exists ? 'Bookmark removed.' : 'Bookmark saved.', exists ? 'info' : 'success');
                    renderBookmarks();
                });
            });

            search?.addEventListener('input', () => {
                const term = search.value.trim().toLowerCase();
                document.querySelectorAll('[data-reader-section]').forEach((section) => {
                    const text = section.textContent.toLowerCase();
                    section.classList.toggle('hidden', Boolean(term) && !text.includes(term));
                });
            });

            renderBookmarks();
        })();
    </script>
</body>
</html>
