import type { ReactNode } from 'react';
import { Head } from '@inertiajs/react';
import AdminLayout from '@/layouts/admin-layout';
import { AdminVerifications } from '@/screens/admin';

export default function Page() {
  return (
    <>
      <Head title="DossiersKYC" />
      <AdminVerifications />
    </>
  );
}

Page.layout = (page: ReactNode) => <AdminLayout>{page}</AdminLayout>;
