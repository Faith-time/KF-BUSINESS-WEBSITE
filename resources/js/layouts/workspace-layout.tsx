import { useState, type ReactNode } from 'react';
import { Link, usePage } from '@inertiajs/react';
import { Menu, X, LogOut, Bell, ChevronDown } from 'lucide-react';
import { getIcon } from '@/lib/icons';
import type { NavItem, WorkspaceConfig } from '@/config/navigation';

type AuthProps = {
  auth?: { user: { nom: string; prenom: string; email: string } | null };
};

const cheminDe = (url: string) => url.split('?')[0].split('#')[0].replace(/\/+$/, '') || '/';

/**
 * Retourne l'élément du menu actif : le premier (tableau de bord) uniquement
 * sur son URL exacte, les autres sur leur URL ou ses sous-URL. Si plusieurs
 * correspondent, l'URL la plus longue l'emporte.
 */
function elementActif(items: NavItem[], url: string): NavItem | null {
  const chemin = cheminDe(url);
  let meilleur: NavItem | null = null;

  items.forEach((item, index) => {
    const href = cheminDe(item.href);
    const correspond = index === 0 ? chemin === href : chemin === href || chemin.startsWith(href + '/');
    if (correspond && (meilleur === null || href.length > cheminDe(meilleur.href).length)) {
      meilleur = item;
    }
  });

  return meilleur;
}

export default function WorkspaceLayout({ config, children }: { config: WorkspaceConfig; children: ReactNode }) {
  const { url, props } = usePage<AuthProps>();
  const [mobileOpen, setMobileOpen] = useState(false);

  const utilisateur = props.auth?.user;
  const initiales = utilisateur
    ? `${utilisateur.prenom?.[0] ?? ''}${utilisateur.nom?.[0] ?? ''}`.toUpperCase()
    : '';
  const nomComplet = utilisateur ? `${utilisateur.prenom} ${utilisateur.nom}` : '';

  const actif = elementActif(config.items, url);

  return (
    <div className="min-h-screen bg-slate-50 flex">
      {/* Sidebar — desktop */}
      <aside className={`hidden lg:flex flex-col w-64 ${config.accentClass} text-white shrink-0 fixed inset-y-0 left-0 z-40`}>
        <SidebarContent config={config} actif={actif} />
      </aside>

      {/* Sidebar — mobile */}
      {mobileOpen && (
        <>
          <div className="fixed inset-0 bg-black/50 z-40 lg:hidden" onClick={() => setMobileOpen(false)} />
          <aside className={`fixed inset-y-0 left-0 w-64 ${config.accentClass} text-white z-50 lg:hidden flex flex-col animate-slide-up`}>
            <SidebarContent config={config} actif={actif} onClose={() => setMobileOpen(false)} />
          </aside>
        </>
      )}

      {/* Main */}
      <div className="flex-1 lg:ml-64 flex flex-col min-h-screen">
        {/* Top bar */}
        <header className="sticky top-0 z-30 bg-white/80 backdrop-blur-md border-b border-slate-200">
          <div className="flex items-center justify-between h-16 px-4 sm:px-6">
            <div className="flex items-center gap-3">
              <button
                onClick={() => setMobileOpen(true)}
                className="lg:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100"
              >
                <Menu className="w-5 h-5" />
              </button>
              <div>
                <h1 className="font-display font-bold text-slate-900 text-base sm:text-lg">
                  {actif?.label ?? config.roleLabel}
                </h1>
                <p className="text-xs text-slate-500 hidden sm:block">
                  {config.roleLabel} • KF Business International
                </p>
              </div>
            </div>
            <div className="flex items-center gap-3">
              <button className="relative p-2 rounded-lg text-slate-600 hover:bg-slate-100">
                <Bell className="w-5 h-5" />
                <span className={`absolute top-1.5 right-1.5 w-2 h-2 rounded-full ${config.accentBg}`} />
              </button>
              <div className="flex items-center gap-2 pl-3 border-l border-slate-200">
                <div className={`w-8 h-8 rounded-full ${config.accentGradient} flex items-center justify-center text-white text-xs font-bold`}>
                  {initiales}
                </div>
                <div className="hidden sm:block">
                  <div className="text-sm font-medium text-slate-800">{nomComplet}</div>
                  <div className="text-xs text-slate-500">{config.roleLabel}</div>
                </div>
                <ChevronDown className="w-4 h-4 text-slate-400 hidden sm:block" />
              </div>
            </div>
          </div>
        </header>

        {/* Page content */}
        <main className="flex-1 p-4 sm:p-6 lg:p-8 animate-fade-in">{children}</main>
      </div>
    </div>
  );
}

function SidebarContent({
  config,
  actif,
  onClose,
}: {
  config: WorkspaceConfig;
  actif: NavItem | null;
  onClose?: () => void;
}) {
  return (
    <>
      {/* Logo header */}
      <div className="flex items-center justify-between p-5 border-b border-white/10">
        <Link href={config.items[0].href} onClick={onClose} className="flex items-center gap-2.5">
          <div className="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center backdrop-blur-sm">
            <span className="text-white font-display font-bold text-lg">KF</span>
          </div>
          <div className="text-left">
            <div className="font-display font-bold text-white text-sm leading-tight">KF Business</div>
            <div className="text-[10px] text-white/60 leading-tight">{config.roleLabel}</div>
          </div>
        </Link>
        {onClose && (
          <button onClick={onClose} className="p-1.5 rounded-lg text-white/60 hover:bg-white/10">
            <X className="w-5 h-5" />
          </button>
        )}
      </div>

      {/* Nav items */}
      <nav className="flex-1 overflow-y-auto py-4 px-3 space-y-1">
        {config.items.map((item) => {
          const Icon = getIcon(item.icon);
          const estActif = actif?.href === item.href;
          return (
            <Link
              key={item.href}
              href={item.href}
              onClick={onClose}
              className={`w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all ${
                estActif
                  ? 'bg-white/15 text-white shadow-sm'
                  : 'text-white/70 hover:text-white hover:bg-white/5'
              }`}
            >
              <Icon className="w-[18px] h-[18px] shrink-0" />
              <span className="truncate">{item.label}</span>
              {estActif && <span className="ml-auto w-1.5 h-1.5 rounded-full bg-white" />}
            </Link>
          );
        })}
      </nav>

      {/* Footer */}
      <div className="p-3 border-t border-white/10 space-y-1">
        <Link
          href="/logout"
          method="post"
          as="button"
          className="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-white/70 hover:text-white hover:bg-white/5 transition-colors"
        >
          <LogOut className="w-[18px] h-[18px]" />
          Déconnexion
        </Link>
      </div>
    </>
  );
}
