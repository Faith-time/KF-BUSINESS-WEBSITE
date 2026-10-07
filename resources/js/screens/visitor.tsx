import { useRouter } from '@/lib/router-shim';
import { getIcon } from '@/lib/icons';
import { projects, investmentTickets, formatFCFA, formatNumber, formatPercent } from '@/lib/data';
import {
  TrendingUp, ShieldCheck, Users, ArrowRight, CheckCircle2, MapPin,
  Clock, Wallet, BarChart3, FileText, Lock, Eye, Download, Star,
  Phone, Mail, Building2, Target, Layers, PieChart, ChevronRight,
  AlertTriangle, HandshakeIcon,
} from 'lucide-react';

const heroImage = 'https://images.pexels.com/photos/4911739/pexels-photo-4911739.jpeg?auto=compress&cs=tinysrgb&w=1200';
const aboutImage = 'https://images.pexels.com/photos/4412608/pexels-photo-4412608.jpeg?auto=compress&cs=tinysrgb&w=800';

/* ============ HOME ============ */
export function HomePage() {
  const { navigate } = useRouter();

  return (
    <div className="animate-fade-in">
      {/* Hero */}
      <section className="relative overflow-hidden bg-gradient-to-br from-brand-900 via-brand-800 to-brand-700">
        <div className="blob bg-brand-400 w-96 h-96 top-0 right-0" />
        <div className="blob bg-gold-400 w-80 h-80 bottom-0 left-10" style={{ animationDelay: '2s' }} />
        <div className="absolute inset-0 opacity-20" style={{ backgroundImage: `url(${heroImage})`, backgroundSize: 'cover', backgroundPosition: 'center' }} />
        <div className="absolute inset-0 bg-gradient-to-r from-brand-900 via-brand-900/80 to-transparent" />

        <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">
          <div className="max-w-2xl">
            <div className="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 text-white text-sm font-medium mb-6">
              <span className="w-2 h-2 rounded-full bg-gold-400 animate-pulse" />
              Mode présentation — Collecte en attente de validation juridique
            </div>
            <h1 className="font-display font-bold text-white text-4xl sm:text-5xl lg:text-6xl leading-tight mb-6">
              Investissez dans l'économie réelle du <span className="text-gold-400">Sénégal</span>
            </h1>
            <p className="text-lg text-slate-200 leading-relaxed mb-8 max-w-xl">
              KF Business Company International vous ouvre les portes de projets entrepreneuriaux à fort impact :
              aviculture, agriculture, agroalimentaire. Devenez actionnaire dès 100 000 FCFA.
            </p>
            <div className="flex flex-col sm:flex-row gap-3">
              <button
                onClick={() => navigate('visitor', 'projects')}
                className="flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-gold-400 text-brand-900 font-semibold shadow-lg shadow-gold-400/20 hover:bg-gold-300 transition-all"
              >
                Découvrir les projets
                <ArrowRight className="w-4 h-4" />
              </button>
              <button
                onClick={() => navigate('visitor', 'register')}
                className="flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-white/10 backdrop-blur-sm border border-white/20 text-white font-semibold hover:bg-white/20 transition-all"
              >
                Créer un compte investisseur
              </button>
            </div>

            {/* Stats bar */}
            <div className="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-12 pt-8 border-t border-white/10">
              {[
                { value: '300M', label: 'FCFA — Projet pilote' },
                { value: '30 000', label: 'Actions émises' },
                { value: '18%', label: 'Rendement visé' },
                { value: '3', label: 'Projets à venir' },
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
          <h2 className="font-display font-bold text-3xl text-slate-900 mb-3">Pourquoi investir avec KF Business ?</h2>
          <p className="text-slate-500 max-w-2xl mx-auto">Une plateforme transparente, sécurisée et accessible à tous pour participer au développement économique du Sénégal.</p>
        </div>
        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
          {[
            { icon: ShieldCheck, title: 'Sécurité & conformité', desc: "Connexion sécurisée, vérification d'identité, droits d'accès différenciés. Validation juridique avant toute collecte.", color: 'bg-brand-50 text-brand-600' },
            { icon: Eye, title: 'Transparence totale', desc: 'Suivez en temps réel votre investissement, les résultats du projet, les dividendes et les rapports financiers.', color: 'bg-emerald-50 text-emerald-600' },
            { icon: Layers, title: 'Multi-projets', desc: 'Aviculture, agriculture, boulangerie, transformation agroalimentaire. Diversifiez votre portefeuille.', color: 'bg-gold-50 text-gold-600' },
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

      {/* Featured project */}
      <section className="bg-slate-50 py-16">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex items-end justify-between mb-8">
            <div>
              <h2 className="font-display font-bold text-3xl text-slate-900">Projet pilote</h2>
              <p className="text-slate-500 mt-1">Ferme avicole de 5 000 pondeuses — Thiès, Sénégal</p>
            </div>
            <button onClick={() => navigate('visitor', 'projects')} className="hidden sm:flex items-center gap-1.5 text-sm font-medium text-brand-600 hover:text-brand-700">
              Voir tous les projets <ChevronRight className="w-4 h-4" />
            </button>
          </div>

          <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
            <div className="relative rounded-2xl overflow-hidden shadow-xl group">
              <img src={projects[0].image} alt="Ferme avicole" className="w-full h-80 object-cover" />
              <div className="absolute inset-0 bg-gradient-to-t from-brand-900/60 to-transparent" />
              <div className="absolute bottom-4 left-4 right-4 flex items-center gap-2">
                <span className="px-3 py-1 rounded-full bg-gold-400 text-brand-900 text-xs font-bold">Projet pilote</span>
                <span className="px-3 py-1 rounded-full bg-white/20 backdrop-blur-sm text-white text-xs font-medium">Mode présentation</span>
              </div>
            </div>
            <div>
              <div className="flex items-center gap-2 text-sm text-slate-500 mb-2">
                <MapPin className="w-4 h-4" /> {projects[0].location}
              </div>
              <h3 className="font-display font-bold text-2xl text-slate-900 mb-3">{projects[0].name}</h3>
              <p className="text-slate-600 leading-relaxed mb-6">{projects[0].description}</p>
              <div className="grid grid-cols-2 gap-4 mb-6">
                {[
                  { label: 'Budget global', value: formatFCFA(projects[0].budget) },
                  { label: 'Rendement visé', value: formatPercent(projects[0].expectedReturn) },
                  { label: 'Actions totales', value: formatNumber(projects[0].sharesTotal) },
                  { label: 'Durée', value: projects[0].duration },
                ].map((s) => (
                  <div key={s.label} className="bg-white rounded-xl p-4 border border-slate-100">
                    <div className="text-xs text-slate-500 mb-1">{s.label}</div>
                    <div className="font-display font-bold text-slate-900">{s.value}</div>
                  </div>
                ))}
              </div>
              <button onClick={() => navigate('visitor', 'project-detail')} className="flex items-center gap-2 px-5 py-3 rounded-xl bg-brand-700 text-white font-semibold hover:bg-brand-800 transition-colors">
                Voir le projet en détail <ArrowRight className="w-4 h-4" />
              </button>
            </div>
          </div>
        </div>
      </section>

      {/* Investment tickets */}
      <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div className="text-center mb-10">
          <h2 className="font-display font-bold text-3xl text-slate-900 mb-3">Choisissez votre ticket d'investissement</h2>
          <p className="text-slate-500">Chaque action vaut 10 000 FCFA. Plusieurs paliers sont disponibles.</p>
        </div>
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
          {investmentTickets.map((t, i) => (
            <div key={t.amount} className={`card-hover rounded-2xl p-5 text-center border ${i === 2 ? 'bg-brand-700 text-white border-brand-700 shadow-lg shadow-brand-500/20' : 'bg-white border-slate-100'}`}>
              {i === 2 && <div className="text-xs font-bold text-gold-400 mb-1">Populaire</div>}
              <div className={`text-xs font-medium mb-2 ${i === 2 ? 'text-white/70' : 'text-slate-500'}`}>{t.label}</div>
              <div className={`font-display font-bold text-xl mb-1 ${i === 2 ? 'text-white' : 'text-slate-900'}`}>{formatNumber(t.amount)}</div>
              <div className={`text-xs ${i === 2 ? 'text-white/70' : 'text-slate-500'}`}>FCFA</div>
              <div className={`mt-3 pt-3 border-t ${i === 2 ? 'border-white/20' : 'border-slate-100'}`}>
                <span className={`text-sm font-medium ${i === 2 ? 'text-white' : 'text-slate-700'}`}>{t.shares} actions</span>
              </div>
            </div>
          ))}
        </div>
      </section>

      {/* How it works */}
      <section className="bg-brand-900 text-white py-16">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-12">
            <h2 className="font-display font-bold text-3xl mb-3">Comment ça marche ?</h2>
            <p className="text-slate-400">Investir en 4 étapes simples et sécurisées.</p>
          </div>
          <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
            {[
              { n: '1', t: 'Créez votre compte', d: "Inscription et vérification d'identité sécurisée." },
              { n: '2', t: 'Choisissez un projet', d: 'Parcourez les projets disponibles et leur business plan.' },
              { n: '3', t: 'Souscrivez', d: 'Sélectionnez votre montant, recevez un récapitulatif et signez.' },
              { n: '4', t: 'Suivez vos résultats', d: 'Accédez aux rapports, dividendes et performances en temps réel.' },
            ].map((s) => (
              <div key={s.n} className="relative">
                <div className="w-12 h-12 rounded-xl bg-gold-400 text-brand-900 font-display font-bold text-xl flex items-center justify-center mb-4">{s.n}</div>
                <h3 className="font-display font-bold text-lg mb-2">{s.t}</h3>
                <p className="text-sm text-slate-400 leading-relaxed">{s.d}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* CTA */}
      <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div className="relative overflow-hidden rounded-3xl bg-gradient-to-br from-brand-800 to-brand-600 p-8 sm:p-12 text-center">
          <div className="blob bg-gold-400 w-72 h-72 top-0 right-0" />
          <div className="relative">
            <h2 className="font-display font-bold text-3xl text-white mb-3">Prêt à investir ?</h2>
            <p className="text-slate-200 mb-6 max-w-xl mx-auto">Créez votre compte investisseur gratuitement et accédez à tous les projets de KF Business.</p>
            <button onClick={() => navigate('visitor', 'register')} className="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl bg-gold-400 text-brand-900 font-semibold shadow-lg hover:bg-gold-300 transition-all">
              Créer mon compte <ArrowRight className="w-4 h-4" />
            </button>
          </div>
        </div>
      </section>
    </div>
  );
}

/* ============ ABOUT ============ */
export function AboutPage() {
  return (
    <div className="animate-fade-in">
      <PageHeader title="KF Business Company International" subtitle="Entrepreneur, investisseur et partenaire du développement économique sénégalais." />
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-16">
          <div>
            <h2 className="font-display font-bold text-2xl text-slate-900 mb-4">Notre mission</h2>
            <p className="text-slate-600 leading-relaxed mb-4">
              KF Business Company International conçoit, finance et opère des projets entrepreneuriaux au Sénégal.
              Notre vocation : ouvrir l'investissement à tous — particuliers, diaspora, professionnels —
              tout en créant de la valeur économique locale et des emplois durables.
            </p>
            <p className="text-slate-600 leading-relaxed mb-6">
              Nous commençons par un projet avicole de 5 000 pondeuses à Thiès, avant de déployer
              d'autres projets en agriculture, boulangerie et transformation agroalimentaire.
            </p>
            <div className="grid grid-cols-2 gap-4">
              {[
                { icon: Target, label: 'Vision', value: 'Diversifier les secteurs' },
                { icon: HandshakeIcon, label: 'Approche', value: 'Co-investissement 50/50' },
                { icon: ShieldCheck, label: 'Conformité', value: 'Validation juridique' },
                { icon: TrendingUp, label: 'Rendement', value: '15–22% visé' },
              ].map((s) => (
                <div key={s.label} className="bg-white rounded-xl p-4 border border-slate-100">
                  <s.icon className="w-5 h-5 text-brand-600 mb-2" />
                  <div className="text-xs text-slate-500">{s.label}</div>
                  <div className="font-semibold text-slate-900 text-sm">{s.value}</div>
                </div>
              ))}
            </div>
          </div>
          <div className="rounded-2xl overflow-hidden shadow-xl">
            <img src={aboutImage} alt="KF Business" className="w-full h-96 object-cover" />
          </div>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
          {[
            { icon: Building2, t: 'Entreprise', d: 'KF Business Company International, enregistrée au Sénégal.' },
            { icon: Users, t: 'Équipe', d: "Des professionnels de la finance, l'agriculture et la gestion de projet." },
            { icon: PieChart, t: 'Gouvernance', d: 'Répartition 50% KF Business / 50% investisseurs sur le projet pilote.' },
          ].map((c) => (
            <div key={c.t} className="bg-white rounded-2xl p-6 border border-slate-100">
              <div className="w-12 h-12 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center mb-4">
                <c.icon className="w-6 h-6" />
              </div>
              <h3 className="font-display font-bold text-lg text-slate-900 mb-2">{c.t}</h3>
              <p className="text-sm text-slate-500">{c.d}</p>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}

/* ============ PROJECTS ============ */
export function ProjectsPage() {
  const { navigate } = useRouter();
  return (
    <div className="animate-fade-in">
      <PageHeader title="Nos projets d'investissement" subtitle="Découvrez les projets entrepreneuriaux portés par KF Business. Chaque projet est structuré en actions de 10 000 FCFA." />
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {projects.map((p) => (
            <div key={p.id} className="card-hover bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-sm">
              <div className="relative h-48 overflow-hidden">
                <img src={p.image} alt={p.name} className="w-full h-full object-cover" />
                <div className="absolute top-3 left-3 flex gap-2">
                  <span className="px-2.5 py-1 rounded-full bg-white/90 backdrop-blur-sm text-xs font-bold text-brand-700">{p.sector}</span>
                </div>
                <div className="absolute top-3 right-3">
                  <span className="px-2.5 py-1 rounded-full bg-gold-400 text-brand-900 text-xs font-bold">Présentation</span>
                </div>
              </div>
              <div className="p-5">
                <div className="flex items-center gap-1.5 text-xs text-slate-500 mb-2">
                  <MapPin className="w-3.5 h-3.5" /> {p.location}
                </div>
                <h3 className="font-display font-bold text-lg text-slate-900 mb-2">{p.name}</h3>
                <p className="text-sm text-slate-500 leading-relaxed mb-4 line-clamp-2">{p.description}</p>
                <div className="grid grid-cols-2 gap-3 mb-4">
                  <div><div className="text-xs text-slate-400">Budget</div><div className="font-semibold text-slate-900 text-sm">{formatFCFA(p.budget)}</div></div>
                  <div><div className="text-xs text-slate-400">Rendement visé</div><div className="font-semibold text-emerald-600 text-sm">{formatPercent(p.expectedReturn)}</div></div>
                </div>
                <button onClick={() => navigate('visitor', 'project-detail')} className="w-full flex items-center justify-center gap-2 py-2.5 rounded-lg bg-brand-50 text-brand-700 font-medium text-sm hover:bg-brand-100 transition-colors">
                  Voir le détail <ArrowRight className="w-4 h-4" />
                </button>
              </div>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}

/* ============ PROJECT DETAIL ============ */
export function ProjectDetailPage() {
  const { navigate } = useRouter();
  const p = projects[0];
  const subscribed = p.sharesTotal - p.sharesAvailable;
  const progress = (subscribed / p.sharesTotal) * 100;

  return (
    <div className="animate-fade-in">
      {/* Hero */}
      <div className="relative h-80 overflow-hidden">
        <img src={p.image} alt={p.name} className="w-full h-full object-cover" />
        <div className="absolute inset-0 bg-gradient-to-t from-brand-900 via-brand-900/60 to-transparent" />
        <div className="absolute bottom-6 left-0 right-0 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex gap-2 mb-3">
            <span className="px-3 py-1 rounded-full bg-gold-400 text-brand-900 text-xs font-bold">Projet pilote</span>
            <span className="px-3 py-1 rounded-full bg-white/20 backdrop-blur-sm text-white text-xs font-medium">Mode présentation</span>
          </div>
          <h1 className="font-display font-bold text-white text-3xl sm:text-4xl mb-2">{p.name}</h1>
          <div className="flex items-center gap-4 text-slate-200 text-sm">
            <span className="flex items-center gap-1.5"><MapPin className="w-4 h-4" /> {p.location}</span>
            <span className="flex items-center gap-1.5"><Clock className="w-4 h-4" /> {p.duration}</span>
          </div>
        </div>
      </div>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
          {/* Main */}
          <div className="lg:col-span-2 space-y-8">
            <div>
              <h2 className="font-display font-bold text-2xl text-slate-900 mb-4">Description du projet</h2>
              <p className="text-slate-600 leading-relaxed">{p.description}</p>
              <p className="text-slate-600 leading-relaxed mt-4">
                La ferme sera implantée à Thiès sur un terrain adapté, avec un bâtiment d'élevage équipé,
                une réserve d'aliment et un système d'abreuvement. L'objectif est d'atteindre un taux de ponte
                optimal de 85% en moyenne sur la phase de production, avec un cycle de 18 mois.
              </p>
            </div>

            {/* Budget breakdown */}
            <div>
              <h3 className="font-display font-bold text-xl text-slate-900 mb-4">Répartition du budget</h3>
              <div className="space-y-3">
                {[
                  { label: 'Construction & équipements', pct: 40, amount: 120_000_000 },
                  { label: 'Achat des pondeuses', pct: 25, amount: 75_000_000 },
                  { label: 'Aliment & consommables (12 mois)', pct: 20, amount: 60_000_000 },
                  { label: 'Fonctionnement & salaires', pct: 10, amount: 30_000_000 },
                  { label: 'Fonds de roulement', pct: 5, amount: 15_000_000 },
                ].map((b) => (
                  <div key={b.label}>
                    <div className="flex justify-between text-sm mb-1">
                      <span className="text-slate-700 font-medium">{b.label}</span>
                      <span className="text-slate-500">{formatFCFA(b.amount)}</span>
                    </div>
                    <div className="h-2 rounded-full bg-slate-100 overflow-hidden">
                      <div className="h-full rounded-full bg-gradient-to-r from-brand-600 to-brand-400" style={{ width: `${b.pct}%` }} />
                    </div>
                  </div>
                ))}
              </div>
            </div>

            {/* Projections */}
            <div>
              <h3 className="font-display font-bold text-xl text-slate-900 mb-4">Projections financières</h3>
              <div className="overflow-hidden rounded-xl border border-slate-200">
                <table className="w-full text-sm">
                  <thead className="bg-slate-50">
                    <tr>
                      <th className="text-left px-4 py-3 font-semibold text-slate-700">Année</th>
                      <th className="text-right px-4 py-3 font-semibold text-slate-700">Revenus</th>
                      <th className="text-right px-4 py-3 font-semibold text-slate-700">Charges</th>
                      <th className="text-right px-4 py-3 font-semibold text-slate-700">Résultat net</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-slate-100">
                    {[
                      { y: 'An 1', r: 180_000_000, c: 120_000_000, n: 60_000_000 },
                      { y: 'An 2', r: 240_000_000, c: 140_000_000, n: 100_000_000 },
                      { y: 'An 3', r: 280_000_000, c: 160_000_000, n: 120_000_000 },
                    ].map((row) => (
                      <tr key={row.y} className="hover:bg-slate-50">
                        <td className="px-4 py-3 font-medium text-slate-800">{row.y}</td>
                        <td className="px-4 py-3 text-right text-slate-600">{formatFCFA(row.r)}</td>
                        <td className="px-4 py-3 text-right text-slate-600">{formatFCFA(row.c)}</td>
                        <td className="px-4 py-3 text-right font-semibold text-emerald-600">{formatFCFA(row.n)}</td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            </div>

            {/* Risks */}
            <div className="bg-amber-50 border border-amber-200 rounded-xl p-5">
              <div className="flex items-start gap-3">
                <AlertTriangle className="w-5 h-5 text-amber-600 shrink-0 mt-0.5" />
                <div>
                  <h4 className="font-semibold text-amber-900 mb-2">Risques liés à l'investissement</h4>
                  <ul className="text-sm text-amber-800 space-y-1 list-disc list-inside">
                    <li>Risque biologique (maladies aviaires, climat)</li>
                    <li>Fluctuation du prix des intrants (aliment)</li>
                    <li>Risque de marché (prix de vente des œufs)</li>
                    <li>Risque de change pour les investisseurs étrangers</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>

          {/* Sidebar */}
          <div className="space-y-6">
            <div className="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm sticky top-20">
              <h3 className="font-display font-bold text-lg text-slate-900 mb-4">Structure de l'offre</h3>
              <div className="space-y-3 mb-6">
                {[
                  { l: 'Budget global', v: formatFCFA(p.budget) },
                  { l: 'Actions totales', v: formatNumber(p.sharesTotal) },
                  { l: 'Prix par action', v: formatFCFA(p.sharePrice) },
                  { l: 'Actions investisseurs', v: formatNumber(p.sharesAvailable) },
                  { l: 'Part KF Business', v: '50%' },
                  { l: 'Rendement visé', v: formatPercent(p.expectedReturn) },
                ].map((s) => (
                  <div key={s.l} className="flex justify-between text-sm">
                    <span className="text-slate-500">{s.l}</span>
                    <span className="font-semibold text-slate-900">{s.v}</span>
                  </div>
                ))}
              </div>
              <div className="mb-6">
                <div className="flex justify-between text-sm mb-2">
                  <span className="text-slate-500">Souscription (pré-inscrite)</span>
                  <span className="font-semibold text-brand-600">{progress.toFixed(0)}%</span>
                </div>
                <div className="h-2.5 rounded-full bg-slate-100 overflow-hidden">
                  <div className="h-full rounded-full bg-gradient-to-r from-brand-600 to-brand-400" style={{ width: `${progress}%` }} />
                </div>
              </div>
              <button onClick={() => navigate('visitor', 'register')} className="w-full py-3 rounded-xl bg-gradient-to-r from-brand-700 to-brand-500 text-white font-semibold shadow-md hover:shadow-lg transition-all mb-2">
                Souscrire à ce projet
              </button>
              <button onClick={() => navigate('visitor', 'how-it-works')} className="w-full py-3 rounded-xl bg-slate-100 text-slate-700 font-medium hover:bg-slate-200 transition-colors text-sm">
                Comment ça marche ?
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}

/* ============ HOW IT WORKS ============ */
export function HowItWorksPage() {
  return (
    <div className="animate-fade-in">
      <PageHeader title="Comment investir" subtitle="Un parcours simple, sécurisé et transparent en 4 étapes." />
      <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div className="space-y-8">
          {[
            { n: '1', icon: Users, t: 'Création de compte', d: "Inscrivez-vous avec votre email, complétez votre profil et soumettez vos pièces d'identité pour vérification." },
            { n: '2', icon: FileText, t: 'Vérification KYC', d: 'Notre équipe vérifie votre identité et votre éligibilité. Vous recevez une confirmation sous 48h.' },
            { n: '3', icon: Wallet, t: 'Souscription', d: "Choisissez un projet, sélectionnez le nombre d'actions ou le montant, recevez un récapitulatif et signez électroniquement." },
            { n: '4', icon: BarChart3, t: 'Suivi & transparence', d: 'Accédez à votre tableau de bord : actions détenues, montant investi, résultats du projet, dividendes, rapports financiers.' },
          ].map((s) => (
            <div key={s.n} className="flex gap-5 items-start bg-white rounded-2xl p-6 border border-slate-100 card-hover">
              <div className="w-14 h-14 rounded-xl bg-gradient-to-br from-brand-700 to-brand-500 text-white flex items-center justify-center shrink-0">
                <s.icon className="w-7 h-7" />
              </div>
              <div>
                <div className="flex items-center gap-2 mb-1">
                  <span className="text-xs font-bold text-brand-600 bg-brand-50 px-2 py-0.5 rounded-full">Étape {s.n}</span>
                </div>
                <h3 className="font-display font-bold text-lg text-slate-900 mb-1">{s.t}</h3>
                <p className="text-sm text-slate-500 leading-relaxed">{s.d}</p>
              </div>
            </div>
          ))}
        </div>

        <div className="mt-12 bg-amber-50 border border-amber-200 rounded-2xl p-6">
          <div className="flex items-start gap-3">
            <AlertTriangle className="w-5 h-5 text-amber-600 shrink-0 mt-0.5" />
            <div>
              <h4 className="font-semibold text-amber-900 mb-1">Mode présentation</h4>
              <p className="text-sm text-amber-800">La plateforme fonctionne actuellement en mode présentation et pré-souscription. Aucune collecte réelle n'est activée. La vente d'actions sera ouverte après validation du montage juridique et réglementaire au Sénégal.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}

/* ============ TRANSPARENCY ============ */
export function TransparencyPage() {
  return (
    <div className="animate-fade-in">
      <PageHeader title="Transparence" subtitle="Chaque investisseur a le droit de savoir exactement où va son argent et comment le projet performe." />
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {[
            { icon: Wallet, t: 'Votre investissement', d: "Montant investi, nombre d'actions, prix unitaire — tout est visible et téléchargeable." },
            { icon: PieChart, t: 'Votre participation', d: 'Pourcentage de détention du projet, part dans les bénéfices, droits de vote.' },
            { icon: TrendingUp, t: 'Résultats du projet', d: 'Indicateurs de performance, production, revenus, charges — mis à jour régulièrement.' },
            { icon: FileText, t: 'Dividendes', d: 'Distribution éventuelle de dividendes, historique, montants perçus.' },
            { icon: BarChart3, t: 'Rapports financiers', d: 'Rapports trimestriels et annuels publiés par KF Business, accessibles à tout moment.' },
            { icon: AlertTriangle, t: 'Risques', d: 'Liste complète des risques liés à chaque projet, mise à jour en continu.' },
          ].map((c) => (
            <div key={c.t} className="card-hover bg-white rounded-2xl p-6 border border-slate-100">
              <div className="w-12 h-12 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center mb-4">
                <c.icon className="w-6 h-6" />
              </div>
              <h3 className="font-display font-bold text-lg text-slate-900 mb-2">{c.t}</h3>
              <p className="text-sm text-slate-500 leading-relaxed">{c.d}</p>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}

/* ============ CONTACT ============ */
export function ContactPage() {
  return (
    <div className="animate-fade-in">
      <PageHeader title="Contactez-nous" subtitle="Une question sur un projet, un investissement ou la plateforme ? Écrivez-nous." />
      <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
          <div className="space-y-6">
            {[
              { icon: Building2, t: 'Siège social', d: 'Dakar, Sénégal' },
              { icon: Mail, t: 'Email', d: 'contact@kfbusiness.sn' },
              { icon: Phone, t: 'Téléphone', d: '+221 33 800 00 00' },
            ].map((c) => (
              <div key={c.t} className="flex items-start gap-4 bg-white rounded-xl p-5 border border-slate-100">
                <div className="w-10 h-10 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center shrink-0">
                  <c.icon className="w-5 h-5" />
                </div>
                <div>
                  <div className="text-xs text-slate-500">{c.t}</div>
                  <div className="font-semibold text-slate-900">{c.d}</div>
                </div>
              </div>
            ))}
          </div>
          <div className="bg-white rounded-2xl p-6 border border-slate-100">
            <h3 className="font-display font-bold text-lg text-slate-900 mb-4">Envoyez un message</h3>
            <div className="space-y-4">
              <input type="text" placeholder="Nom complet" className="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-100 outline-none text-sm" />
              <input type="email" placeholder="Adresse email" className="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-100 outline-none text-sm" />
              <textarea rows={4} placeholder="Votre message" className="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-100 outline-none text-sm resize-none" />
              <button className="w-full py-3 rounded-lg bg-gradient-to-r from-brand-700 to-brand-500 text-white font-semibold shadow-md hover:shadow-lg transition-all">Envoyer</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}

/* ============ LEGAL ============ */
export function LegalPage() {
  return (
    <div className="animate-fade-in">
      <PageHeader title="Informations légales" subtitle="Mentions légales, politique de confidentialité, conditions et avertissement sur les risques." />
      <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8">
        {[
          { t: 'Mentions légales', d: 'KF Business Company International, société enregistrée au Sénégal. La plateforme est éditée par KF Business et hébergée sur des infrastructures sécurisées.' },
          { t: 'Confidentialité & protection des données', d: 'Vos données personnelles sont chiffrées, stockées de manière sécurisée et ne sont jamais cédées à des tiers. Vous pouvez demander leur suppression à tout moment.' },
          { t: "Conditions d'investissement", d: "L'investissement en actions comporte des risques de perte en capital. Les rendements indiqués sont des objectifs et ne constituent pas une garantie. La vente d'actions est soumise à la validation du montage juridique au Sénégal." },
          { t: 'Avertissement sur les risques', d: "Tout investissement dans un projet entrepreneurial présente un risque de perte totale ou partielle du capital investi. KF Business ne garantit pas le rendement. L'investisseur doit lire et accepter les risques avant de souscrire." },
        ].map((s) => (
          <div key={s.t} className="bg-white rounded-2xl p-6 border border-slate-100">
            <h3 className="font-display font-bold text-lg text-slate-900 mb-2">{s.t}</h3>
            <p className="text-sm text-slate-600 leading-relaxed">{s.d}</p>
          </div>
        ))}
      </div>
    </div>
  );
}

/* ============ LOGIN ============ */
export function LoginPage() {
  const { navigate } = useRouter();
  return (
    <div className="min-h-screen flex items-center justify-center bg-gradient-to-br from-brand-900 via-brand-800 to-brand-700 p-4 relative overflow-hidden">
      <div className="blob bg-gold-400 w-96 h-96 top-0 right-0" />
      <div className="blob bg-brand-400 w-80 h-80 bottom-0 left-0" style={{ animationDelay: '2s' }} />
      <div className="relative w-full max-w-md">
        <div className="text-center mb-8">
          <div className="inline-flex w-14 h-14 rounded-2xl bg-white/15 backdrop-blur-sm items-center justify-center mb-4">
            <span className="text-white font-display font-bold text-2xl">KF</span>
          </div>
          <h1 className="font-display font-bold text-white text-2xl">Connexion</h1>
          <p className="text-slate-300 text-sm mt-1">Accédez à votre espace personnel</p>
        </div>
        <div className="bg-white rounded-2xl p-6 shadow-2xl">
          <div className="space-y-4">
            <div>
              <label className="text-xs font-medium text-slate-600 mb-1.5 block">Adresse email</label>
              <input type="email" placeholder="vous@exemple.com" className="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-100 outline-none text-sm" />
            </div>
            <div>
              <label className="text-xs font-medium text-slate-600 mb-1.5 block">Mot de passe</label>
              <input type="password" placeholder="••••••••" className="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-100 outline-none text-sm" />
            </div>
            <div className="flex items-center justify-between text-sm">
              <label className="flex items-center gap-2 text-slate-600">
                <input type="checkbox" className="rounded border-slate-300" /> Se souvenir de moi
              </label>
              <button className="text-brand-600 font-medium hover:text-brand-700">Mot de passe oublié ?</button>
            </div>
            <button onClick={() => navigate('investor', 'inv-dashboard')} className="w-full py-3 rounded-lg bg-gradient-to-r from-brand-700 to-brand-500 text-white font-semibold shadow-md hover:shadow-lg transition-all">
              Se connecter
            </button>
          </div>
          <div className="mt-6 pt-6 border-t border-slate-100 text-center">
            <div className="text-xs text-slate-500 mb-3">Connexion rapide (démo) :</div>
            <div className="flex gap-2">
              <button onClick={() => navigate('investor', 'inv-dashboard')} className="flex-1 py-2 rounded-lg bg-brand-50 text-brand-700 text-xs font-medium hover:bg-brand-100">Investisseur</button>
              <button onClick={() => navigate('accountant', 'acc-dashboard')} className="flex-1 py-2 rounded-lg bg-emerald-50 text-emerald-700 text-xs font-medium hover:bg-emerald-100">Comptable</button>
              <button onClick={() => navigate('admin', 'adm-dashboard')} className="flex-1 py-2 rounded-lg bg-slate-100 text-slate-700 text-xs font-medium hover:bg-slate-200">Admin</button>
            </div>
          </div>
          <p className="text-center text-sm text-slate-500 mt-4">
            Pas de compte ? <button onClick={() => navigate('visitor', 'register')} className="text-brand-600 font-medium hover:text-brand-700">Créer un compte</button>
          </p>
        </div>
      </div>
    </div>
  );
}

/* ============ REGISTER ============ */
export function RegisterPage() {
  const { navigate } = useRouter();
  return (
    <div className="min-h-screen flex items-center justify-center bg-gradient-to-br from-brand-900 via-brand-800 to-brand-700 p-4 relative overflow-hidden">
      <div className="blob bg-gold-400 w-96 h-96 top-0 right-0" />
      <div className="blob bg-brand-400 w-80 h-80 bottom-0 left-0" style={{ animationDelay: '2s' }} />
      <div className="relative w-full max-w-md">
        <div className="text-center mb-6">
          <div className="inline-flex w-14 h-14 rounded-2xl bg-white/15 backdrop-blur-sm items-center justify-center mb-4">
            <span className="text-white font-display font-bold text-2xl">KF</span>
          </div>
          <h1 className="font-display font-bold text-white text-2xl">Créer un compte</h1>
          <p className="text-slate-300 text-sm mt-1">Investissez dès 100 000 FCFA</p>
        </div>
        <div className="bg-white rounded-2xl p-6 shadow-2xl">
          <div className="space-y-4">
            <div className="grid grid-cols-2 gap-3">
              <div>
                <label className="text-xs font-medium text-slate-600 mb-1.5 block">Prénom</label>
                <input type="text" placeholder="Prénom" className="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-100 outline-none text-sm" />
              </div>
              <div>
                <label className="text-xs font-medium text-slate-600 mb-1.5 block">Nom</label>
                <input type="text" placeholder="Nom" className="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-100 outline-none text-sm" />
              </div>
            </div>
            <div>
              <label className="text-xs font-medium text-slate-600 mb-1.5 block">Adresse email</label>
              <input type="email" placeholder="vous@exemple.com" className="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-100 outline-none text-sm" />
            </div>
            <div>
              <label className="text-xs font-medium text-slate-600 mb-1.5 block">Téléphone</label>
              <input type="tel" placeholder="+221 ..." className="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-100 outline-none text-sm" />
            </div>
            <div>
              <label className="text-xs font-medium text-slate-600 mb-1.5 block">Mot de passe</label>
              <input type="password" placeholder="••••••••" className="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-100 outline-none text-sm" />
            </div>
            <label className="flex items-start gap-2 text-xs text-slate-600">
              <input type="checkbox" className="mt-0.5 rounded border-slate-300" />
              J'accepte les conditions d'investissement et la politique de confidentialité.
            </label>
            <button onClick={() => navigate('investor', 'inv-dashboard')} className="w-full py-3 rounded-lg bg-gradient-to-r from-brand-700 to-brand-500 text-white font-semibold shadow-md hover:shadow-lg transition-all">
              Créer mon compte
            </button>
          </div>
          <p className="text-center text-sm text-slate-500 mt-4">
            Déjà inscrit ? <button onClick={() => navigate('visitor', 'login')} className="text-brand-600 font-medium hover:text-brand-700">Se connecter</button>
          </p>
        </div>
      </div>
    </div>
  );
}

/* ============ Shared page header ============ */
function PageHeader({ title, subtitle }: { title: string; subtitle: string }) {
  return (
    <div className="bg-gradient-to-br from-brand-900 to-brand-700 text-white py-16 relative overflow-hidden">
      <div className="blob bg-gold-400 w-72 h-72 top-0 right-10" />
      <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 className="font-display font-bold text-3xl sm:text-4xl mb-2">{title}</h1>
        <p className="text-slate-300 max-w-2xl">{subtitle}</p>
      </div>
    </div>
  );
}
