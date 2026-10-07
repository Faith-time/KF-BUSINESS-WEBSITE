import type { ReactNode } from 'react';
import { Head } from '@inertiajs/react';
import PublicLayout from '@/layouts/public-layout';
import { ProjectsPage } from '@/screens/visitor';

export default function Page() {
  return (
    <>
      <Head title="Projets" />
      <ProjectsPage />
    </>
  );
}

Page.layout = (page: ReactNode) => <PublicLayout>{page}</PublicLayout>;
