import type { ReactNode } from 'react';
import { Head } from '@inertiajs/react';
import InvestisseurLayout from '@/layouts/investisseur-layout';
import { InvestorPortfolio } from '@/screens/investor';

export default function Page() {
  return (
    <>
      <Head title="Portefeuille" />
      <InvestorPortfolio />
    </>
  );
}

Page.layout = (page: ReactNode) => <InvestisseurLayout>{page}</InvestisseurLayout>;
