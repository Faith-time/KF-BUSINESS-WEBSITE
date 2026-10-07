import { useState, type ReactNode } from 'react';
import { Link, usePage } from '@inertiajs/react';
import { Menu, X } from 'lucide-react';
import { getIcon } from '@/lib/icons';
import { publicNav } from '@/config/navigation';

type AuthProps = {
  auth?: { user: { nom: string; prenom: string } | null };
};

export function PublicHeader() {
  const [mobileOpen, setMobileOpen] = useState(false);
  const { auth } = usePage<AuthProps>().props;
  const connecte = Boolean(auth?.user);

  return (
    <header className="sticky top-0 z-50 glass border-b border-slate-200/60">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between h-16">
          {/* Logo */}
          <Link href="/" className="flex items-center gap-2.5 group">
            <div className="w-10 h-10 rounded-xl bg-gradient-to-br from-brand-700 to-brand-500 flex items-center justify-center shadow-lg shadow-brand-500/30 group-hover:scale-105 transition-transform">
              <span className="text-white font-display font-bold text-lg">KF</span>
            </div>
            <div className="text-left hidden sm:block">
              <div className="font-display font-bold text-slate-900 text-sm leading-tight">KF Business</div>
              <div className="text-[10px] text-slate-500 leading-tight">Company International</div>
            </div>
          </Link>

          {/* Desktop nav */}
          <nav className="hidden lg:flex items-center gap-1">
            {publicNav.map((item) => {
              const Icon = getIcon(item.icon);
              return (
                <Link
                  key={item.href}
                  href={item.href}
                  className="flex items-center gap-1.5 px-3.5 py-2 rounded-lg text-sm font-medium text-slate-600 hover:text-brand-700 hover:bg-brand-50 transition-colors"
                >
                  <Icon className="w-4 h-4" />
                  {item.label}
                </Link>
              );
            })}
          </nav>

          {/* Right actions */}
          <div className="flex items-center gap-2">
            {connecte ? (
              <Link
                href="/dashboard"
                className="flex items-center px-4 py-2 rounded-lg bg-gradient-to-r from-brand-700 to-brand-500 text-white text-sm font-semibold shadow-md shadow-brand-500/30 hover:shadow-lg hover:shadow-brand-500/40 transition-all"
              >
                Mon espace
              </Link>
            ) : (
              <>
                <Link
                  href="/login"
                  className="hidden sm:flex items-center px-4 py-2 text-sm font-medium text-slate-700 hover:text-brand-700 transition-colors"
                >
                  Connexion
                </Link>
                <Link
                  href="/register"
                  className="flex items-center px-4 py-2 rounded-lg bg-gradient-to-r from-brand-700 to-brand-500 text-white text-sm font-semibold shadow-md shadow-brand-500/30 hover:shadow-lg hover:shadow-brand-500/40 transition-all"
                >
                  Investir
                </Link>
              </>
            )}
            <button
              onClick={() => setMobileOpen(!mobileOpen)}
              className="lg:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100"
            >
              {mobileOpen ? <X className="w-5 h-5" /> : <Menu className="w-5 h-5" />}
            </button>
          </div>
        </div>

        {/* Mobile nav */}
        {mobileOpen && (
          <nav className="lg:hidden py-3 border-t border-slate-200/60 animate-fade-in">
            {publicNav.map((item) => {
              const Icon = getIcon(item.icon);
              return (
                <Link
                  key={item.href}
                  href={item.href}
                  onClick={() => setMobileOpen(false)}
                  className="flex items-center gap-2.5 w-full px-3 py-2.5 rounded-lg text-sm font-medium text-slate-600 hover:text-brand-700 hover:bg-brand-50"
                >
                  <Icon className="w-4 h-4" />
                  {item.label}
                </Link>
              );
            })}
          </nav>
        )}
      </div>
    </header>
  );
}

export function PublicFooter() {
  return (
    <footer className="bg-brand-900 text-slate-300 mt-20">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div className="grid grid-cols-1 md:grid-cols-4 gap-8">
          {/* Brand */}
          <div className="md:col-span-1">
            <div className="flex items-center gap-2.5 mb-4">
              <div className="w-10 h-10 rounded-xl bg-gradient-to-br from-brand-500 to-brand-700 flex items-center justify-center">
                <span className="text-white font-display font-bold text-lg">KF</span>
              </div>
              <div>
                <div className="font-display font-bold text-white text-sm">KF Business</div>
                <div className="text-[10px] text-slate-400">Company International</div>
              </div>
            </div>
            <p className="text-sm text-slate-400 leading-relaxed">
              Plateforme d'investissement au Sénégal. Financez des projets entrepreneuriaux dans l'agriculture,
              l'agroalimentaire et l'aviculture.
            </p>
          </div>

          {/* Links */}
          <div>
            <h4 className="text-white font-semibold text-sm mb-4">Plateforme</h4>
            <ul className="space-y-2 text-sm">
              <li><Link href="/projets" className="hover:text-white transition-colors">Projets</Link></li>
              <li><Link href="/comment-ca-marche" className="hover:text-white transition-colors">Comment investir</Link></li>
              <li><Link href="/transparence" className="hover:text-white transition-colors">Transparence</Link></li>
              <li><Link href="/a-propos" className="hover:text-white transition-colors">À propos</Link></li>
            </ul>
          </div>

          <div>
            <h4 className="text-white font-semibold text-sm mb-4">Légal</h4>
            <ul className="space-y-2 text-sm">
              <li><Link href="/mentions-legales" className="hover:text-white transition-colors">Mentions légales</Link></li>
              <li><Link href="/politique-de-confidentialite" className="hover:text-white transition-colors">Confidentialité</Link></li>
              <li><Link href="/conditions-generales" className="hover:text-white transition-colors">Conditions d'investissement</Link></li>
              <li><Link href="/conditions-generales" className="hover:text-white transition-colors">Risques</Link></li>
            </ul>
          </div>

          <div>
            <h4 className="text-white font-semibold text-sm mb-4">Contact</h4>
            <ul className="space-y-2 text-sm text-slate-400">
              <li>Dakar, Sénégal</li>
              <li>contact@kfbusiness.sn</li>
              <li>+221 33 800 00 00</li>
            </ul>
            <div className="flex gap-2 mt-4">
              <div className="w-8 h-8 rounded-lg bg-brand-800 hover:bg-brand-700 flex items-center justify-center cursor-pointer transition-colors">
                <span className="text-xs font-bold text-white">in</span>
              </div>
              <div className="w-8 h-8 rounded-lg bg-brand-800 hover:bg-brand-700 flex items-center justify-center cursor-pointer transition-colors">
                <span className="text-xs font-bold text-white">f</span>
              </div>
              <div className="w-8 h-8 rounded-lg bg-brand-800 hover:bg-brand-700 flex items-center justify-center cursor-pointer transition-colors">
                <span className="text-xs font-bold text-white">X</span>
              </div>
            </div>
          </div>
        </div>

        <div className="border-t border-brand-800 mt-10 pt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
          <p className="text-xs text-slate-500">© 2026 KF Business Company International. Tous droits réservés.</p>
          <p className="text-xs text-slate-500">
            Plateforme en mode présentation — Collecte non activée. Validation juridique en cours.
          </p>
        </div>
      </div>
    </footer>
  );
}

export default function PublicLayout({ children }: { children: ReactNode }) {
  return (
    <>
      <PublicHeader />
      <main>{children}</main>
      <PublicFooter />
    </>
  );
}
