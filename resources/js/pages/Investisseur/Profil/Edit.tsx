import type { ReactNode } from 'react';
import { Head } from '@inertiajs/react';
import InvestisseurLayout from '@/layouts/investisseur-layout';
import { InvestorProfile } from '@/screens/investor';

export default function Page() {
  return (
    <>
      <Head title="Edit" />
      <InvestorProfile />
    </>
  );
}

Page.layout = (page: ReactNode) => <InvestisseurLayout>{page}</InvestisseurLayout>;
