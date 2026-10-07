import type { ReactNode } from 'react';
import { Head } from '@inertiajs/react';
import AdminLayout from '@/layouts/admin-layout';
import { AdminSettings } from '@/screens/admin';

export default function Page() {
  return (
    <>
      <Head title="Utilisateurs" />
      <AdminSettings />
    </>
  );
}

Page.layout = (page: ReactNode) => <AdminLayout>{page}</AdminLayout>;
