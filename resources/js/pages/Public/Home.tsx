import type { ReactNode } from 'react';
import { Head, usePage, Link } from '@inertiajs/react';
import PublicLayout from '@/layouts/public-layout';
import { register } from '@/routes';

import {
    TrendingUp, ShieldCheck, Users, ArrowRight, MapPin,
    Clock, Eye, Layers, ChevronRight, Target
} from 'lucide-react';

interface Projet {
    id: number;
    nom: string;
    description_courte: string;
    localisation: string;
    montant_total: number;
    montant_collecte: number;
    taux_rendement_annuel: number;
    duree_mois: number;
    image_hero: string;
    pourcentage_completion: number;
    slug: string;
    type_projet?: {
        nom: string;
    };
}

interface HomeProps {
    featuredProjets: Projet[];
    recentProjets: Projet[];
}

function formatFCFA(value: number | string): string {
    return new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: 'XOF',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(Number(value));
}

function formatPercent(value: number | string): string {
    return `${Number(value).toFixed(1)}%`;
}

export default function Home() {
    const { featuredProjets, recentProjets } = usePage<HomeProps>().props;

    return (
        <>
            <Head title="Accueil" />
            <div className="animate-fade-in">
                {/* Hero */}
                <section className="relative overflow-hidden bg-gradient-to-br from-brand-900 via-brand-800 to-brand-700">
                    <div className="blob bg-brand-400 w-96 h-96 top-0 right-0" />
                    <div className="blob bg-gold-400 w-80 h-80 bottom-0 left-10" style={{ animationDelay: '2s' }} />
                    <div className="absolute inset-0 opacity-20" style={{
                        backgroundImage: `url('https://images.pexels.com/photos/4911739/pexels-photo-4911739.jpeg?auto=compress&cs=tinysrgb&w=1200')`,
                        backgroundSize: 'cover',
                        backgroundPosition: 'center'
                    }} />
                    <div className="absolute inset-0 bg-gradient-to-r from-brand-900 via-brand-900/80 to-transparent" />

                    <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">
                        <div className="max-w-2xl">
                            <div className="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 text-white text-sm font-medium mb-6">
                                <span className="w-2 h-2 rounded-full bg-gold-400 animate-pulse" />
                                Plateforme d'investissement pour le Sénégal
                            </div>
                            <h1 className="font-display font-bold text-white text-4xl sm:text-5xl lg:text-6xl leading-tight mb-6">
                                Investissez dans l'économie réelle du <span className="text-gold-400">Sénégal</span>
                            </h1>
                            <p className="text-lg text-slate-200 leading-relaxed mb-8 max-w-xl">
                                KF Business Company International vous ouvre les portes de projets entrepreneuriaux à fort impact :
                                aviculture, agriculture, agroalimentaire. Devenez actionnaire dès 100 000 FCFA.
                            </p>
                            <div className="flex flex-col sm:flex-row gap-3">
                                <Link
                                    href="/projets"                                    className="flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-gold-400 text-brand-900 font-semibold shadow-lg shadow-gold-400/20 hover:bg-gold-300 transition-all"
                                >
                                    Découvrir les projets
                                    <ArrowRight className="w-4 h-4" />
                                </Link>
                                <Link
                                    href={register()}                                    className="flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-white/10 backdrop-blur-sm border border-white/20 text-white font-semibold hover:bg-white/20 transition-all"
                                >
                                    Créer un compte investisseur
                                </Link>
                            </div>

                            {/* Stats */}
                            <div className="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-12 pt-8 border-t border-white/10">
                                {[
                                    { value: formatFCFA(300_000_000), label: 'Capital levé' },
                                    { value: (recentProjets.length || 3).toString(), label: 'Projets actifs' },
                                    { value: '18%', label: 'Rendement moyen' },
                                    { value: '100K FCFA', label: 'Investissement min.' },
                                ].map((s) => (
                                    <div key={s.label}>
                                        <div className="text-2xl sm:text-3xl font-display font-bold text-white">{s.value}</div>
                                        <div className="text-xs text-slate-400 mt-0.5">{s.label}</div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    </div>
                </section>

                {/* Features */}
                <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                    <div className="text-center mb-12">
                        <h2 className="font-display font-bold text-3xl text-slate-900 mb-3">
                            Pourquoi investir avec KF Business ?
                        </h2>
                        <p className="text-slate-500 max-w-2xl mx-auto">
                            Une plateforme transparente, sécurisée et accessible à tous pour participer au développement économique du Sénégal.
                        </p>
                    </div>
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {[
                            {
                                icon: ShieldCheck,
                                title: 'Sécurité & conformité',
                                desc: "Connexion sécurisée, vérification d'identité, droits d'accès différenciés.",
                                color: 'bg-brand-50 text-brand-600'
                            },
                            {
                                icon: Eye,
                                title: 'Transparence totale',
                                desc: 'Suivez en temps réel votre investissement, les résultats du projet et les dividendes.',
                                color: 'bg-emerald-50 text-emerald-600'
                            },
                            {
                                icon: Layers,
                                title: 'Multi-projets',
                                desc: 'Aviculture, agriculture, transformation agroalimentaire. Diversifiez votre portefeuille.',
                                color: 'bg-gold-50 text-gold-600'
                            },
                        ].map((f) => (
                            <div key={f.title} className="card-hover bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
                                <div className={`w-12 h-12 rounded-xl ${f.color} flex items-center justify-center mb-4`}>
                                    <f.icon className="w-6 h-6" />
                                </div>
                                <h3 className="font-display font-bold text-lg text-slate-900 mb-2">{f.title}</h3>
                                <p className="text-sm text-slate-500 leading-relaxed">{f.desc}</p>
                            </div>
                        ))}
                    </div>
                </section>

                {/* Featured Projects */}
                {featuredProjets.length > 0 && (
                    <section className="bg-slate-50 py-16">
                        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                            <div className="flex items-end justify-between mb-8">
                                <div>
                                    <h2 className="font-display font-bold text-3xl text-slate-900">Projets en vedette</h2>
                                    <p className="text-slate-500 mt-1">Les projets avec le plus fort potentiel</p>
                                </div>
                                <Link
                                    href="/projets"
                                    className="hidden sm:flex items-center gap-1.5 text-sm font-medium text-brand-600 hover:text-brand-700"
                                >
                                    Voir tous les projets <ChevronRight className="w-4 h-4" />
                                </Link>
                            </div>

                            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {featuredProjets.slice(0, 2).map((projet) => (
                                    <Link
                                        key={projet.id}
                                        href={`/projets/${projet.slug}`}                                        className="group card-hover bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-sm hover:shadow-md transition-all"
                                    >
                                        <div className="relative overflow-hidden h-48">
                                            <img
                                                src={projet.image_hero || 'https://images.pexels.com/photos/4911739/pexels-photo-4911739.jpeg'}
                                                alt={projet.nom}
                                                className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                            />
                                            <div className="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent" />
                                            <div className="absolute bottom-4 left-4 right-4 flex items-center gap-2">
                        <span className="px-3 py-1 rounded-full bg-gold-400 text-brand-900 text-xs font-bold">
                          {projet.type_projet?.nom || 'Projet'}
                        </span>
                                            </div>
                                        </div>
                                        <div className="p-6">
                                            <div className="flex items-center gap-2 text-sm text-slate-500 mb-2">
                                                <MapPin className="w-4 h-4" /> {projet.localisation}
                                            </div>
                                            <h3 className="font-display font-bold text-lg text-slate-900 mb-2">{projet.nom}</h3>
                                            <p className="text-sm text-slate-600 line-clamp-2 mb-4">{projet.description_courte}</p>

                                            <div className="space-y-3">
                                                <div className="w-full bg-slate-200 rounded-full h-2">
                                                    <div
                                                        className="bg-gradient-to-r from-brand-500 to-gold-400 h-2 rounded-full transition-all"
                                                        style={{ width: `${Math.min(projet.pourcentage_completion, 100)}%` }}
                                                    />
                                                </div>
                                                <div className="flex justify-between text-xs text-slate-500">
                                                    <span>{formatPercent(projet.pourcentage_completion)} financé</span>
                                                    <span>{formatFCFA(projet.montant_collecte)} / {formatFCFA(projet.montant_total)}</span>
                                                </div>
                                            </div>

                                            <div className="grid grid-cols-2 gap-3 mt-4 pt-4 border-t border-slate-100">
                                                <div>
                                                    <div className="text-xs text-slate-500">Rendement</div>
                                                    <div className="text-sm font-semibold text-slate-900">
                                                        {formatPercent(projet.taux_rendement_annuel)}/an
                                                    </div>
                                                </div>
                                                <div>
                                                    <div className="text-xs text-slate-500">Durée</div>
                                                    <div className="text-sm font-semibold text-slate-900">{projet.duree_mois} mois</div>
                                                </div>
                                            </div>
                                        </div>
                                    </Link>
                                ))}
                            </div>
                        </div>
                    </section>
                )}

                {/* Recent Projects */}
                {recentProjets.length > 0 && (
                    <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                        <div className="flex items-end justify-between mb-8">
                            <div>
                                <h2 className="font-display font-bold text-3xl text-slate-900">Projets récents</h2>
                                <p className="text-slate-500 mt-1">Nouvelles opportunités d'investissement</p>
                            </div>
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                            {recentProjets.slice(0, 3).map((projet) => (
                                <Link
                                    key={projet.id}
                                    href={`/projets/${projet.slug}`}                                    className="group card-hover bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-sm hover:shadow-md transition-all"
                                >
                                    <div className="relative overflow-hidden h-40">
                                        <img
                                            src={projet.image_hero || 'https://images.pexels.com/photos/4407319/pexels-photo-4407319.jpeg'}
                                            alt={projet.nom}
                                            className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                        />
                                    </div>
                                    <div className="p-4">
                                        <div className="text-xs text-brand-600 font-semibold mb-1">
                                            {projet.type_projet?.nom || 'Projet'}
                                        </div>
                                        <h3 className="font-display font-bold text-slate-900 mb-1 line-clamp-2">{projet.nom}</h3>
                                        <div className="flex items-center gap-1 text-xs text-slate-500 mb-3">
                                            <MapPin className="w-3 h-3" /> {projet.localisation}
                                        </div>

                                        <div className="w-full bg-slate-200 rounded-full h-1.5 mb-2">
                                            <div
                                                className="bg-gradient-to-r from-brand-500 to-gold-400 h-1.5 rounded-full"
                                                style={{ width: `${Math.min(projet.pourcentage_completion, 100)}%` }}
                                            />
                                        </div>
                                        <div className="text-xs text-slate-500 mb-3">
                                            {formatPercent(projet.pourcentage_completion)} financé
                                        </div>

                                        <div className="grid grid-cols-2 gap-2 text-xs">
                                            <div className="bg-slate-50 p-2 rounded">
                                                <div className="text-slate-500">Rendement</div>
                                                <div className="font-semibold text-slate-900">{formatPercent(projet.taux_rendement_annuel)}</div>
                                            </div>
                                            <div className="bg-slate-50 p-2 rounded">
                                                <div className="text-slate-500">Durée</div>
                                                <div className="font-semibold text-slate-900">{projet.duree_mois}m</div>
                                            </div>
                                        </div>
                                    </div>
                                </Link>
                            ))}
                        </div>
                    </section>
                )}

                {/* CTA Section */}
                <section className="bg-gradient-to-r from-brand-700 to-brand-900 py-16">
                    <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                        <h2 className="font-display font-bold text-3xl text-white mb-4">
                            Prêt à démarrer votre investissement ?
                        </h2>
                        <p className="text-slate-200 mb-8 max-w-2xl mx-auto">
                            Ouvrez un compte en quelques minutes et commencez à investir dans des projets entrepreneurs sénégalais.
                        </p>
                        <div className="flex flex-col sm:flex-row gap-3 justify-center">
                            <Link
                                href={register()}
                                className="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-gold-400 text-brand-900 font-semibold hover:bg-gold-300 transition-all"
                            >
                                Créer un compte
                            </Link>
                            <Link
                                href="/projets"
                                className="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-white/10 border border-white/20 text-white font-semibold hover:bg-white/20 transition-all"
                            >
                                Voir les projets
                            </Link>
                        </div>
                    </div>
                </section>
            </div>
        </>
    );
}

Home.layout = (page: ReactNode) => <PublicLayout>{page}</PublicLayout>;
