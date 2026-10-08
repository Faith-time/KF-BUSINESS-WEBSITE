import type { ReactNode } from 'react';
import { useState } from 'react';
import { Head, Link, usePage } from '@inertiajs/react';
import {
    AlertTriangle, ArrowRight, CheckCircle2, Clock, Download,
    MapPin, Percent, Target, TrendingUp, Users,
} from 'lucide-react';
import PublicLayout from '@/layouts/public-layout';

type Nombre = number | string | null | undefined;

interface TypeProjet {
    id: number;
    nom: string;
}

interface TicketInvestissement {
    id: number;
    montant_min: Nombre;
    montant_max: Nombre;
    nombre_actions: Nombre;
    actif: boolean;
}

interface Projet {
    id: number;
    nom: string;
    description: string | null;
    description_courte: string | null;
    localisation: string | null;
    montant_total: Nombre;
    montant_collecte: Nombre;
    taux_rendement_annuel: Nombre;
    duree_mois: Nombre;
    image_hero: string | null;
    pourcentage_completion: Nombre;
    slug: string;
    date_debut: string | null;
    date_fin: string | null;
    risques?: string | null;
    opportunites?: string | null;
    montant_min_investissement: Nombre;
    type_projet?: TypeProjet | null;
}

interface ProjetShowProps {
    projet: Projet;
    tickets: TicketInvestissement[];
    nombreInvestisseurs: number;
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

function formatDate(date: string | null): string {
    if (!date) return '—';

    return new Date(date).toLocaleDateString('fr-FR', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
}

export default function ProjetShow() {
    const { projet, tickets, nombreInvestisseurs } = usePage<ProjetShowProps>().props;
    const [selectedTicket, setSelectedTicket] = useState<TicketInvestissement | null>(tickets[0] || null);
    const [showFullDescription, setShowFullDescription] = useState(false);

    const description = projet.description || projet.description_courte || '';
    const remainingAmount = Math.max(0, num(projet.montant_total) - num(projet.montant_collecte));
    const daysLeft = projet.date_fin
        ? Math.max(0, Math.ceil((new Date(projet.date_fin).getTime() - new Date().getTime()) / (1000 * 60 * 60 * 24)))
        : 0;

    return (
        <>
            <Head title={projet.nom} />
            <div className="animate-fade-in">
                {/* Hero Section */}
                <div className="relative overflow-hidden h-96 bg-gradient-to-br from-brand-900 to-brand-700">
                    <img
                        src={projet.image_hero || 'https://images.pexels.com/photos/4911739/pexels-photo-4911739.jpeg'}
                        alt={projet.nom}
                        className="w-full h-full object-cover opacity-60"
                    />
                    <div className="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/50 to-transparent" />
                    <div className="absolute inset-0 flex items-end">
                        <div className="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 pb-8">
                            <div className="inline-flex items-center gap-2 mb-4">
                                <span className="px-3 py-1 rounded-full bg-gold-400 text-brand-900 text-xs font-bold">
                                    {projet.type_projet?.nom || 'Projet'}
                                </span>
                                <span className="px-3 py-1 rounded-full bg-emerald-500 text-white text-xs font-medium">
                                    {formatPercent(projet.pourcentage_completion)} financé
                                </span>
                            </div>
                            <h1 className="font-display font-bold text-white text-4xl mb-3">{projet.nom}</h1>
                            <div className="flex items-center gap-4 text-slate-200">
                                <div className="flex items-center gap-1">
                                    <MapPin className="w-4 h-4" />
                                    {projet.localisation}
                                </div>
                                <div className="flex items-center gap-1">
                                    <Users className="w-4 h-4" />
                                    {nombreInvestisseurs} investisseur{nombreInvestisseurs > 1 ? 's' : ''}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Main Content */}
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                    <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        {/* Left Column - Content */}
                        <div className="lg:col-span-2 space-y-8">
                            {/* Description */}
                            <section>
                                <h2 className="font-display font-bold text-2xl text-slate-900 mb-4">
                                    À propos du projet
                                </h2>
                                <div className="prose prose-sm text-slate-600 max-w-none">
                                    <p className={showFullDescription ? '' : 'line-clamp-3'}>{description}</p>
                                </div>
                                {description.length > 300 && (
                                    <button
                                        onClick={() => setShowFullDescription(!showFullDescription)}
                                        className="mt-3 text-sm font-medium text-brand-600 hover:text-brand-700"
                                    >
                                        {showFullDescription ? 'Lire moins' : 'Lire plus'}
                                    </button>
                                )}
                            </section>

                            {/* Key Metrics */}
                            <section>
                                <h3 className="font-display font-bold text-xl text-slate-900 mb-4">Chiffres clés</h3>
                                <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    {[
                                        { label: 'Budget total', value: formatFCFA(projet.montant_total), icon: Target },
                                        { label: 'Collecté', value: formatFCFA(projet.montant_collecte), icon: TrendingUp },
                                        { label: 'Rendement annuel', value: formatPercent(projet.taux_rendement_annuel), icon: Percent },
                                        { label: 'Durée', value: `${num(projet.duree_mois)} mois`, icon: Clock },
                                    ].map((metric) => (
                                        <div
                                            key={metric.label}
                                            className="bg-gradient-to-br from-slate-50 to-slate-100 rounded-2xl p-4 border border-slate-200"
                                        >
                                            <div className="flex items-center gap-2 mb-2">
                                                <metric.icon className="w-4 h-4 text-brand-600" />
                                                <span className="text-xs font-medium text-slate-600">{metric.label}</span>
                                            </div>
                                            <div className="text-lg font-display font-bold text-slate-900">
                                                {metric.value}
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            </section>

                            {/* Progress & Timeline */}
                            <section>
                                <h3 className="font-display font-bold text-xl text-slate-900 mb-4">Progression</h3>
                                <div className="space-y-4">
                                    <div>
                                        <div className="flex justify-between items-center mb-2">
                                            <span className="text-sm font-medium text-slate-700">Financement</span>
                                            <span className="text-sm font-semibold text-slate-900">
                                                {formatPercent(projet.pourcentage_completion)}
                                            </span>
                                        </div>
                                        <div className="w-full bg-slate-200 rounded-full h-3">
                                            <div
                                                className="bg-gradient-to-r from-brand-500 to-gold-400 h-3 rounded-full transition-all"
                                                style={{ width: `${Math.min(num(projet.pourcentage_completion), 100)}%` }}
                                            />
                                        </div>
                                        <div className="flex justify-between mt-2 text-xs text-slate-500">
                                            <span>{formatFCFA(projet.montant_collecte)}</span>
                                            <span>{formatFCFA(projet.montant_total)}</span>
                                        </div>
                                    </div>

                                    {daysLeft > 0 && (
                                        <div className="bg-brand-50 border border-brand-200 rounded-xl p-4">
                                            <div className="flex items-start gap-3">
                                                <Clock className="w-5 h-5 text-brand-600 shrink-0 mt-0.5" />
                                                <div>
                                                    <div className="font-semibold text-brand-900">
                                                        {daysLeft} jour{daysLeft > 1 ? 's' : ''} restant{daysLeft > 1 ? 's' : ''}
                                                    </div>
                                                    <div className="text-sm text-brand-700">
                                                        Avant fermeture de la collecte
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    )}
                                </div>
                            </section>

                            {/* Risks & Opportunities */}
                            {(projet.risques || projet.opportunites) && (
                                <section>
                                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        {projet.risques && (
                                            <div className="border border-amber-200 rounded-2xl p-6 bg-amber-50">
                                                <div className="flex items-center gap-2 mb-3">
                                                    <AlertTriangle className="w-5 h-5 text-amber-600" />
                                                    <h4 className="font-display font-bold text-slate-900">Risques</h4>
                                                </div>
                                                <p className="text-sm text-slate-600">{projet.risques}</p>
                                            </div>
                                        )}
                                        {projet.opportunites && (
                                            <div className="border border-emerald-200 rounded-2xl p-6 bg-emerald-50">
                                                <div className="flex items-center gap-2 mb-3">
                                                    <CheckCircle2 className="w-5 h-5 text-emerald-600" />
                                                    <h4 className="font-display font-bold text-slate-900">Opportunités</h4>
                                                </div>
                                                <p className="text-sm text-slate-600">{projet.opportunites}</p>
                                            </div>
                                        )}
                                    </div>
                                </section>
                            )}
                        </div>

                        {/* Right Column - Investment Card */}
                        <div className="lg:col-span-1">
                            <div className="sticky top-4 bg-white rounded-2xl border border-slate-200 shadow-lg p-6 space-y-6">
                                {/* Status Summary */}
                                <div className="space-y-3">
                                    <div className="flex justify-between items-center pb-3 border-b border-slate-100">
                                        <span className="text-sm text-slate-600">Collecté</span>
                                        <span className="font-semibold text-slate-900">
                                            {formatFCFA(projet.montant_collecte)}
                                        </span>
                                    </div>
                                    <div className="flex justify-between items-center pb-3 border-b border-slate-100">
                                        <span className="text-sm text-slate-600">Restant</span>
                                        <span className="font-semibold text-slate-900">
                                            {formatFCFA(remainingAmount)}
                                        </span>
                                    </div>
                                    <div className="flex justify-between items-center">
                                        <span className="text-sm text-slate-600">Investisseurs</span>
                                        <span className="font-semibold text-slate-900">{nombreInvestisseurs}</span>
                                    </div>
                                </div>

                                {/* Investment Tickets */}
                                {tickets.length > 0 && (
                                    <div>
                                        <h4 className="font-display font-bold text-slate-900 mb-3">
                                            Paliers d'investissement
                                        </h4>
                                        <div className="space-y-2">
                                            {tickets
                                                .filter((t) => t.actif)
                                                .map((ticket) => (
                                                    <button
                                                        key={ticket.id}
                                                        onClick={() => setSelectedTicket(ticket)}
                                                        className={`w-full text-left p-3 rounded-lg border-2 transition-all ${
                                                            selectedTicket?.id === ticket.id
                                                                ? 'border-brand-500 bg-brand-50'
                                                                : 'border-slate-200 hover:border-brand-300'
                                                        }`}
                                                    >
                                                        <div className="flex justify-between items-start mb-1">
                                                            <span className="font-semibold text-slate-900">
                                                                {formatFCFA(ticket.montant_min)}
                                                            </span>
                                                            <span className="text-xs bg-gold-100 text-gold-700 px-2 py-0.5 rounded">
                                                                {num(ticket.nombre_actions)} actions
                                                            </span>
                                                        </div>
                                                        <div className="text-xs text-slate-500">
                                                            à {formatFCFA(ticket.montant_max)}
                                                        </div>
                                                    </button>
                                                ))}
                                        </div>
                                    </div>
                                )}

                                {/* Call to Action */}
                                <div className="pt-6 border-t border-slate-100 space-y-3">
                                    <button className="w-full py-3 rounded-xl bg-gradient-to-r from-brand-600 to-brand-700 text-white font-semibold shadow-lg hover:shadow-xl transition-all flex items-center justify-center gap-2 hover:to-brand-800">
                                        <ArrowRight className="w-4 h-4" />
                                        Investir maintenant
                                    </button>

                                    <Link
                                        href="/comment-ca-marche"
                                        className="block w-full py-2 rounded-xl border border-slate-200 text-slate-700 font-medium hover:bg-slate-50 transition-all text-center text-sm"
                                    >
                                        Comment investir ?
                                    </Link>

                                    <button className="w-full py-2 rounded-xl border border-slate-200 text-slate-700 font-medium hover:bg-slate-50 transition-all flex items-center justify-center gap-2 text-sm">
                                        <Download className="w-4 h-4" />
                                        Prospectus
                                    </button>
                                </div>

                                {/* Info Box */}
                                <div className="bg-slate-50 rounded-xl p-4 text-xs text-slate-600 space-y-2">
                                    <div>
                                        <strong className="text-slate-900">Début :</strong> {formatDate(projet.date_debut)}
                                    </div>
                                    <div>
                                        <strong className="text-slate-900">Fin estimée :</strong> {formatDate(projet.date_fin)}
                                    </div>
                                    <div>
                                        <strong className="text-slate-900">Investissement minimum :</strong>{' '}
                                        {formatFCFA(projet.montant_min_investissement)}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {/* CTA Section */}
                <section className="bg-gradient-to-r from-brand-700 to-brand-900 py-12 mt-16">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                        <h2 className="font-display font-bold text-3xl text-white mb-4">
                            Vous êtes intéressé par ce projet ?
                        </h2>
                        <p className="text-slate-200 mb-6 max-w-2xl mx-auto">
                            Créez un compte et commencez à investir dans ce projet entrepreneur sénégalais.
                        </p>
                        <div className="flex flex-col sm:flex-row gap-3 justify-center">
                            <button className="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-gold-400 text-brand-900 font-semibold hover:bg-gold-300 transition-all">
                                Investir dans ce projet
                            </button>
                            <Link
                                href="/projets"
                                className="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-white/10 border border-white/20 text-white font-semibold hover:bg-white/20 transition-all"
                            >
                                Voir d'autres projets
                            </Link>
                        </div>
                    </div>
                </section>
            </div>
        </>
    );
}

ProjetShow.layout = (page: ReactNode) => <PublicLayout>{page}</PublicLayout>;
