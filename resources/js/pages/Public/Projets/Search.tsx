import type { ReactNode } from 'react';
import { Head } from '@inertiajs/react';
import PublicLayout from '@/layouts/public-layout';
import { ProjectsPage } from '@/screens/visitor';

export default function Page() {
  return (
    <>
      <Head title="Search" />
      <ProjectsPage />
    </>
  );
}

Page.layout = (page: ReactNode) => <PublicLayout>{page}</PublicLayout>;
