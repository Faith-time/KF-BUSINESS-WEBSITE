import type { ReactNode } from 'react';
import { Head } from '@inertiajs/react';
import PublicLayout from '@/layouts/public-layout';
import { ProjectDetailPage } from '@/screens/visitor';

export default function Page() {
  return (
    <>
      <Head title="Show" />
      <ProjectDetailPage />
    </>
  );
}

Page.layout = (page: ReactNode) => <PublicLayout>{page}</PublicLayout>;
