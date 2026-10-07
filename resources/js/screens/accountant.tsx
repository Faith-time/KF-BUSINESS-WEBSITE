import { formatFCFA, formatNumber, formatPercent } from '@/lib/data';
import {
  Wallet, Users, FileText, Download, TrendingUp, CheckCircle2,
  Clock, AlertCircle, BarChart3, ArrowRight, Settings, ShieldCheck,
  Search, Filter, Eye,
} from 'lucide-react';

function PageTitle({ title, subtitle }: { title: string; subtitle: string }) {
  return (
    <div>
      <h1 className="font-display font-bold text-2xl text-slate-900">{title}</h1>
      <p className="text-sm text-slate-500 mt-1">{subtitle}</p>
    </div>
  );
}

export function AccountantDashboard() {
  const stats = [
    { label: 'Total collecté', value: formatFCFA(125_000_000), icon: Wallet, color: 'bg-emerald-50 text-emerald-600' },
    { label: 'Souscriptions', value: '47', icon: FileText, color: 'bg-brand-50 text-brand-600' },
    { label: 'Investisseurs actifs', value: '32', icon: Users, color: 'bg-gold-50 text-gold-600' },
    { label: 'Paiements en attente', value: '8', icon: Clock, color: 'bg-amber-50 text-amber-600' },
  ];
  return (
    <div className="space-y-6">
      <div className="relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-800 to-emerald-600 p-6 sm:p-8 text-white">
        <div className="blob bg-emerald-400 w-64 h-64 top-0 right-0" />
        <div className="relative">
          <h2 className="font-display font-bold text-2xl mb-1">Tableau de bord comptable</h2>
          <p className="text-emerald-100 text-sm">Suivi des paiements, souscriptions et rapports financiers.</p>
        </div>
      </div>

      <div className="grid grid-cols-2 lg:grid-cols-4 gap-4">
        {stats.map((s) => (
          <div key={s.label} className="bg-white rounded-xl p-5 border border-slate-100 card-hover">
            <div className={`w-10 h-10 rounded-lg ${s.color} flex items-center justify-center mb-3`}><s.icon className="w-5 h-5" /></div>
            <div className="text-xl font-display font-bold text-slate-900">{s.value}</div>
            <div className="text-xs text-slate-500 mt-0.5">{s.label}</div>
          </div>
        ))}
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div className="bg-white rounded-2xl p-6 border border-slate-100">
          <h3 className="font-display font-bold text-lg text-slate-900 mb-4">Souscriptions récentes</h3>
          <div className="space-y-3">
            {[
              { name: 'A. Diop', amount: 1_000_000, status: 'confirmed' },
              { name: 'F. Ndiaye', amount: 500_000, status: 'pending' },
              { name: 'M. Sarr', amount: 5_000_000, status: 'confirmed' },
              { name: 'K. Fall', amount: 100_000, status: 'pending' },
            ].map((r, i) => (
              <div key={i} className="flex items-center gap-3 p-3 rounded-lg border border-slate-100 hover:bg-slate-50 transition-colors">
                <div className="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs font-bold">{r.name[0]}</div>
                <div className="flex-1">
                  <div className="text-sm font-medium text-slate-900">{r.name}</div>
                  <div className="text-xs text-slate-500">{formatFCFA(r.amount)}</div>
                </div>
                {r.status === 'confirmed' ? (
                  <span className="px-2 py-1 rounded-full bg-emerald-50 text-emerald-600 text-xs font-medium flex items-center gap-1"><CheckCircle2 className="w-3 h-3" /> Confirmé</span>
                ) : (
                  <span className="px-2 py-1 rounded-full bg-amber-50 text-amber-600 text-xs font-medium flex items-center gap-1"><Clock className="w-3 h-3" /> En attente</span>
                )}
              </div>
            ))}
          </div>
        </div>

        <div className="bg-white rounded-2xl p-6 border border-slate-100">
          <h3 className="font-display font-bold text-lg text-slate-900 mb-4">Collecte par projet</h3>
          <div className="space-y-4">
            {[
              { name: 'Ferme Avicole', collected: 80_000_000, target: 150_000_000, color: 'from-emerald-600 to-emerald-400' },
              { name: 'Mini-Boulangerie', collected: 30_000_000, target: 40_000_000, color: 'from-brand-600 to-brand-400' },
              { name: 'Culture Maraîchère', collected: 15_000_000, target: 75_000_000, color: 'from-gold-500 to-gold-400' },
            ].map((p) => (
              <div key={p.name}>
                <div className="flex justify-between text-sm mb-1">
                  <span className="text-slate-700 font-medium">{p.name}</span>
                  <span className="text-slate-500">{formatFCFA(p.collected)} / {formatFCFA(p.target)}</span>
                </div>
                <div className="h-2.5 rounded-full bg-slate-100 overflow-hidden">
                  <div className={`h-full rounded-full bg-gradient-to-r ${p.color}`} style={{ width: `${(p.collected / p.target) * 100}%` }} />
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
}

/* ============ ACCOUNTANT PAYMENTS ============ */
export function AccountantPayments() {
  return (
    <div className="space-y-6">
      <PageTitle title="Suivi des paiements" subtitle="Validez et suivez tous les paiements des investisseurs." />
      <div className="bg-white rounded-2xl p-6 border border-slate-100">
        <div className="flex flex-col sm:flex-row gap-3 mb-4">
          <div className="flex-1 relative">
            <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
            <input type="text" placeholder="Rechercher un investisseur..." className="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none text-sm" />
          </div>
          <button className="flex items-center gap-2 px-4 py-2.5 rounded-lg border border-slate-200 text-sm font-medium text-slate-700 hover:bg-slate-50">
            <Filter className="w-4 h-4" /> Filtrer
          </button>
        </div>
        <div className="overflow-x-auto">
          <table className="w-full text-sm">
            <thead className="bg-slate-50 text-xs text-slate-500 uppercase">
              <tr>
                <th className="text-left px-4 py-3">Investisseur</th>
                <th className="text-left px-4 py-3">Projet</th>
                <th className="text-right px-4 py-3">Montant</th>
                <th className="text-left px-4 py-3">Moyen</th>
                <th className="text-center px-4 py-3">Date</th>
                <th className="text-center px-4 py-3">Statut</th>
                <th className="text-center px-4 py-3">Action</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-100">
              {[
                { inv: 'A. Diop', proj: 'Ferme Avicole', amt: 1_000_000, method: 'Virement', date: '22/09', status: 'confirmed' },
                { inv: 'F. Ndiaye', proj: 'Ferme Avicole', amt: 500_000, method: 'Wave', date: '21/09', status: 'pending' },
                { inv: 'M. Sarr', proj: 'Boulangerie', amt: 5_000_000, method: 'Orange Money', date: '20/09', status: 'confirmed' },
                { inv: 'K. Fall', proj: 'Ferme Avicole', amt: 100_000, method: 'Free Money', date: '19/09', status: 'pending' },
                { inv: 'B. Diallo', proj: 'Maraîchère', amt: 500_000, method: 'Virement', date: '18/09', status: 'confirmed' },
              ].map((r, i) => (
                <tr key={i} className="hover:bg-slate-50">
                  <td className="px-4 py-3 font-medium text-slate-900">{r.inv}</td>
                  <td className="px-4 py-3 text-slate-600">{r.proj}</td>
                  <td className="px-4 py-3 text-right font-medium">{formatFCFA(r.amt)}</td>
                  <td className="px-4 py-3 text-slate-600">{r.method}</td>
                  <td className="px-4 py-3 text-center text-slate-500">{r.date}</td>
                  <td className="px-4 py-3 text-center">
                    {r.status === 'confirmed' ? (
                      <span className="px-2 py-1 rounded-full bg-emerald-50 text-emerald-600 text-xs font-medium flex items-center gap-1 w-fit mx-auto"><CheckCircle2 className="w-3 h-3" /> Validé</span>
                    ) : (
                      <span className="px-2 py-1 rounded-full bg-amber-50 text-amber-600 text-xs font-medium flex items-center gap-1 w-fit mx-auto"><Clock className="w-3 h-3" /> En attente</span>
                    )}
                  </td>
                  <td className="px-4 py-3 text-center">
                    {r.status === 'pending' ? (
                      <button className="px-3 py-1 rounded-lg bg-emerald-600 text-white text-xs font-medium hover:bg-emerald-700 transition-colors">Valider</button>
                    ) : (
                      <button className="text-slate-400 hover:text-slate-600"><Eye className="w-4 h-4 mx-auto" /></button>
                    )}
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

/* ============ ACCOUNTANT SUBSCRIPTIONS ============ */
export function AccountantSubscriptions() {
  return (
    <div className="space-y-6">
      <PageTitle title="Souscriptions" subtitle="Toutes les demandes de souscription et leur statut." />
      <div className="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        {[
          { label: 'Total souscriptions', value: '47', color: 'text-emerald-600' },
          { label: 'Confirmées', value: '39', color: 'text-brand-600' },
          { label: 'En attente', value: '8', color: 'text-amber-600' },
        ].map((s) => (
          <div key={s.label} className="bg-white rounded-xl p-5 border border-slate-100">
            <div className={`text-2xl font-display font-bold ${s.color}`}>{s.value}</div>
            <div className="text-xs text-slate-500 mt-0.5">{s.label}</div>
          </div>
        ))}
      </div>
      <div className="bg-white rounded-2xl p-6 border border-slate-100">
        <div className="overflow-x-auto">
          <table className="w-full text-sm">
            <thead className="bg-slate-50 text-xs text-slate-500 uppercase">
              <tr>
                <th className="text-left px-4 py-3">Date</th>
                <th className="text-left px-4 py-3">Investisseur</th>
                <th className="text-left px-4 py-3">Projet</th>
                <th className="text-right px-4 py-3">Actions</th>
                <th className="text-right px-4 py-3">Montant</th>
                <th className="text-center px-4 py-3">Statut</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-100">
              {[
                { date: '22/09', inv: 'A. Diop', proj: 'Ferme Avicole', shares: 100, amt: 1_000_000, status: 'confirmed' },
                { date: '21/09', inv: 'F. Ndiaye', proj: 'Ferme Avicole', shares: 50, amt: 500_000, status: 'pending' },
                { date: '20/09', inv: 'M. Sarr', proj: 'Boulangerie', shares: 500, amt: 5_000_000, status: 'confirmed' },
                { date: '19/09', inv: 'K. Fall', proj: 'Ferme Avicole', shares: 10, amt: 100_000, status: 'pending' },
              ].map((r, i) => (
                <tr key={i} className="hover:bg-slate-50">
                  <td className="px-4 py-3 text-slate-600">{r.date}</td>
                  <td className="px-4 py-3 font-medium text-slate-900">{r.inv}</td>
                  <td className="px-4 py-3 text-slate-600">{r.proj}</td>
                  <td className="px-4 py-3 text-right">{r.shares}</td>
                  <td className="px-4 py-3 text-right font-medium">{formatFCFA(r.amt)}</td>
                  <td className="px-4 py-3 text-center">
                    {r.status === 'confirmed' ? (
                      <span className="px-2 py-1 rounded-full bg-emerald-50 text-emerald-600 text-xs font-medium flex items-center gap-1 w-fit mx-auto"><CheckCircle2 className="w-3 h-3" /> Confirmée</span>
                    ) : (
                      <span className="px-2 py-1 rounded-full bg-amber-50 text-amber-600 text-xs font-medium flex items-center gap-1 w-fit mx-auto"><Clock className="w-3 h-3" /> En attente</span>
                    )}
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

/* ============ ACCOUNTANT INVESTORS ============ */
export function AccountantInvestors() {
  return (
    <div className="space-y-6">
      <PageTitle title="Investisseurs" subtitle="Liste des investisseurs et leur historique de paiement." />
      <div className="bg-white rounded-2xl p-6 border border-slate-100">
        <div className="overflow-x-auto">
          <table className="w-full text-sm">
            <thead className="bg-slate-50 text-xs text-slate-500 uppercase">
              <tr>
                <th className="text-left px-4 py-3">Nom</th>
                <th className="text-left px-4 py-3">Email</th>
                <th className="text-right px-4 py-3">Total investi</th>
                <th className="text-right px-4 py-3">Actions</th>
                <th className="text-center px-4 py-3">KYC</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-100">
              {[
                { name: 'Awa Diop', email: 'awa@email.sn', total: 1_000_000, shares: 100, kyc: true },
                { name: 'Moussa Sarr', email: 'moussa@email.sn', total: 5_000_000, shares: 500, kyc: true },
                { name: 'Fatou Ndiaye', email: 'fatou@email.sn', total: 500_000, shares: 50, kyc: false },
                { name: 'Khadi Fall', email: 'khadi@email.sn', total: 100_000, shares: 10, kyc: true },
              ].map((r, i) => (
                <tr key={i} className="hover:bg-slate-50">
                  <td className="px-4 py-3 font-medium text-slate-900">{r.name}</td>
                  <td className="px-4 py-3 text-slate-600">{r.email}</td>
                  <td className="px-4 py-3 text-right font-medium">{formatFCFA(r.total)}</td>
                  <td className="px-4 py-3 text-right">{r.shares}</td>
                  <td className="px-4 py-3 text-center">
                    {r.kyc ? (
                      <span className="px-2 py-1 rounded-full bg-emerald-50 text-emerald-600 text-xs font-medium flex items-center gap-1 w-fit mx-auto"><CheckCircle2 className="w-3 h-3" /> Vérifié</span>
                    ) : (
                      <span className="px-2 py-1 rounded-full bg-amber-50 text-amber-600 text-xs font-medium flex items-center gap-1 w-fit mx-auto"><Clock className="w-3 h-3" /> En attente</span>
                    )}
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

/* ============ ACCOUNTANT REPORTS ============ */
export function AccountantReports() {
  return (
    <div className="space-y-6">
      <PageTitle title="Rapports financiers" subtitle="Publiez et gérez les rapports financiers des projets." />
      <div className="flex justify-end">
        <button className="flex items-center gap-2 px-4 py-2.5 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 transition-colors">
          <FileText className="w-4 h-4" /> Publier un rapport
        </button>
      </div>
      <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
        {[
          { name: 'Rapport Q3 2026 — Ferme Avicole', date: '01/10/2026', type: 'Trimestriel' },
          { name: 'Rapport Q2 2026 — Ferme Avicole', date: '01/07/2026', type: 'Trimestriel' },
          { name: 'Rapport annuel 2025', date: '15/01/2026', type: 'Annuel' },
          { name: 'Rapport Q1 2026 — Boulangerie', date: '01/04/2026', type: 'Trimestriel' },
        ].map((r) => (
          <div key={r.name} className="bg-white rounded-2xl p-5 border border-slate-100 card-hover flex items-center gap-4">
            <div className="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
              <FileText className="w-6 h-6" />
            </div>
            <div className="flex-1 min-w-0">
              <div className="font-medium text-slate-900 text-sm truncate">{r.name}</div>
              <div className="text-xs text-slate-500 mt-0.5">{r.type} • {r.date}</div>
            </div>
            <button className="p-2.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 transition-colors">
              <Download className="w-4 h-4" />
            </button>
          </div>
        ))}
      </div>
    </div>
  );
}

/* ============ ACCOUNTANT DOCUMENTS ============ */
export function AccountantDocuments() {
  return (
    <div className="space-y-6">
      <PageTitle title="Documents" subtitle="Gérez les documents comptables et contractuels." />
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        {[
          { name: 'Factures fournisseurs', count: 24, icon: FileText },
          { name: 'Reçus de paiement', count: 47, icon: Wallet },
          { name: 'Contrats signés', count: 39, icon: ShieldCheck },
          { name: 'Déclarations fiscales', count: 6, icon: BarChart3 },
          { name: 'États financiers', count: 4, icon: TrendingUp },
          { name: 'Justificatifs', count: 18, icon: FileText },
        ].map((d) => (
          <div key={d.name} className="bg-white rounded-2xl p-5 border border-slate-100 card-hover">
            <div className="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-3">
              <d.icon className="w-6 h-6" />
            </div>
            <div className="font-medium text-slate-900 text-sm">{d.name}</div>
            <div className="text-xs text-slate-500 mt-1">{d.count} documents</div>
          </div>
        ))}
      </div>
    </div>
  );
}

/* ============ ACCOUNTANT EXPORTS ============ */
export function AccountantExports() {
  return (
    <div className="space-y-6">
      <PageTitle title="Exportations comptables" subtitle="Exportez les données comptables et financières." />
      <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
        {[
          { name: 'Export des paiements', desc: 'Tous les paiements validés et en attente', icon: Wallet },
          { name: 'Export des souscriptions', desc: 'Toutes les souscriptions par projet', icon: FileText },
          { name: 'Export des investisseurs', desc: 'Liste complète des investisseurs', icon: Users },
          { name: 'Grand livre comptable', desc: 'Tous les mouvements comptables', icon: BarChart3 },
        ].map((e) => (
          <div key={e.name} className="bg-white rounded-2xl p-6 border border-slate-100 flex items-center gap-4">
            <div className="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
              <e.icon className="w-6 h-6" />
            </div>
            <div className="flex-1">
              <div className="font-medium text-slate-900 text-sm">{e.name}</div>
              <div className="text-xs text-slate-500 mt-0.5">{e.desc}</div>
            </div>
            <button className="flex items-center gap-2 px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700 transition-colors">
              <Download className="w-4 h-4" /> Exporter
            </button>
          </div>
        ))}
      </div>
    </div>
  );
}

/* ============ ACCOUNTANT SETTINGS ============ */
export function AccountantSettings() {
  return (
    <div className="space-y-6">
      <PageTitle title="Paramètres" subtitle="Gérez vos préférences et votre accès." />
      <div className="bg-white rounded-2xl p-6 border border-slate-100">
        <h3 className="font-display font-bold text-lg text-slate-900 mb-4 flex items-center gap-2"><Settings className="w-5 h-5 text-emerald-600" /> Préférences</h3>
        <div className="space-y-4">
          <div className="flex items-center justify-between p-3 rounded-lg bg-slate-50">
            <div>
              <div className="text-sm font-medium text-slate-700">Notifications de paiement</div>
              <div className="text-xs text-slate-500">Recevoir un email à chaque nouveau paiement</div>
            </div>
            <button className="relative w-11 h-6 rounded-full bg-emerald-600 transition-colors">
              <span className="absolute top-0.5 right-0.5 w-5 h-5 rounded-full bg-white shadow" />
            </button>
          </div>
          <div className="flex items-center justify-between p-3 rounded-lg bg-slate-50">
            <div>
              <div className="text-sm font-medium text-slate-700">Rapports automatiques</div>
              <div className="text-xs text-slate-500">Générer un rapport mensuel automatiquement</div>
            </div>
            <button className="relative w-11 h-6 rounded-full bg-emerald-600 transition-colors">
              <span className="absolute top-0.5 right-0.5 w-5 h-5 rounded-full bg-white shadow" />
            </button>
          </div>
        </div>
      </div>
    </div>
  );
}
