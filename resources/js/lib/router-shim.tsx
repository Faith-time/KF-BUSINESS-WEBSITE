import { router } from '@inertiajs/react';

export type Role = 'visitor' | 'investor' | 'accountant' | 'admin';

// Slug du projet utilisé par les écrans de démo pour « Voir le projet ».
// À remplacer par le slug réel (ou par un lien construit avec projet.slug).
export const PROJET_DEMO_SLUG = 'ferme-avicole-5000-pondeuses';

const URLS: Record<string, string> = {
  // visiteur
  home: '/',
  about: '/a-propos',
  projects: '/projets',
  'project-detail': `/projets/${PROJET_DEMO_SLUG}`,
  'how-it-works': '/comment-ca-marche',
  transparency: '/transparence',
  contact: '/contact',
  legal: '/mentions-legales',
  login: '/login',
  register: '/register',
  // investisseur
  'inv-dashboard': '/investisseur',
  'inv-projects': '/investisseur/projets',
  'inv-subscribe': '/investisseur/souscriptions/create',
  'inv-portfolio': '/investisseur/portefeuille',
  'inv-history': '/investisseur/souscriptions',
  'inv-documents': '/investisseur/documents',
  'inv-results': '/investisseur/resultats',
  'inv-transparency': '/investisseur/transparence',
  'inv-profile': '/investisseur/profil',
  'inv-settings': '/settings/security',
  // comptable
  'acc-dashboard': '/comptable',
  'acc-payments': '/comptable/paiements',
  'acc-subscriptions': '/comptable/souscriptions',
  'acc-investors': '/comptable/investisseurs',
  'acc-reports': '/comptable/rapports',
  'acc-documents': '/comptable/documents',
  'acc-exports': '/comptable/exports',
  'acc-settings': '/settings/profile',
  // administrateur
  'adm-dashboard': '/admin',
  'adm-projects': '/admin/projets',
  'adm-investors': '/admin/investisseurs',
  'adm-verifications': '/admin/kyc',
  'adm-subscriptions': '/admin/souscriptions',
  'adm-payments': '/admin/paiements',
  'adm-shares': '/admin/actions',
  'adm-documents': '/admin/documents',
  'adm-reports': '/admin/rapports',
  'adm-exports': '/admin/exports',
  'adm-logs': '/admin/journal',
  'adm-settings': '/admin/utilisateurs',
};

/** Même API que le routeur de la maquette : navigate(role, page) → navigation Inertia. */
export function useRouter() {
  return {
    navigate: (_role: Role, page: string) => {
      router.visit(URLS[page] ?? '/');
    },
  };
}
