<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\ActivityLog\Models\Activity;
use Inertia\Inertia;

class JournalController extends Controller
{
    public function __invoke()
    {
        $activities = Activity::query()
            ->with('causer', 'subject')
            ->latest('created_at')
            ->paginate(50);

        // Regrouper par type d'activité
        $typesActivite = [
            'created' => 'Créé',
            'updated' => 'Mis à jour',
            'deleted' => 'Supprimé',
            'approved' => 'Approuvé',
            'rejected' => 'Rejeté',
            'verified' => 'Vérifié',
            'confirmed' => 'Confirmé',
        ];

        return Inertia::render('Admin/Journal/Index', [
            'activities' => $activities,
            'typesActivite' => $typesActivite,
        ]);
    }

    public function filterByUser()
    {
        $userId = request('user_id');

        $activities = Activity::query()
            ->where('causer_id', $userId)
            ->with('causer', 'subject')
            ->latest('created_at')
            ->paginate(50);

        return response()->json($activities);
    }

    public function filterByModel()
    {
        $model = request('model');

        $activities = Activity::query()
            ->where('subject_type', $model)
            ->with('causer', 'subject')
            ->latest('created_at')
            ->paginate(50);

        return response()->json($activities);
    }

    public function filterByDate()
    {
        $validated = request()->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
        ]);

        $activities = Activity::query()
            ->whereBetween('created_at', [$validated['date_debut'], $validated['date_fin']])
            ->with('causer', 'subject')
            ->latest('created_at')
            ->paginate(50);

        return response()->json($activities);
    }

    public function filterByType()
    {
        $type = request('type');

        $activities = Activity::query()
            ->where('description', $type)
            ->with('causer', 'subject')
            ->latest('created_at')
            ->paginate(50);

        return response()->json($activities);
    }

    public function show(Activity $activity)
    {
        $activity->load('causer', 'subject');

        return Inertia::render('Admin/Journal/Show', [
            'activity' => $activity,
        ]);
    }

    public function export()
    {
        $validated = request()->validate([
            'format' => 'required|in:json,csv',
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date|after:date_debut',
        ]);

        $query = Activity::query();

        if ($validated['date_debut'] && $validated['date_fin']) {
            $query->whereBetween('created_at', [$validated['date_debut'], $validated['date_fin']]);
        }

        $activities = $query->with('causer', 'subject')->get();

        if ($validated['format'] === 'json') {
            return response()->json($activities);
        }

        // CSV Export
        $csv = fopen('php://output', 'w');
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="journal.csv"');

        fputcsv($csv, ['Date', 'Utilisateur', 'Action', 'Modèle', 'ID', 'Propriétés']);

        foreach ($activities as $activity) {
            fputcsv($csv, [
                $activity->created_at,
                $activity->causer?->name ?? 'Système',
                $activity->description,
                $activity->subject_type,
                $activity->subject_id,
                json_encode($activity->properties),
            ]);
        }

        fclose($csv);
    }

    public function search()
    {
        $query = request('q');

        $activities = Activity::query()
            ->where('description', 'like', "%{$query}%")
            ->orWhereHas('subject', function ($q) use ($query) {
                // Chercher dans le modèle sujet
            })
            ->with('causer', 'subject')
            ->latest('created_at')
            ->paginate(50);

        return response()->json($activities);
    }

    public function stats()
    {
        $totalActivities = Activity::count();
        $activitiesJour = Activity::whereDate('created_at', today())->count();
        $activitiesMois = Activity::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();

        // Top 10 des utilisateurs actifs
        $topUsers = Activity::selectRaw('causer_id, count(*) as total')
            ->groupBy('causer_id')
            ->orderByDesc('total')
            ->limit(10)
            ->with('causer')
            ->get();

        // Activités par type
        $activitesByType = Activity::selectRaw('description, count(*) as total')
            ->groupBy('description')
            ->get();

        return response()->json([
            'totalActivities' => $totalActivities,
            'activitiesJour' => $activitiesJour,
            'activitiesMois' => $activitiesMois,
            'topUsers' => $topUsers,
            'activitesByType' => $activitesByType,
        ]);
    }

    public function clear()
    {
        $validated = request()->validate([
            'older_than_days' => 'required|integer|min:1',
        ]);

        $date = now()->subDays($validated['older_than_days']);

        $deleted = Activity::where('created_at', '<', $date)->delete();

        return back()->with('success', $deleted . ' entrée(s) supprimée(s).');
    }
}
