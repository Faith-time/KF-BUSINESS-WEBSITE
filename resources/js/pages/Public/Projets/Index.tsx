import type { ReactNode } from 'react';
import { useMemo, useState } from 'react';
import { Head, Link, usePage } from '@inertiajs/react';
import { ChevronRight, Clock, MapPin, Search, TrendingUp } from 'lucide-react';
import PublicLayout from '@/layouts/public-layout';

type Nombre = number | string | null | undefined;

interface Projet {
    id: number;
    nom: string;
    description_courte: string | null;
    localisation: string | null;
    montant_total: Nombre;
    montant_collecte: Nombre;
    taux_rendement_annuel: Nombre;
    duree_mois: Nombre;
    image_hero: string | null;
    pourcentage_completion: Nombre;
    slug: string;
    type_projet?: {
        id: number;
        nom: string;
    } | null;
}

interface PaginatedData {
    data: Projet[];
    current_page: number;
    per_page: number;
    total: number;
    last_page: number;
}

interface ProjetsIndexProps {
    projets: PaginatedData;
}

// Les colonnes decimal de Laravel arrivent parfois en chaînes : on convertit toujours.
const num = (value: Nombre): number => Number(value ?? 0);

function formatFCFA(value: Nombre): string {
    return new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: 'XOF',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(num(value));
}

function formatPercent(value: Nombre): string {
    return `${num(value).toFixed(1)}%`;
}

export default function ProjetIndex() {
    const { projets } = usePage<ProjetsIndexProps>().props;
    const [searchQuery, setSearchQuery] = useState('');
    const [sortBy, setSortBy] = useState<'recent' | 'return' | 'progress'>('recent');

    // Filtre local pour la recherche (sur la page courante)
    const filteredProjects = useMemo(() => {
        let filtered = projets.data;

        if (searchQuery.trim()) {
            const query = searchQuery.toLowerCase();
            filtered = filtered.filter(
                (p) =>
                    p.nom.toLowerCase().includes(query) ||
                    (p.localisation ?? '').toLowerCase().includes(query) ||
                    (p.type_projet?.nom ?? '').toLowerCase().includes(query),
            );
        }

        const sorted = [...filtered];
        switch (sortBy) {
            case 'return':
                sorted.sort((a, b) => num(b.taux_rendement_annuel) - num(a.taux_rendement_annuel));
                break;
            case 'progress':
                sorted.sort((a, b) => num(b.pourcentage_completion) - num(a.pourcentage_completion));
                break;
            case 'recent':
            default:
                // Pas de tri nécessaire (déjà trié par le contrôleur)
                break;
        }

        return sorted;
    }, [searchQuery, sortBy, projets.data]);

    return (
        <>
            <Head title="Projets d'investissement" />
            <div className="animate-fade-in">
                {/* Page Header */}
                <div className="bg-gradient-to-br from-brand-900 to-brand-700 text-white py-16 relative overflow-hidden">
                    <div className="blob bg-gold-400 w-72 h-72 top-0 right-10" />
                    <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <h1 className="font-display font-bold text-3xl sm:text-4xl mb-2">
                            Projets d'investissement
                        </h1>
                        <p className="text-slate-300 max-w-2xl">
                            Découvrez tous nos projets entrepreneuriaux et sélectionnez celui qui correspond à vos objectifs d'investissement.
                        </p>
                    </div>
                </div>

                {/* Search & Filter */}
                <section className="bg-slate-50 py-8 border-b border-slate-200">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                            {/* Search Input */}
                            <div className="md:col-span-2 relative">
                                <Search className="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 w-5 h-5" />
                                <input
                                    type="text"
                                    placeholder="Rechercher un projet, localisation, type..."
                                    value={searchQuery}
                                    onChange={(e) => setSearchQuery(e.target.value)}
                                    className="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-100 outline-none text-sm"
                                />
                            </div>

                            {/* Sort Dropdown */}
                            <div>
                                <select
                                    value={sortBy}
                                    onChange={(e) => setSortBy(e.target.value as typeof sortBy)}
                                    className="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-100 outline-none text-sm"
                                >
                                    <option value="recent">Plus récents</option>
                                    <option value="return">Meilleur rendement</option>
                                    <option value="progress">Progression</option>
                                </select>
                            </div>
                        </div>

                        {/* Results count */}
                        <div className="text-sm text-slate-600 mt-4">
                            {filteredProjects.length} projet{filteredProjects.length > 1 ? 's' : ''} trouvé
                            {searchQuery && ` pour "${searchQuery}"`}
                        </div>
                    </div>
                </section>

                {/* Projects Grid */}
                <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                    {filteredProjects.length > 0 ? (
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            {filteredProjects.map((projet) => (
                                <Link
                                    key={projet.id}
                                    href={`/projets/${projet.slug}`}
                                    className="group card-hover bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-sm hover:shadow-md transition-all flex flex-col"
                                >
                                    {/* Image */}
                                    <div className="relative overflow-hidden h-48">
                                        <img
                                            src={projet.image_hero || 'https://images.pexels.com/photos/4911739/pexels-photo-4911739.jpeg'}
                                            alt={projet.nom}
                                            className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                        />
                                        <div className="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent" />
                                        <div className="absolute bottom-4 left-4 right-4 flex items-start justify-between">
                                            <span className="px-3 py-1 rounded-full bg-gold-400 text-brand-900 text-xs font-bold">
                                                {projet.type_projet?.nom || 'Projet'}
                                            </span>
                                            <span className="px-3 py-1 rounded-full bg-emerald-500 text-white text-xs font-medium">
                                                {formatPercent(projet.pourcentage_completion)} financé
                                            </span>
                                        </div>
                                    </div>

                                    {/* Content */}
                                    <div className="p-6 flex-1 flex flex-col">
                                        {/* Location */}
                                        <div className="flex items-center gap-2 text-sm text-slate-500 mb-2">
                                            <MapPin className="w-4 h-4 shrink-0" />
                                            <span className="truncate">{projet.localisation}</span>
                                        </div>

                                        {/* Title & Description */}
                                        <h3 className="font-display font-bold text-lg text-slate-900 mb-2 line-clamp-2">
                                            {projet.nom}
                                        </h3>
                                        <p className="text-sm text-slate-600 line-clamp-2 mb-4 flex-1">
                                            {projet.description_courte}
                                        </p>

                                        {/* Progress Bar */}
                                        <div className="space-y-2 mb-4">
                                            <div className="w-full bg-slate-200 rounded-full h-2">
                                                <div
                                                    className="bg-gradient-to-r from-brand-500 to-gold-400 h-2 rounded-full transition-all"
                                                    style={{ width: `${Math.min(num(projet.pourcentage_completion), 100)}%` }}
                                                />
                                            </div>
                                            <div className="flex justify-between text-xs text-slate-500">
                                                <span>{formatFCFA(projet.montant_collecte)}</span>
                                                <span>{formatFCFA(projet.montant_total)}</span>
                                            </div>
                                        </div>

                                        {/* Stats Grid */}
                                        <div className="grid grid-cols-2 gap-3 pt-4 border-t border-slate-100">
                                            <div className="space-y-1">
                                                <div className="flex items-center gap-1 text-xs text-slate-500">
                                                    <TrendingUp className="w-3 h-3" />
                                                    Rendement
                                                </div>
                                                <div className="text-sm font-semibold text-slate-900">
                                                    {formatPercent(projet.taux_rendement_annuel)}/an
                                                </div>
                                            </div>
                                            <div className="space-y-1">
                                                <div className="flex items-center gap-1 text-xs text-slate-500">
                                                    <Clock className="w-3 h-3" />
                                                    Durée
                                                </div>
                                                <div className="text-sm font-semibold text-slate-900">
                                                    {num(projet.duree_mois)} mois
                                                </div>
                                            </div>
                                        </div>

                                        {/* Faux bouton : le lien englobe déjà toute la carte */}
                                        <span className="mt-4 w-full py-2 rounded-lg bg-slate-50 text-slate-700 text-sm font-medium group-hover:bg-slate-100 transition-colors flex items-center justify-center gap-2">
                                            Voir les détails
                                            <ChevronRight className="w-4 h-4" />
                                        </span>
                                    </div>
                                </Link>
                            ))}
                        </div>
                    ) : (
                        <div className="text-center py-12">
                            <div className="text-slate-400 mb-2">
                                <Search className="w-12 h-12 mx-auto opacity-50" />
                            </div>
                            <h3 className="text-lg font-semibold text-slate-700 mb-1">Aucun projet trouvé</h3>
                            <p className="text-slate-500">Essayez de modifier votre recherche ou de revenir plus tard.</p>
                        </div>
                    )}
                </section>

                {/* Pagination */}
                {projets.last_page > 1 && (
                    <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 border-t border-slate-200">
                        <div className="flex items-center justify-center gap-2">
                            {projets.current_page > 1 && (
                                <Link
                                    href={`/projets?page=${projets.current_page - 1}`}
                                    className="px-4 py-2 rounded-lg border border-slate-200 text-slate-700 text-sm font-medium hover:bg-slate-50 transition-colors"
                                >
                                    ← Précédent
                                </Link>
                            )}

                            <div className="flex items-center gap-1">
                                {Array.from({ length: projets.last_page }, (_, i) => i + 1).map((page) => (
                                    <Link
                                        key={page}
                                        href={`/projets?page=${page}`}
                                        className={`px-3 py-1 rounded text-sm font-medium transition-colors ${
                                            page === projets.current_page
                                                ? 'bg-brand-500 text-white'
                                                : 'border border-slate-200 text-slate-700 hover:bg-slate-50'
                                        }`}
                                    >
                                        {page}
                                    </Link>
                                ))}
                            </div>

                            {projets.current_page < projets.last_page && (
                                <Link
                                    href={`/projets?page=${projets.current_page + 1}`}
                                    className="px-4 py-2 rounded-lg border border-slate-200 text-slate-700 text-sm font-medium hover:bg-slate-50 transition-colors"
                                >
                                    Suivant →
                                </Link>
                            )}
                        </div>
                    </section>
                )}
            </div>
        </>
    );
}

ProjetIndex.layout = (page: ReactNode) => <PublicLayout>{page}</PublicLayout>;
