import type { ReactNode } from 'react';
import { Head } from '@inertiajs/react';
import AdminLayout from '@/layouts/admin-layout';
import { AdminInvestors } from '@/screens/admin';

export default function Page() {
  return (
    <>
      <Head title="Investisseurs" />
      <AdminInvestors />
    </>
  );
}

Page.layout = (page: ReactNode) => <AdminLayout>{page}</AdminLayout>;
