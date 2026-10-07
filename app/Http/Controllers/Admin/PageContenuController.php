<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageContenu;
use Inertia\Inertia;

class PageContenuController extends Controller
{
    public function index()
    {
        $pages = PageContenu::latest('created_at')->paginate(20);

        return Inertia::render('Admin/Pages/Index', [
            'pages' => $pages,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Pages/Create');
    }

    public function store()
    {
        $validated = request()->validate([
            'slug' => 'required|string|unique:page_contenus|max:255',
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'hero_image' => 'nullable|url',
            'published' => 'boolean',
        ]);

        if ($validated['published']) {
            $validated['published_at'] = now();
        }

        PageContenu::create($validated);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page créée.');
    }

    public function edit(PageContenu $page)
    {
        return Inertia::render('Admin/Pages/Edit', [
            'page' => $page,
        ]);
    }

    public function update(PageContenu $page)
    {
        $validated = request()->validate([
            'slug' => 'required|string|unique:page_contenus,slug,' . $page->id . '|max:255',
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'hero_image' => 'nullable|url',
            'published' => 'boolean',
        ]);

        if ($validated['published'] && !$page->published) {
            $validated['published_at'] = now();
        } elseif (!$validated['published']) {
            $validated['published_at'] = null;
        }

        $page->update($validated);

        return back()->with('success', 'Page mise à jour.');
    }

    public function show(PageContenu $page)
    {
        return Inertia::render('Admin/Pages/Show', [
            'page' => $page,
        ]);
    }

    public function publish(PageContenu $page)
    {
        $page->publish();

        return back()->with('success', 'Page publiée.');
    }

    public function unpublish(PageContenu $page)
    {
        $page->unpublish();

        return back()->with('success', 'Page dépubliée.');
    }

    public function preview(PageContenu $page)
    {
        return Inertia::render('Public/Pages/Show', [
            'page' => $page,
            'preview' => true,
        ]);
    }

    public function destroy(PageContenu $page)
    {
        // Empêcher la suppression de certaines pages importantes
        $pagesImportantes = ['mentions-legales', 'politique-confidentialite', 'conditions-generales', 'apropos'];

        if (in_array($page->slug, $pagesImportantes)) {
            return back()->with('error', 'Cette page ne peut pas être supprimée.');
        }

        $page->delete();

        return back()->with('success', 'Page supprimée.');
    }

    public function duplicate(PageContenu $page)
    {
        $newPage = $page->replicate();
        $newPage->slug = $page->slug . '-copie-' . now()->timestamp;
        $newPage->published = false;
        $newPage->published_at = null;
        $newPage->save();

        return redirect()->route('admin.pages.edit', $newPage)
            ->with('success', 'Page dupliquée. Modifiez-la et publiez si nécessaire.');
    }

    public function export()
    {
        $pages = PageContenu::all();

        return response()->json($pages);
    }

    public function import()
    {
        $validated = request()->validate([
            'fichier' => 'required|file|mimes:json',
        ]);

        $contents = json_decode($validated['fichier']->getContent(), true);

        foreach ($contents as $pageData) {
            PageContenu::updateOrCreate(
                ['slug' => $pageData['slug']],
                $pageData
            );
        }

        return back()->with('success', count($contents) . ' page(s) importée(s).');
    }
}
