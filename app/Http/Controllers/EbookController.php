<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ebook;

class EbookController extends Controller
{
    public function index(Request $request)
    {
        $query = Ebook::where('is_published', true);

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('search')) {
            $query->where(function ($inner) use ($request) {
                $inner->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%')
                    ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        $ebooks = $query->latest()->paginate(12)->appends($request->query());
        $categories = [
            'chalisa' => 'Chalisa',
            'aarti' => 'Aarti Books',
            'katha' => 'Katha Books',
            'festival' => 'Festival Books',
            'puja_vidhi' => 'Puja Vidhi',
            'scripture' => 'Scriptures',
        ];
        return view('ebooks.index', compact('ebooks', 'categories'));
    }

    public function show(Ebook $ebook)
    {
        $ebook->increment('views');
        $related = Ebook::where('category', $ebook->category)->where('id', '!=', $ebook->id)->take(4)->get();
        $reader = json_decode((string) $ebook->content, true);
        $chapters = is_array($reader) && isset($reader['chapters']) ? $reader['chapters'] : null;

        return view('ebooks.show', compact('ebook', 'related', 'chapters'));
    }
}
