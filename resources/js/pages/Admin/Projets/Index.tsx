import type { ReactNode } from 'react';
import { Head } from '@inertiajs/react';
import AdminLayout from '@/layouts/admin-layout';
import { AdminProjects } from '@/screens/admin';

export default function Page() {
  return (
    <>
      <Head title="Projets" />
      <AdminProjects />
    </>
  );
}

Page.layout = (page: ReactNode) => <AdminLayout>{page}</AdminLayout>;
