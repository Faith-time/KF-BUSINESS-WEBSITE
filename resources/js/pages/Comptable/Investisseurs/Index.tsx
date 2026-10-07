import type { ReactNode } from 'react';
import { Head } from '@inertiajs/react';
import ComptableLayout from '@/layouts/comptable-layout';
import { AccountantInvestors } from '@/screens/accountant';

export default function Page() {
  return (
    <>
      <Head title="Investisseurs" />
      <AccountantInvestors />
    </>
  );
}

Page.layout = (page: ReactNode) => <ComptableLayout>{page}</ComptableLayout>;
