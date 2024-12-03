<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;

use App\Models\Note;

class NoteController extends Controller
{
    public function home(Request $request): View
    {
        $search = $request->input('search');
        $query = Note::query()->where();

        if ($search) {
            $query->where('title', 'LIKE', "%{$search}%");
        }

        $note = $query->paginate(9);

        if ($form->isEmpty() && $search) {
            session()->flash('error', 'Catatan tidak ditemukan');
        }

        return view('website.home', compact('note'));
    }

    public function read($slug): View
    {
        $note = Note::where('slug', $slug)->firstOrFail();
        return view('website.read', compact('note'));
    }

    public function create(): View
    {
        return view('website.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' =>  'required|string|regex:/^[^;\'"\*]+$/|unique:note,title',
            'text'  =>  'required|text',
        ], [
            'title.regex'   =>  'Judul tidak boleh mengandung karakter selain huruf',
            'title.unique'  =>  'Sudah ada catatan dengan judul yang sama, coba judul lain'
        ]);

        $sanitizedData = [
            'title' =>  htmlspecialchars($request->title, ENT_QUOTES, 'UTF-8'),
            'text'  =>  htmlspecialchars($request->text, ENT_QUOTES, 'UTF-8'),
        ];

        $slug = $this->genereateUniqueSlug($sanitizedData['title']);

        Note::create([
            'title' =>  $sanitizedData['title'],
            'text'  =>  $sanitizedData['text'],
        ]);

        return redirect()->route('home')->with(['success' => 'Catatan berhasil dibuat!']);
    }

    public function edit(string $slug): View
    {
        $note = Note::where('slug', $slug)->firstOrFail();
        return view('website.edit', compact('note'));
    }

    public function update(Request $request, string $slug): RedirectResponse
    {
        $note = Note::where('slug', $slug)->firstOrFail();

        $request->validate([
            'title' =>  'required|string|regex:/^[^;\'"\*]+$/|unique:note,title',
            'text'  =>  'required|text',
        ], [
            'title.regex'   =>  'Judul tidak boleh mengandung karakter selain huruf',
            'title.unique'  =>  'Sudah ada catatan dengan judul yang sama, coba judul lain'
        ]);

        $sanitizedData = [
            'title' =>  htmlspecialchars($request->title, ENT_QUOTES, 'UTF-8'),
            'text'  =>  htmlspecialchars($request->text, ENT_QUOTES, 'UTF-8'),
        ];

        $slug = $this->generateUniqueSlug($sanitizedData['title']);

        $note->update([
            'title' =>  $sanitizedData['title'],
            'text'  =>  $sanitizedData['text'],
        ]);

        return redirect()->route('home')->with(['success' => 'Catatan berhasil diperbarui!']);
    }

    public function delete($slug, Request $request)
    {
        $note = Note::where('slug', $slug)->first();

        $note->delete();
        return redirect()->route('home')->with('success', 'Catatan berhasil dihapus');
    }

    private function generateUniqueSlug($title, $existingSlug = null)
    {
        $slug = Str::slug($title, '-');
        $originalSlug = $slug;
        $counter = 1;

        while (Note::where('slug', $slug)->where('slug', '!=', $existingSlug)->exist()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
