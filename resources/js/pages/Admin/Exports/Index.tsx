import type { ReactNode } from 'react';
import { Head } from '@inertiajs/react';
import AdminLayout from '@/layouts/admin-layout';
import { AdminExports } from '@/screens/admin';

export default function Page() {
  return (
    <>
      <Head title="Exports" />
      <AdminExports />
    </>
  );
}

Page.layout = (page: ReactNode) => <AdminLayout>{page}</AdminLayout>;
