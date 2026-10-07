import type { ReactNode } from 'react';
import { Head } from '@inertiajs/react';
import AdminLayout from '@/layouts/admin-layout';
import { AdminLogs } from '@/screens/admin';

export default function Page() {
  return (
    <>
      <Head title="Journal" />
      <AdminLogs />
    </>
  );
}

Page.layout = (page: ReactNode) => <AdminLayout>{page}</AdminLayout>;
