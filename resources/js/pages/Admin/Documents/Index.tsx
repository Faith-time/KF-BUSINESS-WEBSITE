import type { ReactNode } from 'react';
import { Head } from '@inertiajs/react';
import AdminLayout from '@/layouts/admin-layout';
import { AdminDocuments } from '@/screens/admin';

export default function Page() {
  return (
    <>
      <Head title="Documents" />
      <AdminDocuments />
    </>
  );
}

Page.layout = (page: ReactNode) => <AdminLayout>{page}</AdminLayout>;
