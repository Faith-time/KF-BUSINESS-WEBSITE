import type { ReactNode } from 'react';
import { Head } from '@inertiajs/react';
import InvestisseurLayout from '@/layouts/investisseur-layout';
import { InvestorResults } from '@/screens/investor';

export default function Page() {
  return (
    <>
      <Head title="Resultats" />
      <InvestorResults />
    </>
  );
}

Page.layout = (page: ReactNode) => <InvestisseurLayout>{page}</InvestisseurLayout>;
