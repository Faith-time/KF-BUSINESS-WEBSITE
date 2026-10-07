import type { ReactNode } from 'react';
import { Head } from '@inertiajs/react';
import AdminLayout from '@/layouts/admin-layout';
import { AdminSubscriptions } from '@/screens/admin';

export default function Page() {
  return (
    <>
      <Head title="Souscriptions" />
      <AdminSubscriptions />
    </>
  );
}

Page.layout = (page: ReactNode) => <AdminLayout>{page}</AdminLayout>;
