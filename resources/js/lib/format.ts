// Les colonnes decimal de Laravel arrivent en chaînes ("300000000.00") : on convertit toujours en nombre.
type Valeur = number | string | null | undefined;

const enNombre = (v: Valeur): number => Number(v ?? 0);

export function formatFCFA(montant: Valeur): string {
  return new Intl.NumberFormat('fr-FR').format(enNombre(montant)) + ' FCFA';
}

export function formatNumber(n: Valeur): string {
  return new Intl.NumberFormat('fr-FR').format(enNombre(n));
}

export function formatPercent(n: Valeur): string {
  return enNombre(n).toFixed(1) + '%';
}
