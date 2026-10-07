import type { ReactNode } from 'react';
import { Head } from '@inertiajs/react';
import ComptableLayout from '@/layouts/comptable-layout';
import { AccountantDashboard } from '@/screens/accountant';

export default function Page() {
  return (
    <>
      <Head title="TableauDeBord" />
      <AccountantDashboard />
    </>
  );
}

Page.layout = (page: ReactNode) => <ComptableLayout>{page}</ComptableLayout>;
