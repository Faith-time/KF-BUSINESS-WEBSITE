import type { ReactNode } from 'react';
import { Head } from '@inertiajs/react';
import InvestisseurLayout from '@/layouts/investisseur-layout';
import { InvestorDashboard } from '@/screens/investor';

export default function Page() {
  return (
    <>
      <Head title="TableauDeBord" />
      <InvestorDashboard />
    </>
  );
}

Page.layout = (page: ReactNode) => <InvestisseurLayout>{page}</InvestisseurLayout>;
