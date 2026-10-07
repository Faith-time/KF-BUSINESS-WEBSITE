import type { ReactNode } from 'react';
import { Head } from '@inertiajs/react';
import PublicLayout from '@/layouts/public-layout';
import { LegalPage } from '@/screens/visitor';

export default function Page() {
  return (
    <>
      <Head title="CGV" />
      <LegalPage />
    </>
  );
}

Page.layout = (page: ReactNode) => <PublicLayout>{page}</PublicLayout>;
