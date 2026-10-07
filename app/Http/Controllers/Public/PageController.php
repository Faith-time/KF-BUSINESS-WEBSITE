<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\PageContenu;
use Inertia\Inertia;

class PageController extends Controller
{
    public function show($slug)
    {
        $page = PageContenu::where('slug', $slug)
            ->where('published', true)
            ->firstOrFail();

        return Inertia::render('Public/Pages/Show', [
            'page' => $page,
        ]);
    }

    public function home()
    {
        $featured = \App\Models\Projet::query()
            ->with('typeProjet', 'mediaProjet')
            ->where('visible', true)
            ->where('featured', true)
            ->where('statut', 'financement')
            ->limit(6)
            ->get();

        $recent = \App\Models\Projet::query()
            ->with('typeProjet', 'mediaProjet')
            ->where('visible', true)
            ->where('statut', 'financement')
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();

        return Inertia::render('Public/Home', [
            'featuredProjets' => $featured,
            'recentProjets' => $recent,
        ]);
    }

    public function apropos()
    {
        $page = PageContenu::where('slug', 'apropos')
            ->where('published', true)
            ->first();

        return Inertia::render('Public/Apropos', [
            'page' => $page,
        ]);
    }

    public function mentions()
    {
        $page = PageContenu::where('slug', 'mentions-legales')
            ->where('published', true)
            ->first();

        return Inertia::render('Public/Mentions', [
            'page' => $page,
        ]);
    }

    public function confidentialite()
    {
        $page = PageContenu::where('slug', 'politique-confidentialite')
            ->where('published', true)
            ->first();

        return Inertia::render('Public/Confidentialite', [
            'page' => $page,
        ]);
    }

    public function cgv()
    {
        $page = PageContenu::where('slug', 'conditions-generales')
            ->where('published', true)
            ->first();

        return Inertia::render('Public/CGV', [
            'page' => $page,
        ]);
    }
}
