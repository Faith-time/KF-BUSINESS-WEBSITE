import type { ReactNode } from 'react';
import { Head } from '@inertiajs/react';
import ComptableLayout from '@/layouts/comptable-layout';
import { AccountantPayments } from '@/screens/accountant';

export default function Page() {
  return (
    <>
      <Head title="Paiements" />
      <AccountantPayments />
    </>
  );
}

Page.layout = (page: ReactNode) => <ComptableLayout>{page}</ComptableLayout>;
