export type Espace = 'investisseur' | 'comptable' | 'admin';

export interface NavItem {
  label: string;
  href: string;
  icon: string;
}

export interface WorkspaceConfig {
  espace: Espace;
  roleLabel: string;
  accentClass: string;
  accentBg: string;
  accentText: string;
  accentBorder: string;
  accentGradient: string;
  items: NavItem[]; // le premier élément est toujours le tableau de bord
}

export const publicNav: NavItem[] = [
  { label: 'Accueil', href: '/', icon: 'home' },
  { label: 'KF Business', href: '/a-propos', icon: 'home' },
  { label: 'Projets', href: '/projets', icon: 'projects' },
  { label: 'Comment investir', href: '/comment-ca-marche', icon: 'trending' },
  { label: 'Transparence', href: '/transparence', icon: 'clipboard' },
  { label: 'Contact', href: '/contact', icon: 'help' },
];

export const investisseurNav: WorkspaceConfig = {
  espace: 'investisseur',
  roleLabel: 'Espace Investisseur',
  accentClass: 'bg-brand-900',
  accentBg: 'bg-brand-500',
  accentText: 'text-brand-600',
  accentBorder: 'border-brand-500',
  accentGradient: 'bg-gradient-to-br from-brand-500 to-brand-700',
  items: [
    { label: 'Tableau de bord', href: '/investisseur', icon: 'dashboard' },
    { label: 'Projets disponibles', href: '/investisseur/projets', icon: 'projects' },
    { label: 'Souscrire', href: '/investisseur/souscriptions/create', icon: 'wallet' },
    { label: 'Mon portefeuille', href: '/investisseur/portefeuille', icon: 'briefcase' },
    { label: 'Historique des souscriptions', href: '/investisseur/souscriptions', icon: 'clipboard' },
    { label: 'Mes documents', href: '/investisseur/documents', icon: 'documents' },
    { label: 'Suivi des résultats', href: '/investisseur/resultats', icon: 'trending' },
    { label: 'Transparence', href: '/investisseur/transparence', icon: 'clipboard' }, // route à ajouter
    { label: 'Mon profil', href: '/investisseur/profil', icon: 'user' },
    { label: 'Paramètres & sécurité', href: '/settings/security', icon: 'settings' },
  ],
};

export const comptableNav: WorkspaceConfig = {
  espace: 'comptable',
  roleLabel: 'Espace Comptable',
  accentClass: 'bg-emerald-800',
  accentBg: 'bg-emerald-500',
  accentText: 'text-emerald-600',
  accentBorder: 'border-emerald-500',
  accentGradient: 'bg-gradient-to-br from-emerald-500 to-emerald-700',
  items: [
    { label: 'Tableau de bord', href: '/comptable', icon: 'dashboard' },
    { label: 'Suivi des paiements', href: '/comptable/paiements', icon: 'wallet' },
    { label: 'Souscriptions', href: '/comptable/souscriptions', icon: 'clipboard' },
    { label: 'Investisseurs', href: '/comptable/investisseurs', icon: 'users' },
    { label: 'Rapports financiers', href: '/comptable/rapports', icon: 'reports' }, // route à ajouter
    { label: 'Documents', href: '/comptable/documents', icon: 'documents' },
    { label: 'Exportations comptables', href: '/comptable/exports', icon: 'download' }, // route index à ajouter
    { label: 'Paramètres', href: '/settings/profile', icon: 'settings' },
  ],
};

export const adminNav: WorkspaceConfig = {
  espace: 'admin',
  roleLabel: 'Administration',
  accentClass: 'bg-slate-900',
  accentBg: 'bg-slate-500',
  accentText: 'text-slate-600',
  accentBorder: 'border-slate-500',
  accentGradient: 'bg-gradient-to-br from-slate-600 to-slate-800',
  items: [
    { label: 'Tableau de bord', href: '/admin', icon: 'dashboard' },
    { label: 'Gestion des projets', href: '/admin/projets', icon: 'projects' },
    { label: 'Gestion des investisseurs', href: '/admin/investisseurs', icon: 'users' },
    { label: 'Validation des dossiers', href: '/admin/kyc', icon: 'verification' },
    { label: 'Suivi des souscriptions', href: '/admin/souscriptions', icon: 'clipboard' },
    { label: 'Suivi des paiements', href: '/admin/paiements', icon: 'wallet' },
    { label: 'Gestion des actions', href: '/admin/actions', icon: 'trending' }, // à décider
    { label: 'Gestion des documents', href: '/admin/documents', icon: 'documents' },
    { label: 'Rapports financiers', href: '/admin/rapports', icon: 'reports' }, // route à ajouter
    { label: 'Exportations', href: '/admin/exports', icon: 'download' },        // route index à ajouter
    { label: 'Journal des opérations', href: '/admin/journal', icon: 'book' },
    { label: 'Paramètres & accès', href: '/admin/utilisateurs', icon: 'settings' },
  ],
};
