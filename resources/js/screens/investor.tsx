import { useRouter } from '@/lib/router-shim';
import { getIcon } from '@/lib/icons';
import { projects, investmentTickets, formatFCFA, formatNumber, formatPercent } from '@/lib/data';
import {
  TrendingUp, Wallet, FileText, Download, CheckCircle2, Clock, AlertCircle,
  MapPin, ArrowRight, ShieldCheck, Bell, PieChart, BarChart3, Eye, Lock,
  User, Mail, Phone, Calendar, Briefcase, ChevronRight,
} from 'lucide-react';

const heroImg = 'https://images.pexels.com/photos/4911739/pexels-photo-4911739.jpeg?auto=compress&cs=tinysrgb&w=800';

/* ============ INVESTOR DASHBOARD ============ */
export function InvestorDashboard() {
  const { navigate } = useRouter();

  const stats = [
    { label: 'Montant investi', value: formatFCFA(2_500_000), icon: Wallet, color: 'bg-brand-50 text-brand-600' },
    { label: 'Actions détenues', value: '250', icon: PieChart, color: 'bg-emerald-50 text-emerald-600' },
    { label: 'Valeur estimée', value: formatFCFA(2_950_000), icon: TrendingUp, color: 'bg-gold-50 text-gold-600' },
    { label: 'Dividendes perçus', value: formatFCFA(0), icon: BarChart3, color: 'bg-slate-100 text-slate-600' },
  ];

  return (
    <div className="space-y-6">
      {/* Welcome banner */}
      <div className="relative overflow-hidden rounded-2xl bg-gradient-to-br from-brand-800 to-brand-600 p-6 sm:p-8 text-white">
        <div className="blob bg-gold-400 w-64 h-64 top-0 right-0" />
        <div className="relative">
          <h2 className="font-display font-bold text-2xl mb-1">Bienvenue, Investisseur</h2>
          <p className="text-slate-200 text-sm mb-4">Voici un aperçu de votre portefeuille et de l'activité du projet.</p>
          <button onClick={() => navigate('investor', 'inv-projects')} className="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gold-400 text-brand-900 font-semibold text-sm hover:bg-gold-300 transition-colors">
            Voir les projets <ArrowRight className="w-4 h-4" />
          </button>
        </div>
      </div>

      {/* Stats */}
      <div className="grid grid-cols-2 lg:grid-cols-4 gap-4">
        {stats.map((s) => (
          <div key={s.label} className="bg-white rounded-xl p-5 border border-slate-100 card-hover">
            <div className={`w-10 h-10 rounded-lg ${s.color} flex items-center justify-center mb-3`}>
              <s.icon className="w-5 h-5" />
            </div>
            <div className="text-2xl font-display font-bold text-slate-900">{s.value}</div>
            <div className="text-xs text-slate-500 mt-0.5">{s.label}</div>
          </div>
        ))}
      </div>

      {/* Portfolio + project status */}
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div className="lg:col-span-2 bg-white rounded-2xl p-6 border border-slate-100">
          <div className="flex items-center justify-between mb-4">
            <h3 className="font-display font-bold text-lg text-slate-900">Mon portefeuille</h3>
            <button onClick={() => navigate('investor', 'inv-portfolio')} className="text-sm text-brand-600 font-medium hover:text-brand-700">Voir tout</button>
          </div>
          <div className="space-y-3">
            {[
              { name: 'Ferme Avicole 5 000 Pondeuses', shares: 250, amount: 2_500_000, pct: 0.83 },
              { name: 'Mini-Boulangerie Dakar', shares: 0, amount: 0, pct: 0, pending: true },
            ].map((h) => (
              <div key={h.name} className="flex items-center gap-4 p-3 rounded-lg border border-slate-100 hover:bg-slate-50 transition-colors">
                <div className="w-10 h-10 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center shrink-0">
                  <Briefcase className="w-5 h-5" />
                </div>
                <div className="flex-1 min-w-0">
                  <div className="font-medium text-slate-900 text-sm truncate">{h.name}</div>
                  <div className="text-xs text-slate-500">{h.shares} actions • {formatFCFA(h.amount)}</div>
                </div>
                {h.pending ? (
                  <span className="px-2 py-1 rounded-full bg-amber-50 text-amber-600 text-xs font-medium flex items-center gap-1">
                    <Clock className="w-3 h-3" /> En attente
                  </span>
                ) : (
                  <span className="px-2 py-1 rounded-full bg-emerald-50 text-emerald-600 text-xs font-medium flex items-center gap-1">
                    <CheckCircle2 className="w-3 h-3" /> Actif
                  </span>
                )}
              </div>
            ))}
          </div>
        </div>

        <div className="bg-white rounded-2xl p-6 border border-slate-100">
          <h3 className="font-display font-bold text-lg text-slate-900 mb-4">Projet pilote</h3>
          <div className="rounded-xl overflow-hidden mb-4">
            <img src={heroImg} alt="Ferme avicole" className="w-full h-32 object-cover" />
          </div>
          <div className="space-y-2 text-sm">
            <div className="flex justify-between"><span className="text-slate-500">Progression</span><span className="font-semibold text-slate-900">12%</span></div>
            <div className="h-2 rounded-full bg-slate-100 overflow-hidden">
              <div className="h-full rounded-full bg-gradient-to-r from-brand-600 to-brand-400" style={{ width: '12%' }} />
            </div>
            <div className="flex justify-between pt-2"><span className="text-slate-500">Phase actuelle</span><span className="font-semibold text-slate-900">Préparation</span></div>
            <div className="flex justify-between"><span className="text-slate-500">Prochaine étape</span><span className="font-semibold text-slate-900">Installation</span></div>
          </div>
          <button onClick={() => navigate('investor', 'inv-results')} className="w-full mt-4 py-2.5 rounded-lg bg-brand-50 text-brand-700 text-sm font-medium hover:bg-brand-100 transition-colors">
            Suivre les résultats
          </button>
        </div>
      </div>

      {/* Recent activity */}
      <div className="bg-white rounded-2xl p-6 border border-slate-100">
        <h3 className="font-display font-bold text-lg text-slate-900 mb-4">Activité récente</h3>
        <div className="space-y-3">
          {[
            { icon: CheckCircle2, color: 'text-emerald-600 bg-emerald-50', t: 'Souscription confirmée — Ferme avicole', d: '250 actions • 2 500 000 FCFA', time: 'Il y a 3 jours' },
            { icon: FileText, color: 'text-brand-600 bg-brand-50', t: 'Document contractuel disponible', d: 'Contrat de souscription signé', time: 'Il y a 3 jours' },
            { icon: Clock, color: 'text-amber-600 bg-amber-50', t: 'Pré-souscription — Mini-Boulangerie', d: 'En attente de validation KYC', time: 'Il y a 5 jours' },
          ].map((a, i) => (
            <div key={i} className="flex items-start gap-3 p-3 rounded-lg hover:bg-slate-50 transition-colors">
              <div className={`w-9 h-9 rounded-lg ${a.color} flex items-center justify-center shrink-0`}>
                <a.icon className="w-4 h-4" />
              </div>
              <div className="flex-1">
                <div className="font-medium text-slate-900 text-sm">{a.t}</div>
                <div className="text-xs text-slate-500">{a.d}</div>
              </div>
              <div className="text-xs text-slate-400">{a.time}</div>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}

/* ============ INVESTOR PROJECTS ============ */
export function InvestorProjects() {
  const { navigate } = useRouter();
  return (
    <div className="space-y-6">
      <PageTitle title="Projets disponibles" subtitle="Parcourez et souscrivez aux projets ouverts par KF Business." />
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {projects.map((p) => (
          <div key={p.id} className="card-hover bg-white rounded-2xl overflow-hidden border border-slate-100">
            <div className="relative h-40 overflow-hidden">
              <img src={p.image} alt={p.name} className="w-full h-full object-cover" />
              <span className="absolute top-3 left-3 px-2.5 py-1 rounded-full bg-white/90 backdrop-blur-sm text-xs font-bold text-brand-700">{p.sector}</span>
            </div>
            <div className="p-5">
              <div className="flex items-center gap-1.5 text-xs text-slate-500 mb-2"><MapPin className="w-3.5 h-3.5" /> {p.location}</div>
              <h3 className="font-display font-bold text-base text-slate-900 mb-2">{p.name}</h3>
              <div className="grid grid-cols-2 gap-2 mb-4">
                <div><div className="text-xs text-slate-400">Rendement visé</div><div className="font-semibold text-emerald-600 text-sm">{formatPercent(p.expectedReturn)}</div></div>
                <div><div className="text-xs text-slate-400">Actions dispo.</div><div className="font-semibold text-slate-900 text-sm">{formatNumber(p.sharesAvailable)}</div></div>
              </div>
              <button onClick={() => navigate('investor', 'inv-subscribe')} className="w-full py-2.5 rounded-lg bg-brand-700 text-white text-sm font-semibold hover:bg-brand-800 transition-colors">
                Souscrire
              </button>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}

/* ============ INVESTOR SUBSCRIBE ============ */
export function InvestorSubscribe() {
  const p = projects[0];
  return (
    <div className="space-y-6">
      <PageTitle title="Souscrire au projet" subtitle="Ferme Avicole 5 000 Pondeuses — Thiès, Sénégal" />

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div className="lg:col-span-2 space-y-6">
          {/* Ticket selection */}
          <div className="bg-white rounded-2xl p-6 border border-slate-100">
            <h3 className="font-display font-bold text-lg text-slate-900 mb-4">1. Choisissez un ticket</h3>
            <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
              {investmentTickets.map((t, i) => (
                <button key={t.amount} className={`p-4 rounded-xl border-2 text-center transition-all ${i === 2 ? 'border-brand-600 bg-brand-50' : 'border-slate-200 hover:border-brand-300'}`}>
                  <div className="text-xs text-slate-500 mb-1">{t.label}</div>
                  <div className="font-display font-bold text-slate-900">{formatNumber(t.amount)}</div>
                  <div className="text-xs text-slate-500">FCFA</div>
                  <div className="text-xs text-brand-600 font-medium mt-1">{t.shares} actions</div>
                </button>
              ))}
            </div>
          </div>

          {/* Custom amount */}
          <div className="bg-white rounded-2xl p-6 border border-slate-100">
            <h3 className="font-display font-bold text-lg text-slate-900 mb-4">2. Ou personnalisez votre montant</h3>
            <div className="flex items-center gap-3">
              <input type="number" placeholder="500 000" className="flex-1 px-4 py-2.5 rounded-lg border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-100 outline-none text-sm" />
              <span className="text-sm text-slate-500">FCFA</span>
            </div>
            <div className="mt-3 text-sm text-slate-500">
              Soit <span className="font-semibold text-brand-600">50 actions</span> à {formatFCFA(p.sharePrice)} / action
            </div>
          </div>

          {/* Payment method */}
          <div className="bg-white rounded-2xl p-6 border border-slate-100">
            <h3 className="font-display font-bold text-lg text-slate-900 mb-4">3. Moyen de paiement</h3>
            <div className="space-y-3">
              {[
                { name: 'Virement bancaire', desc: 'RIB fourni après confirmation', icon: '🏦' },
                { name: 'Wave', desc: 'Paiement mobile money', icon: '🌊' },
                { name: 'Orange Money', desc: 'Paiement mobile money', icon: '🟠' },
                { name: 'Free Money', desc: 'Paiement mobile money', icon: '📱' },
              ].map((m, i) => (
                <label key={m.name} className="flex items-center gap-3 p-3 rounded-lg border border-slate-200 cursor-pointer hover:bg-slate-50 transition-colors">
                  <input type="radio" name="payment" defaultChecked={i === 0} className="text-brand-600" />
                  <span className="text-xl">{m.icon}</span>
                  <div className="flex-1">
                    <div className="font-medium text-slate-900 text-sm">{m.name}</div>
                    <div className="text-xs text-slate-500">{m.desc}</div>
                  </div>
                </label>
              ))}
            </div>
            <div className="mt-4 p-3 rounded-lg bg-amber-50 border border-amber-200 flex items-start gap-2">
              <AlertCircle className="w-4 h-4 text-amber-600 shrink-0 mt-0.5" />
              <p className="text-xs text-amber-800">Le paiement définitif sera activé après validation du montage juridique. Votre souscription est enregistrée en mode pré-souscription.</p>
            </div>
          </div>
        </div>

        {/* Summary */}
        <div>
          <div className="bg-white rounded-2xl p-6 border border-slate-100 sticky top-20">
            <h3 className="font-display font-bold text-lg text-slate-900 mb-4">Récapitulatif</h3>
            <div className="space-y-3 text-sm">
              <div className="flex justify-between"><span className="text-slate-500">Projet</span><span className="font-medium text-slate-900 text-right">Ferme Avicole</span></div>
              <div className="flex justify-between"><span className="text-slate-500">Nombre d'actions</span><span className="font-medium text-slate-900">50</span></div>
              <div className="flex justify-between"><span className="text-slate-500">Prix unitaire</span><span className="font-medium text-slate-900">{formatFCFA(p.sharePrice)}</span></div>
              <div className="flex justify-between"><span className="text-slate-500">Frais de gestion</span><span className="font-medium text-slate-900">0 FCFA</span></div>
              <div className="border-t border-slate-100 pt-3 flex justify-between">
                <span className="font-semibold text-slate-900">Total</span>
                <span className="font-display font-bold text-brand-700 text-lg">{formatFCFA(500_000)}</span>
              </div>
            </div>
            <button className="w-full mt-5 py-3 rounded-lg bg-gradient-to-r from-brand-700 to-brand-500 text-white font-semibold shadow-md hover:shadow-lg transition-all">
              Confirmer la pré-souscription
            </button>
            <p className="text-xs text-slate-400 text-center mt-3">Vous recevrez un récapitulatif par email</p>
          </div>
        </div>
      </div>
    </div>
  );
}

/* ============ INVESTOR PORTFOLIO ============ */
export function InvestorPortfolio() {
  return (
    <div className="space-y-6">
      <PageTitle title="Mon portefeuille" subtitle="Vue d'ensemble de vos investissements et de leur performance." />

      <div className="grid grid-cols-2 lg:grid-cols-4 gap-4">
        {[
          { label: 'Montant total investi', value: formatFCFA(2_500_000), icon: Wallet, color: 'bg-brand-50 text-brand-600' },
          { label: 'Actions totales', value: '250', icon: PieChart, color: 'bg-emerald-50 text-emerald-600' },
          { label: 'Valeur estimée', value: formatFCFA(2_950_000), icon: TrendingUp, color: 'bg-gold-50 text-gold-600' },
          { label: 'Plus-value', value: '+18%', icon: BarChart3, color: 'bg-emerald-50 text-emerald-600' },
        ].map((s) => (
          <div key={s.label} className="bg-white rounded-xl p-5 border border-slate-100">
            <div className={`w-10 h-10 rounded-lg ${s.color} flex items-center justify-center mb-3`}><s.icon className="w-5 h-5" /></div>
            <div className="text-xl font-display font-bold text-slate-900">{s.value}</div>
            <div className="text-xs text-slate-500 mt-0.5">{s.label}</div>
          </div>
        ))}
      </div>

      <div className="bg-white rounded-2xl p-6 border border-slate-100">
        <h3 className="font-display font-bold text-lg text-slate-900 mb-4">Détail des détentions</h3>
        <div className="overflow-x-auto">
          <table className="w-full text-sm">
            <thead className="bg-slate-50 text-xs text-slate-500 uppercase">
              <tr>
                <th className="text-left px-4 py-3">Projet</th>
                <th className="text-right px-4 py-3">Actions</th>
                <th className="text-right px-4 py-3">Montant investi</th>
                <th className="text-right px-4 py-3">Valeur actuelle</th>
                <th className="text-right px-4 py-3">Participation</th>
                <th className="text-center px-4 py-3">Statut</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-100">
              <tr className="hover:bg-slate-50">
                <td className="px-4 py-3 font-medium text-slate-900">Ferme Avicole 5 000 Pondeuses</td>
                <td className="px-4 py-3 text-right">250</td>
                <td className="px-4 py-3 text-right">{formatFCFA(2_500_000)}</td>
                <td className="px-4 py-3 text-right font-semibold text-emerald-600">{formatFCFA(2_950_000)}</td>
                <td className="px-4 py-3 text-right">0.83%</td>
                <td className="px-4 py-3 text-center"><span className="px-2 py-1 rounded-full bg-emerald-50 text-emerald-600 text-xs font-medium">Actif</span></td>
              </tr>
              <tr className="hover:bg-slate-50">
                <td className="px-4 py-3 font-medium text-slate-900">Mini-Boulangerie Dakar</td>
                <td className="px-4 py-3 text-right">—</td>
                <td className="px-4 py-3 text-right">—</td>
                <td className="px-4 py-3 text-right">—</td>
                <td className="px-4 py-3 text-right">—</td>
                <td className="px-4 py-3 text-center"><span className="px-2 py-1 rounded-full bg-amber-50 text-amber-600 text-xs font-medium">En attente</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
}

/* ============ INVESTOR HISTORY ============ */
export function InvestorHistory() {
  return (
    <div className="space-y-6">
      <PageTitle title="Historique des souscriptions" subtitle="Toutes vos demandes de souscription, leur statut et leur historique." />
      <div className="bg-white rounded-2xl p-6 border border-slate-100">
        <div className="overflow-x-auto">
          <table className="w-full text-sm">
            <thead className="bg-slate-50 text-xs text-slate-500 uppercase">
              <tr>
                <th className="text-left px-4 py-3">Date</th>
                <th className="text-left px-4 py-3">Projet</th>
                <th className="text-right px-4 py-3">Actions</th>
                <th className="text-right px-4 py-3">Montant</th>
                <th className="text-center px-4 py-3">Statut</th>
                <th className="text-center px-4 py-3">Document</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-100">
              {[
                { date: '22/09/2026', project: 'Ferme Avicole', shares: 250, amount: 2_500_000, status: 'confirmed' },
                { date: '20/09/2026', project: 'Mini-Boulangerie', shares: 50, amount: 500_000, status: 'pending' },
                { date: '15/09/2026', project: 'Ferme Avicole', shares: 10, amount: 100_000, status: 'confirmed' },
              ].map((r, i) => (
                <tr key={i} className="hover:bg-slate-50">
                  <td className="px-4 py-3 text-slate-600">{r.date}</td>
                  <td className="px-4 py-3 font-medium text-slate-900">{r.project}</td>
                  <td className="px-4 py-3 text-right">{r.shares}</td>
                  <td className="px-4 py-3 text-right font-medium">{formatFCFA(r.amount)}</td>
                  <td className="px-4 py-3 text-center">
                    {r.status === 'confirmed' ? (
                      <span className="px-2 py-1 rounded-full bg-emerald-50 text-emerald-600 text-xs font-medium flex items-center gap-1 w-fit mx-auto"><CheckCircle2 className="w-3 h-3" /> Confirmée</span>
                    ) : (
                      <span className="px-2 py-1 rounded-full bg-amber-50 text-amber-600 text-xs font-medium flex items-center gap-1 w-fit mx-auto"><Clock className="w-3 h-3" /> En attente</span>
                    )}
                  </td>
                  <td className="px-4 py-3 text-center">
                    <button className="text-brand-600 hover:text-brand-700"><Download className="w-4 h-4 mx-auto" /></button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
}

/* ============ INVESTOR DOCUMENTS ============ */
export function InvestorDocuments() {
  const docs = [
    { name: 'Contrat de souscription — Ferme Avicole', type: 'PDF', date: '22/09/2026', size: '1.2 Mo' },
    { name: 'Business plan — Ferme Avicole', type: 'PDF', date: '10/09/2026', size: '3.4 Mo' },
    { name: 'Rapport trimestriel Q3 2026', type: 'PDF', date: '01/10/2026', size: '2.1 Mo' },
    { name: 'Statuts de la société', type: 'PDF', date: '05/09/2026', size: '0.8 Mo' },
  ];
  return (
    <div className="space-y-6">
      <PageTitle title="Mes documents" subtitle="Téléchargez vos contrats, rapports et documents contractuels." />
      <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
        {docs.map((d) => (
          <div key={d.name} className="bg-white rounded-2xl p-5 border border-slate-100 card-hover flex items-center gap-4">
            <div className="w-12 h-12 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center shrink-0">
              <FileText className="w-6 h-6" />
            </div>
            <div className="flex-1 min-w-0">
              <div className="font-medium text-slate-900 text-sm truncate">{d.name}</div>
              <div className="text-xs text-slate-500 mt-0.5">{d.type} • {d.size} • {d.date}</div>
            </div>
            <button className="p-2.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-brand-50 hover:text-brand-600 transition-colors">
              <Download className="w-4 h-4" />
            </button>
          </div>
        ))}
      </div>
    </div>
  );
}

/* ============ INVESTOR RESULTS ============ */
export function InvestorResults() {
  return (
    <div className="space-y-6">
      <PageTitle title="Suivi des résultats" subtitle="Performance du projet, indicateurs de production et projections." />

      <div className="grid grid-cols-2 lg:grid-cols-4 gap-4">
        {[
          { label: 'Taux de ponte', value: '82%', icon: TrendingUp, color: 'bg-emerald-50 text-emerald-600' },
          { label: 'Œufs / jour', value: '4 100', icon: BarChart3, color: 'bg-brand-50 text-brand-600' },
          { label: 'Revenus mensuels', value: formatFCFA(15_000_000), icon: Wallet, color: 'bg-gold-50 text-gold-600' },
          { label: 'Charges mensuelles', value: formatFCFA(8_500_000), icon: BarChart3, color: 'bg-slate-100 text-slate-600' },
        ].map((s) => (
          <div key={s.label} className="bg-white rounded-xl p-5 border border-slate-100">
            <div className={`w-10 h-10 rounded-lg ${s.color} flex items-center justify-center mb-3`}><s.icon className="w-5 h-5" /></div>
            <div className="text-xl font-display font-bold text-slate-900">{s.value}</div>
            <div className="text-xs text-slate-500 mt-0.5">{s.label}</div>
          </div>
        ))}
      </div>

      <div className="bg-white rounded-2xl p-6 border border-slate-100">
        <h3 className="font-display font-bold text-lg text-slate-900 mb-4">Évolution de la production</h3>
        <div className="flex items-end gap-2 h-48">
          {[40, 55, 62, 70, 78, 82, 85].map((v, i) => (
            <div key={i} className="flex-1 flex flex-col items-center gap-1">
              <div className="w-full bg-gradient-to-t from-brand-600 to-brand-400 rounded-t-lg transition-all hover:opacity-80" style={{ height: `${v}%` }} />
              <span className="text-xs text-slate-400">M{i + 1}</span>
            </div>
          ))}
        </div>
      </div>

      <div className="bg-white rounded-2xl p-6 border border-slate-100">
        <h3 className="font-display font-bold text-lg text-slate-900 mb-4">Rapports disponibles</h3>
        <div className="space-y-3">
          {['Rapport Q3 2026', 'Rapport Q2 2026', 'Rapport Q1 2026'].map((r) => (
            <div key={r} className="flex items-center gap-3 p-3 rounded-lg border border-slate-100 hover:bg-slate-50 transition-colors">
              <FileText className="w-5 h-5 text-brand-600" />
              <span className="flex-1 text-sm font-medium text-slate-900">{r}</span>
              <button className="text-brand-600 hover:text-brand-700"><Download className="w-4 h-4" /></button>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}

/* ============ INVESTOR TRANSPARENCY ============ */
export function InvestorTransparency() {
  return (
    <div className="space-y-6">
      <PageTitle title="Transparence" subtitle="Toutes les informations sur votre investissement et le projet." />
      <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
        {[
          { icon: Wallet, t: 'Mon investissement', v: formatFCFA(2_500_000), d: 'Montant total investi dans le projet avicole' },
          { icon: PieChart, t: 'Ma participation', v: '0.83%', d: 'Part de détention dans le projet' },
          { icon: TrendingUp, t: 'Résultats du projet', v: '82% ponte', d: 'Taux de ponte moyen actuel' },
          { icon: BarChart3, t: 'Dividendes', v: formatFCFA(0), d: 'Aucune distribution pour le moment' },
        ].map((c) => (
          <div key={c.t} className="bg-white rounded-2xl p-6 border border-slate-100">
            <div className="flex items-start gap-4">
              <div className="w-12 h-12 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center shrink-0">
                <c.icon className="w-6 h-6" />
              </div>
              <div>
                <div className="text-xs text-slate-500">{c.t}</div>
                <div className="font-display font-bold text-2xl text-slate-900 my-1">{c.v}</div>
                <div className="text-sm text-slate-500">{c.d}</div>
              </div>
            </div>
          </div>
        ))}
      </div>

      <div className="bg-amber-50 border border-amber-200 rounded-2xl p-6">
        <div className="flex items-start gap-3">
          <AlertCircle className="w-5 h-5 text-amber-600 shrink-0 mt-0.5" />
          <div>
            <h4 className="font-semibold text-amber-900 mb-2">Risques liés à votre investissement</h4>
            <ul className="text-sm text-amber-800 space-y-1 list-disc list-inside">
              <li>Risque biologique (maladies aviaires)</li>
              <li>Fluctuation du prix des intrants</li>
              <li>Risque de marché</li>
              <li>Pas de garantie de rendement</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  );
}

/* ============ INVESTOR PROFILE ============ */
export function InvestorProfile() {
  return (
    <div className="space-y-6">
      <PageTitle title="Mon profil" subtitle="Vos informations personnelles et statut de vérification." />
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div className="bg-white rounded-2xl p-6 border border-slate-100 text-center">
          <div className="w-20 h-20 rounded-full bg-gradient-to-br from-brand-500 to-brand-700 flex items-center justify-center text-white font-display font-bold text-2xl mx-auto mb-4">ID</div>
          <h3 className="font-display font-bold text-lg text-slate-900">Investisseur Démo</h3>
          <p className="text-sm text-slate-500">investisseur@kfbusiness.sn</p>
          <div className="mt-3 inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-xs font-medium">
            <ShieldCheck className="w-3.5 h-3.5" /> Vérifié (KYC)
          </div>
        </div>
        <div className="lg:col-span-2 bg-white rounded-2xl p-6 border border-slate-100">
          <h3 className="font-display font-bold text-lg text-slate-900 mb-4">Informations personnelles</h3>
          <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
            {[
              { icon: User, label: 'Nom complet', value: 'Investisseur Démo' },
              { icon: Mail, label: 'Email', value: 'investisseur@kfbusiness.sn' },
              { icon: Phone, label: 'Téléphone', value: '+221 77 123 45 67' },
              { icon: Calendar, label: "Date d'inscription", value: '15/09/2026' },
            ].map((f) => (
              <div key={f.label} className="flex items-center gap-3 p-3 rounded-lg bg-slate-50">
                <f.icon className="w-4 h-4 text-slate-400 shrink-0" />
                <div>
                  <div className="text-xs text-slate-500">{f.label}</div>
                  <div className="text-sm font-medium text-slate-900">{f.value}</div>
                </div>
              </div>
            ))}
          </div>
          <button className="mt-4 px-4 py-2 rounded-lg bg-brand-50 text-brand-700 text-sm font-medium hover:bg-brand-100 transition-colors">Modifier mes informations</button>
        </div>
      </div>
    </div>
  );
}

/* ============ INVESTOR SETTINGS ============ */
export function InvestorSettings() {
  return (
    <div className="space-y-6">
      <PageTitle title="Paramètres & sécurité" subtitle="Gérez la sécurité de votre compte et vos préférences." />
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div className="bg-white rounded-2xl p-6 border border-slate-100">
          <h3 className="font-display font-bold text-lg text-slate-900 mb-4 flex items-center gap-2"><Lock className="w-5 h-5 text-brand-600" /> Sécurité</h3>
          <div className="space-y-4">
            <div>
              <label className="text-xs font-medium text-slate-600 mb-1.5 block">Mot de passe actuel</label>
              <input type="password" className="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-100 outline-none text-sm" />
            </div>
            <div>
              <label className="text-xs font-medium text-slate-600 mb-1.5 block">Nouveau mot de passe</label>
              <input type="password" className="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-100 outline-none text-sm" />
            </div>
            <button className="px-4 py-2 rounded-lg bg-brand-700 text-white text-sm font-semibold hover:bg-brand-800 transition-colors">Mettre à jour</button>
          </div>
        </div>
        <div className="bg-white rounded-2xl p-6 border border-slate-100">
          <h3 className="font-display font-bold text-lg text-slate-900 mb-4 flex items-center gap-2"><ShieldCheck className="w-5 h-5 text-emerald-600" /> Double authentification</h3>
          <p className="text-sm text-slate-500 mb-4">Renforcez la sécurité de votre compte avec un code à usage unique envoyé par SMS.</p>
          <div className="flex items-center justify-between p-3 rounded-lg bg-slate-50">
            <span className="text-sm font-medium text-slate-700">2FA par SMS</span>
            <button className="relative w-11 h-6 rounded-full bg-brand-600 transition-colors">
              <span className="absolute top-0.5 right-0.5 w-5 h-5 rounded-full bg-white shadow" />
            </button>
          </div>
          <div className="mt-4 flex items-center justify-between p-3 rounded-lg bg-slate-50">
            <span className="text-sm font-medium text-slate-700">Notifications par email</span>
            <button className="relative w-11 h-6 rounded-full bg-brand-600 transition-colors">
              <span className="absolute top-0.5 right-0.5 w-5 h-5 rounded-full bg-white shadow" />
            </button>
          </div>
        </div>
      </div>
    </div>
  );
}

/* ============ Shared ============ */
function PageTitle({ title, subtitle }: { title: string; subtitle: string }) {
  return (
    <div>
      <h1 className="font-display font-bold text-2xl text-slate-900">{title}</h1>
      <p className="text-sm text-slate-500 mt-1">{subtitle}</p>
    </div>
  );
}
