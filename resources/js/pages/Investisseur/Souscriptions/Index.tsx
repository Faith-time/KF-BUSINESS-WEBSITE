import type { ReactNode } from 'react';
import { Head } from '@inertiajs/react';
import InvestisseurLayout from '@/layouts/investisseur-layout';
import { InvestorHistory } from '@/screens/investor';

export default function Page() {
  return (
    <>
      <Head title="Souscriptions" />
      <InvestorHistory />
    </>
  );
}

Page.layout = (page: ReactNode) => <InvestisseurLayout>{page}</InvestisseurLayout>;
