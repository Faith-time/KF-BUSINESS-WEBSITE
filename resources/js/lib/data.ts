export interface Project {
  id: string;
  name: string;
  sector: string;
  location: string;
  budget: number;
  sharesTotal: number;
  sharePrice: number;
  sharesAvailable: number;
  expectedReturn: number;
  duration: string;
  image: string;
  status: 'presentation' | 'open' | 'funded' | 'closed';
  description: string;
}

export const projects: Project[] = [
  {
    id: 'aviculture-5000',
    name: 'Ferme Avicole 5 000 Pondeuses',
    sector: 'Aviculture',
    location: 'Thiès, Sénégal',
    budget: 300_000_000,
    sharesTotal: 30_000,
    sharePrice: 10_000,
    sharesAvailable: 15_000,
    expectedReturn: 18,
    duration: '36 mois',
    image: 'https://images.pexels.com/photos/4911739/pexels-photo-4911739.jpeg?auto=compress&cs=tinysrgb&w=800',
    status: 'presentation',
    description:
      "Projet de ferme avicole de 5 000 poules pondeuses à Thiès. Production d'œufs destinés au marché sénégalais avec objectif d'autosuffisance progressive.",
  },
  {
    id: 'agriculture',
    name: 'Culture Maraîchère Industrielle',
    sector: 'Agriculture',
    location: 'Saint-Louis, Sénégal',
    budget: 150_000_000,
    sharesTotal: 15_000,
    sharePrice: 10_000,
    sharesAvailable: 7_500,
    expectedReturn: 15,
    duration: '24 mois',
    image: 'https://images.pexels.com/photos/4407319/pexels-photo-4407319.jpeg?auto=compress&cs=tinysrgb&w=800',
    status: 'presentation',
    description:
      "Projet de culture maraîchère irriguée sur 10 hectares, destiné à la production de légumes pour les marchés urbains.",
  },
  {
    id: 'boulangerie',
    name: 'Mini-Boulangerie Communautaire',
    sector: 'Agroalimentaire',
    location: 'Dakar, Sénégal',
    budget: 80_000_000,
    sharesTotal: 8_000,
    sharePrice: 10_000,
    sharesAvailable: 4_000,
    expectedReturn: 22,
    duration: '18 mois',
    image: 'https://images.pexels.com/photos/2092060/pexels-photo-2092060.jpeg?auto=compress&cs=tinysrgb&w=800',
    status: 'presentation',
    description:
      "Unité de production de pain et pâtisseries pour la communauté urbaine de Dakar avec distribution locale.",
  },
];

export const investmentTickets = [
  { amount: 100_000, shares: 10, label: 'Découverte' },
  { amount: 500_000, shares: 50, label: 'Initiation' },
  { amount: 1_000_000, shares: 100, label: 'Croissance' },
  { amount: 5_000_000, shares: 500, label: 'Dynamique' },
  { amount: 10_000_000, shares: 1000, label: 'Stratégique' },
];

export { formatFCFA, formatNumber, formatPercent } from './format';
