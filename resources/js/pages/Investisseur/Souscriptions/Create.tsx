import type { ReactNode } from 'react';
import { Head } from '@inertiajs/react';
import InvestisseurLayout from '@/layouts/investisseur-layout';
import { InvestorSubscribe } from '@/screens/investor';

export default function Page() {
  return (
    <>
      <Head title="Create" />
      <InvestorSubscribe />
    </>
  );
}

Page.layout = (page: ReactNode) => <InvestisseurLayout>{page}</InvestisseurLayout>;
