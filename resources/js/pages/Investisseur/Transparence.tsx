import type { ReactNode } from 'react';
import { Head } from '@inertiajs/react';
import InvestisseurLayout from '@/layouts/investisseur-layout';
import { InvestorTransparency } from '@/screens/investor';

export default function Page() {
  return (
    <>
      <Head title="Transparence" />
      <InvestorTransparency />
    </>
  );
}

Page.layout = (page: ReactNode) => <InvestisseurLayout>{page}</InvestisseurLayout>;
